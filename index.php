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
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="theme-color" content="#1769AA" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="Koronadal City" />
    <meta name="description" content="Koronadal City digital gateway for online government services, emergency contacts, city information and citizen resources." />
    <link rel="manifest" href="manifest.webmanifest" />
    <title>Koronadal City — Digital Gateway</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="static/css/koronadal-app.css" />
</head>

<body>
<header class="portal-header" id="portalHeader">
  <div class="header-inner">
    <a class="brand" href="#home"><img src="Logo.png" alt="City of Koronadal"><span><strong>City of Koronadal</strong><small>Digital Gateway</small></span></a>
    <nav class="desktop-nav"><a href="#home" class="active">Home</a><a href="#services">Services</a><a href="#information">City Info</a><a href="#leadership">Leadership</a><a href="emergency-contacts.php">Emergency</a></nav>
    <div class="header-actions"><span class="official-pill"><i class="fas fa-circle"></i> Official Portal</span><a class="header-service-btn" href="#services"><i class="fas fa-grid-2"></i> Services</a><button class="menu-btn" id="menuBtn"><i class="fas fa-bars"></i></button></div>
  </div><div class="brand-accent"><i></i><i></i><i></i></div>
</header>
<main class="portal-shell" id="home">
  <section class="home-hero">
  <div class="hero-content">
    <div class="hero-kicker"><span class="live-dot"></span> CITY OF KORONADAL</div>
    <h1>Maayung Adlaw,<br><b>Koronadaleño!</b></h1>
    <p>Your digital front door to city services, information and assistance.</p>
    <div class="hero-actions"><a href="#services" class="primary-action"><i class="fas fa-arrow-down"></i> Find a Service</a><a href="emergency-contacts.php" class="secondary-action"><i class="fas fa-phone-volume"></i> Emergency</a></div>
  </div>
