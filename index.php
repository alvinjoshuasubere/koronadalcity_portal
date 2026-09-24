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

    <!-- FLOATING ORBS -->
    <div class="floating-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- TOP NAV -->
    <nav class="topnav" id="topnav">
        <div class="topnav-in">
            <a class="brand" href="#">
                <img src="Logo.png" alt="Koronadal City" class="brand-img" />
                <div class="brand-text"><strong>City of Koronadal</strong><small>Online Services</small></div>
            </a>
            <ul class="nav-links">
                <li><a href="#home" class="on" data-nav="home">Home</a></li>
                <li><a href="#egov-services" data-nav="services">Services</a></li>
                <li><a href="/emergency-contacts.php">Emergency</a></li>
                <li><a href="/city-officials.php">Officials</a></li>
                <li><a href="/mayor-corner.php">Mayor's Corner</a></li>
                <li><a href="#egov-all-services" data-nav="quick">Quick Access</a></li>
                <li><a href="https://koronadal.gov.ph/" target="_blank">LGU Website</a></li>
            </ul>
            <div class="topnav-r">
                <span class="topnav-status"><i class="fas fa-circle"></i> Official City Portal</span>
                <a href="#egov-services" class="nav-cta"><i class="fas fa-th-large" style="font-size:.5rem"></i> Services</a>
                <button class="burger" id="burgerBtn" aria-label="Menu"><i class="fas fa-bars"></i></button>
            </div>
        </div>
    </nav>

    <!-- MOBILE DRAWER -->
    <div class="mnav-overlay" id="mnavOverlay"></div>
    <div class="mnav" id="mnav">
        <div class="mnav-head">
            <a class="brand" href="#">
                <img src="Logo.png" alt="Koronadal City" class="brand-img" />
                <div class="brand-text"><strong>City of Koronadal</strong><small>Online Services</small></div>
            </a>
            <button class="mnav-x" id="mnavClose" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <div class="mnav-links">
            <a href="#home" class="on"><i class="fas fa-home"></i> Home</a>
            <a href="#egov-services"><i class="fas fa-th-large"></i> Services</a>
            <a href="/emergency-contacts.php"><i class="fas fa-phone-alt"></i> Emergency</a>
            <a href="/city-officials.php"><i class="fas fa-users"></i> Officials</a>
            <a href="/mayor-corner.php"><i class="fas fa-landmark"></i> Mayor's Corner</a>
            <a href="#egov-all-services"><i class="fas fa-link"></i> Quick Access</a>
            <a href="https://koronadal.gov.ph/" target="_blank"><i class="fas fa-globe"></i> LGU Website</a>
        </div>
        <div class="mnav-foot">
            <a href="#egov-services"><i class="fas fa-arrow-pointer" style="font-size:.6rem"></i> Browse Services</a>
        </div>
    </div>

    <!-- APP CONTENT -->
    <div class="app">



        <!-- FULL KORONADAL Digital Gateway app SHELL -->
        <main class="egov-full-app" id="Digital GatewayFullApp">
            <section class="egov-app-body">
                <div class="egov-welcome-row"><div><span class="egov-kicker">KORONADAL CITY</span><h1>Maayung Adlaw!<br><b>Koronadaleño!</b></h1></div><div class="egov-weather"><i class="fas fa-sun"></i><span>Koronadal<br><small>South Cotabato</small></span></div></div>
                <div class="egov-search"><i class="fas fa-search"></i><input id="Digital GatewaySearch" type="search" placeholder="What do you need today?" aria-label="Search city services"><button type="button" aria-label="Clear search" onclick="document.getElementById('Digital GatewaySearch').value='';document.getElementById('Digital GatewaySearch').dispatchEvent(new Event('input'));"><i class="fas fa-xmark"></i></button></div>
                <section class="egov-hero-card"><div class="egov-hero-copy"><small>YOUR DIGITAL CITY GATEWAY</small><h2>Koronadal City<br><b>Digital Gateway Services</b></h2><p>Access government services, information and assistance wherever you are.</p><a href="#egov-services">Explore services <i class="fas fa-arrow-right"></i></a></div><div class="egov-city-art gateway-nature" aria-hidden="true"><div class="nature-sun"></div><div class="nature-cloud cloud-1"></div><div class="nature-cloud cloud-2"></div><div class="nature-mountain mountain-back"></div><div class="nature-mountain mountain-front"></div><div class="nature-field field-back"></div><div class="nature-field field-front"></div><div class="nature-tree tree-left"><i></i><i></i><i></i></div><div class="nature-tree tree-right"><i></i><i></i><i></i></div></div></section>
        <!-- CITY LEADERSHIP — compact app placement -->
                <section class="leadership" id="officials">
                    <div class="sec-pad">
                        <div class="home-section-head home-section-head-centered home-mayor-section-head a">
                            <div class="home-section-icon"><i class="fas fa-landmark"></i></div>
                            <div>
                                <span>LEADERSHIP</span>
                                <h2>Mayor's Corner</h2>
                                <p>Building a Smarter Koronadal</p>
                            </div>
                        </div>

                        <?php
                        $mayorData = null;
                        $viceMayorData = null;
                        $councilors = [];
                        if (!empty($officialsData) && is_array($officialsData)) {
                            foreach ($officialsData as $off) {
                                $pos = strtolower($off['position'] ?? '');
                                if (strpos($pos, 'mayor') !== false && strpos($pos, 'vice') === false) {
                                    $mayorData = $off;
                                } elseif (strpos($pos, 'vice') !== false) {
                                    $viceMayorData = $off;
                                } else {
                                    $councilors[] = $off;
                                }
                            }
                        }
                        ?>

                        <div class="home-mayor-card a d1">
                            <div class="home-mayor-content">
                                <div class="home-mayor-heading">
                                    <div>
                                        <span class="home-mayor-kicker">MAYOR'S CORNER</span>
                                        <h3><?= htmlspecialchars($mayorData['name'] ?? 'Hon. Erlinda "Bing" Pabi-Araquil') ?></h3>
                                    </div>
                                    <span class="home-mayor-seal">CITY MAYOR</span>
                                </div>
                                <p class="home-mayor-desc">
                                    <?= !empty($mayorData['ordinance'])
                                        ? htmlspecialchars($mayorData['ordinance'])
                                        : 'Leading Koronadal City with genuine service, transparency, innovation, and community-centered governance.' ?>
                                </p>
                                <div class="home-mayor-motto">
                                    <i class="fas fa-quote-left"></i>
                                    <span>“Genuine Service for God and for the People... EPAdayon Ang Kanami Sang Bagong Koronadal”</span>
                                </div>
                                <?php if (!empty($mayorData['committee'])): ?>
                                <div class="home-mayor-tags">
                                    <?php foreach (array_slice(array_map('trim', explode(',', $mayorData['committee'])), 0, 3) as $tag): ?>
                                        <span><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="home-mayor-cover">
                                <img src="Mayor_bg.png" alt="<?= htmlspecialchars($mayorData['name'] ?? 'City Mayor') ?>" />
                                <div class="home-mayor-cover-shade"></div>
                                <span class="home-mayor-status">City Mayor</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="egov-services" class="egov-module egov-services-section">
                    <div class="egov-module-title">
                        <div><small>LGU SERVICES</small><h2>What can we help you with?</h2></div>
                        <a href="#egov-all-services">View all <i class="fas fa-chevron-right"></i></a>
                    </div>
                    <p class="egov-section-note">Choose a service below to start your city transaction or find the right office.</p>
                    <div class="egov-service-grid" id="egovServiceGrid">
                        <a href="#egov-all-services" data-service="business permits licensing" class="egov-service"><span class="svc-icon blue"><i class="fas fa-file-signature"></i></span><b>Business Permits</b><small>Licensing</small></a>
                        <a href="https://citizen.koronadalcityonlineservices.com/" target="_blank" rel="noopener" data-service="citizen services documents" class="egov-service"><span class="svc-icon cyan"><i class="fas fa-id-card"></i></span><b>Citizen Services</b><small>Online transactions</small></a>
                        <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank" rel="noopener" data-service="jobs careers employment" class="egov-service"><span class="svc-icon purple"><i class="fas fa-briefcase"></i></span><b>Jobs & Careers</b><small>Opportunities</small></a>
                        <a href="#egov-all-services" data-service="health social assistance welfare" class="egov-service"><span class="svc-icon pink"><i class="fas fa-heart-pulse"></i></span><b>Health & Assistance</b><small>Social services</small></a>
                        <a href="#egov-all-services" data-service="transport mtop traffic" class="egov-service"><span class="svc-icon indigo"><i class="fas fa-motorcycle"></i></span><b>Transport</b><small>MTOP services</small></a>
                        <a href="#egov-all-services" data-service="agriculture farmers livelihood" class="egov-service"><span class="svc-icon green"><i class="fas fa-wheat-awn"></i></span><b>Agriculture</b><small>Farmers & livelihood</small></a>
                        <a href="#egov-all-services" data-service="tourism culture events" class="egov-service"><span class="svc-icon teal"><i class="fas fa-compass"></i></span><b>Tourism & Culture</b><small>Discover the city</small></a>
                        <a href="emergency-contacts.php" data-service="emergency report korona 911" class="egov-service"><span class="svc-icon red"><i class="fas fa-phone-volume"></i></span><b>Emergency</b><small>Korona 911 • 24/7</small></a>
                    </div>
                </section>
                <section class="egov-module egov-discover-section">
                    <div class="egov-module-title"><div><small>DISCOVER KORONADAL</small><h2>City information</h2></div></div>
                    <div class="egov-feature-row">
                        <a class="egov-feature" href="city-officials.php"><span class="feature-tag">DIRECTORY</span><h3>City Offices &<br>Officials</h3><p>Find the right office and contact information</p><i class="fas fa-building-columns feature-mark"></i></a>
                        <a class="egov-feature" href="https://koronadal.gov.ph/citizens-charter/" target="_blank" rel="noopener"><span class="feature-tag">GUIDE</span><h3>Citizen's<br>Charter</h3><p>Requirements, steps and processing times</p><i class="fas fa-book-open feature-mark"></i></a>
                        <a class="egov-feature" href="emergency-contacts.php"><span class="feature-tag">SAFETY</span><h3>Emergency<br>Contacts</h3><p>Police, fire, hospital and disaster assistance</p><i class="fas fa-shield-heart feature-mark"></i></a>
                        <a class="egov-feature" href="https://koronadal.gov.ph/city-history/" target="_blank" rel="noopener"><span class="feature-tag">HERITAGE</span><h3>City<br>History</h3><p>Learn about Koronadal's history and identity</p><i class="fas fa-landmark feature-mark"></i></a>
                        <a class="egov-feature" href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener"><span class="feature-tag">COMMUNITY</span><h3>27<br>Barangays</h3><p>Community information and local resources</p><i class="fas fa-map-location-dot feature-mark"></i></a>
                    </div>
                </section>
                <section id="egov-all-services" class="egov-all-services"><div class="egov-module-title"><div><small>ALL LGU SERVICES</small><h2>Explore City Services</h2></div></div><p class="egov-section-note">More services and online portals available from the City Government of Koronadal.</p><div class="egov-list">
                    <a href="https://koronadalcityportal.com/v2/login" target="_blank" rel="noopener"><span class="list-icon blue"><i class="fas fa-file-contract"></i></span><div><strong>Business Permits & Licensing</strong><small>Apply, renew and manage business permits</small></div><i class="fas fa-chevron-right"></i></a>
                    <a href="https://traffic.koronadalcityonlineservices.com/" target="_blank" rel="noopener"><span class="list-icon orange"><i class="fas fa-motorcycle"></i></span><div><strong>MTOP Verification</strong><small>Verify motorized tricycle operator permits</small></div><i class="fas fa-chevron-right"></i></a>
                    <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank" rel="noopener"><span class="list-icon purple"><i class="fas fa-briefcase"></i></span><div><strong>Koronadal Job Portal</strong><small>Browse city government opportunities</small></div><i class="fas fa-chevron-right"></i></a>
                    <a href="https://careers.koronadalcityonlineservices.com/" target="_blank" rel="noopener"><span class="list-icon indigo"><i class="fas fa-user-tie"></i></span><div><strong>LGU Recruitment</strong><small>Track applications and recruitment</small></div><i class="fas fa-chevron-right"></i></a>
                    <a href="https://citizen.koronadalcityonlineservices.com/" target="_blank" rel="noopener"><span class="list-icon cyan"><i class="fas fa-database"></i></span><div><strong>Unified Citizen Services</strong><small>Access integrated city transactions</small></div><i class="fas fa-chevron-right"></i></a>
                    <a href="https://koronadal.gov.ph/full-disclosure/" target="_blank" rel="noopener"><span class="list-icon green"><i class="fas fa-chart-line"></i></span><div><strong>Transparency & Full Disclosure</strong><small>Public reports and financial information</small></div><i class="fas fa-chevron-right"></i></a>
                </div></section>
                <section id="account" class="egov-account"><div class="account-avatar"><img src="Logo.png" alt="Koronadal City"></div><div><small>KORONADAL CITY</small><h2>Citizen Account</h2><p>Sign in to access personalized transactions, applications and service history.</p></div><a href="https://citizen.koronadalcityonlineservices.com/" target="_blank" rel="noopener">Open Citizen Portal <i class="fas fa-arrow-right"></i></a></section>
            </section>
        </main>
    <!-- HERO -->
        <section class="hero" id="home">
            <div class="hero-bg"></div>
            <div class="hero-glow-ring"></div>
            <!-- 3D Parallax Shapes -->
            <div class="hero-parallax" id="heroParallax">
                <div class="hp-layer" data-speed="0.02" style="top:15%;left:10%">
                    <div class="hp-shape hex"></div>
                </div>
                <div class="hp-layer" data-speed="0.04" style="top:60%;left:8%">
                    <div class="hp-shape diamond"></div>
                </div>
                <div class="hp-layer" data-speed="0.03" style="top:25%;right:12%">
                    <div class="hp-shape circle"></div>
                </div>
                <div class="hp-layer" data-speed="0.05" style="top:70%;right:15%">
                    <div class="hp-shape tri"></div>
                </div>
                <div class="hp-layer" data-speed="0.02" style="top:40%;left:50%">
                    <div class="hp-shape hex" style="width:40px;height:40px;animation-duration:18s"></div>
                </div>
                <div class="hp-layer" data-speed="0.06" style="top:80%;left:45%">
                    <div class="hp-shape dot"></div>
                </div>
                <div class="hp-layer" data-speed="0.03" style="top:10%;left:70%">
                    <div class="hp-shape dot" style="width:4px;height:4px"></div>
                </div>
                <div class="hp-layer" data-speed="0.04" style="top:50%;right:5%">
                    <div class="hp-shape diamond" style="width:25px;height:25px"></div>
                </div>
                <div class="hp-layer" data-speed="0.05" style="top:35%;left:25%">
                    <div class="hp-shape dot"
                        style="width:5px;height:5px;background:rgba(23, 105, 170, .3);box-shadow:0 0 8px rgba(23, 105, 170, .2)">
                    </div>
                </div>
            </div>
            <div class="hero-inner">
                <h1>Maayung Adlaw<br /><span class="hl">Koronadale&ntilde;o!</span></h1>
                <p class="hero-sub">Your digital gateway to city services, emergency assistance, public information and everyday government transactions.</p>
                <div class="hero-btns">
                    <a href="#citizen-hub" class="hb hb-w"><i class="fas fa-grid-2" style="font-size:.6rem"></i>
                        Explore Koronadal</a>
                    <a href="emergency-contacts.php" class="hb hb-o"><i class="fas fa-phone" style="font-size:.6rem"></i>
                        Emergency</a>
                </div>
            </div>
        </section>

        <!-- CITY OVERVIEW — 3D Glass Card -->
        <section class="city-overview a d1">
            <div class="sec-pad">
                <div class="co-card">
                    <div class="co-left">
                        <div class="co-label"><i class="fas fa-location-dot"></i> South Cotabato, Philippines</div>
                        <h2 class="co-title">Crown City of the South</h2>
                        <p class="co-desc">Koronadal City, officially the City of Koronadal and also known as Marbel, is
                            a 1st class component city and capital of the province of South Cotabato, Philippines. Known
                            for its rich cultural heritage, vibrant economy, and natural beauty.</p>
                        <div class="co-tags">
                            <span class="leader-tag">SOCCSKSARGEN</span>
                            <span class="leader-tag">Region XII</span>
                            <span class="leader-tag">South Cotabato</span>
                        </div>
                    </div>
                    <div class="co-right">
                        <div class="co-stat">
                            <div class="co-stat-num">27</div>
                            <div class="co-stat-lbl">Barangays</div>
                        </div>
                        <div class="co-stat">
                            <div class="co-stat-num">201K+</div>
                            <div class="co-stat-lbl">Population</div>
                        </div>
                        <div class="co-stat">
                            <div class="co-stat-num">277km²</div>
                            <div class="co-stat-lbl">Land Area</div>
                        </div>
                        <div class="co-stat">
                            <div class="co-stat-num">451m</div>
                            <div class="co-stat-lbl">Elevation</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CITY MOTTO — 3D Parallax -->
        <section class="city-motto a d2">
            <div class="sec-pad">
                <div class="cm-card">
                    <div class="cm-deco cm-deco-1"></div>
                    <div class="cm-deco cm-deco-2"></div>
                    <div class="cm-quote">"<span class="hl">EPAdayon ang Kanami sang Bagong Koronadal</span>"</div>
                    <div class="cm-attr">City of Koronadal Official Motto</div>
                </div>
            </div>
        </section>

        <!-- 3D CITY FEATURES -->
        <section class="city-features a d3">
            <div class="sec-pad">
                <div class="sec-head a">
                    <div class="sec-label"><i class="fas fa-star"></i> Discover Koronadal</div>
                    <h2>What Makes Us Unique</h2>
                </div>
                <div class="cf-grid">
                    <div class="cf-card a d1">
                        <div class="cf-icon"><i class="fas fa-mountain"></i></div>
                        <div class="cf-name">Natural Beauty</div>
                        <div class="cf-desc">Nestled in the valley surrounded by mountains, with lush greenery and cool
                            climate year-round</div>
                    </div>
                    <div class="cf-card a d2">
                        <div class="cf-icon"><i class="fas fa-users-rectangle"></i></div>
                        <div class="cf-name">Cultural Heritage</div>
                        <div class="cf-desc">Home to diverse cultures — B'laan, T'boli, Ilonggo, and Tagalog communities
                            living in harmony</div>
                    </div>
                    <div class="cf-card a d3">
                        <div class="cf-icon"><i class="fas fa-seedling"></i></div>
                        <div class="cf-name">Garden City</div>
                        <div class="cf-desc">Known as the Spring Garden City with abundant flowers, parks, and green
                            public spaces</div>
                    </div>
                    <div class="cf-card a d1">
                        <div class="cf-icon"><i class="fas fa-store"></i></div>
                        <div class="cf-name">Economic Hub</div>
                        <div class="cf-desc">Regional commercial center of SOCCSKSARGEN with thriving trade and industry
                        </div>
                    </div>
                    <div class="cf-card a d2">
                        <div class="cf-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="cf-name">Education Center</div>
                        <div class="cf-desc">Home to universities and colleges serving students across the region</div>
                    </div>
                    <div class="cf-card a d3">
                        <div class="cf-icon"><i class="fas fa-handshake"></i></div>
                        <div class="cf-name">Peace & Unity</div>
                        <div class="cf-desc">A model of interfaith dialogue and peaceful coexistence among diverse
                            communities</div>
                    </div>
                </div>
            </div>
        </section>

