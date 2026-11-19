<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? '';
if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== parse_url('http://' . $host, PHP_URL_HOST)) {
    http_response_code(403); echo json_encode(['error'=>'Origin not allowed']); exit;
}
$body = file_get_contents('php://input', false, null, 0, 6000);
$data = json_decode($body ?: '', true);
$message = trim((string)($data['message'] ?? ''));
if (!$message || strlen($message)>2000) { http_response_code(400); echo json_encode(['error'=>'Please enter a question under 500 characters.']); exit; }
$key = getenv('OPENAI_API_KEY') ?: '';
if ($key === '' || !function_exists('curl_init')) {
    http_response_code(503);
    echo json_encode(['configured'=>false,'error'=>'AI response service is not configured. Use the portal assistant’s quick service answers.']);
    exit;
}
// Rate limit server-side requests to help protect the API account.
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = sys_get_temp_dir().'/kd-chat-'.hash('sha256',$ip).'.json';
$now = time();
$hits = is_file($rateFile) ? json_decode((string)@file_get_contents($rateFile),true) : [];
$hits = is_array($hits) ? array_values(array_filter($hits,fn($t)=>is_numeric($t)&&$t>$now-60)) : [];
if (count($hits)>=8) { http_response_code(429); echo json_encode(['error'=>'Too many messages. Please try again in a minute.']); exit; }
$hits[]=$now;
@file_put_contents($rateFile,json_encode($hits),LOCK_EX);
$system = 'You are the Koronadal City online services portal assistant. Answer concisely in English or Hiligaynon depending on the question. Only provide reliable general navigation help. Do not invent government rules, eligibility, documents, prices, processing times, officials, or emergency numbers. You cannot see private permit records, applications, or live transaction status. Known portal links: Business Permits & Licensing https://koronadalcityportal.com/v2/login ; Verify Business Permit https://bplo.koronadalcityonlineservices.com/ ; Unified Citizen Services https://citizen.koronadalcityonlineservices.com/register ; Jobs https://jobs.koronadalcityonlineservices.com/ ; LGU Recruitment https://careers.koronadalcityonlineservices.com/ ; MTOP Verification https://traffic.koronadalcityonlineservices.com/ ; Online Assistance https://assistance.koronadalcityonlineservices.com/ . Official city website https://koronadal.gov.ph/ . For emergencies call 911 in the Philippines. Do not ask for passwords, IDs, or personal account details. Say you are an automated assistant, not a government staff member.';
// Keep only short, valid recent messages supplied by the current chat session.
$history = [];
foreach (array_slice(is_array($data['history'] ?? null) ? $data['history'] : [], -6) as $item) {
    if (!is_array($item)) continue;
    $role = $item['role'] ?? '';
    $content = trim((string)($item['content'] ?? ''));
    if (!in_array($role, ['user','assistant'], true) || $content === '' || strlen($content) > 1200) continue;
    $history[] = ['role'=>$role,'content'=>$content];
}
$history[] = ['role'=>'user','content'=>$message];
$payload = json_encode([
    'model'=>getenv('KORONADAL_CHAT_MODEL') ?: 'gpt-4.1-mini',
    'instructions'=>$system,
    'input'=>$history,
    'max_output_tokens'=>350,
    'store'=>false
],JSON_UNESCAPED_SLASHES);
$ch=curl_init('https://api.openai.com/v1/responses');
curl_setopt_array($ch,[
    CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$payload,
    CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$key,'Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>20
]);
$result=curl_exec($ch);
$code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
$decoded=is_string($result)?json_decode($result,true):null;
$reply='';
foreach (($decoded['output'] ?? []) as $item) {
    foreach (($item['content'] ?? []) as $part) {
        if (($part['type']??'')==='output_text') $reply.=(string)($part['text']??'');
    }
}
if ($code<200 || $code>=300 || trim($reply)==='') {
    http_response_code(502); echo json_encode(['error'=>'AI answers are temporarily unavailable. Please use the service links below.']); exit;
}
echo json_encode(['reply'=>substr(trim($reply),0,2500)],JSON_UNESCAPED_UNICODE);