</section>
  <div class="portal-search"><i class="fas fa-search"></i><input id="serviceSearch" type="search" placeholder="Search services, portals or city information..."><button id="clearSearch"><i class="fas fa-xmark"></i></button></div>
  <section class="quick-strip">
    <a href="https://citizen.koronadalcityonlineservices.com/" target="_blank"><span class="quick-icon blue"><i class="fas fa-id-card"></i></span><span><b>Citizen Portal</b><small>Online transactions</small></span><i class="fas fa-chevron-right"></i></a>
    <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank"><span class="quick-icon purple"><i class="fas fa-briefcase"></i></span><span><b>Job Portal</b><small>Find opportunities</small></span><i class="fas fa-chevron-right"></i></a>
    <a href="emergency-contacts.php"><span class="quick-icon red"><i class="fas fa-phone"></i></span><span><b>Emergency</b><small>Help is available</small></span><i class="fas fa-chevron-right"></i></a>
  </section>
  <section class="portal-section" id="services">
    <div class="section-heading"><div><span>START HERE</span><h2>What do you need today?</h2><p>Everything is grouped by what you want to accomplish.</p></div><span class="section-count">8 SERVICES</span></div>
    <div class="service-grid" id="serviceGrid">
      <a class="service-card" href="#portals" data-search="business permit licensing business"><span class="service-icon blue"><i class="fas fa-file-signature"></i></span><strong>Business</strong><small>Permits & licensing</small></a>
      <a class="service-card" href="https://citizen.koronadalcityonlineservices.com/" target="_blank" data-search="citizen id documents online services"><span class="service-icon cyan"><i class="fas fa-id-card"></i></span><strong>Citizen</strong><small>Records & transactions</small></a>
      <a class="service-card" href="https://jobs.koronadalcityonlineservices.com/" target="_blank" data-search="jobs career employment"><span class="service-icon purple"><i class="fas fa-briefcase"></i></span><strong>Jobs</strong><small>Careers & opportunities</small></a>
      <a class="service-card" href="#portals" data-search="health assistance social welfare medical"><span class="service-icon rose"><i class="fas fa-heart-pulse"></i></span><strong>Health</strong><small>Assistance & welfare</small></a>
      <a class="service-card" href="#portals" data-search="transport mtop tricycle traffic"><span class="service-icon indigo"><i class="fas fa-motorcycle"></i></span><strong>Transport</strong><small>MTOP & verification</small></a>
      <a class="service-card" href="#portals" data-search="agriculture farmers livelihood"><span class="service-icon green"><i class="fas fa-wheat-awn"></i></span><strong>Agriculture</strong><small>Farmers & livelihood</small></a>
      <a class="service-card" href="#information" data-search="tourism culture history places"><span class="service-icon teal"><i class="fas fa-compass"></i></span><strong>Tourism</strong><small>Places & culture</small></a>
      <a class="service-card emergency-card" href="emergency-contacts.php" data-search="emergency police fire hospital 911"><span class="service-icon red"><i class="fas fa-phone-volume"></i></span><strong>Emergency</strong><small>911 & local hotlines</small></a>
    </div><div class="search-empty" id="searchEmpty">No matching service found. Try business, citizen, jobs or emergency.</div>
  </section>
  <section class="portal-section" id="portals">
    <div class="section-heading"><div><span>DIGITAL PORTALS</span><h2>Go directly to a service</h2><p>One place for the city's most-used online systems.</p></div></div>
    <div class="portal-list">
      <a class="portal-row" href="https://koronadalcityportal.com/v2/login" target="_blank"><span class="portal-logo blue"><i class="fas fa-file-contract"></i></span><span class="portal-copy"><strong>Business Permits & Licensing</strong><small>Apply, renew and manage business permits.</small></span><span class="portal-badge">BUSINESS</span><i class="fas fa-arrow-up-right-from-square"></i></a>
      <a class="portal-row" href="https://traffic.koronadalcityonlineservices.com/" target="_blank"><span class="portal-logo indigo"><i class="fas fa-motorcycle"></i></span><span class="portal-copy"><strong>MTOP Verification</strong><small>Verify motorized tricycle operator permits.</small></span><span class="portal-badge">TRANSPORT</span><i class="fas fa-arrow-up-right-from-square"></i></a>
      <a class="portal-row" href="https://citizen.koronadalcityonlineservices.com/" target="_blank"><span class="portal-logo cyan"><i class="fas fa-id-card"></i></span><span class="portal-copy"><strong>Unified Citizen Services</strong><small>Access integrated citizen transactions.</small></span><span class="portal-badge">CITIZEN</span><i class="fas fa-arrow-up-right-from-square"></i></a>
      <a class="portal-row" href="https://jobs.koronadalcityonlineservices.com/" target="_blank"><span class="portal-logo purple"><i class="fas fa-briefcase"></i></span><span class="portal-copy"><strong>Koronadal Job Portal</strong><small>Browse available employment opportunities.</small></span><span class="portal-badge">JOBS</span><i class="fas fa-arrow-up-right-from-square"></i></a>
      <a class="portal-row" href="https://careers.koronadalcityonlineservices.com/" target="_blank"><span class="portal-logo violet"><i class="fas fa-user-tie"></i></span><span class="portal-copy"><strong>LGU Recruitment</strong><small>Recruitment and application information.</small></span><span class="portal-badge">CAREERS</span><i class="fas fa-arrow-up-right-from-square"></i></a>
      <a class="portal-row" href="https://koronadal.gov.ph/full-disclosure/" target="_blank"><span class="portal-logo green"><i class="fas fa-chart-column"></i></span><span class="portal-copy"><strong>Transparency & Full Disclosure</strong><small>Public reports and disclosure documents.</small></span><span class="portal-badge">OPEN DATA</span><i class="fas fa-arrow-up-right-from-square"></i></a>
    </div>
  </section>
  <section class="emergency-panel" id="emergency">
    <div class="emergency-main"><div class="emergency-symbol"><i class="fas fa-phone-volume"></i></div><div><span>EMERGENCY ASSISTANCE</span><h2>Need help right now?</h2><p>For urgent emergencies, call the appropriate hotline immediately.</p></div><a href="tel:911" class="emergency-call"><strong>911</strong><small>CALL NOW</small></a></div>
    <div class="hotline-grid"><?php foreach (array_slice($emergencyData, 1, 3) as $hotline): ?><a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $hotline['phone'] ?? '')) ?>"><i class="fas fa-phone"></i><span><b><?= htmlspecialchars($hotline['name'] ?? '') ?></b><small><?= htmlspecialchars($hotline['phone'] ?? '') ?></small></span></a><?php endforeach; ?></div>
    <a class="panel-link" href="emergency-contacts.php">View all emergency contacts <i class="fas fa-arrow-right"></i></a>
  </section>
  <section class="portal-section leadership-section" id="leadership">
    <div class="section-heading"><div><span>CITY LEADERSHIP</span><h2>Mayor's Corner</h2><p>Leadership, service and a smarter Koronadal.</p></div><a class="text-link" href="city-officials.php">All officials <i class="fas fa-arrow-right"></i></a></div>
    <div class="mayor-card"><div class="mayor-copy"><div class="mayor-label"><i class="fas fa-landmark"></i> CITY MAYOR</div><h3><?= htmlspecialchars($mayor['name'] ?? 'HON. ERLINDA PABI-ARAQUIL') ?></h3><p><?= htmlspecialchars($mayor['ordinance'] ?? 'Spearheading digital governance and smart city initiatives for Koronadal City') ?></p><div class="mayor-motto"><i class="fas fa-quote-left"></i><span>“Genuine Service for God and for the People... EPAdayon Ang Kanami Sang Bagong Koronadal”</span></div><div class="mayor-meta"><span><i class="fas fa-building"></i> City Government of Koronadal</span><a href="city-officials.php">Meet the city officials <i class="fas fa-arrow-right"></i></a></div></div><div class="mayor-photo"><img src="Mayor_bg.png" alt="<?= htmlspecialchars($mayor['name'] ?? 'City Mayor') ?>"><span>Mayor's Corner</span></div></div>
  </section>
  <section class="portal-section" id="information">
    <div class="section-heading"><div><span>KNOW YOUR CITY</span><h2>Koronadal at a glance</h2><p>Useful information for residents, visitors and partners.</p></div></div>
    <div class="info-grid">
      <a href="city-officials.php" class="info-card"><span><i class="fas fa-building-columns"></i></span><strong>City Offices & Officials</strong><small>Find offices, leaders and responsibilities.</small><i class="fas fa-arrow-right"></i></a>
      <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" class="info-card"><span><i class="fas fa-book-open"></i></span><strong>Citizen's Charter</strong><small>Requirements, steps and processing information.</small><i class="fas fa-arrow-right"></i></a>
      <a href="https://koronadal.gov.ph/city-history/" target="_blank" class="info-card"><span><i class="fas fa-landmark"></i></span><strong>City History</strong><small>Discover Koronadal's history and identity.</small><i class="fas fa-arrow-right"></i></a>
      <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" class="info-card"><span><i class="fas fa-map-location-dot"></i></span><strong>27 Barangays</strong><small>Explore the city's communities and local resources.</small><i class="fas fa-arrow-right"></i></a>
    </div>
  </section>
  <section class="citizen-banner"><div class="citizen-badge"><i class="fas fa-id-card"></i></div><div><span>CITIZEN ACCESS</span><h2>Continue your city transaction</h2><p>Use the Citizen Portal for online services and digital transactions.</p></div><a href="https://citizen.koronadalcityonlineservices.com/" target="_blank">Open Citizen Portal <i class="fas fa-arrow-right"></i></a></section>
