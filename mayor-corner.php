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
    <meta name="theme-color" content="#0A192F">
    <title>Mayor's Corner — Koronadal City</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:#F8FAFC; --surface:#FFFFFF; --surface-2:#FFFFFF; --glass:rgba(255,255,255,.85); --border:#E2E8F0; --border-glow:rgba(29,78,216,.25); --rose:#1D4ED8; --rose-light:#2563EB; --rose-glow:rgba(29,78,216,.25); --cyan:#60A5FA; --cyan-glow:rgba(96,165,250,.2); --gold:#0F2C59; --gold-dim:rgba(15,44,89,.10); --text:#1E293B; --text-dim:#64748B; --text-bright:#0A192F;
            --serif: 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
            --sans: 'Sora', ui-sans-serif, system-ui, sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── BACKGROUND ── */
        .bg-layer { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .bg-orb { position: absolute; border-radius: 50%; filter: blur(100px); }
        .bg-orb-1 { width: 600px; height: 600px; background: radial-gradient(circle, rgba(29,78,216,0.22), transparent 70%); top: -200px; left: -150px; animation: orbDrift1 25s ease-in-out infinite; }
        .bg-orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(15,44,89,0.1), transparent 70%); bottom: -100px; right: -100px; animation: orbDrift2 20s ease-in-out infinite; }
        .bg-orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(96,165,250,0.08), transparent 70%); top: 40%; left: 60%; animation: orbDrift3 18s ease-in-out infinite; }
        @keyframes orbDrift1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(80px,60px)} }
        @keyframes orbDrift2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-60px,-80px)} }
        @keyframes orbDrift3 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-40px,40px) scale(1.15)} }
        .bg-grid {
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M0 30h60M30 0v60' stroke='%231D4ED8' stroke-width='.15' opacity='.06'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }

        /* ── NAVBAR ── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem; height: 68px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);
            border-bottom: 1px solid var(--border);
            transition: all .3s;
        }
        .nav.scrolled { background: rgba(255,255,255,0.97); border-bottom-color: rgba(29,78,216,0.1) }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-brand img { height: 40px; width: 40px; object-fit: contain; border-radius: 10px; padding: 3px; border: 1px solid rgba(29,78,216,0.2); filter: drop-shadow(0 0 8px rgba(29,78,216,0.2)); }
        .nav-brand-text { display: flex; flex-direction: column; line-height: 1.2 }
        .nav-brand-text strong { font-size: .95rem; font-weight: 700; color: var(--text-bright) }
        .nav-brand-text small { font-size: .55rem; font-weight: 500; color: var(--rose-light); text-transform: uppercase; letter-spacing: .12em }
        .nav-back {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 20px; border-radius: 10px;
            background: rgba(29,78,216,0.06); border: 1px solid rgba(29,78,216,0.25);
            color: var(--rose-light); font-size: .78rem; font-weight: 500;
            text-decoration: none; transition: all .25s;
        }
        .nav-back:hover { background: rgba(29,78,216,0.1); border-color: rgba(29,78,216,0.4); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 20px rgba(29,78,216,0.2); }

        /* ── HERO ── */
        .hero {
            position: relative; padding: 140px 2rem 0; overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(29,78,216,0.08) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-inner {
            position: relative; z-index: 2; max-width: 900px; margin: 0 auto;
            display: flex; align-items: center; gap: 48px;
        }
        .hero-photo-wrap {
            flex-shrink: 0; position: relative;
        }
        .hero-photo-ring {
            width: 180px; height: 180px; border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, var(--rose), var(--gold));
            box-shadow: 0 8px 40px rgba(29,78,216,0.2), 0 0 0 1px rgba(15,44,89,0.1);
        }
        .hero-photo-inner {
            width: 100%; height: 100%; border-radius: 50%;
            background: var(--bg);
            display: grid; place-items: center;
            overflow: hidden;
            position: relative;
        }
        .hero-photo-inner img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .hero-photo-initials {
            font-size: 2.8rem; font-weight: 800; color: var(--rose);
            font-family: var(--serif);
        }
        .hero-photo-badge {
            position: absolute; bottom: 4px; right: 4px;
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--gold); border: 3px solid var(--bg);
            display: grid; place-items: center;
            box-shadow: 0 4px 16px rgba(15,44,89,0.35);
        }
        .hero-photo-badge i { font-size: .75rem; color: #fff; }

        .hero-text { flex: 1; min-width: 0; }
        .hero-label {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: .6rem; font-weight: 700; color: var(--gold);
            text-transform: uppercase; letter-spacing: .15em;
            margin-bottom: 10px;
        }
        .hero-label i { font-size: .5rem; }
        .hero-name {
            font-family: var(--serif);
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 700; color: var(--text-bright);
            line-height: 1.15; margin-bottom: 8px;
        }
        .hero-title {
            font-size: .85rem; color: var(--text-dim);
            font-weight: 400; margin-bottom: 16px;
        }
        .hero-motto-line {
            display: flex; align-items: center; gap: 12px;
        }
        .hero-motto-line::before, .hero-motto-line::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(15,44,89,0.3), transparent);
        }
        .hero-motto-text {
            font-family: var(--serif);
            font-size: .82rem; font-weight: 600; font-style: italic;
            color: var(--gold); white-space: nowrap;
            letter-spacing: .02em;
        }

        /* ── TABS ── */
        .tabs-section {
            position: relative; z-index: 2;
            max-width: 900px; margin: 48px auto 0; padding: 0 2rem;
        }
        .tabs-nav {
            display: flex; gap: 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .tabs-nav::-webkit-scrollbar { display: none; }
        .tab-btn {
            flex-shrink: 0;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 24px;
            background: none; border: none;
            font-family: var(--sans);
            font-size: .78rem; font-weight: 600;
            color: var(--text-dim);
            cursor: pointer;
            position: relative;
            transition: color .25s;
            white-space: nowrap;
        }
        .tab-btn i { font-size: .7rem; }
        .tab-btn::after {
            content: ''; position: absolute; bottom: -1px; left: 12px; right: 12px; height: 2px;
            background: var(--rose);
            border-radius: 2px 2px 0 0;
            transform: scaleX(0);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .tab-btn:hover { color: var(--text); }
        .tab-btn.active { color: #1D4ED8; }
        .tab-btn.active::after { transform: scaleX(1); }

        .tab-panels { padding: 40px 0 60px; }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; animation: tabFadeIn .4s ease; }
        @keyframes tabFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* ── FORMAL SECTION STYLES ── */
        .formal-section { margin-bottom: 36px; }
        .formal-section:last-child { margin-bottom: 0; }

        .formal-heading {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 20px;
        }
        .formal-heading-line {
            flex: 1; height: 1px;
            background: linear-gradient(90deg, rgba(15,44,89,0.3), transparent);
        }
        .formal-heading-text {
            font-family: var(--serif);
            font-size: 1.1rem; font-weight: 700; color: var(--text-bright);
            letter-spacing: .02em; white-space: nowrap;
        }
        .formal-heading-text .hl { color: var(--gold); }

        /* Formal paragraph card */
        .formal-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 32px;
            position: relative; overflow: hidden;
            transition: border-color .3s, box-shadow .3s;
        }
        .formal-card:hover {
            border-color: rgba(15,44,89,0.15);
            box-shadow: 0 8px 40px rgba(10,25,47,0.10);
        }
        .formal-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--rose), var(--gold), var(--cyan));
        }
        .formal-card p {
            font-size: .88rem; line-height: 1.9; color: var(--text);
            margin-bottom: 14px;
            text-align: justify;
        }
        .formal-card p:last-child { margin-bottom: 0; }
        .formal-card p strong { color: var(--text-bright); font-weight: 600; }

        /* Formal quote */
        .formal-quote {
            margin: 28px 0; padding: 28px 32px;
            background: linear-gradient(135deg, rgba(29,78,216,0.06), rgba(15,44,89,0.04));
            border: 1px solid rgba(15,44,89,0.12);
            border-radius: 14px;
            position: relative;
        }
        .formal-quote::before {
            content: '\201C'; position: absolute; top: -6px; left: 18px;
            font-size: 3.5rem; color: var(--gold); opacity: .25;
            font-family: var(--serif); line-height: 1;
        }
        .formal-quote-text {
            font-family: var(--serif);
            font-size: 1.15rem; font-weight: 700; font-style: italic;
            color: #0A192F; line-height: 1.6;
            position: relative; z-index: 1;
        }
        .formal-quote-attr {
            font-size: .72rem; color: var(--text-dim);
            margin-top: 10px; position: relative; z-index: 1;
            font-style: normal;
        }

        /* Letter-style block */
        .formal-letter {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 36px;
            position: relative;
        }
        .formal-letter::after {
            content: ''; position: absolute; bottom: 0; left: 32px; right: 32px; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(15,44,89,0.2), transparent);
        }
        .formal-letter p {
            font-family: var(--serif);
            font-size: .95rem; line-height: 1.9; color: var(--text);
            margin-bottom: 16px; text-align: justify;
        }
        .formal-letter p:last-child { margin-bottom: 0; }
        .formal-letter p strong { color: var(--text-bright); }

        /* ── EPA PILLARS ── */
        .epa-grid { display: grid; grid-template-columns: 1fr; gap: 18px; }
        .epa-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 28px;
            display: flex; gap: 20px; align-items: flex-start;
            position: relative; overflow: hidden;
            transition: border-color .3s, box-shadow .3s, transform .3s;
        }
        .epa-card:hover {
            border-color: rgba(29,78,216,0.2);
            box-shadow: 0 8px 32px rgba(10,25,47,0.10);
            transform: translateY(-2px);
        }
        .epa-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%;
        }
        .epa-card.epa-e::before { background: var(--rose); }
        .epa-card.epa-p::before { background: var(--gold); }
        .epa-card.epa-a::before { background: var(--cyan); }

        .epa-letter-box {
            flex-shrink: 0;
            width: 52px; height: 52px; border-radius: 14px;
            display: grid; place-items: center;
            font-size: 1.3rem; font-weight: 800;
            font-family: var(--serif);
        }
        .epa-e .epa-letter-box { background: rgba(29,78,216,0.12); color: var(--rose-light); border: 1px solid rgba(29,78,216,0.2); }
        .epa-p .epa-letter-box { background: rgba(15,44,89,0.1); color: var(--gold); border: 1px solid rgba(15,44,89,0.2); }
        .epa-a .epa-letter-box { background: rgba(96,165,250,0.08); color: var(--cyan); border: 1px solid rgba(96,165,250,0.15); }

        .epa-body h3 {
            font-size: .95rem; font-weight: 700; color: var(--text-bright); margin-bottom: 8px;
        }
        .epa-body p {
            font-size: .82rem; line-height: 1.8; color: var(--text);
        }

        /* ── SECTOR CARDS ── */
        .sectors-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 14px; }
        .sector-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 22px;
            transition: border-color .3s, box-shadow .3s, transform .25s;
        }
        .sector-card:hover {
            border-color: rgba(15,44,89,0.15);
            box-shadow: 0 6px 24px rgba(10,25,47,0.08);
            transform: translateY(-2px);
        }
        .sector-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: grid; place-items: center; font-size: .85rem;
            margin-bottom: 12px;
        }
        .sector-card h4 { font-size: .85rem; font-weight: 700; color: var(--text-bright); margin-bottom: 6px; }
        .sector-card p { font-size: .76rem; line-height: 1.7; color: var(--text-dim); }

        .si-social { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.15); }
        .si-economic { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.15); }
        .si-environment { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.15); }
        .si-infrastructure { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.15); }
        .si-institutional { background: rgba(29,78,216,0.1); color: var(--rose-light); border: 1px solid rgba(29,78,216,0.15); }

        /* ── OUTCOME ── */
        .outcome-block {
            text-align: center; padding: 40px 32px;
            background: linear-gradient(135deg, rgba(29,78,216,0.05), rgba(15,44,89,0.03));
            border: 1px solid rgba(15,44,89,0.1);
            border-radius: 18px;
        }
        .outcome-block p {
            font-family: var(--serif);
            font-size: 1.05rem; line-height: 1.8; color: var(--text);
            max-width: 650px; margin: 0 auto;
        }
        .outcome-block p strong { color: var(--text-bright); }

        /* ── FOOTER ── */
        .footer {
            position: relative; z-index: 2;
            padding: 32px 2rem; margin-top: 40px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        }
        .footer-brand { display: flex; align-items: center; gap: 10px; }
        .footer-brand img { height: 32px; width: 32px; object-fit: contain; border-radius: 8px; border: 1px solid rgba(29,78,216,0.15); }
        .footer-brand span { font-size: .82rem; font-weight: 600; color: var(--text-dim); }
        .footer p { font-size: .68rem; color: var(--text-dim); font-weight: 500; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .nav { padding: 0 1.2rem; height: 60px }
            .nav-brand-text strong { font-size: .82rem }
            .nav-back span { display: none }
            .nav-back { padding: 8px 12px }

            .hero { padding: 110px 1.2rem 0 }
            .hero-inner { flex-direction: column; text-align: center; gap: 28px }
            .hero-photo-ring { width: 150px; height: 150px }
            .hero-photo-initials { font-size: 2.2rem }
            .hero-name { font-size: 1.8rem }
            .hero-motto-line { max-width: 280px; margin: 0 auto }

            .tabs-section { padding: 0 1.2rem }
            .tab-btn { padding: 12px 16px; font-size: .72rem }
            .tab-btn span.tab-label { display: none }
            .tab-btn i { font-size: .8rem }

            .formal-card, .formal-letter { padding: 24px }
            .formal-quote { padding: 22px 24px }
            .formal-quote-text { font-size: 1rem }

            .sectors-grid { grid-template-columns: 1fr }

            .footer { flex-direction: column; gap: 12px; text-align: center; padding: 24px 1.2rem }
        }

        @media (max-width: 480px) {
            .hero-name { font-size: 1.5rem }
            .hero-photo-ring { width: 120px; height: 120px }
            .hero-photo-initials { font-size: 1.8rem }
            .epa-card { flex-direction: column; gap: 14px }
        }
    </style>
</head>
<body>

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

<!-- Footer -->
<footer class="footer">
    <div class="footer-brand">
        <img src="Logo.png" alt="Koronadal City" onerror="this.style.display='none'">
        <span>Koronadal City</span>
    </div>
    <p>&copy; <?= date('Y') ?> Koronadal City Government. All rights reserved.</p>
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
