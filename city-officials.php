<?php
$officials = [];
$dataFile = __DIR__ . '/data/officials.json';
if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $decoded = json_decode($json, true);
    if (is_array($decoded)) {
        $officials = $decoded;
    }
}
$totalOfficials = count($officials);

$mayorData = null;
$viceMayorData = null;
$councilors = [];
foreach ($officials as $off) {
    $pos = strtolower($off['position'] ?? '');
    if (strpos($pos, 'mayor') !== false && strpos($pos, 'vice') === false) {
        $mayorData = $off;
    } elseif (strpos($pos, 'vice') !== false) {
        $viceMayorData = $off;
    } else {
        $councilors[] = $off;
    }
}

function getInitials($name) {
    $name = preg_replace('/^(Hon\.\s*)/', '', $name);
    $name = preg_replace('/["\']/', '', $name);
    $parts = preg_split('/\s+/', trim($name));
    if (count($parts) >= 2) {
        return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
    }
    return mb_strtoupper(mb_substr($name, 0, 2));
}

$committeeColors = [
    'Executive' => ['bg' => 'rgba(23,105,170,0.08)', 'border' => 'rgba(23,105,170,0.25)', 'text' => '#1769AA'],
    'General Governance' => ['bg' => 'rgba(130,80,220,0.08)', 'border' => 'rgba(130,80,220,0.25)', 'text' => '#b890f0'],
    'Legislative Affairs' => ['bg' => 'rgba(50,150,200,0.08)', 'border' => 'rgba(50,150,200,0.25)', 'text' => '#6ac0e8'],
    'Ethics' => ['bg' => 'rgba(200,160,50,0.08)', 'border' => 'rgba(200,160,50,0.25)', 'text' => '#e8cc60'],
    'Health' => ['bg' => 'rgba(50,200,120,0.08)', 'border' => 'rgba(50,200,120,0.25)', 'text' => '#60e8a0'],
    'Social Services' => ['bg' => 'rgba(200,80,160,0.08)', 'border' => 'rgba(200,80,160,0.25)', 'text' => '#e880c8'],
    'Infrastructure' => ['bg' => 'rgba(220,160,50,0.08)', 'border' => 'rgba(220,160,50,0.25)', 'text' => '#e8c060'],
    'Public Works' => ['bg' => 'rgba(80,170,220,0.08)', 'border' => 'rgba(80,170,220,0.25)', 'text' => '#80c8e8'],
    'Education' => ['bg' => 'rgba(80,190,220,0.08)', 'border' => 'rgba(80,190,220,0.25)', 'text' => '#80d8f0'],
    'Culture' => ['bg' => 'rgba(220,80,100,0.08)', 'border' => 'rgba(220,80,100,0.25)', 'text' => '#e88090'],
    'Agriculture' => ['bg' => 'rgba(60,200,80,0.08)', 'border' => 'rgba(60,200,80,0.25)', 'text' => '#70e880'],
    'Food' => ['bg' => 'rgba(220,180,60,0.08)', 'border' => 'rgba(220,180,60,0.25)', 'text' => '#e8d070'],
];
$defaultColor = ['bg' => 'rgba(23,105,170,0.08)', 'border' => 'rgba(23,105,170,0.2)', 'text' => '#3D8FD1'];