</main>
<footer class="portal-footer"><div class="footer-inner"><div class="footer-brand"><img src="Logo.png" alt="Koronadal City"><div><strong>City of Koronadal</strong><span>Digital Gateway</span></div></div><div class="footer-copy">Official digital access to city services, information, assistance and citizen resources.</div><div class="footer-links"><a href="#services">Services</a><a href="#portals">Digital Portals</a><a href="#information">City Info</a><a href="city-officials.php">Officials</a><a href="emergency-contacts.php">Emergency</a><a href="https://koronadal.gov.ph/" target="_blank">LGU Website</a></div><div class="footer-bottom"><span>© <?= date('Y') ?> City Government of Koronadal</span><span>Official City Portal</span></div></div></footer>
<nav class="bottom-nav"><a href="#home" class="active"><i class="fas fa-house"></i><span>Home</span></a><a href="#services"><i class="fas fa-grid-2"></i><span>Services</span></a><a href="#portals" class="center"><i class="fas fa-window-restore"></i><span>Portals</span></a><a href="#information"><i class="fas fa-city"></i><span>City</span></a><a href="emergency-contacts.php"><i class="fas fa-phone-volume"></i><span>Help</span></a></nav>
<div class="view-switcher" id="viewSwitcher"><button class="view-trigger" id="viewTrigger"><i class="fas fa-sliders"></i></button><div class="view-menu"><button data-view="auto" class="active">Auto</button><button data-view="mobile">Mobile</button><button data-view="desktop">Desktop</button></div></div>
<script>
(function(){
 var body=document.body,drawer=document.getElementById('mobileDrawer'),back=document.getElementById('drawerBackdrop');
 var search=document.getElementById('serviceSearch'), empty=document.getElementById('searchEmpty');
 search.oninput=function(){var q=search.value.toLowerCase().trim(),n=0;document.querySelectorAll('.service-card').forEach(function(c){var ok=!q||(c.innerText+' '+(c.dataset.search||'')).toLowerCase().includes(q);c.hidden=!ok;if(ok)n++});empty.classList.toggle('show',!!q&&!n)};
 document.getElementById('clearSearch').onclick=function(){search.value='';search.oninput();search.focus()};
 document.querySelectorAll('a[href^="#"]').forEach(function(a){a.onclick=function(e){var t=document.querySelector(a.getAttribute('href'));if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})}}});
 var ids=['home','services','portals','information','emergency','leadership'], obs=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){document.querySelectorAll('.bottom-nav a').forEach(function(a){a.classList.toggle('active',a.getAttribute('href')==='#'+e.target.id)});document.querySelectorAll('.desktop-nav a').forEach(function(a){a.classList.toggle('active',a.getAttribute('href')==='#'+e.target.id)})}})},{rootMargin:'-45% 0px -45% 0px'});
 ids.forEach(function(id){var el=document.getElementById(id);if(el)obs.observe(el)});
 var sw=document.getElementById('viewSwitcher');document.getElementById('viewTrigger').onclick=function(e){e.stopPropagation();sw.classList.toggle('open')};
 document.addEventListener('click',function(e){if(!sw.contains(e.target))sw.classList.remove('open')});
 document.querySelectorAll('.view-menu button').forEach(function(b){b.onclick=function(){body.classList.remove('force-mobile','force-desktop');if(b.dataset.view==='mobile')body.classList.add('force-mobile');if(b.dataset.view==='desktop')body.classList.add('force-desktop');localStorage.setItem('kdc-view',b.dataset.view);document.querySelectorAll('.view-menu button').forEach(function(x){x.classList.toggle('active',x===b)});sw.classList.remove('open')}});
 var saved=localStorage.getItem('kdc-view')||'auto',sb=document.querySelector('.view-menu button[data-view="'+saved+'"]');if(sb)sb.click();
})();
</script>
</body></html>