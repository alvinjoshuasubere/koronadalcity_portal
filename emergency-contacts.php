<?php
$contacts = [];
$dataFile = __DIR__ . '/data/emergency_contacts.json';
if (file_exists($dataFile)) {
    $raw = file_get_contents($dataFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $contacts = array_values(array_filter($decoded, 'is_array'));
    }
}

$totalCount = count($contacts);
$typeCounts = [];
foreach ($contacts as $c) {
    $t = isset($c['type']) ? strtolower($c['type']) : 'general';
    if (!isset($typeCounts[$t])) $typeCounts[$t] = 0;
    $typeCounts[$t]++;
}

$typeConfig = [
    'police'   => ['icon' => 'fa-shield-halved', 'color' => '#1856a7', 'label' => 'Police'],
    'fire'     => ['icon' => 'fa-fire',           'color' => '#bd2432', 'label' => 'Fire'],
    'hospital' => ['icon' => 'fa-hospital',       'color' => '#22c55e', 'label' => 'Hospital'],
    'medical'  => ['icon' => 'fa-heart-pulse',    'color' => '#ef4444', 'label' => 'Medical'],
    'disaster' => ['icon' => 'fa-triangle-exclamation', 'color' => '#1766b1', 'label' => 'Disaster'],
    'general'  => ['icon' => 'fa-phone',           'color' => '#0D9488', 'label' => 'General'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c70">
    <title>Emergency Contacts — Koronadal City</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="civic-responsive.css?v=20261118-30" />
  <link rel="stylesheet" href="city-portal.css?v=20261119-1" />
</head>

<body class="emergency-page">

    <!-- Portal navigation -->
    <nav class="navbar">
        <div class="navbar-in">
            <a href="index.php" class="brand">
                <img src="Logo.png" alt="Koronadal City" class="brand-img">
                <div class="brand-text">
                    <strong>City of Koronadal</strong>
                    <small>Government Services Portal</small>
                </div>
            </a>
            <div class="emergency-nav-actions">
            <a href="mayor-corner.php" class="nav-back">
                <i class="fa-solid fa-landmark"></i>
                <span>Mayor's Corner</span>
            </a>
            <a href="index.php" class="nav-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Portal</span>
            </a>
            </div>
        </div>
    </nav>

    <!-- Emergency header: simple left-aligned information and clear emergency action -->
    <section class="hero emergency-hero-v2" aria-labelledby="emergencyHeroTitle">
      <div class="emergency-hero-layout">
        <div class="emergency-hero-main">
          <span class="emergency-eyebrow"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> CITY OF KORONADAL</span>
          <h1 id="emergencyHeroTitle"><span class="hl">Emergency</span> Contacts</h1>
          <p class="hero-sub">Find the emergency hotline you need. Call the appropriate office directly for assistance in Koronadal City.</p>
          <div class="emergency-hero-actions">
            <a class="emergency-primary-call" href="tel:911" aria-label="Call national emergency hotline 911"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i><span>Call 911</span></a>
            <a class="emergency-secondary-link" href="#contactDirectory"><i class="fa-solid fa-address-book" aria-hidden="true"></i><span>Browse Contacts</span></a>
          </div>
          <p class="emergency-call-note"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> For immediate emergencies in the Philippines, dial 911.</p>
        </div>
        <aside class="emergency-hero-summary" aria-label="Available emergency hotlines">
          <div class="emergency-summary-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></div>
          <div class="emergency-summary-info">
            <span class="emergency-summary-count"><?= (int) $totalCount ?></span>
            <span class="emergency-summary-title">Emergency contacts</span>
            <p>Quick access to local response and assistance hotlines</p>
          </div>
          <div class="emergency-summary-types" aria-label="Types of response services">
            <?php foreach ($typeCounts as $type => $count):
              $cfg = isset($typeConfig[$type]) ? $typeConfig[$type] : $typeConfig['general']; ?>
              <span class="emergency-type-chip"><i class="fa-solid <?= htmlspecialchars($cfg['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i><?= htmlspecialchars($cfg['label'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endforeach; ?>
          </div>
        </aside>
      </div>
    </section>

        <!-- Contacts Grid -->
    <section class="contacts-section" id="contactDirectory">
        <div class="section-label">
            <i class="fa-solid fa-address-book"></i>
            All Emergency Contacts
        </div>

        <div class="emergency-directory-head">
          <div class="emergency-directory-copy"><h2>Emergency Hotlines</h2><p>Choose the appropriate office and tap the phone number to call.</p></div>
          <label class="emergency-search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><span class="emergency-sr-only">Search emergency contacts</span><input id="emergencySearch" type="search" placeholder="Search contacts..." autocomplete="off" aria-label="Search emergency contacts"></label>
        </div>
        <div id="emergencySearchEmpty" class="emergency-search-empty" hidden>No matching contacts found. Try another keyword.</div>
        <div class="cards-grid" id="emergencyContactGrid">
            <?php if (empty($contacts)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-phone-slash"></i>
                <h3>No Contacts Available</h3>
                <p>Emergency contact information will appear here once configured.</p>
            </div>
            <?php else: ?>
            <?php foreach ($contacts as $contact):
                $type = isset($contact['type']) ? strtolower($contact['type']) : 'general';
                $cfg = isset($typeConfig[$type]) ? $typeConfig[$type] : $typeConfig['general'];
                $phone = isset($contact['phone']) ? $contact['phone'] : '';
                $phoneBase = preg_replace('/\s+ext\.?\s*\d+$/i', '', trim($phone));
                $phoneClean = preg_replace('/[^0-9+]/', '', $phoneBase);
            ?>
            <article class="contact-card revealed type-<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>" data-contact-search="<?= htmlspecialchars(strtolower(($contact['name'] ?? '') . ' ' . ($contact['description'] ?? '') . ' ' . $cfg['label']), ENT_QUOTES, 'UTF-8') ?>">
                <div class="card-top">
                    <div class="type-icon">
                        <i class="fa-solid <?= htmlspecialchars($cfg['icon']) ?>"></i>
                    </div>
                    <div class="type-badge"><?= htmlspecialchars($cfg['label']) ?></div>
                </div>
                <div class="card-name"><?= htmlspecialchars($contact['name'] ?? '') ?></div>
                <div class="card-desc"><?= htmlspecialchars($contact['description'] ?? '') ?></div>
                <?php if ($phoneClean !== ''): ?>
                <a href="tel:<?= htmlspecialchars($phoneClean, ENT_QUOTES, 'UTF-8') ?>" class="card-phone" aria-label="Call <?= htmlspecialchars($contact['name'] ?? 'Emergency contact', ENT_QUOTES, 'UTF-8') ?> at <?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>">
                    <i class="fa-solid fa-phone card-phone-icon"></i>
                    <span class="card-phone-num"><?= htmlspecialchars($phone) ?></span>
                    <span class="card-phone-call">Call now</span>
                </a>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <script>
      (function () {
        const field = document.getElementById('emergencySearch');
        const cards = Array.from(document.querySelectorAll('#emergencyContactGrid .contact-card'));
        const empty = document.getElementById('emergencySearchEmpty');
        if (!field || !empty) return;
        field.addEventListener('input', function () {
          const term = field.value.toLocaleLowerCase().trim();
          let count = 0;
          cards.forEach(function (card) {
            const match = !term || (card.dataset.contactSearch || '').includes(term);
            card.hidden = !match;
            if (match) count++;
          });
          empty.hidden = !term || count > 0;
        });
      })();
    </script>

    <!-- Footer -->
    <?php include __DIR__ . '/city-live-panel.php'; ?>
<footer class="footer">
        <div class="footer-in">
            <div class="footer-brand">
                <img src="Logo.png" alt="Koronadal City" class="footer-brand-img">
                <div>
                    <strong>Koronadal City</strong>
                    <small>Online Services Portal</small>
                </div>
            </div>
            <div class="footer-copy">&copy; <?= date('Y') ?> Koronadal City. All rights reserved.</div>
        </div>
    </footer>


</body>

</html>