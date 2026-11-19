<?php
$officials = [];
$file = __DIR__ . '/data/officials.json';
if (is_file($file)) {
  $data = json_decode(file_get_contents($file), true);
  if (is_array($data)) $officials = array_values(array_filter($data, 'is_array'));
}
$mayor = null; $viceMayor = null; $council = [];
foreach ($officials as $official) {
  $role = strtolower((string)($official['position'] ?? ''));
  if (strpos($role, 'vice mayor') !== false) $viceMayor = $official;
  elseif (strpos($role, 'mayor') !== false) $mayor = $official;
  else $council[] = $official;
}
function officialEsc($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#123f74">
<title>City Government & Officials | City of Koronadal</title>
<meta name="description" content="Know the executive and legislative branches and elected officials of the City Government of Koronadal.">
<link rel="icon" href="favicon.ico">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="city-portal.css?v=20261119-1" />
</head>
<body class="officials-page civic-government-page">
<header class="cg-header">
  <div class="cg-header-in">
    <a href="index.php" class="cg-brand"><img src="Logo.png" alt="City of Koronadal seal"><span><strong>City Government of Koronadal</strong><small>Government and elected officials</small></span></a>
    <nav class="cg-nav" aria-label="Page navigation"><a href="index.php"><i class="fas fa-house" aria-hidden="true"></i> Home</a><a href="mayor-corner.php">Mayor’s Corner</a><a href="emergency-contacts.php">Emergency Contacts</a></nav>
  </div>
</header>
<main class="cg-main">
  <section class="cg-hero">
    <span class="cg-kicker"><i class="fas fa-landmark" aria-hidden="true"></i> CITY GOVERNMENT</span>
    <h1>Government of Koronadal</h1>
    <p>Know how our city government serves residents through its executive offices, legislative council, and barangay communities.</p>
  </section>
  <section class="cg-section" aria-labelledby="cgStructure">
    <div class="cg-heading"><span>LOCAL GOVERNMENT</span><h2 id="cgStructure">How the City Government Works</h2><p>Three important parts of local government work together to deliver services.</p></div>
    <div class="cg-structure">
      <article><span class="cg-structure-icon"><i class="fas fa-building-columns" aria-hidden="true"></i></span><h3>Executive</h3><p>Office of the City Mayor, executive offices, and departments managing day-to-day government services.</p><a href="#cgExecutive">Meet the executive leaders <i class="fas fa-arrow-right" aria-hidden="true"></i></a></article>
      <article><span class="cg-structure-icon"><i class="fas fa-users" aria-hidden="true"></i></span><h3>Legislative</h3><p>The Sangguniang Panlungsod enacts ordinances, approves local measures, and represents the city's people.</p><a href="#cgCouncil">Meet the city council <i class="fas fa-arrow-right" aria-hidden="true"></i></a></article>
      <article><span class="cg-structure-icon"><i class="fas fa-location-dot" aria-hidden="true"></i></span><h3>Barangays</h3><p>Koronadal's 27 barangays are the city's local communities, each led by its own barangay officials.</p><a href="#cgBarangays">Barangay information <i class="fas fa-arrow-right" aria-hidden="true"></i></a></article>
    </div>
  </section>
  <section class="cg-section" id="cgExecutive" aria-labelledby="cgExecutiveTitle">
    <div class="cg-heading"><span>EXECUTIVE LEADERSHIP</span><h2 id="cgExecutiveTitle">City Mayor and Vice Mayor</h2><p>Officials serving in the leadership of the City of Koronadal.</p></div>
    <div class="cg-featured-grid">
      <?php foreach ([$mayor, $viceMayor] as $leader): if (!is_array($leader)) continue; ?>
      <article class="cg-official cg-featured">
        <div class="cg-photo">
          <?php if (!empty($leader['image'])): ?><img src="<?= officialEsc($leader['image']) ?>" alt="Portrait of <?= officialEsc($leader['name'] ?? 'City official') ?>" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><?php endif; ?>
          <span class="cg-photo-fallback" <?= !empty($leader['image']) ? 'hidden' : '' ?>><i class="fas fa-user-tie" aria-hidden="true"></i></span>
        </div>
        <div class="cg-official-info"><span class="cg-official-role"><?= officialEsc($leader['position'] ?? '') ?></span><h3><?= officialEsc($leader['name'] ?? '') ?></h3>
          <p>City Government of Koronadal</p>
          <?php if (stripos($leader['position'] ?? '', 'vice') === false): ?><a href="mayor-corner.php">Visit Mayor’s Corner <i class="fas fa-arrow-right" aria-hidden="true"></i></a><?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>
  <section class="cg-section" id="cgCouncil" aria-labelledby="cgCouncilTitle">
    <div class="cg-heading"><span>LEGISLATIVE BRANCH</span><h2 id="cgCouncilTitle">Sangguniang Panlungsod</h2><p>Meet the city council members and sectoral representatives listed in the official directory.</p></div>
    <div class="cg-council-grid">
      <?php foreach ($council as $official): ?>
      <article class="cg-official cg-council-member">
        <div class="cg-photo">
          <?php if (!empty($official['image'])): ?><img src="<?= officialEsc($official['image']) ?>" alt="Portrait of <?= officialEsc($official['name'] ?? 'Council member') ?>" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><?php endif; ?>
          <span class="cg-photo-fallback" <?= !empty($official['image']) ? 'hidden' : '' ?>><i class="fas fa-user" aria-hidden="true"></i></span>
        </div>
        <div class="cg-official-info"><span class="cg-official-role"><?= officialEsc($official['position'] ?? '') ?></span><h3><?= officialEsc($official['name'] ?? '') ?></h3></div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php if (!$council): ?><p class="cg-empty">Council profiles are being updated.</p><?php endif; ?>
  </section>
  <section class="cg-section" id="cgBarangays" aria-labelledby="cgBarangayTitle">
    <div class="cg-barangay-note"><div><span>COMMUNITY GOVERNANCE</span><h2 id="cgBarangayTitle">27 Barangays of Koronadal</h2><p>Barangays deliver essential local services and connect residents with the city government.</p></div><a href="https://koronadal.gov.ph/" target="_blank" rel="noopener noreferrer">Visit the city website <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a></div>
  </section>
  <?php include __DIR__ . '/city-live-panel.php'; ?>
</main>
<footer class="cg-footer"><div class="cg-footer-inner"><a href="index.php" class="cg-footer-brand"><img src="Logo.png" alt="Koronadal city logo"><span><strong>City Government of Koronadal</strong><small>Koronadal, South Cotabato, Philippines</small></span></a><nav aria-label="Footer links"><a href="index.php">Home</a><a href="mayor-corner.php">Mayor’s Corner</a><a href="emergency-contacts.php">Emergency</a></nav><p>© <?= date('Y') ?> City Government of Koronadal</p></div></footer>
</body></html>