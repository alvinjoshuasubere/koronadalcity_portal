<?php
$officials = [];
$dataFile = __DIR__ . '/data/officials.json';
if (file_exists($dataFile)) {
    $decoded = json_decode(file_get_contents($dataFile), true);
    if (is_array($decoded)) $officials = $decoded;
}
$mayorData = null;
foreach ($officials as $off) {
    $pos = strtolower($off['position'] ?? '');
    if (strpos($pos, 'mayor') !== false && strpos($pos, 'vice') === false) {
        $mayorData = $off;
        break;
    }
}
function mcInitials($name) {
    $name = preg_replace('/^(Hon\.\s*)/i', '', $name);
    $name = preg_replace('/["\']/', '', $name);
    $parts = preg_split('/\s+/', trim($name));
    if (count($parts) >= 2) return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
    return mb_strtoupper(mb_substr($name, 0, 2));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1769AA">
    <title>Mayor's Corner — Koronadal City</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/css/koronadal-app.css" />
    <link rel="stylesheet" href="static/css/koronadal-theme.css" />
</head>
<body class="mc-page">

<!-- Background -->
<div class="bg-layer">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="bg-grid"></div>
</div>

<!-- Navbar -->
<nav class="nav" id="nav">
    <a href="/" class="nav-brand">
        <img src="Logo.png" alt="Koronadal City">
        <div class="nav-brand-text">
            <strong>City of Koronadal</strong>
            <small>Online Services</small>
        </div>
    </a>
    <a href="/" class="nav-back">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Portal</span>
    </a>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-inner">
        <div class="hero-photo-wrap">
            <div class="hero-photo-ring">
                <div class="hero-photo-inner">
                    <?php if (!empty($mayorData['image'])): ?>
                        <img src="<?= htmlspecialchars($mayorData['image']) ?>" alt="<?= htmlspecialchars($mayorData['name'] ?? '') ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="hero-photo-initials" style="display:none"><?= mcInitials($mayorData['name'] ?? 'M') ?></div>
                    <?php else: ?>
                        <div class="hero-photo-initials"><?= mcInitials($mayorData['name'] ?? 'M') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-photo-badge"><i class="fas fa-star"></i></div>
        </div>
        <div class="hero-text">
            <div class="hero-label"><i class="fas fa-landmark"></i> Office of the City Mayor</div>
            <h1 class="hero-name"><?= htmlspecialchars($mayorData['name'] ?? 'HON. ERLINDA PABI-ARAQUIL') ?></h1>
            <p class="hero-title">Mayor of Koronadal City</p>
            <div class="hero-motto-line">
                <span class="hero-motto-text">Serbisyong Totoo para sa Diyos at para sa Tao</span>
            </div>
        </div>
    </div>
</section>

<!-- Tabs -->
<div class="tabs-section">
    <div class="tabs-nav" id="tabsNav">
        <button class="tab-btn active" data-tab="profile">
            <i class="fas fa-user"></i> <span class="tab-label">Brief Profile</span>
        </button>
        <button class="tab-btn" data-tab="governance">
            <i class="fas fa-shield-halved"></i> <span class="tab-label">Governance Brand</span>
        </button>
        <button class="tab-btn" data-tab="agenda">
            <i class="fas fa-chart-line"></i> <span class="tab-label">Development Agenda</span>
        </button>
    </div>

    <div class="tab-panels">

        <!-- ═══ TAB: PROFILE ═══ -->
        <div class="tab-panel active" id="tab-profile">

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text"><span class="hl">Educational</span> Background</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="formal-card">
                    <p>A graduate of <strong>Bachelor of Science in Development Communication major in Broadcasting</strong>.</p>
                    <p>A Masters Degree holder in <strong>Master in Development Management and Governance</strong>, and a <strong>Doctor in Public Administration</strong>.</p>
                </div>
            </div>

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text"><span class="hl">Broadcasting</span> Career</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="formal-card">
                    <p>She is a professional Broadcast Journalist for many years now. She started her radio broadcast career in <strong>1993</strong> as News Director and Reporter/Anchor at <strong>RMN Radio Mindanao Network</strong>.</p>
                    <p>She also worked as a reporter/anchor/news director at <strong>Bombo Radyo Philippines</strong> and later as part of <strong>GMA Super Radyo</strong>.</p>
                    <p>She is also more popularly known as <strong>BRIGADA BING</strong> since she is one of the Brigada Group's broadcast icons, having been part of it for more than a decade.</p>
                </div>
            </div>

            <div class="formal-quote">
                <div class="formal-quote-text">SERBISYONG TOTOO PARA SA DIYOS AT PARA SA TAO!</div>
                <div class="formal-quote-attr">Mayor Erlinda "Bing" Pabi-Araquil</div>
            </div>

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text"><span class="hl">Personal</span> Life</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="formal-card">
                    <p>A wife to <strong>Atty. Phinney Araquil</strong> and a loving mother to two bright and pretty daughters, <strong>Kathleen</strong> and <strong>Katrina Charm</strong>.</p>
                </div>
            </div>

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text"><span class="hl">Public Service</span> Journey</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="formal-letter">
                    <p>A defender of the masses, a <strong>three-term Councilor</strong> of the City of Koronadal. Her guiding principle as the former Vice Mayor of the City of Koronadal is&hellip;</p>
                    <p>Mayor Araquil brings with her a wealth of experience in public service and a deep commitment to the welfare of the people of Koronadal. Prior to assuming the role of Mayor, she served as the <strong>City Vice Mayor</strong>, where she demonstrated strong leadership, dedication, and a passion for making a positive difference in the lives of her constituents.</p>
                    <p>Throughout her career, Mayor Araquil has been actively involved in various community initiatives and projects aimed at promoting <strong>social development</strong>, <strong>economic growth</strong>, and <strong>environmental sustainability</strong>. She has been a strong advocate for women's rights, children's welfare, and the empowerment of marginalized sectors of society.</p>
                    <p>As the new Mayor of Koronadal City, Mayor Araquil is committed to building on the progress and achievements of the previous administration and working towards a more prosperous, inclusive, and sustainable future for the city.</p>
                    <p>She has outlined a clear vision and agenda for the city, which includes priorities such as improving infrastructure, enhancing public services, promoting economic development, protecting the environment, and strengthening community engagement.</p>
                    <p>Mayor Araquil is a leader who listens to the voices of the people and is dedicated to working with them to address their needs and concerns. She believes in the power of collaboration and partnership, and she is committed to working with all sectors of society to achieve the shared goals and aspirations of the people of Koronadal City.</p>
                </div>
            </div>

            <div class="formal-quote">
                <div class="formal-quote-text">EPADAYON ANG KANAMI SANG BAGONG KORONADAL&hellip;</div>
                <div class="formal-quote-attr">SERBISYONG TOTOO PARA SA DIYOS AT PARA SA TAO!</div>
            </div>
        </div>

        <!-- ═══ TAB: GOVERNANCE ═══ -->
        <div class="tab-panel" id="tab-governance">

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text">&ldquo;Serbisyong Totoo. <span class="hl">EPAdayon.</span>&rdquo;</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="formal-card">
                    <p>&ldquo;Serbisyong Totoo. EPAdayon&rdquo; embodies a leadership brand rooted in integrity, faith, and unwavering commitment to the people. Guided by the principles of <strong>EPA&mdash;Empowered by Faith, People-Centered, Accountable Governance</strong>, it reflects an administration that draws strength from moral conviction, listens and responds to the needs of every sector, and upholds transparency and responsibility in public service.</p>
                    <p>More than a slogan, it is a continuing promise to sustain progress, deepen trust, and ensure that every program, decision, and action genuinely serves the common good&mdash;moving Koronadal forward with purpose and unity.</p>
                </div>
            </div>

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text">The <span class="hl">Three Pillars</span> of Governance</div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="epa-grid">
                    <div class="epa-card epa-e">
                        <div class="epa-letter-box">E</div>
                        <div class="epa-body">
                            <h3>Empowered by Faith</h3>
                            <p>Every action and decision is anchored on a strong and unwavering faith in God. This serves as the source of strength, guidance, and purpose in all government undertakings. Public service is regarded as a sacred duty, where integrity, moral values, and respect for all creation are always upheld. Faith stands as the firm foundation in facing challenges and ensuring that all endeavors are carried out for the common good and for the greater glory of God.</p>
                        </div>
                    </div>
                    <div class="epa-card epa-p">
                        <div class="epa-letter-box">P</div>
                        <div class="epa-body">
                            <h3>People-Centered Governance</h3>
                            <p>The utmost priority is the welfare and well-being of every constituent. All programs, projects, and services are designed and implemented based on the genuine needs and aspirations of the people. Every individual&mdash;regardless of status or standing in society&mdash;is accorded equal opportunity, respect, and attention. The administration constantly listens to the voice of the public and strives to address their concerns, necessities, and dreams. The progress of the city is measured by the tangible improvement in the quality of life of each and every resident.</p>
                        </div>
                    </div>
                    <div class="epa-card epa-a">
                        <div class="epa-letter-box">A</div>
                        <div class="epa-body">
                            <h3>Accountable Governance</h3>
                            <p>Leadership is exercised with transparency, honesty, and full responsibility for all actions and decisions. Public resources are utilized wisely, efficiently, and solely for purposes that benefit the greater majority. All processes are clear and accessible, allowing the people to monitor and inquire about government transactions at any time. Officials and public servants are always ready to explain their actions and answer inquiries, while errors are promptly corrected to prevent recurrence. The trust given by the people is deeply valued and continuously nurtured through consistent truthfulness, accuracy, and uprightness in all official dealings.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="formal-quote">
                <div class="formal-quote-text">Governance Brand: &ldquo;Serbisyong Totoo. EPAdayon.&rdquo;</div>
                <div class="formal-quote-attr">A leadership approach grounded in faith, driven by genuine public service, and committed to inclusive, transparent, and accountable governance&mdash;sustaining progress for a stronger Koronadal.</div>
            </div>
        </div>

        <!-- ═══ TAB: AGENDA ═══ -->
        <div class="tab-panel" id="tab-agenda">

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text">5 Integrated <span class="hl">Development Sectors</span></div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="sectors-grid">
                    <div class="sector-card">
                        <div class="sector-icon si-social"><i class="fas fa-heart"></i></div>
                        <h4>Social Development</h4>
                        <p>Strengthening health, education, social welfare, and cultural development while promoting discipline, inclusivity, and empowered communities.</p>
                    </div>
                    <div class="sector-card">
                        <div class="sector-icon si-economic"><i class="fas fa-coins"></i></div>
                        <h4>Economic Development</h4>
                        <p>Advancing inclusive growth through agriculture, MSMEs, and tourism, while fostering productivity, innovation, and sustainable livelihoods.</p>
                    </div>
                    <div class="sector-card">
                        <div class="sector-icon si-environment"><i class="fas fa-leaf"></i></div>
                        <h4>Environmental Sustainability</h4>
                        <p>Promoting climate resilience, environmental protection, and responsible resource management through community participation and shared stewardship.</p>
                    </div>
                    <div class="sector-card">
                        <div class="sector-icon si-infrastructure"><i class="fas fa-road"></i></div>
                        <h4>Infrastructure & Urban Development</h4>
                        <p>Delivering safe, resilient, inclusive, and future-ready infrastructure and public service systems that support long-term urban growth.</p>
                    </div>
                    <div class="sector-card">
                        <div class="sector-icon si-institutional"><i class="fas fa-landmark"></i></div>
                        <h4>Institutional Strengthening & Governance</h4>
                        <p>Enhancing transparency, efficiency, digital governance, and citizen engagement to build trust and improve public service delivery.</p>
                    </div>
                </div>
            </div>

            <div class="formal-section">
                <div class="formal-heading">
                    <div class="formal-heading-text">Strategic <span class="hl">Outcome</span></div>
                    <div class="formal-heading-line"></div>
                </div>
                <div class="outcome-block">
                    <p>A <strong>faith-driven, people-centered, and accountable</strong> City Government of Koronadal that delivers <strong>sustainable, resilient, and inclusive growth</strong>, anchored on genuine public service and transformed citizen behavior&mdash;ensuring that progress is not only achieved, but sustained.</p>
                </div>
            </div>

            <div class="formal-quote">
                <div class="formal-quote-text">Faith guides &rarr; Genuine Service to People &rarr; protected by Accountability</div>
                <div class="formal-quote-attr">The flow of the EPAdayon Governance Framework</div>
            </div>
        </div>

    </div>
</div>

<!-- App Footer -->
<footer class="footer mc-footer">
    <div class="mc-footer-inner">
        <div class="mc-footer-main">
            <div class="mc-footer-brand">
                <div class="mc-footer-logo">
                    <img src="Logo.png" alt="Koronadal City" onerror="this.style.display='none'">
                </div>
                <div>
                    <strong>City Government of Koronadal</strong>
                    <span>Office of the City Mayor</span>
                </div>
            </div>
            <p class="mc-footer-desc">Serving Koronadal with faith, integrity, and genuine public service.</p>
            <div class="mc-footer-actions">
                <a href="/" class="mc-footer-action"><i class="fas fa-house"></i><span>Portal Home</span></a>
                <a href="city-officials.php" class="mc-footer-action"><i class="fas fa-users"></i><span>Officials</span></a>
                <a href="mailto:info.koronadalcity@gmail.com" class="mc-footer-action"><i class="fas fa-envelope"></i><span>Contact</span></a>
            </div>
        </div>
        <div class="mc-footer-note">
            <span><i class="fas fa-shield-halved"></i> Official City Government Portal</span>
            <span>&copy; <?= date('Y') ?> City Government of Koronadal</span>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll
    var nav = document.getElementById('nav');
    window.addEventListener('scroll', function() {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });

    // Tab switching
    var tabBtns = document.querySelectorAll('.tab-btn');
    var tabPanels = document.querySelectorAll('.tab-panel');
    tabBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = this.getAttribute('data-tab');
            tabBtns.forEach(function(b) { b.classList.remove('active'); });
            tabPanels.forEach(function(p) { p.classList.remove('active'); });
            btn.classList.add('active');
            document.getElementById('tab-' + target).classList.add('active');
        });
    });

    // Scroll reveal
    var reveals = document.querySelectorAll('.formal-card, .formal-letter, .formal-quote, .epa-card, .sector-card, .outcome-block, .formal-section');
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateY(0)';
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
        reveals.forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity .5s ease, transform .5s ease';
            obs.observe(el);
        });
    } else {
        reveals.forEach(function(el) { el.style.opacity = '1'; });
    }
});
</script>
</body>
</html>
