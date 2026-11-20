<?php
/**
 * Read-only Koronadal barangay boundaries from the GeoRisk PSA layer.
 * Caches remote GeoJSON for one day to reduce third-party requests.
 * Source: PSA/Barangay MapServer layer 4 (indicative 2016 boundaries).
 */
declare(strict_types=1);
header('Content-Type: application/geo+json; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$cache = sys_get_temp_dir() . '/koronadal-psa-barangays-v4.geojson';
$validCache = static function () use ($cache): ?string {
    if (!is_file($cache)) return null;
    $body = @file_get_contents($cache);
    if (!$body) return null;
    $data = json_decode($body, true);
    return is_array($data) && ($data['type'] ?? null) === 'FeatureCollection' &&
        !empty($data['features']) ? $body : null;
};
$existing = $validCache();
if ($existing !== null && time() - (int) @filemtime($cache) < 86400) {
    echo $existing;
    exit;
}

$url = 'https://portal.georisk.gov.ph/arcgis/rest/services/PSA/Barangay/MapServer/4/query?' .
    http_build_query([
        'where' => "city_name LIKE '%Koronadal%' AND prov_name LIKE '%South Cotabato%'",
        'outFields' => 'objectid,brgy_name,brgy_code,psgc_10d,city_name,city_code,prov_name',
        'returnGeometry' => 'true',
        'outSR' => '4326',
        'geometryPrecision' => '7',
        'f' => 'geojson',
    ], '', '&', PHP_QUERY_RFC3986);

$body = false;
if (function_exists('curl_init')) {
    $curl = curl_init($url);
    if ($curl !== false) {
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_TIMEOUT => 16,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_USERAGENT => 'KoronadalCityPortal/1.0 (read-only GeoJSON boundary viewer)',
            CURLOPT_HTTPHEADER => ['Accept: application/geo+json, application/json'],
        ]);
        $body = curl_exec($curl);
        $code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code !== 200) $body = false;
    }
} elseif (filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN)) {
    $context = stream_context_create(['http'=>['timeout'=>14,'header'=>"Accept: application/json\r\n"]]);
    $body = @file_get_contents($url, false, $context);
}
$data = is_string($body) ? json_decode($body, true) : null;
if (is_array($data) && ($data['type'] ?? null) === 'FeatureCollection' && !empty($data['features'])) {
    // Cache only validated GeoJSON. Never expose remote errors as a successful response.
    @file_put_contents($cache, $body, LOCK_EX);
    echo $body;
    exit;
}
if ($existing !== null) {
    header('X-Koronadal-Boundaries: stale-cache');
    echo $existing;
    exit;
}
http_response_code(503);
header('Cache-Control: no-store');
echo json_encode(['error'=>['message'=>'Koronadal boundary source unavailable; browser fallback will be used']]);
