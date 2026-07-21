<?php
$contacts = [];
$dataFile = __DIR__ . '/data/emergency_contacts.json';
if (file_exists($dataFile)) {
    $raw = file_get_contents($dataFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $contacts = $decoded;
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
    'police'   => ['icon' => 'fa-shield-halved', 'color' => '#3b82f6', 'label' => 'Police'],
    'fire'     => ['icon' => 'fa-fire',           'color' => '#f97316', 'label' => 'Fire'],
    'hospital' => ['icon' => 'fa-hospital',       'color' => '#22c55e', 'label' => 'Hospital'],
    'medical'  => ['icon' => 'fa-heart-pulse',    'color' => '#ef4444', 'label' => 'Medical'],
    'disaster' => ['icon' => 'fa-triangle-exclamation', 'color' => '#f59e0b', 'label' => 'Disaster'],
    'general'  => ['icon' => 'fa-phone',           'color' => '#A83D5C', 'label' => 'General'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0B0E14">
    <title>Emergency Contacts — Koronadal City</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Sora', ui-sans-serif, system-ui, sans-serif;
            background: #0B0E14;
            color: #E2E4EA;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
            font-size: 14px;
            overflow-x: hidden;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        ::selection { background: #A83D5C; color: #fff; }
        a { text-decoration: none; color: inherit; }
        img { display: block; max-width: 100%; }

        /* ===== FLOATING ORBS ===== */
        .orbs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            will-change: transform;
        }

        .orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168,61,92,0.35), transparent 70%);
            top: -15%; right: -10%;
            animation: floatOrb 22s ease-in-out infinite alternate;
        }

        .orb-2 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(168,61,92,0.2), transparent 70%);
            bottom: 10%; left: -8%;
            animation: floatOrb 18s ease-in-out infinite alternate-reverse;
            animation-delay: -5s;
        }

        .orb-3 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(140,40,72,0.25), transparent 70%);
            top: 45%; right: 15%;
            animation: floatOrb 25s ease-in-out infinite alternate;
            animation-delay: -12s;
        }

        @keyframes floatOrb {
            0%   { transform: translate(0, 0) scale(1); }
            33%  { transform: translate(40px, -50px) scale(1.08); }
            66%  { transform: translate(-30px, 40px) scale(0.92); }
            100% { transform: translate(20px, -25px) scale(1.04); }
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            height: 60px;
            background: rgba(11, 14, 20, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(226, 228, 234, 0.06);
        }

        .navbar-in {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-img {
            width: 38px; height: 38px;
            border-radius: 8px;
            object-fit: contain;
            background: rgba(22, 27, 38, 0.8);
            padding: 4px;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 0 15px rgba(168, 61, 92, 0.3);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-text strong {
            font-size: 0.95rem;
            font-weight: 700;
            color: #E2E4EA;
            line-height: 1.15;
        }

        .brand-text small {
            font-size: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #A83D5C;
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            color: #9DA2B0;
            font-size: 0.72rem;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            transition: all 0.2s;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            cursor: pointer;
        }

        .nav-back:hover {
            background: rgba(168, 61, 92, 0.15);
            border-color: rgba(168, 61, 92, 0.3);
            color: #A83D5C;
            box-shadow: 0 0 20px rgba(168, 61, 92, 0.15);
        }

        .nav-back i { font-size: 0.62rem; }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            padding: 120px 20px 50px;
            overflow: hidden;
            text-align: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 30%, rgba(168,61,92,0.12), transparent 65%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M0 30h60M30 0v60' stroke='%23A83D5C' stroke-width='.3'/%3E%3Ccircle cx='30' cy='30' r='1.5' fill='%23A83D5C' opacity='.3'/%3E%3C/svg%3E");
            background-size: 60px 60px;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Pulse ring behind phone icon */
        .hero-icon-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .hero-icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(168,61,92,0.12);
            border: 1px solid rgba(168,61,92,0.2);
            display: grid;
            place-items: center;
            font-size: 2rem;
            color: #A83D5C;
            position: relative;
            z-index: 2;
        }

        .pulse-ring {
            position: absolute;
            top: 50%; left: 50%;
            width: 80px; height: 80px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 2px solid rgba(168,61,92,0.25);
            animation: pulseRing 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .pulse-ring-2 {
            animation-delay: 0.8s;
        }

        @keyframes pulseRing {
            0%   { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
            100% { transform: translate(-50%, -50%) scale(2.2); opacity: 0; }
        }

        .hero h1 {
            font-size: clamp(2rem, 6vw, 3.2rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.04em;
            margin-bottom: 12px;
            text-shadow: 0 0 40px rgba(168,61,92,0.35), 0 0 80px rgba(168,61,92,0.15);
        }

        .hero h1 .hl {
            background: linear-gradient(135deg, #A83D5C, #D06882, #E8919F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 25px rgba(168,61,92,0.4));
        }

        .hero-sub {
            font-size: clamp(0.82rem, 2vw, 1rem);
            color: rgba(255,255,255,0.45);
            max-width: 440px;
            margin: 0 auto 32px;
            line-height: 1.7;
        }

        /* ===== STATS BAR ===== */
        .stats-bar {
            display: inline-flex;
            gap: 4px;
            background: rgba(22,27,38,0.7);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 10px 8px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0,0,0,0.3);
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat-item {
            text-align: center;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.2s;
            min-width: 70px;
        }

        .stat-item:hover {
            background: rgba(168,61,92,0.1);
        }

        .stat-num {
            font-size: 1.4rem;
            font-weight: 800;
            color: #A83D5C;
            line-height: 1;
            text-shadow: 0 0 15px rgba(168,61,92,0.4);
        }

        .stat-label {
            font-size: 0.52rem;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 4px;
        }

        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.06);
            align-self: stretch;
            margin: 4px 2px;
        }

        /* ===== CONTACTS SECTION ===== */
        .contacts-section {
            position: relative;
            z-index: 2;
            padding: 40px 20px 60px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.58rem;
            font-weight: 700;
            color: #A83D5C;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            margin-bottom: 28px;
        }

        .section-label i { font-size: 0.5rem; }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(168,61,92,0.3), transparent);
        }

        /* ===== CARDS GRID ===== */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            perspective: 1200px;
        }

        .contact-card {
            background: rgba(22, 27, 38, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        box-shadow 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            opacity: 0;
            transform: translateY(30px);
        }

        .contact-card.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .contact-card::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 16px;
            border: 1px solid transparent;
            background: linear-gradient(135deg, rgba(168,61,92,0.25), transparent 50%) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .contact-card:hover {
            transform: perspective(800px) rotateX(2deg) rotateY(-2deg) translateY(-5px);
            box-shadow: 0 0 30px rgba(168,61,92,0.3);
        }

        .contact-card:hover::before {
            opacity: 1;
        }

        .contact-card:active {
            transform: scale(0.98);
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        /* Type Icon */
        .type-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            position: relative;
            flex-shrink: 0;
        }

        .type-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .contact-card:hover .type-icon::after {
            opacity: 1;
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.15); opacity: 0.8; }
        }

        /* Type colors */
        .type-police   .type-icon { background: rgba(59,130,246,0.12); color: #3b82f6; }
        .type-police   .type-icon::after { box-shadow: 0 0 20px rgba(59,130,246,0.4); }
        .type-fire     .type-icon { background: rgba(249,115,22,0.12); color: #f97316; }
        .type-fire     .type-icon::after { box-shadow: 0 0 20px rgba(249,115,22,0.4); }
        .type-hospital .type-icon { background: rgba(34,197,94,0.12);  color: #22c55e; }
        .type-hospital .type-icon::after { box-shadow: 0 0 20px rgba(34,197,94,0.4); }
        .type-medical  .type-icon { background: rgba(239,68,68,0.12);  color: #ef4444; }
        .type-medical  .type-icon::after { box-shadow: 0 0 20px rgba(239,68,68,0.4); }
        .type-disaster .type-icon { background: rgba(245,158,11,0.12); color: #f59e0b; }
        .type-disaster .type-icon::after { box-shadow: 0 0 20px rgba(245,158,11,0.4); }
        .type-general  .type-icon { background: rgba(168,61,92,0.12);  color: #A83D5C; }
        .type-general  .type-icon::after { box-shadow: 0 0 20px rgba(168,61,92,0.4); }

        .contact-card:hover .type-icon {
            transform: scale(1.08);
        }

        /* Type badge */
        .type-badge {
            font-size: 0.52rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .type-police   .type-badge { background: rgba(59,130,246,0.12);  color: #3b82f6; }
        .type-fire     .type-badge { background: rgba(249,115,22,0.12); color: #f97316; }
        .type-hospital .type-badge { background: rgba(34,197,94,0.12);  color: #22c55e; }
        .type-medical  .type-badge { background: rgba(239,68,68,0.12);  color: #ef4444; }
        .type-disaster .type-badge { background: rgba(245,158,11,0.12); color: #f59e0b; }
        .type-general  .type-badge { background: rgba(168,61,92,0.12);  color: #A83D5C; }

        .card-name {
            font-size: 1.02rem;
            font-weight: 700;
            color: #E2E4EA;
            line-height: 1.25;
        }

        .card-desc {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.4);
            line-height: 1.6;
            flex: 1;
        }

        /* Phone link */
        .card-phone {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 4px;
        }

        .card-phone:hover {
            background: rgba(168,61,92,0.12);
            border-color: rgba(168,61,92,0.3);
        }

        .card-phone-icon {
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .type-police   .card-phone-icon { color: #3b82f6; }
        .type-fire     .card-phone-icon { color: #f97316; }
        .type-hospital .card-phone-icon { color: #22c55e; }
        .type-medical  .card-phone-icon { color: #ef4444; }
        .type-disaster .card-phone-icon { color: #f59e0b; }
        .type-general  .card-phone-icon { color: #A83D5C; }

        .card-phone-num {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            transition: text-shadow 0.3s;
        }

        .type-police   .card-phone-num { color: #3b82f6; }
        .type-fire     .card-phone-num { color: #f97316; }
        .type-hospital .card-phone-num { color: #22c55e; }
        .type-medical  .card-phone-num { color: #ef4444; }
        .type-disaster .card-phone-num { color: #f59e0b; }
        .type-general  .card-phone-num { color: #A83D5C; }

        .type-police   .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(59,130,246,0.5); }
        .type-fire     .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(249,115,22,0.5); }
        .type-hospital .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(34,197,94,0.5); }
        .type-medical  .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(239,68,68,0.5); }
        .type-disaster .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(245,158,11,0.5); }
        .type-general  .card-phone:hover .card-phone-num { text-shadow: 0 0 20px rgba(168,61,92,0.5); }

        .card-phone-call {
            font-size: 0.62rem;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            margin-left: auto;
            transition: color 0.2s;
        }

        .card-phone:hover .card-phone-call {
            color: #fff;
        }

        /* Empty state */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 2.5rem;
            color: rgba(168,61,92,0.3);
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: rgba(255,255,255,0.6);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.3);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #080A0F;
            border-top: 1px solid rgba(255,255,255,0.04);
            padding: 40px 20px 24px;
            margin-top: auto;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(168,61,92,0.4), transparent);
        }

        .footer-in {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand-img {
            width: 32px; height: 32px;
            border-radius: 6px;
            background: rgba(22,27,38,0.6);
            padding: 3px;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .footer-brand strong {
            font-size: 0.82rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
        }

        .footer-brand small {
            display: block;
            font-size: 0.52rem;
            color: rgba(255,255,255,0.2);
            font-weight: 500;
        }

        .footer-copy {
            font-size: 0.62rem;
            color: rgba(255,255,255,0.2);
            font-weight: 500;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 600px) {
            .navbar-in { padding: 0 14px; }
            .brand-text strong { font-size: 0.82rem; }
            .nav-back span { display: none; }
            .nav-back { padding: 8px 10px; }

            .hero { padding: 100px 16px 40px; }
            .hero h1 { font-size: 1.8rem; }

            .hero-icon { width: 64px; height: 64px; font-size: 1.6rem; }
            .pulse-ring { width: 64px; height: 64px; }

            .stats-bar { width: 100%; }
            .stat-item { padding: 8px 10px; min-width: 0; flex: 1; }
            .stat-num { font-size: 1.1rem; }

            .contacts-section { padding: 30px 16px 50px; }
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .card-phone-num { font-size: 1rem; }

            .footer-in { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<!-- Floating Orbs -->
<div class="orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-in">
        <a href="/" class="brand">
            <img src="Logo.png" alt="Koronadal City" class="brand-img">
            <div class="brand-text">
                <strong>Koronadal City</strong>
                <small>Online Services</small>
            </div>
        </a>
        <a href="/" class="nav-back">
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
        <p class="hero-sub">24/7 hotlines for your safety and emergency needs. Tap any number to call directly.</p>

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
                <div class="stat-num" style="color: <?= $cfg['color'] ?>; text-shadow: 0 0 15px <?= $cfg['color'] ?>40;"><?= $count ?></div>
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
                $phoneClean = preg_replace('/[^\d+]/', '', $phone);
            ?>
            <div class="contact-card type-<?= htmlspecialchars($type) ?>">
                <div class="card-top">
                    <div class="type-icon">
                        <i class="fa-solid <?= htmlspecialchars($cfg['icon']) ?>"></i>
                    </div>
                    <div class="type-badge"><?= htmlspecialchars($cfg['label']) ?></div>
                </div>
                <div class="card-name"><?= htmlspecialchars($contact['name'] ?? '') ?></div>
                <div class="card-desc"><?= htmlspecialchars($contact['description'] ?? '') ?></div>
                <a href="tel:<?= htmlspecialchars($phoneClean) ?>" class="card-phone">
                    <i class="fa-solid fa-phone card-phone-icon"></i>
                    <span class="card-phone-num"><?= htmlspecialchars($phone) ?></span>
                    <span class="card-phone-call">Call now</span>
                </a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
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

<script>
(function() {
    var cards = document.querySelectorAll('.contact-card');
    if (!cards.length) return;

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var el = entry.target;
                    var idx = Array.prototype.indexOf.call(cards, el);
                    setTimeout(function() {
                        el.classList.add('revealed');
                    }, idx * 80);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        cards.forEach(function(card) {
            observer.observe(card);
        });
    } else {
        cards.forEach(function(card) {
            card.classList.add('revealed');
        });
    }

    cards.forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'perspective(800px) rotateX(2deg) rotateY(-2deg) translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
})();
</script>
</body>
</html>
