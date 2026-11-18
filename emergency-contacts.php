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
    'disaster' => ['icon' => 'fa-triangle-exclamation', 'color' => '#f59e0b', 'label' => 'Disaster'],
    'general'  => ['icon' => 'fa-phone',           'color' => '#0D9488', 'label' => 'General'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#9d1727">
    <title>Emergency Contacts — Koronadal City</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="portal.css?v=20261118-17" />
  <link rel="stylesheet" href="civic-responsive.css?v=20261118-28" />
</head>

<body class="emergency-page">

    <!-- Floating Orbs -->
    

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-in">
            <a href="index.php" class="brand">
                <img src="Logo.png" alt="Koronadal City" class="brand-img">
                <div class="brand-text">
                    <strong>City of Koronadal</strong>
                    <small>Government Services Portal</small>
                </div>
            </a>
            <a href="mayor-corner.php" class="nav-back" style="margin-right:8px">
                <i class="fa-solid fa-landmark"></i>
                <span>Mayor's Corner</span>
            </a>
            <a href="index.php" class="nav-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Portal</span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-icon-wrap">
                <div class="hero-icon">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <div class="pulse-ring"></div>
                <div class="pulse-ring pulse-ring-2"></div>
            </div>

            <h1><span class="hl">Emergency</span> Contacts</h1>
            <p class="hero-sub">Find Koronadal City emergency hotlines and call the appropriate response service directly.</p>
            <a class="emergency-primary-call" href="tel:911"><i class="fa-solid fa-phone" aria-hidden="true"></i> Call 911</a>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-num"><?= $totalCount ?></div>
                    <div class="stat-label">Total</div>
                </div>
                <?php foreach ($typeCounts as $type => $count):
                $cfg = isset($typeConfig[$type]) ? $typeConfig[$type] : $typeConfig['general'];
            ?>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num"
                        ><?= $count ?>
                    </div>
                    <div class="stat-label"><?= htmlspecialchars($cfg['label']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contacts Grid -->
    <section class="contacts-section">
        <div class="section-label">
            <i class="fa-solid fa-address-book"></i>
            All Emergency Contacts
        </div>

        <div class="cards-grid">
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
            <div class="contact-card revealed type-<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">
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
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

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