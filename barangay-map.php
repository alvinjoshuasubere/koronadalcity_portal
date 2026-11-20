<?php /* Barangay names from the City Government of Koronadal 27-barangay directory. */ ?>
<section class="kd-barangay-section" aria-labelledby="kdBarangayMapTitle">
  <div class="kd-barangay-heading">
    <div><span class="kd-live-eyebrow">27 BARANGAYS OF KORONADAL</span>
      <h2 id="kdBarangayMapTitle">Explore the Barangays</h2>
      <p>Explore the individual boundaries of Koronadal's 27 barangays. Zoom in for names, then hover or tap to view population.</p>
    </div>
    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener noreferrer">Official barangay directory <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
  </div>
  <div class="kd-barangay-all-layout">
    <span id="kdBarangayStatus" class="kd-map-screenreader-status" role="status" aria-live="polite">Loading all Koronadal barangay boundaries.</span>
    <div class="kd-barangay-canvas-wrap">
      <div class="kd-map-message" id="kdMapMessage" role="status" hidden></div>
      <div id="kdBarangayMap" class="kd-barangay-map" role="region" aria-label="Interactive map showing available Koronadal barangay boundary lines">
        <div class="kd-barangay-map-loading">Loading OpenFreeMap...</div>
      </div>
      <div class="kd-barangay-attribution">Map tiles by <a href="https://openfreemap.org/" target="_blank" rel="noopener noreferrer">OpenFreeMap</a>. Boundaries: <a href="https://portal.georisk.gov.ph/arcgis/rest/services/PSA/Barangay/MapServer/4" target="_blank" rel="noopener noreferrer">PSA GeoRisk</a> (indicative 2016) with locally hosted <a href="https://github.com/faeldon/philippines-json-maps" target="_blank" rel="noopener noreferrer">NAMRIA/PSA–OCHA GeoJSON</a> (CC BY-IGO) as fallback. These outlines are indicative and not certified survey boundaries.</div>
    </div>
    <p class="kd-barangay-pop-source">Population data: <a href="https://psa.gov.ph/classification/psgc/barangays/1206306000" target="_blank" rel="noopener noreferrer">Philippine Statistics Authority, 2024 Census of Population</a>. Displayed boundaries are geographic references, not legal property or cadastral boundaries.</p>
  </div>
</section>
<link href="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.css" rel="stylesheet">
<script src="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.js" defer></script>
<script src="https://unpkg.com/osmtogeojson@3.0.0-beta.5/osmtogeojson.js" defer></script>
<script defer src="barangay-map.js?v=20261120-3"></script>
