<?php
$emergencyData = [];
$officialsData = [];
$emergencyFile = __DIR__ . '/data/emergency_contacts.json';
$officialsFile = __DIR__ . '/data/officials.json';
if (file_exists($emergencyFile)) {
  $emergencyData = json_decode(file_get_contents($emergencyFile), true);
}
if (file_exists($officialsFile)) {
  $officialsData = json_decode(file_get_contents($officialsFile), true);
}
$mayor = null;
if (is_array($officialsData)) {
  foreach ($officialsData as $official) {
    $position = strtolower($official['position'] ?? '');
    if (strpos($position, 'mayor') !== false && strpos($position, 'vice') === false) {
      $mayor = $official;
      break;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
  <meta name="theme-color" content="#123c78" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="default" />
  <meta name="apple-mobile-web-app-title" content="Koronadal City" />
  <meta name="description"
    content="Koronadal City digital gateway for online government services, emergency contacts, city information and citizen resources." />
  <link rel="manifest" href="manifest.webmanifest" />
  <title>Koronadal Digital Gateway</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="portal.css?v=20261118-26" />
  <link rel="stylesheet" href="civic-responsive.css?v=20261118-34" />
  <link rel="stylesheet" href="mobile-app.css?v=20261118-2" />
</head>

<body class="portal-home-page">
  <div class="kd-utility">
    <div class="kd-utility-inner"><span><i class="fas fa-landmark"></i> CITY GOVERNMENT OF KORONADAL</span></div>
  </div>
  <header class="portal-header" id="portalHeader">
    <div class="header-inner"><a class="brand" href="#home"><img src="Logo.png"
          alt="City of Koronadal official seal"><span><strong>Koronadal Digital Gateway</strong><small>City of Koronadal
            Online Services</small></span></a>
      <nav class="desktop-nav" id="desktopNav" aria-label="Main navigation"><a href="#home" class="active">Home</a><a
          href="#services">Services <i class="fas fa-chevron-down"></i></a><a href="#city-info">City Info <i
            class="fas fa-chevron-down"></i></a><a href="#leadership">Leadership</a><a class="kd-nav-emergency"
          href="emergency-contacts.php"><i class="fas fa-phone-volume"></i> Emergency</a></nav>
      <div class="header-actions"><button class="menu-btn" id="menuBtn" type="button" aria-label="Toggle navigation"
          aria-controls="desktopNav" aria-expanded="false"><i class="fas fa-bars"></i></button></div>
    </div>
  </header>
  <main class="portal-shell" id="home">
    <section class="home-hero" aria-labelledby="gatewayHeading">
      <div class="hero-content">
        <span class="kd-hero-eyebrow"><i class="fas fa-circle-check" aria-hidden="true"></i> CITY GOVERNMENT OF
          KORONADAL</span>
        <h1 id="gatewayHeading">Maayong Adlaw,<br>Koronadaleño!</h1>
        <p>Online government services, information and assistance in one convenient place.</p>
        <div class="portal-search" role="search"><i class="fas fa-search" aria-hidden="true"></i><input
            id="serviceSearch" type="search" aria-label="Search city services"
            placeholder="Search city services..."><button id="clearSearch" type="button" aria-label="Clear search"><i
              class="fas fa-xmark"></i></button><button id="submitSearch" type="button"
            class="portal-search-submit">Search</button></div>
        <div class="hero-actions"><a href="#services" class="primary-action"><i class="fas fa-grip"></i> Explore
            Services <i class="fas fa-arrow-right"></i></a><a href="emergency-contacts.php" class="secondary-action"><i
              class="fas fa-phone-volume"></i> Emergency Contacts</a></div>
      </div>
    </section>
    <section class="portal-section services-section" id="services">
      <div class="services-title"><span class="kd-section-mark"></span>
        <span class="kd-section-eyebrow">DIGITAL SERVICE DIRECTORY</span>
        <h2 class="directory-heading">Online City Services</h2>
        <p>Find the service you need and continue directly to its dedicated portal.</p>
      </div>
      <div class="service-grid kd-aborlan-grid" id="serviceGrid" aria-label="Available online services">
        <a class="service-card kd-aborlan-card" href="https://koronadalcityportal.com/v2/login" target="_blank" rel="noopener noreferrer" data-search="business permits licensing bplo renewal">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-file-signature" aria-hidden="true"></i></span><strong>Business Permits & Licensing</strong></span>
          <ul class="kd-aborlan-benefits"><li>New business permits</li><li>Business permit renewal</li><li>Licensing transactions</li></ul>
          <span class="kd-aborlan-footer">Open Business Permits & Licensing <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        <a class="service-card kd-aborlan-card" href="https://citizen.koronadalcityonlineservices.com/register" target="_blank" rel="noopener noreferrer" data-search="citizen records transactions digital government">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-id-card" aria-hidden="true"></i></span><strong>Unified Citizen Services</strong></span>
          <ul class="kd-aborlan-benefits"><li>Citizen registration</li><li>Digital citizen profile</li><li>Online city transactions</li></ul>
          <span class="kd-aborlan-footer">Open Unified Citizen Services <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        <a class="service-card kd-aborlan-card" href="https://jobs.koronadalcityonlineservices.com/" target="_blank" rel="noopener noreferrer" data-search="jobs career employment">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span><strong>Koronadal Job Portal</strong></span>
          <ul class="kd-aborlan-benefits"><li>Available job postings</li><li>Employment opportunities</li><li>Job search and listings</li></ul>
          <span class="kd-aborlan-footer">Open Koronadal Job Portal <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        <a class="service-card kd-aborlan-card" href="https://careers.koronadalcityonlineservices.com/" target="_blank" rel="noopener noreferrer" data-search="lgu recruitment careers applications">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-user-tie" aria-hidden="true"></i></span><strong>LGU Recruitment</strong></span>
          <ul class="kd-aborlan-benefits"><li>LGU job openings</li><li>Recruitment announcements</li><li>Application information</li></ul>
          <span class="kd-aborlan-footer">Open LGU Recruitment <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        <a class="service-card kd-aborlan-card" href="https://traffic.koronadalcityonlineservices.com/" target="_blank" rel="noopener noreferrer" data-search="mtop transport tricycle verification">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-motorcycle" aria-hidden="true"></i></span><strong>MTOP Verification</strong></span>
          <ul class="kd-aborlan-benefits"><li>Operator permit lookup</li><li>Tricycle permit verification</li><li>Permit record reference</li></ul>
          <span class="kd-aborlan-footer">Open MTOP Verification <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        <a class="service-card kd-aborlan-card" href="https://assistance.koronadalcityonlineservices.com/" target="_blank" rel="noopener noreferrer" data-search="assistance help support social services">
          <span class="kd-aborlan-card-heading"><span class="kd-aborlan-icon"><i class="fas fa-hand-holding-heart" aria-hidden="true"></i></span><strong>Online Assistance</strong></span>
          <ul class="kd-aborlan-benefits"><li>Assistance portal access</li><li>Public assistance inquiries</li><li>Citizen support resources</li></ul>
          <span class="kd-aborlan-footer">Open Online Assistance <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
      </div>
      <div class="search-empty" id="searchEmpty" role="status" aria-live="polite">No service found. Try another search.
      </div>
    </section>
    <section class="kd-emergency-banner" id="emergency" aria-labelledby="kdEmergencyTitle">
      <div class="kd-emergency-heading">
        <div class="kd-emergency-heading-copy">
          <span class="kd-emergency-label"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i> EMERGENCY
            HOTLINES</span>
          <h2 id="kdEmergencyTitle">Emergency Assistance</h2>
          <p>Call the appropriate emergency response office directly.</p>
        </div>
      </div>
      <div class="kd-emergency-direct-grid">
        <?php
        $priorityHotlines = [
          ['label' => '911', 'type' => 'general', 'icon' => 'fa-phone-volume', 'subtitle' => 'National Emergency Hotline'],
          ['label' => 'PNP', 'type' => 'police', 'icon' => 'fa-shield-halved', 'logo' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Philippine_National_Police_seal.svg', 'subtitle' => 'Koronadal City Police Station'],
          ['label' => 'BFP', 'type' => 'fire', 'icon' => 'fa-fire-extinguisher', 'logo' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Bureau_of_Fire_Protection.png', 'subtitle' => 'Koronadal City Fire Station'],
          ['label' => 'CDRRMO', 'type' => 'disaster', 'icon' => 'fa-house-flood-water', 'logo' => 'Logo.png', 'subtitle' => 'City Disaster Risk Reduction']
        ];
        foreach ($priorityHotlines as $priority):
          $record = null;
          foreach ($emergencyData as $candidate) {
            if (is_array($candidate) && strtolower((string) ($candidate['type'] ?? '')) === $priority['type']) {
              $record = $candidate;
              break;
            }
          }
          $displayPhone = trim((string) ($record['phone'] ?? ''));
          $phoneWithoutExtension = preg_replace('/\s+ext\.?\s*\d+$/i', '', $displayPhone);
          $callNumber = preg_replace('/[^0-9+]/', '', $phoneWithoutExtension);
          ?>
          <div class="kd-direct-hotline">
            <span class="kd-direct-icon <?= !empty($priority['logo']) ? 'kd-direct-logo' : '' ?>">
              <?php if (!empty($priority['logo'])): ?>
                <img src="<?= htmlspecialchars($priority['logo'], ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($priority['label'], ENT_QUOTES, 'UTF-8') ?> official seal" loading="lazy"
                  decoding="async" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                <i class="fas <?= htmlspecialchars($priority['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"
                  hidden></i>
              <?php else: ?>
                <i class="fas <?= htmlspecialchars($priority['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i>
              <?php endif; ?>
            </span>
            <strong><?= htmlspecialchars($priority['label'], ENT_QUOTES, 'UTF-8') ?></strong>
            <small><?= htmlspecialchars($priority['subtitle'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php if ($callNumber !== ''): ?>
              <a class="kd-direct-call" href="tel:<?= htmlspecialchars($callNumber, ENT_QUOTES, 'UTF-8') ?>"
                aria-label="Call <?= htmlspecialchars($priority['label'], ENT_QUOTES, 'UTF-8') ?> at <?= htmlspecialchars($displayPhone, ENT_QUOTES, 'UTF-8') ?>">
                <i class="fas fa-phone" aria-hidden="true"></i> <?= htmlspecialchars($displayPhone, ENT_QUOTES, 'UTF-8') ?>
              </a>
            <?php else: ?>
              <span class="kd-direct-unavailable">Number unavailable</span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <a class="kd-emergency-all" href="emergency-contacts.php"><i class="fas fa-address-book"
          aria-hidden="true"></i><span>View all emergency contacts</span><i class="fas fa-arrow-right"
          aria-hidden="true"></i></a>
    </section>
    <section class="kd-info-panels">
      <article class="kd-info-panel kd-mayor-panel" id="leadership"><span class="kd-section-mark"></span>
        <h2>Mayor's Corner</h2>
        <div class="kd-mayor-body">
          <div class="kd-mayor-avatar"><img src="Mayor.png" alt="City Mayor of Koronadal" loading="lazy" width="145"
              height="160"></div>
          <div><span class="kd-caption">A message from the City Mayor</span>
            <h3><?= htmlspecialchars($mayor['name'] ?? 'City Mayor', ENT_QUOTES, 'UTF-8') ?></h3>
            <p>We are committed to a more accessible, transparent, and people-centered local government through digital
              innovation.</p><a href="mayor-corner.php">Read Mayor's Corner <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </article>
      <article class="kd-info-panel" id="city-info"><span class="kd-section-mark"></span>
        <h2>Koronadal at a Glance</h2>
        <div class="kd-facts">
          <div><i class="fas fa-users"></i><strong>27 Barangays</strong></div>
          <div><i class="fas fa-location-dot"></i><strong>SOCCSKSARGEN Regional Center</strong></div>
          <div><i class="fas fa-map"></i><strong>South Cotabato</strong></div>
        </div>
      </article>
    </section>
    <?php include __DIR__ . '/city-live-panel.php'; ?>
  </main>
  <footer class="portal-footer">
    <div class="footer-inner">
      <div class="kd-footer-top">
        <div class="footer-brand"><span class="kd-footer-seal"><img src="Logo.png" alt="Official City of Koronadal seal"
              loading="lazy" decoding="async"></span>
          <div><strong>City Government of Koronadal</strong><span>Koronadal, South Cotabato, Philippines</span></div>
        </div>
        <nav class="footer-links" aria-label="Footer navigation"><a href="#home">Home</a><a
            href="#services">Services</a><a href="#city-info">City Info</a><a href="city-officials.php">Leadership</a><a
            href="emergency-contacts.php">Emergency</a><a href="https://koronadal.gov.ph/" target="_blank"
            rel="noopener noreferrer">Contact Us</a></nav>
      </div>
      <div class="footer-bottom"><span>© <?= date('Y') ?> City Government of Koronadal. All Rights
          Reserved.</span><span>Official City Portal</span></div>
    </div>
  </footer>
  <nav class="bottom-nav" aria-label="Portal navigation"><a href="#home" class="active" aria-label="Home"><i
        class="fas fa-house"></i><span>Home</span></a><a href="city-officials.php" aria-label="City leadership"><i
        class="fas fa-users"></i><span>Officials</span></a><a href="#services" class="center"
      aria-label="Browse services"><i class="fas fa-grip"></i><span>Services</span></a><a
      href="https://koronadal.gov.ph/" target="_blank" rel="noopener noreferrer" aria-label="City information"><i
        class="fas fa-city"></i><span>City</span></a><a href="emergency-contacts.php" aria-label="Emergency contacts"><i
        class="fas fa-phone-volume"></i><span>Help</span></a></nav>
  <div class="view-switcher" id="viewSwitcher">
    <button type="button" class="view-trigger" id="viewTrigger" aria-label="Choose layout mode" aria-haspopup="true"
      aria-controls="viewModeMenu" aria-expanded="false">
      <i class="fas fa-display" aria-hidden="true"></i><span id="viewLabel" class="view-label">Auto view</span><i
        class="fas fa-chevron-up view-chevron" aria-hidden="true"></i>
    </button>
    <div class="view-menu" id="viewModeMenu" role="group" aria-label="Layout preview mode">
      <span class="view-menu-title">Choose your view</span>
      <button type="button" data-view="auto" class="active" aria-pressed="true"><i class="fas fa-arrows-left-right"
          aria-hidden="true"></i> Auto (responsive)</button>
      <button type="button" data-view="mobile" aria-pressed="false"><i class="fas fa-mobile-screen-button"
          aria-hidden="true"></i> Mobile app view</button>
      <button type="button" data-view="desktop" aria-pressed="false"><i class="fas fa-desktop" aria-hidden="true"></i>
        Desktop view</button>
    </div>
  </div>
  <script>
    (function () {
      var body = document.body;
      var menuBtn = document.getElementById('menuBtn');
      menuBtn.onclick = function () { body.classList.toggle('header-collapsed'); var expanded = body.classList.contains('header-collapsed'); menuBtn.setAttribute('aria-expanded', String(expanded)); menuBtn.querySelector('i').className = expanded ? 'fas fa-xmark' : 'fas fa-bars' };
      var search = document.getElementById('serviceSearch'), empty = document.getElementById('searchEmpty');
      search.oninput = function () { var q = search.value.toLowerCase().trim(), n = 0; document.querySelectorAll('.service-card').forEach(function (c) { var ok = !q || (c.innerText + ' ' + (c.dataset.search || '')).toLowerCase().includes(q); c.hidden = !ok; if (ok) n++ }); empty.classList.toggle('show', !!q && !n); };
      document.getElementById('clearSearch').onclick = function () { search.value = ''; search.oninput(); search.focus() };
      document.getElementById('submitSearch').onclick = function () { search.oninput(); document.getElementById('services').scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }) };
      search.addEventListener('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); document.getElementById('submitSearch').click() } });
      document.querySelectorAll('a[href^="#"]').forEach(function (a) { a.onclick = function (e) { var hash = a.getAttribute('href'); if (!hash || hash === '#') return; var t = document.getElementById(hash.slice(1)); if (t) { e.preventDefault(); t.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }); if (body.classList.contains('header-collapsed')) menuBtn.click() } } });
      var ids = ['home', 'services', 'emergency', 'leadership', 'city-info'], obs = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { document.querySelectorAll('.bottom-nav a').forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id) }); document.querySelectorAll('.desktop-nav a').forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id) }) } }) }, { rootMargin: '-45% 0px -45% 0px' });
      ids.forEach(function (id) { var el = document.getElementById(id); if (el) obs.observe(el) });

      // Auto follows screen size. Manual modes persist across reloads and device widths.
      var switcher = document.getElementById('viewSwitcher');
      var trigger = document.getElementById('viewTrigger');
      var label = document.getElementById('viewLabel');
      var controls = Array.from(switcher.querySelectorAll('[data-view]'));
      function setMenu(open) {
        switcher.classList.toggle('open', open);
        trigger.setAttribute('aria-expanded', String(open));
      }
      function setView(mode) {
        if (!['auto', 'mobile', 'desktop'].includes(mode)) mode = 'auto';
        body.classList.remove('force-mobile', 'force-desktop', 'header-collapsed');
        menuBtn.setAttribute('aria-expanded', 'false');
        menuBtn.querySelector('i').className = 'fas fa-bars';
        if (mode !== 'auto') body.classList.add('force-' + mode);
        label.textContent = mode.charAt(0).toUpperCase() + mode.slice(1) + ' view';
        trigger.setAttribute('aria-label', 'Layout: ' + mode + '. Open view options');
        controls.forEach(function (button) {
          var chosen = button.dataset.view === mode;
          button.classList.toggle('active', chosen);
          button.setAttribute('aria-pressed', String(chosen));
        });
        try { localStorage.setItem('kdc-view', mode) } catch (e) { }
        setMenu(false);
      }
      trigger.addEventListener('click', function () { setMenu(!switcher.classList.contains('open')) });
      controls.forEach(function (button) { button.addEventListener('click', function () { setView(button.dataset.view) }) });
      document.addEventListener('click', function (e) { if (!switcher.contains(e.target)) setMenu(false) });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && switcher.classList.contains('open')) { setMenu(false); trigger.focus() } });
      var savedMode = 'auto';
      try { savedMode = localStorage.getItem('kdc-view') || 'auto' } catch (e) { }
      setView(savedMode);

    })();
  </script>
</body>

</html>