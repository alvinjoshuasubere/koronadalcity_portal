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
  <meta name="theme-color" content="#3a0ca3" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="default" />
  <meta name="apple-mobile-web-app-title" content="Koronadal City" />
  <meta name="description"
    content="Koronadal City digital gateway for online government services, emergency contacts, city information and citizen resources." />
  <link rel="manifest" href="manifest.webmanifest" />
  <title>Koronadal City — Digital Gateway</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="portal.css?v=20261118-10" />
</head>

<body class="portal-home-page">
  <header class="portal-header" id="portalHeader">
    <div class="header-inner">
      <a class="brand" href="#home"><img src="Logo.png" alt="City of Koronadal"><span><strong>City of
            Koronadal</strong><small>Government Services Portal</small></span></a>
      <nav class="desktop-nav" id="desktopNav" aria-label="Main navigation"><a href="#home" class="active"><i
            class="fas fa-house" aria-hidden="true"></i> Home</a><a href="#services"><i class="fas fa-grip"
            aria-hidden="true"></i> Services</a><a href="city-officials.php"><i class="fas fa-users" aria-hidden="true"></i>
          Officials</a><a href="https://koronadal.gov.ph/" target="_blank" rel="noopener noreferrer"><i class="fas fa-circle-info" aria-hidden="true"></i> About the City</a><a
          href="emergency-contacts.php"><i class="fas fa-phone-volume" aria-hidden="true"></i> Emergency</a></nav>
      <div class="header-actions"><span class="official-pill"><i class="fas fa-circle"></i> Official Portal</span><a
          class="header-service-btn" href="#services"><i class="fas fa-grid-2"></i> Services</a><button class="menu-btn"
          id="menuBtn" type="button" aria-label="Toggle navigation" aria-controls="desktopNav" aria-expanded="false"><i
            class="fas fa-bars"></i></button></div>
    </div>
    <div class="brand-accent"><i></i><i></i><i></i></div>
  </header>
  <main class="portal-shell" id="home">
    <div class="mobile-welcome">
      <div class="welcome-copy"><span>CITY OF KORONADAL</span><strong>Maayong Aga! Koronadaleño</strong></div>
      <div class="welcome-icon" aria-hidden="true"><i class="fas fa-sun"></i></div>
    </div>
    <section class="home-hero">
      <div class="hero-content">
        <div class="hero-logo"><img src="Logo.png" alt="Koronadal City"></div>
        <div class="hero-kicker"><span class="live-dot"></span> CITY SERVICES, MADE SIMPLE</div>
        <div class="hero-greeting">Maayong Aga! Koronadaleño</div>
        <h1>Koronadal City<br><b>Services Portal</b></h1>
        <p>Welcome to the City of Koronadal Government Services Portal. Access online services, city information, and assistance in one place.</p>
        <div class="hero-actions"><a href="#services" class="primary-action"><i class="fas fa-arrow-down"></i> Explore
            Services</a><a href="emergency-contacts.php" class="secondary-action"><i class="fas fa-phone-volume"></i>
            Emergency Help</a></div>
      </div>
      <div class="hero-city-art" aria-hidden="true">
        <div class="city-art-orbit orbit-one"></div>
        <div class="city-art-orbit orbit-two"></div>
        <div class="city-art-building building-one"></div>
        <div class="city-art-building building-two"></div>
        <div class="city-art-building building-three"></div>
        <div class="city-art-platform"></div>
        <div class="city-art-glow"></div>
      </div>
    </section>
    <script>
      (function () {
        var hero = document.querySelector('.home-hero');
        if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var raf = 0, px = 0, py = 0;
        hero.addEventListener('pointermove', function (e) {
          var r = hero.getBoundingClientRect();
          px = ((e.clientX - r.left) / r.width - .5) * 10;
          py = ((e.clientY - r.top) / r.height - .5) * 7;
          hero.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
          hero.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
          if (!raf) raf = requestAnimationFrame(function () {
            hero.style.setProperty('--hero-x', px.toFixed(2) + 'px');
            hero.style.setProperty('--hero-y', py.toFixed(2) + 'px');
            raf = 0;
          });
        });
        hero.addEventListener('pointerleave', function () {
          hero.style.setProperty('--hero-x', '0px');
          hero.style.setProperty('--hero-y', '0px');
          hero.style.setProperty('--mx', '50%');
          hero.style.setProperty('--my', '50%');
        });
      })();
    </script>
    <div class="portal-search" role="search"><i class="fas fa-search" aria-hidden="true"></i><input id="serviceSearch"
        type="search" aria-label="Search city services"
        placeholder="Search services, requirements, information..."><button id="clearSearch" type="button"
        aria-label="Clear search"><i class="fas fa-xmark"></i></button><button id="submitSearch" type="button"
        class="portal-search-submit"><span>Search</span><i class="fas fa-arrow-right" aria-hidden="true"></i></button>
    </div>
    <section class="portal-section services-section" id="services">
      <div class="services-title">
        <div><span>ONLINE SERVICES</span>
          <h2 class="directory-heading">Online Service Directory</h2>
          <p>Choose a service to view information or continue to its online portal.</p>
        </div><a class="app-directory-link" href="https://koronadal.gov.ph/citizens-charter/" target="_blank"
          rel="noopener noreferrer">Service guide <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="service-grid" id="serviceGrid" aria-label="Available online services">
        <a class="service-card" href="https://koronadalcityportal.com/v2/login" target="_blank" rel="noopener"
          data-search="business permits licensing"><span class="service-icon"><i
              class="fas fa-file-signature"></i></span><strong>Business Permits & Licensing</strong><small>Apply, renew
            and manage business permits.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card" href="https://citizen.koronadalcityonlineservices.com/register" target="_blank"
          rel="noopener" data-search="citizen records transactions"><span class="service-icon"><i
              class="fas fa-id-card"></i></span><strong>Unified Citizen Services</strong><small>Access integrated
            citizen transactions.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card" href="https://jobs.koronadalcityonlineservices.com/" target="_blank" rel="noopener"
          data-search="jobs career employment"><span class="service-icon"><i
              class="fas fa-briefcase"></i></span><strong>Koronadal Job Portal</strong><small>Browse employment
            opportunities.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card" href="https://careers.koronadalcityonlineservices.com/" target="_blank" rel="noopener"
          data-search="lgu recruitment careers applications"><span class="service-icon"><i
              class="fas fa-user-tie"></i></span><strong>LGU Recruitment</strong><small>View recruitment and application
            information.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card" href="https://traffic.koronadalcityonlineservices.com/" target="_blank" rel="noopener"
          data-search="mtop transport tricycle verification"><span class="service-icon"><i
              class="fas fa-motorcycle"></i></span><strong>MTOP Verification</strong><small>Verify motorized tricycle
            operator permits.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card" href="https://koronadal.gov.ph/full-disclosure/" target="_blank" rel="noopener"
          data-search="transparency full disclosure reports open data"><span class="service-icon"><i
              class="fas fa-chart-column"></i></span><strong>Transparency & Full Disclosure</strong><small>View public
            reports and disclosure documents.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
        <a class="service-card emergency-card" href="emergency-contacts.php"
          data-search="emergency police fire hospital 911"><span class="service-icon red"><i
              class="fas fa-phone-volume"></i></span><strong>Emergency</strong><small>911 and local emergency
            hotlines.</small><i class="fas fa-arrow-up-right-from-square service-open"></i></a>
      </div>
      <div class="search-empty" id="searchEmpty" role="status" aria-live="polite">No service found. Try another search.
      </div>
    </section>
    <section class="home-emergency-section" id="emergency" aria-labelledby="homeEmergencyHeading">
      <div class="home-emergency-intro">
        <div>
          <span class="home-emergency-eyebrow"><i class="fas fa-headset" aria-hidden="true"></i> PUBLIC SAFETY</span>
          <h2 id="homeEmergencyHeading">Emergency Contacts</h2>
          <p>For emergencies, contact the appropriate hotline immediately. Tap a number to call.</p>
        </div>
        <a class="home-emergency-all" href="emergency-contacts.php">All emergency contacts <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
      </div>
      <div class="home-emergency-grid">
        <?php if (is_array($emergencyData) && count($emergencyData) > 0): ?>
          <?php foreach ($emergencyData as $hotline):
            $kind = strtolower((string)($hotline['type'] ?? 'general'));
            $symbols = ['general'=>'fa-phone-volume', 'police'=>'fa-shield-halved', 'fire'=>'fa-fire-flame-curved', 'hospital'=>'fa-hospital', 'disaster'=>'fa-house-flood-water', 'medical'=>'fa-kit-medical'];
            $symbol = $symbols[$kind] ?? 'fa-phone';
            $number = trim((string)($hotline['phone'] ?? ''));
            $dial = preg_replace('/[^0-9+]/', '', preg_split('/\\s+ext\\.?\\s*/i', $number)[0]);
          ?>
          <article class="home-emergency-card">
            <span class="home-emergency-icon" aria-hidden="true"><i class="fas <?= htmlspecialchars($symbol, ENT_QUOTES, 'UTF-8') ?>"></i></span>
            <h3><?= htmlspecialchars((string)($hotline['name'] ?? 'Emergency Hotline'), ENT_QUOTES, 'UTF-8') ?></h3>
            <p><?= htmlspecialchars((string)($hotline['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
            <?php if ($dial !== ''): ?>
              <a class="home-emergency-call" href="tel:<?= htmlspecialchars($dial, ENT_QUOTES, 'UTF-8') ?>" aria-label="Call <?= htmlspecialchars((string)($hotline['name'] ?? 'Emergency Hotline'), ENT_QUOTES, 'UTF-8') ?> at <?= htmlspecialchars($number, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-phone" aria-hidden="true"></i> <?= htmlspecialchars($number, ENT_QUOTES, 'UTF-8') ?></a>
            <?php endif; ?>
          </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="home-emergency-empty">Emergency contact information is currently unavailable. For urgent emergencies, call <a href="tel:911">911</a>.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <footer class="portal-footer">
    <div class="footer-inner">
      <div class="footer-brand"><img src="Logo.png" alt="Koronadal City">
        <div><strong>City of Koronadal</strong><span>Government Services Portal</span></div>
      </div>
      <div class="footer-copy">Official digital access to city services, information, assistance and citizen resources.
      </div>
      <div class="footer-links"><a href="#services">Services</a><a href="#services">Digital Portals</a><a
          href="https://koronadal.gov.ph/" target="_blank" rel="noopener noreferrer">City Info</a><a href="city-officials.php">Officials</a><a
          href="emergency-contacts.php">Emergency</a><a href="https://koronadal.gov.ph/" target="_blank">LGU Website</a>
      </div>
      <div class="footer-bottom"><span>© <?= date('Y') ?> City Government of Koronadal</span><span>Official City
          Portal</span></div>
    </div>
  </footer>
  <nav class="bottom-nav" aria-label="Portal navigation"><a href="#home" class="active" aria-label="Home"><i
        class="fas fa-house"></i><span>Home</span></a><a href="city-officials.php" aria-label="City leadership"><i
        class="fas fa-users"></i><span>Officials</span></a><a href="#services" class="center"
      aria-label="Browse services"><i class="fas fa-grip"></i><span>Services</span></a><a href="https://koronadal.gov.ph/" target="_blank" rel="noopener noreferrer"
      aria-label="City information"><i class="fas fa-city"></i><span>City</span></a><a href="emergency-contacts.php"
      aria-label="Emergency contacts"><i class="fas fa-phone-volume"></i><span>Help</span></a></nav>
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
      search.oninput = function () { var q = search.value.toLowerCase().trim(), n = 0; document.querySelectorAll('.service-card').forEach(function (c) { var ok = !q || (c.innerText + ' ' + (c.dataset.search || '')).toLowerCase().includes(q); c.hidden = !ok; if (ok) n++ }); empty.classList.toggle('show', !!q && !n);  };
      document.getElementById('clearSearch').onclick = function () { search.value = ''; search.oninput(); search.focus() };
      document.getElementById('submitSearch').onclick = function () { search.oninput(); document.getElementById('services').scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }) };
      search.addEventListener('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); document.getElementById('submitSearch').click() } });
      document.querySelectorAll('a[href^="#"]').forEach(function (a) { a.onclick = function (e) { var hash = a.getAttribute('href'); if (!hash || hash === '#') return; var t = document.getElementById(hash.slice(1)); if (t) { e.preventDefault(); t.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }); if (body.classList.contains('header-collapsed')) menuBtn.click() } } });
      var ids = ['home', 'services'], obs = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { document.querySelectorAll('.bottom-nav a').forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id) }); document.querySelectorAll('.desktop-nav a').forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id) }) } }) }, { rootMargin: '-45% 0px -45% 0px' });
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