<!-- CITIZEN HUB — MyNaga-inspired citizen-first gateway -->
        <section class="citizen-hub a d2" id="citizen-hub">
            <div class="sec-pad">
                <div class="citizen-hub-card">
                    <div class="citizen-hub-head">
                        <div>
                            <div class="sec-label"><i class="fas fa-sparkles"></i> Koronadal Digital Gateway</div>
                            <h2>Everything you need from the City, in one place.</h2>
                            <p>Find services, emergency contacts, public information and official city resources without having to search through multiple pages.</p>
                        </div>
                        <div class="hub-badge"><i class="fas fa-mobile-screen-button"></i><span>Mobile ready</span></div>
                    </div>

                    <div class="hub-search">
                        <i class="fas fa-magnifying-glass"></i>
                        <input id="serviceSearch" type="search" placeholder="Search services, permits, jobs, health, ordinances..." aria-label="Search Koronadal City services">
                        <button id="clearServiceSearch" type="button" aria-label="Clear service search"><i class="fas fa-xmark"></i></button>
                    </div>

                    <div class="hub-actions" aria-label="Citizen shortcuts">
                        <a href="#services" class="hub-action action-blue">
                            <span class="hub-action-icon"><i class="fas fa-landmark"></i></span>
                            <span><strong>City Services</strong><small>Permits & online transactions</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="emergency-contacts.php" class="hub-action action-red">
                            <span class="hub-action-icon"><i class="fas fa-phone-volume"></i></span>
                            <span><strong>Emergency</strong><small>Korona 911 & hotlines</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" rel="noopener" class="hub-action action-green">
                            <span class="hub-action-icon"><i class="fas fa-book-open"></i></span>
                            <span><strong>Citizen's Charter</strong><small>Requirements & service guides</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="city-officials.php" class="hub-action action-orange">
                            <span class="hub-action-icon"><i class="fas fa-address-card"></i></span>
                            <span><strong>City Directory</strong><small>Offices & public officials</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank" rel="noopener" class="hub-action action-yellow">
                            <span class="hub-action-icon"><i class="fas fa-briefcase"></i></span>
                            <span><strong>Jobs</strong><small>Current opportunities</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="https://koronadal.gov.ph/ordinance/" target="_blank" rel="noopener" class="hub-action action-purple">
                            <span class="hub-action-icon"><i class="fas fa-scale-balanced"></i></span>
                            <span><strong>Ordinances</strong><small>Local laws & resolutions</small></span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div class="hub-info-grid">
                        <a href="https://koronadal.gov.ph/full-disclosure/" target="_blank" rel="noopener" class="hub-info">
                            <span class="hub-info-icon"><i class="fas fa-chart-line"></i></span>
                            <span><strong>Transparency</strong><small>Full disclosure & reports</small></span>
                        </a>
                        <a href="https://koronadal.gov.ph/culture/" target="_blank" rel="noopener" class="hub-info">
                            <span class="hub-info-icon"><i class="fas fa-camera-retro"></i></span>
                            <span><strong>Tourism & Culture</strong><small>Discover Koronadal</small></span>
                        </a>
                        <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener" class="hub-info">
                            <span class="hub-info-icon"><i class="fas fa-map-location-dot"></i></span>
                            <span><strong>27 Barangays</strong><small>Community information</small></span>
                        </a>
                        <a href="https://koronadal.gov.ph/" target="_blank" rel="noopener" class="hub-info">
                            <span class="hub-info-icon"><i class="fas fa-globe"></i></span>
                            <span><strong>Official Website</strong><small>News & announcements</small></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICES -->
        <section class="svc" id="services">
            <div class="sec-pad">
                <div class="sec-head a">
                    <div class="sec-label"><i class="fas fa-bolt"></i> Online Services</div>
                    <h2>Services</h2>
                </div>
                <div class="svc-filters a d1">
                    <button class="fb on" data-f="all">All Services</button>
                    <button class="fb" data-f="permits">Permits</button>
                    <button class="fb" data-f="info">Information</button>
                    <button class="fb" data-f="jobs">Jobs & Bids</button>
                    <button class="fb" data-f="safety">Safety & Health</button>
                    <button class="fb" data-f="trans">Transparency</button>
                </div>
                <div class="scnt a d1">Showing <b id="cnt">12</b> services</div>

                <!-- DESKTOP: PORTAL CARDS -->
                <div class="portals">
                    <a href="https://koronadalcityportal.com/v2/login" target="_blank" class="portal a d2"
                        data-c="permits">
                        <div class="portal-accent rose"></div>
                        <div class="portal-body">
                            <div class="portal-top">
                                <div class="portal-icon rose"><i class="fas fa-file-contract"></i></div>

                            </div>
                            <h3>Business Permits & Licensing</h3>
                            <p class="portal-desc">Apply, renew, and manage business permits online.</p>
                            <span class="portal-cta rose">Open Portal <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="https://traffic.koronadalcityonlineservices.com/" target="_blank" class="portal a d2"
                        data-c="safety">
                        <div class="portal-accent rose"></div>
                        <div class="portal-body">
                            <div class="portal-top">
                                <div class="portal-icon rose"><i class="fas fa-motorcycle"></i></div>

                            </div>
                            <h3>MTOP Verification Portal</h3>
                            <p class="portal-desc">Verify MTOP and View traffic violations online.</p>
                            <span class="portal-cta rose">Open Portal <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank" class="portal a d3"
                        data-c="jobs">
                        <div class="portal-accent rose"></div>
                        <div class="portal-body">
                            <div class="portal-top">
                                <div class="portal-icon rose"><i class="fas fa-briefcase"></i></div>

                            </div>
                            <h3>Job Portal</h3>
                            <p class="portal-desc">Browse and apply for city government job vacancies.</p>
                            <span class="portal-cta rose">Open Portal <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="https://careers.koronadalcityonlineservices.com/" target="_blank" class="portal a d3"
                        data-c="jobs">
                        <div class="portal-accent rose"></div>
                        <div class="portal-body">
                            <div class="portal-top">
                                <div class="portal-icon rose"><i class="fas fa-user-tie"></i></div>

                            </div>
                            <h3>LGU Recruitment Tracking</h3>
                            <p class="portal-desc">Apply online for government positions.</p>
                            <span class="portal-cta rose">Open Portal <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <a href="https://citizen.koronadalcityonlineservices.com/" target="_blank" class="portal a d3"
                        data-c="info">
                        <div class="portal-accent rose"></div>
                        <div class="portal-body">
                            <div class="portal-top">
                                <div class="portal-icon rose"><i class="fas fa-database"></i></div>

                            </div>
                            <h3>Unified Systems Portal</h3>
                            <p class="portal-desc">Access all city services in one unified platform.</p>
                            <span class="portal-cta rose">Open Portal <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <!-- FEATURED: Emergency & Officials -->
                <div class="svc-sec-head a d3">
                    <h3>Quick Access Services</h3>
                </div>
                <div class="svc-featured">
                    <a href="emergency-contacts.php" class="svc-feat-card svc-feat-emergency a d3" data-c="safety">
                        <div class="svc-feat-glow"></div>
                        <div class="svc-feat-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="svc-feat-body">
                            <h4>Emergency Contacts</h4>
                            <p>24/7 police, fire, hospital & disaster hotlines</p>
                        </div>
                        <div class="svc-feat-badge"><i class="fas fa-arrow-right"></i></div>
                    </a>
                    <a href="city-officials.php" class="svc-feat-card svc-feat-officials a d3" data-c="info">
                        <div class="svc-feat-glow"></div>
                        <div class="svc-feat-icon"><i class="fas fa-address-book"></i></div>
                        <div class="svc-feat-body">
                            <h4>Officials Directory</h4>
                            <p>City officials, ordinances & committees</p>
                        </div>
                        <div class="svc-feat-badge"><i class="fas fa-arrow-right"></i></div>
                    </a>
                </div>

                <!-- DESKTOP: SECONDARY SERVICES -->
                <div class="svc-sec-head a d3">
                    <h3>More Services</h3>
                </div>
                <div class="svc-sec">
                    <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" class="svc-sec-item a d3"
                        data-c="info">
                        <div class="svc-sec-icon rose"><i class="fas fa-book-open"></i></div>
                        <div class="svc-sec-body">
                            <h4>Citizen's Charter</h4>
                            <p>Service guides & timelines</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/bids-and-awards/" target="_blank" class="svc-sec-item a d3"
                        data-c="jobs">
                        <div class="svc-sec-icon rose"><i class="fas fa-gavel"></i></div>
                        <div class="svc-sec-body">
                            <h4>Bids & Awards</h4>
                            <p>Procurement opportunities</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/full-disclosure/" target="_blank" class="svc-sec-item a d3"
                        data-c="trans">
                        <div class="svc-sec-icon rose"><i class="fas fa-receipt"></i></div>
                        <div class="svc-sec-body">
                            <h4>Financial Reports</h4>
                            <p>Budget & transparency</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/fare-matrix/" target="_blank" class="svc-sec-item a d3"
                        data-c="info">
                        <div class="svc-sec-icon rose"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="svc-sec-body">
                            <h4>Fare Rates</h4>
                            <p>Transport fare matrix</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/culture-copy/" target="_blank" class="svc-sec-item a d3"
                        data-c="safety">
                        <div class="svc-sec-icon rose"><i class="fas fa-heartbeat"></i></div>
                        <div class="svc-sec-body">
                            <h4>Health Services</h4>
                            <p>Health facilities & programs</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/ordinance/" target="_blank" class="svc-sec-item a d3"
                        data-c="trans">
                        <div class="svc-sec-icon rose"><i class="fas fa-scale-balanced"></i></div>
                        <div class="svc-sec-body">
                            <h4>City Ordinances</h4>
                            <p>Local laws & resolutions</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/culture/" target="_blank" class="svc-sec-item a d3" data-c="info">
                        <div class="svc-sec-icon rose"><i class="fas fa-palette"></i></div>
                        <div class="svc-sec-body">
                            <h4>Tourism & Culture</h4>
                            <p>Heritage & events</p>
                        </div>
                        <i class="fas fa-chevron-right svc-sec-arrow"></i>
                    </a>
                </div>

                <!-- MOBILE LIST VIEW -->
                <div class="svc-list">
                    <!-- Portal cards -->
                    <a href="https://koronadalcityportal.com/v2/login" target="_blank" class="svc-portal a"
                        data-c="permits">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-file-contract"></i></div>
                            <div class="svc-portal-body">
                                <h4>Business Permits & Licensing</h4>
                                <p>Apply, renew & pay online</p>
                            </div>
                            <i class="fas fa-arrow-up-right-from-square svc-portal-arrow"></i>
                        </div>
                    </a>
                    <a href="https://traffic.koronadalcityonlineservices.com/" target="_blank" class="svc-portal a d1"
                        data-c="safety">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-car-crash"></i></div>
                            <div class="svc-portal-body">
                                <h4>MTOP Checker</h4>
                                <p>Search & settle violations</p>
                            </div>
                            <i class="fas fa-arrow-up-right-from-square svc-portal-arrow"></i>
                        </div>
                    </a>
                    <a href="https://jobs.koronadalcityonlineservices.com/" target="_blank" class="svc-portal a d1"
                        data-c="jobs">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-briefcase"></i></div>
                            <div class="svc-portal-body">
                                <h4>Job Portal</h4>
                                <p>Browse vacancies & apply</p>
                            </div>
                            <i class="fas fa-arrow-up-right-from-square svc-portal-arrow"></i>
                        </div>
                    </a>
                    <a href="https://careers.koronadalcityonlineservices.com/" target="_blank" class="svc-portal a d1"
                        data-c="jobs">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-user-tie"></i></div>
                            <div class="svc-portal-body">
                                <h4>LGU Recruitment Tracking</h4>
                                <p>Track recruitment applications & status</p>
                            </div>
                            <i class="fas fa-arrow-up-right-from-square svc-portal-arrow"></i>
                        </div>
                    </a>
                    <a href="https://citizen.koronadalcityonlineservices.com/" target="_blank" class="svc-portal a d1"
                        data-c="info">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-grid-2"></i></div>
                            <div class="svc-portal-body">
                                <h4>Unified Systems Portal</h4>
                                <p>Access all city services in one platform</p>
                            </div>
                            <i class="fas fa-arrow-up-right-from-square svc-portal-arrow"></i>
                        </div>
                    </a>

                    <!-- Featured: Emergency & Officials -->
                    <div class="svc-list-divider a d2">Quick Access Services</div>
                    <a href="emergency-contacts.php" class="svc-portal a d2" data-c="safety">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-phone-alt"></i></div>
                            <div class="svc-portal-body">
                                <h4>Emergency Contacts</h4>
                                <p>24/7 hotlines for safety & emergencies</p>
                            </div>
                            <i class="fas fa-arrow-right svc-portal-arrow"></i>
                        </div>
                    </a>
                    <a href="city-officials.php" class="svc-portal a d2" data-c="info">
                        <div class="svc-portal-accent rose"></div>
                        <div class="svc-portal-inner">
                            <div class="svc-portal-icon rose"><i class="fas fa-address-book"></i></div>
                            <div class="svc-portal-body">
                                <h4>Officials Directory</h4>
                                <p>City officials, ordinances & committees</p>
                            </div>
                            <i class="fas fa-arrow-right svc-portal-arrow"></i>
                        </div>
                    </a>

                    <!-- Secondary services -->
                    <div class="svc-list-divider a d2">More Services</div>
                    <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" class="svc-row a d2"
                        data-c="info">
                        <div class="svc-row-icon rose"><i class="fas fa-book-open"></i></div>
                        <div class="svc-row-body">
                            <h4>Citizen's Charter</h4>
                            <p>Service guides, requirements & timelines</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/bids-and-awards/" target="_blank" class="svc-row a d2"
                        data-c="jobs">
                        <div class="svc-row-icon rose"><i class="fas fa-gavel"></i></div>
                        <div class="svc-row-body">
                            <h4>Bids & Awards</h4>
                            <p>Procurement opportunities & invitations</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/full-disclosure/" target="_blank" class="svc-row a d3"
                        data-c="trans">
                        <div class="svc-row-icon rose"><i class="fas fa-receipt"></i></div>
                        <div class="svc-row-body">
                            <h4>Financial Reports</h4>
                            <p>Budget allocations & transparency</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/fare-matrix/" target="_blank" class="svc-row a d3" data-c="info">
                        <div class="svc-row-icon rose"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="svc-row-body">
                            <h4>Fare Rates</h4>
                            <p>Official public transport fare matrix</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/culture-copy/" target="_blank" class="svc-row a d3"
                        data-c="safety">
                        <div class="svc-row-icon rose"><i class="fas fa-heartbeat"></i></div>
                        <div class="svc-row-body">
                            <h4>Health Services</h4>
                            <p>Hospitals, health centers & programs</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/ordinance/" target="_blank" class="svc-row a d3" data-c="trans">
                        <div class="svc-row-icon rose"><i class="fas fa-scale-balanced"></i></div>
                        <div class="svc-row-body">
                            <h4>City Ordinances</h4>
                            <p>Local laws & council resolutions</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/culture/" target="_blank" class="svc-row a d3" data-c="info">
                        <div class="svc-row-icon rose"><i class="fas fa-palette"></i></div>
                        <div class="svc-row-body">
                            <h4>Tourism & Culture</h4>
                            <p>Attractions, heritage & events</p>
                        </div>
                        <i class="fas fa-chevron-right svc-row-arrow"></i>
                    </a>
                </div>
            </div>
        </section>


        <!-- QUICK ACCESS — MOBILE APP STYLE -->
        <section class="qacc home-quick" id="quick">
            <div class="sec-pad">
                

                <div class="home-promo-strip a d1">
                    <div class="home-promo-icon"><i class="fas fa-city"></i></div>
                    <div class="home-promo-copy">
                        <span>YOUR CITY • YOUR SERVICES</span>
                        <strong>Everything Koronadal, right at your fingertips.</strong>
                        <small>Discover city programs, public information, community resources and official services in one digital gateway.</small>
                    </div>
                    <a href="#Digital GatewayFullApp" aria-label="Explore Koronadal"><i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="home-quick-grid">
                    <a href="https://koronadal.gov.ph/" target="_blank" rel="noopener" class="home-quick-card a d1">
                        <span class="home-quick-icon blue"><i class="fas fa-globe"></i></span>
                        <span><strong>Official Website</strong><small>News & announcements</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/city-history/" target="_blank" rel="noopener" class="home-quick-card a d1">
                        <span class="home-quick-icon gold"><i class="fas fa-landmark"></i></span>
                        <span><strong>City History</strong><small>Heritage & origins</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/vision-mission/" target="_blank" rel="noopener" class="home-quick-card a d2">
                        <span class="home-quick-icon red"><i class="fas fa-bullseye"></i></span>
                        <span><strong>Vision & Mission</strong><small>Goals & direction</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener" class="home-quick-card a d2">
                        <span class="home-quick-icon green"><i class="fas fa-map-location-dot"></i></span>
                        <span><strong>27 Barangays</strong><small>Community information</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/geography/" target="_blank" rel="noopener" class="home-quick-card a d3">
                        <span class="home-quick-icon cyan"><i class="fas fa-globe-asia"></i></span>
                        <span><strong>Geography</strong><small>Location & climate</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/awards/" target="_blank" rel="noopener" class="home-quick-card a d3">
                        <span class="home-quick-icon purple"><i class="fas fa-trophy"></i></span>
                        <span><strong>City Awards</strong><small>Recognitions & milestones</small></span>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>

                <div class="home-promo-grid">
                    <a href="city-officials.php" class="home-promo-card a d2">
                        <span><i class="fas fa-users"></i></span>
                        <div><small>MEET THE TEAM</small><strong>City Officials</strong><p>Know the people serving Koronadal.</p></div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="emergency-contacts.php" class="home-promo-card danger a d2">
                        <span><i class="fas fa-phone-volume"></i></span>
                        <div><small>READY WHEN YOU NEED IT</small><strong>Emergency Assistance</strong><p>Find important hotlines and response contacts.</p></div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" rel="noopener" class="home-promo-card a d3">
                        <span><i class="fas fa-book-open"></i></span>
                        <div><small>KNOW YOUR SERVICE</small><strong>Citizen's Charter</strong><p>Requirements, steps and processing information.</p></div>
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="fgrid">
                <div>
                    <div class="f-brand"><img src="Logo.png" alt="Logo" /><strong>Koronadal City</strong></div>
                    <p class="f-desc">Your one-stop digital portal for Koronadal City government services.</p>
                    <div class="f-soc">
                        <a href="https://www.facebook.com/CityGovernmentofKoronadal" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="mailto:info.koronadalcity@gmail.com"><i class="fas fa-envelope"></i></a>
                        <a href="tel:(083)2286095"><i class="fas fa-phone"></i></a>
                    </div>
                </div>
                <div class="fcol">
                    <div class="fcol-t">Services</div>
                    <ul>
                        <li><a href="https://koronadalcityportal.com/v2/login" target="_blank">Business Permits</a></li>
                        <li><a href="https://traffic.koronadalcityonlineservices.com/" target="_blank">MTOP Checker</a>
                        </li>
                        <li><a href="https://jobs.koronadalcityonlineservices.com/" target="_blank">Job Openings</a>
                        </li>
                        <li><a href="https://careers.koronadalcityonlineservices.com/" target="_blank">LGU
                                Recruitment</a>
                        </li>
                        <li><a href="https://citizen.koronadalcityonlineservices.com/" target="_blank">Unified
                                Systems</a>
                        </li>
                    </ul>
                </div>
                <div class="fcol">
                    <div class="fcol-t">Government</div>
                    <ul>
                        <li><a href="city-officials.php">Officials Directory</a>
                        </li>
                        <li><a href="https://koronadal.gov.ph/citizens-charter/" target="_blank">Citizen's Charter</a>
                        </li>
                        <li><a href="https://koronadal.gov.ph/full-disclosure/" target="_blank">Financial Reports</a>
                        </li>
                    </ul>
                </div>
                <div class="fcol">
                    <div class="fcol-t">City Info</div>
                    <ul>
                        <li><a href="https://koronadal.gov.ph/culture/" target="_blank">Tourism</a></li>
                        <li><a href="https://koronadal.gov.ph/27-barangay/" target="_blank">Barangays</a></li>
                        <li><a href="https://koronadal.gov.ph/geography/" target="_blank">About the City</a></li>
                    </ul>
                </div>
            </div>
            <div class="f-bottom">
                <span class="f-copy">&copy; <?php echo date('Y'); ?> City Government of Koronadal. All rights
                    reserved.</span>
                <span class="f-motto">One People, One Dream, One Koronadal</span>
            </div>
        </footer>

    </div><!-- .app -->

    <!-- BOTTOM NAV -->
    <nav class="bottomnav" id="bottomnav">
        <div class="bn-row">
            <a href="#Digital GatewayFullApp" class="bn-item active" data-bnav="home"><span class="bn-icon"><i class="fas fa-house"></i></span><span class="bn-label">Home</span></a>
            <a href="#egov-services" class="bn-item" data-bnav="services"><span class="bn-icon"><i class="fas fa-th-large"></i></span><span class="bn-label">Services</span></a>
            <a href="#egov-services" class="bn-item bn-center" data-bnav="services-qr"><span class="bn-icon"><i class="fas fa-qrcode"></i></span><span class="bn-label">Scan</span></a>
            <a href="#egov-all-services" class="bn-item" data-bnav="news"><span class="bn-icon"><i class="fas fa-bullhorn"></i></span><span class="bn-label">News</span></a>
            <a href="#account" class="bn-item" data-bnav="account"><span class="bn-icon"><i class="fas fa-user"></i></span><span class="bn-label">Account</span></a>
        </div>
    </nav>

    <!-- VIEW TOGGLE -->
    <div class="view-toggle" id="viewToggle">
        <div class="view-toggle-menu">
            <button class="view-toggle-btn active" data-view="auto" id="viewAuto"><i class="fas fa-sync-alt"></i>
                Auto</button>
            <button class="view-toggle-btn" data-view="mobile" id="viewMobile"><i
                    class="fas fa-mobile-screen-button"></i> Mobile</button>
            <button class="view-toggle-btn" data-view="desktop" id="viewDesktop"><i class="fas fa-desktop"></i>
                Desktop</button>
        </div>
        <button class="view-toggle-trigger" id="viewTrigger" aria-label="Toggle view"><i
                class="fas fa-arrow-left"></i></button>
    </div>

    <script>
    // === THEME ===
    var html = document.documentElement;
    document.documentElement.setAttribute('data-theme', 'light');

    // === VIEW TOGGLE ===
    var viewBtns = document.querySelectorAll('.view-toggle-btn');
    var viewToggle = document.getElementById('viewToggle');
    var viewTrigger = document.getElementById('viewTrigger');

    function setViewMode(mode) {
        html.classList.remove('force-mobile', 'force-desktop');
        viewBtns.forEach(function(b) {
            b.classList.remove('active')
        });
        document.querySelector('[data-view="' + mode + '"]').classList.add('active');
        if (mode === 'mobile') html.classList.add('force-mobile');
        else if (mode === 'desktop') html.classList.add('force-desktop');
        try {
            localStorage.setItem('kdc-view', mode)
        } catch (e) {}
        viewToggle.classList.remove('open')
    }
    (function() {
        var v = null;
        try {
            v = localStorage.getItem('kdc-view')
        } catch (e) {}
        setViewMode(v || 'auto')
    })();
    viewTrigger.addEventListener('click', function() {
        viewToggle.classList.toggle('open')
    });
    viewBtns.forEach(function(b) {
        b.addEventListener('click', function() {
            setViewMode(this.dataset.view)
        })
    });
    document.addEventListener('click', function(e) {
        if (!viewToggle.contains(e.target)) viewToggle.classList.remove('open')
    });

    
    // === Digital Gateway app SERVICE SEARCH ===
    (function(){
        var input=document.getElementById('Digital GatewaySearch');
        var grid=document.getElementById('Digital GatewayServiceGrid');
        if(!input || !grid) return;
        input.addEventListener('input',function(){
            var q=(this.value||'').toLowerCase().trim();
            grid.querySelectorAll('.egov-service').forEach(function(item){
                var hay=(item.textContent+' '+(item.getAttribute('data-service')||'')).toLowerCase();
                item.style.display=(!q || hay.indexOf(q)!==-1)?'flex':'none';
            });
        });
    })();

    // === CITIZEN HUB SERVICE SEARCH ===
    var serviceSearch = document.getElementById('serviceSearch');
    var clearServiceSearch = document.getElementById('clearServiceSearch');
    if (serviceSearch) {
        function filterCitizenServices(term) {
            var q = (term || '').toLowerCase().trim();
            var cards = document.querySelectorAll('.portals .portal, .svc-sec .svc-sec-item, .svc-list .svc-portal, .svc-list .svc-row');
            cards.forEach(function(card) {
                var text = (card.textContent || '').toLowerCase();
                card.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
            });
            clearServiceSearch.style.opacity = q ? '1' : '.45';
        }
        serviceSearch.addEventListener('input', function() {
            filterCitizenServices(this.value);
        });
        if (clearServiceSearch) {
            clearServiceSearch.addEventListener('click', function() {
                serviceSearch.value = '';
                filterCitizenServices('');
                serviceSearch.focus();
            });
        }
    }

    // Auto mode follows the actual viewport while manual modes remain fixed.
    function syncAutoView() {
        if (!html.classList.contains('force-mobile') && !html.classList.contains('force-desktop')) {
            html.classList.toggle('auto-mobile', window.innerWidth <= 768);
            html.classList.toggle('auto-desktop', window.innerWidth > 768);
        }
    }
    window.addEventListener('resize', syncAutoView);
    syncAutoView();

    // === MOBILE DRAWER ===
    var burgerBtn = document.getElementById('burgerBtn'),
        mnav = document.getElementById('mnav'),
        mnavOverlay = document.getElementById('mnavOverlay'),
        mnavClose = document.getElementById('mnavClose');

    function openNav() {
        mnav.classList.add('on');
        mnavOverlay.classList.add('on');
        document.body.classList.add('nav-open')
    }

    function closeNav() {
        mnav.classList.remove('on');
        mnavOverlay.classList.remove('on');
        document.body.classList.remove('nav-open')
    }
    burgerBtn.addEventListener('click', function() {
        mnav.classList.contains('on') ? closeNav() : openNav()
    });
    mnavClose.addEventListener('click', closeNav);
    mnavOverlay.addEventListener('click', closeNav);
    mnav.querySelectorAll('.mnav-links a,.mnav-foot a').forEach(function(a) {
        a.addEventListener('click', function() {
            closeNav();
            var h = this.getAttribute('href');
            if (h && h.startsWith('#') && h !== '#') setTimeout(function() {
                var t = document.querySelector(h);
                if (t) {
                    if (t.style.display === 'none') {
                        t.style.display = '';
                        setTimeout(function() {
                            t.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }, 50);
                    } else {
                        t.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            }, 350)
        })
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mnav.classList.contains('on')) closeNav()
    });

    // === BOTTOM NAV — IntersectionObserver tracking ===
    var bnItems = document.querySelectorAll('.bn-item[data-bnav]');
    var sections = ['Digital GatewayHome', 'home', 'services', 'quick', 'citizen-hub'];
    var sectionEls = sections.map(function(id) {
        return document.getElementById(id)
    }).filter(Boolean);
    var bnObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                var id = e.target.id;
                if (id === 'home') id = 'Digital GatewayHome';
                bnItems.forEach(function(b) {
                    b.classList.toggle('active', b.dataset.bnav === id);
                });
            }
        });
    }, {
        threshold: 0,
        rootMargin: '-40% 0px -40% 0px'
    });
    sectionEls.forEach(function(s) {
        bnObs.observe(s)
    });

    // Bottom nav click (with lazy reveal)
    document.querySelectorAll('.bn-item[data-bnav]').forEach(function(b) {
        b.addEventListener('click', function(e) {
            bnItems.forEach(function(item) {
                item.classList.toggle('active', item === b);
            });
            var h = this.getAttribute('href');
            if (h && h.startsWith('#') && h !== '#') {
                e.preventDefault();
                var t = document.querySelector(h);
                if (t) {
                    if (t.style.display === 'none') {
                        t.style.display = '';
                        setTimeout(function() {
                            t.scrollIntoView({                                behavior: 'smooth'
                            });
                        }, 50);
                    } else {
                        t.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            }
        });
    });

    // === HERO 3D PARALLAX ===
    var heroSection = document.getElementById('home');
    var parallaxLayers = document.querySelectorAll('#heroParallax .hp-layer');
    if (heroSection && parallaxLayers.length) {
        heroSection.addEventListener('mousemove', function(e) {
            var rect = heroSection.getBoundingClientRect();
            var mx = (e.clientX - rect.left) / rect.width - 0.5;
            var my = (e.clientY - rect.top) / rect.height - 0.5;
            parallaxLayers.forEach(function(layer) {
                var speed = parseFloat(layer.dataset.speed) || 0.03;
                var x = mx * speed * 400;
                var y = my * speed * 400;
                layer.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
            });
        });
        heroSection.addEventListener('mouseleave', function() {
            parallaxLayers.forEach(function(layer) {
                layer.style.transform = 'translate(0,0)';
            });
        });
    }

    // === 3D TILT ON CARDS ===
    document.querySelectorAll('.co-card, .cf-card, .svc-feat-card, .qc').forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            var rect = card.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            var cx = rect.width / 2;
            var cy = rect.height / 2;
            var ry = ((x - cx) / cx) * 4;
            var rx = ((cy - y) / cy) * 3;
            card.style.transform = 'perspective(800px) rotateY(' + ry + 'deg) rotateX(' + rx +
                'deg) translateY(-4px) translateZ(6px)';
        });
        card.addEventListener('mouseleave', function() {
            card.style.transform = '';
        });
    });

    // === ANIMATED STAT COUNTERS ===
    var statNums = document.querySelectorAll('.co-stat-num');
    var statObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                var el = e.target;
                var text = el.textContent.trim();
                var match = text.match(/^(\d+)/);
                if (match) {
                    var target = parseInt(match[1]);
                    var suffix = text.replace(/^\d+/, '');
                    var current = 0;
                    var step = Math.max(1, Math.floor(target / 30));
                    var timer = setInterval(function() {
                        current += step;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        el.textContent = current + suffix;
                    }, 30);
                }
                statObs.unobserve(el);
            }
        });
    }, {
        threshold: 0.5
    });
    statNums.forEach(function(s) {
        statObs.observe(s);
    });

    // === SCROLL REVEAL ===
    var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                e.target.classList.add('show');
                obs.unobserve(e.target)
            }
        })
    }, {
        threshold: 0.05,
        rootMargin: '0px 0px -10px 0px'
    });
    document.querySelectorAll('.a').forEach(function(el) {
        obs.observe(el)
    });

    // === DESKTOP NAV smooth scroll (with lazy reveal for emergency/officials) ===
    document.querySelectorAll('.nav-links a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            var h = this.getAttribute('href');
            var t = document.querySelector(h);
            if (t) {
                e.preventDefault();
                if (t.style.display === 'none') {
                    t.style.display = '';
                    setTimeout(function() {
                        t.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }, 50);
                } else {
                    t.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        })
    });
    // === FILTERS ===
    document.querySelectorAll('.fb').forEach(function(b) {
        b.addEventListener('click', function() {
            document.querySelectorAll('.fb').forEach(function(x) {
                x.classList.remove('on')
            });
            this.classList.add('on');
            var f = this.dataset.f,
                n = 0;
            // Desktop portal cards
            document.querySelectorAll('.portals .portal').forEach(function(c) {
                if (f === 'all' || c.dataset.c === f) {
                    c.style.display = '';
                    n++
                } else {
                    c.style.display = 'none'
                }
            });
            // Desktop secondary items
            document.querySelectorAll('.svc-sec .svc-sec-item').forEach(function(c) {
                if (f === 'all' || c.dataset.c === f) {
                    c.style.display = ''
                } else {
                    c.style.display = 'none'
                }
            });
            // Mobile portal cards
            document.querySelectorAll('.svc-list .svc-portal').forEach(function(c) {
                if (f === 'all' || c.dataset.c === f) {
                    c.style.display = ''
                } else {
                    c.style.display = 'none'
                }
            });
            // Mobile list rows
            document.querySelectorAll('.svc-list .svc-row').forEach(function(c) {
                if (f === 'all' || c.dataset.c === f) {
                    c.style.display = ''
                } else {
                    c.style.display = 'none'
                }
            });
            // Mobile divider visibility
            var dividers = document.querySelectorAll('.svc-list .svc-list-divider');
            dividers.forEach(function(d) {
                d.style.display = f === 'all' ? '' : 'none'
            });
            document.getElementById('cnt').textContent = n;
        });
    });
    </script>
</body>

</html>