function getCommitteeColor($tag, $committeeColors, $defaultColor) {
    if (isset($committeeColors[$tag])) return $committeeColors[$tag];
    foreach ($committeeColors as $key => $val) {
        if (stripos($tag, $key) !== false) return $val;
    }
    return $defaultColor;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3a0ca3">
    <title>City Officials Directory - Koronadal City</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="portal.css?v=20261118-13" />
</head>

<body class="officials-page">

    <div class="bg-layer">
        <div class="bg-orb bg-orb-1"></div>
        <div class="bg-orb bg-orb-2"></div>
        <div class="bg-orb bg-orb-3"></div>
        <div class="bg-grid"></div>
    </div>

    <!-- NAVBAR -->
    <nav class="nav" id="nav">
        <a href="/" class="nav-brand">
            <img src="Logo.png" alt="Koronadal City" onerror="this.style.display='none'">
            <div class="nav-brand-text">
                <strong>City of Koronadal</strong>
                <small>Government Services Portal</small>
            </div>
        </a>
        <a href="/mayor-corner.php" class="nav-back" style="margin-right:8px">
            <i class="fas fa-landmark"></i>
            Mayor's Corner
        </a>
        <a href="/" class="nav-back">
            <i class="fas fa-arrow-left"></i>
            Back to Portal
        </a>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="hero-content">
            <div class="hero-icon-wrap"><i class="fas fa-landmark"></i></div>
            <div class="hero-divider"></div>
            <h1>City <span class="glow">Officials</span></h1>
            <p class="hero-sub">Meet the leaders serving Koronadal City</p>
            <div class="hero-stats">
                <div class="stat-num"><?= $totalOfficials ?></div>
                <div class="stat-label"><strong>Officials</strong>Serving the city</div>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <main class="content">

        <?php if ($totalOfficials > 0): ?>

        <!-- MAYOR — Full Featured Banner -->
        <?php if ($mayorData): ?>
        <div class="leader-featured">
            <div class="mayor-hero-card">
                <div class="mayor-photo-section">
                    <div class="mayor-avatar-frame">
                        <div class="hex-ring"></div>
                        <?php if (!empty($mayorData['image'])): ?>
                        <img src="<?= htmlspecialchars($mayorData['image']) ?>"
                            alt="<?= htmlspecialchars($mayorData['name'] ?? '') ?>"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="initials" style="display:none"><?= getInitials($mayorData['name'] ?? 'M') ?></div>
                        <?php else: ?>
                        <div class="initials"><?= getInitials($mayorData['name'] ?? 'M') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mayor-info">
                    <div class="mayor-badge"><i class="fas fa-star"></i> City Mayor</div>
                    <div class="mayor-name"><?= htmlspecialchars($mayorData['name'] ?? '') ?></div>
                    <?php if (!empty($mayorData['ordinance'])): ?>
                    <p class="mayor-desc"><?= htmlspecialchars($mayorData['ordinance']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($mayorData['committee'])): ?>
                    <div class="mayor-tags">
                        <?php foreach (array_map('trim', explode(',', $mayorData['committee'])) as $tag):
                            if (empty($tag)) continue;
                            $c = getCommitteeColor($tag, $committeeColors, $defaultColor);
                        ?>
                        <span class="tag-pill"
                            style="background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;color:<?= $c['text'] ?>"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <div class="mayor-quote">"Genuine Service for God and for the People... EPAdayon Ang Kanami Sang
                        Bagong Koronadal"</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VICE MAYOR -->
        <?php if ($viceMayorData): ?>
        <div class="leader-featured" style="margin-bottom:40px">
            <div class="vice-card">
                <div class="vice-avatar">
                    <?php if (!empty($viceMayorData['image'])): ?>
                    <img src="<?= htmlspecialchars($viceMayorData['image']) ?>"
                        alt="<?= htmlspecialchars($viceMayorData['name'] ?? '') ?>"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <i class="fas fa-user-tie" style="display:none"></i>
                    <?php else: ?>
                    <i class="fas fa-user-tie"></i>
                    <?php endif; ?>
                </div>
                <div class="vice-info">
                    <div class="vice-badge"><i class="fas fa-medal"></i> Vice Mayor</div>
                    <div class="vice-name"><?= htmlspecialchars($viceMayorData['name'] ?? '') ?></div>
                    <?php if (!empty($viceMayorData['ordinance'])): ?>
                    <p class="vice-desc"><?= htmlspecialchars($viceMayorData['ordinance']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($viceMayorData['committee'])): ?>
                    <div class="vice-tags">
                        <?php foreach (array_map('trim', explode(',', $viceMayorData['committee'])) as $tag):
                            if (empty($tag)) continue;
                            $c = getCommitteeColor($tag, $committeeColors, $defaultColor);
                        ?>
                        <span class="tag-pill"
                            style="background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;color:<?= $c['text'] ?>"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- COUNCILORS -->
        <?php if (!empty($councilors)): ?>
        <div class="council-section">
            <div class="council-header">
                <i class="fas fa-users"></i>
                <h2>Sangguniang Panlungsod</h2>
                <span class="count"><?= count($councilors) ?> Members</span>
            </div>
            <div class="council-grid">
                <?php foreach ($councilors as $i => $c):
                $name = htmlspecialchars($c['name'] ?? 'Unknown');
                $position = htmlspecialchars($c['position'] ?? '');
                $ordinance = htmlspecialchars($c['ordinance'] ?? '');
                $committeeRaw = $c['committee'] ?? '';
                $committees = array_map('trim', explode(',', $committeeRaw));
                $initials = getInitials($c['name'] ?? 'U');
                $imgPath = htmlspecialchars($c['image'] ?? '');
            ?>
                <div class="council-card" style="transition-delay:<?= $i * 60 ?>ms">
                    <div class="council-avatar">
                        <div class="ring"></div>
                        <?php if ($imgPath): ?>
                        <img src="<?= $imgPath ?>" alt="<?= $name ?>"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="initials" style="display:none"><?= $initials ?></div>
                        <?php else: ?>
                        <div class="initials"><?= $initials ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="council-name"><?= $name ?></div>
                    <div class="council-pos"><?= $position ?></div>
                    <?php if ($ordinance): ?>
                    <div class="council-ordinance"><?= $ordinance ?></div>
                    <?php endif; ?>
                    <div class="council-tags">
                        <?php foreach ($committees as $tag):
                            $tag = trim($tag);
                            if (empty($tag)) continue;
                            $c2 = getCommitteeColor($tag, $committeeColors, $defaultColor);
                        ?>
                        <span class="tag-pill"
                            style="background:<?= $c2['bg'] ?>;border-color:<?= $c2['border'] ?>;color:<?= $c2['text'] ?>"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h3>No Officials Listed</h3>
            <p>The officials directory is currently being updated.</p>
        </div>
        <?php endif; ?>

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-brand">
            <img src="Logo.png" alt="Koronadal City" onerror="this.style.display='none'">
            <span>Koronadal City</span>
        </div>
        <p>&copy; <?= date('Y') ?> Koronadal City Government. All rights reserved.</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll reveal
        var cards = document.querySelectorAll('.council-card');
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('revealed');
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -30px 0px'
            });
            cards.forEach(function(c) {
                obs.observe(c);
            });
        } else {
            cards.forEach(function(c) {
                c.classList.add('revealed');
            });
        }

        // Keep profile cards steady for touch and keyboard users.

        // Navbar scroll
        var nav = document.getElementById('nav');
        window.addEventListener('scroll', function() {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });
    });
    </script>
</body>

</html>