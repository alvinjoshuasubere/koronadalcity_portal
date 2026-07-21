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
    'Executive' => ['bg' => 'rgba(168,61,92,0.18)', 'border' => 'rgba(168,61,92,0.5)', 'text' => '#e88aa0'],
    'General Governance' => ['bg' => 'rgba(130,80,220,0.18)', 'border' => 'rgba(130,80,220,0.5)', 'text' => '#b890f0'],
    'Legislative Affairs' => ['bg' => 'rgba(50,150,200,0.18)', 'border' => 'rgba(50,150,200,0.5)', 'text' => '#6ac0e8'],
    'Ethics' => ['bg' => 'rgba(200,160,50,0.18)', 'border' => 'rgba(200,160,50,0.5)', 'text' => '#e8cc60'],
    'Health' => ['bg' => 'rgba(50,200,120,0.18)', 'border' => 'rgba(50,200,120,0.5)', 'text' => '#60e8a0'],
    'Social Services' => ['bg' => 'rgba(200,80,160,0.18)', 'border' => 'rgba(200,80,160,0.5)', 'text' => '#e880c8'],
    'Infrastructure' => ['bg' => 'rgba(220,160,50,0.18)', 'border' => 'rgba(220,160,50,0.5)', 'text' => '#e8c060'],
    'Public Works' => ['bg' => 'rgba(80,170,220,0.18)', 'border' => 'rgba(80,170,220,0.5)', 'text' => '#80c8e8'],
    'Education' => ['bg' => 'rgba(80,190,220,0.18)', 'border' => 'rgba(80,190,220,0.5)', 'text' => '#80d8f0'],
    'Culture' => ['bg' => 'rgba(220,80,100,0.18)', 'border' => 'rgba(220,80,100,0.5)', 'text' => '#e88090'],
    'Agriculture' => ['bg' => 'rgba(60,200,80,0.18)', 'border' => 'rgba(60,200,80,0.5)', 'text' => '#70e880'],
    'Food' => ['bg' => 'rgba(220,180,60,0.18)', 'border' => 'rgba(220,180,60,0.5)', 'text' => '#e8d070'],
];
$defaultColor = ['bg' => 'rgba(168,61,92,0.15)', 'border' => 'rgba(168,61,92,0.35)', 'text' => '#d4729a'];

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
    <title>City Officials Directory - Koronadal City</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box }

        :root {
            --bg: #060a12;
            --surface: rgba(14,18,30,0.85);
            --surface-2: rgba(20,26,44,0.7);
            --glass: rgba(22,28,48,0.6);
            --border: rgba(255,255,255,0.05);
            --border-glow: rgba(168,61,92,0.3);
            --rose: #A83D5C;
            --rose-light: #d4729a;
            --rose-glow: rgba(168,61,92,0.4);
            --cyan: #3ad8d8;
            --cyan-glow: rgba(58,216,216,0.3);
            --text: #c8cdd8;
            --text-dim: rgba(200,205,216,0.5);
            --text-bright: #eef0f5;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── ANIMATED BACKGROUND ── */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
        }
        .bg-orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(168,61,92,0.25), transparent 70%);
            top: -200px; left: -150px;
            animation: orbDrift1 25s ease-in-out infinite;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(58,216,216,0.12), transparent 70%);
            bottom: -100px; right: -100px;
            animation: orbDrift2 20s ease-in-out infinite;
        }
        .bg-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(130,80,220,0.15), transparent 70%);
            top: 40%; left: 60%;
            animation: orbDrift3 18s ease-in-out infinite;
        }
        @keyframes orbDrift1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(80px,60px)} }
        @keyframes orbDrift2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-60px,-80px)} }
        @keyframes orbDrift3 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-40px,40px) scale(1.15)} }

        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M0 30h60M30 0v60' stroke='%23A83D5C' stroke-width='.15' opacity='.08'/%3E%3C/svg%3E");
            background-size: 60px 60px;
            animation: gridFade 10s ease-in-out infinite;
        }
        @keyframes gridFade { 0%,100%{opacity:.4} 50%{opacity:.7} }

        /* ── NAVBAR ── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem; height: 68px;
            background: rgba(6,10,18,0.7);
            backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);
            border-bottom: 1px solid var(--border);
            transition: all .3s;
        }
        .nav.scrolled { background: rgba(6,10,18,0.95); border-bottom-color: rgba(168,61,92,0.1) }
        .nav-brand {
            display: flex; align-items: center; gap: 12px; text-decoration: none;
        }
        .nav-brand img {
            height: 40px; width: 40px; object-fit: contain;
            border-radius: 10px; padding: 3px;
            border: 1px solid rgba(168,61,92,0.2);
            filter: drop-shadow(0 0 8px rgba(168,61,92,0.2));
        }
        .nav-brand-text { display: flex; flex-direction: column; line-height: 1.2 }
        .nav-brand-text strong { font-size: .95rem; font-weight: 700; color: #fff }
        .nav-brand-text small { font-size: .55rem; font-weight: 500; color: var(--rose-light); text-transform: uppercase; letter-spacing: .12em }
        .nav-back {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 20px; border-radius: 10px;
            background: rgba(168,61,92,0.1); border: 1px solid rgba(168,61,92,0.2);
            color: var(--rose-light); font-size: .78rem; font-weight: 500;
            text-decoration: none; transition: all .25s;
        }
        .nav-back:hover {
            background: rgba(168,61,92,0.2); border-color: rgba(168,61,92,0.4);
            color: #fff; transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(168,61,92,0.2);
        }

        /* ── HERO ── */
        .hero {
            position: relative; padding: 150px 2rem 70px; text-align: center; overflow: hidden;
        }
        .hero-particles {
            position: absolute; inset: 0; pointer-events: none;
        }
        .particle {
            position: absolute; width: 3px; height: 3px; border-radius: 50%;
            background: var(--rose); opacity: 0;
            animation: particleFloat 6s ease-in-out infinite;
        }
        .particle:nth-child(1) { left:10%; top:20%; animation-delay:0s }
        .particle:nth-child(2) { left:25%; top:60%; animation-delay:1.2s; background:var(--cyan) }
        .particle:nth-child(3) { left:50%; top:30%; animation-delay:2.4s }
        .particle:nth-child(4) { left:70%; top:70%; animation-delay:0.8s; background:var(--cyan) }
        .particle:nth-child(5) { left:85%; top:40%; animation-delay:3.6s }
        .particle:nth-child(6) { left:40%; top:80%; animation-delay:1.8s; background:var(--cyan) }
        .particle:nth-child(7) { left:60%; top:15%; animation-delay:4.2s }
        .particle:nth-child(8) { left:15%; top:45%; animation-delay:2.8s; background:var(--cyan) }
        @keyframes particleFloat {
            0%,100% { opacity:0; transform:translateY(0) scale(1) }
            20% { opacity:.6 }
            50% { opacity:.8; transform:translateY(-40px) scale(1.5) }
            80% { opacity:.4 }
        }

        .hero-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto }
        .hero-icon-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            width: 90px; height: 90px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(168,61,92,0.15), rgba(58,216,216,0.08));
            border: 1px solid rgba(168,61,92,0.2);
            margin-bottom: 24px; position: relative;
            animation: iconFloat 4s ease-in-out infinite;
        }
        .hero-icon-wrap::before {
            content:''; position:absolute; inset:-8px; border-radius:50%;
            border: 1px dashed rgba(168,61,92,0.15);
            animation: iconSpin 20s linear infinite;
        }
        .hero-icon-wrap::after {
            content:''; position:absolute; inset:-16px; border-radius:50%;
            border: 1px dashed rgba(58,216,216,0.1);
            animation: iconSpin 30s linear infinite reverse;
        }
        @keyframes iconFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        @keyframes iconSpin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        .hero-icon-wrap i { font-size: 2rem; color: var(--rose); filter: drop-shadow(0 0 15px var(--rose-glow)) }

        .hero h1 {
            font-size: clamp(2.4rem, 5vw, 3.8rem); font-weight: 800; color: #fff;
            letter-spacing: -.03em; line-height: 1.1; margin-bottom: 12px;
        }
        .hero h1 .glow {
            background: linear-gradient(135deg, var(--rose), var(--cyan), var(--rose));
            background-size: 200% 200%;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease-in-out infinite;
            text-shadow: none;
        }
        @keyframes gradientShift { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
        .hero-sub { font-size: 1rem; color: var(--text-dim); font-weight: 300; margin-bottom: 32px; letter-spacing: .01em }
        .hero-divider {
            width: 80px; height: 2px; margin: 0 auto 28px;
            background: linear-gradient(90deg, transparent, var(--rose), var(--cyan), transparent);
            border-radius: 1px;
        }
        .hero-stats {
            display: inline-flex; align-items: center; gap: 16px;
            padding: 16px 36px; border-radius: 16px;
            background: var(--glass); border: 1px solid var(--border);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            position: relative; overflow: hidden;
        }
        .hero-stats::before {
            content:''; position:absolute; inset:0;
            background: linear-gradient(135deg, rgba(168,61,92,0.05), transparent, rgba(58,216,216,0.03));
        }
        .hero-stats .stat-num {
            font-size: 2rem; font-weight: 800; color: var(--rose); line-height: 1;
            position: relative;
        }
        .hero-stats .stat-label {
            font-size: .75rem; color: var(--text-dim); text-align: left; line-height: 1.3;
            position: relative;
        }
        .hero-stats .stat-label strong { color: var(--text-bright); font-weight: 600; display: block }

        /* ── CONTENT ── */
        .content { position: relative; z-index: 2; max-width: 1200px; margin: 0 auto; padding: 20px 2rem 80px }

        /* ── MAYOR FEATURED ── */
        .leader-featured {
            display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
            margin-bottom: 40px; perspective: 1200px;
        }
        .mayor-hero-card {
            grid-column: 1 / -1;
            display: flex; align-items: stretch; gap: 0;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 24px; overflow: hidden;
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            transition: all .4s cubic-bezier(.25,.46,.45,.94);
            transform-style: preserve-3d; position: relative;
        }
        .mayor-hero-card::before {
            content:''; position:absolute; inset:0; border-radius:24px; padding:1px;
            background: linear-gradient(135deg, rgba(168,61,92,0.3), transparent 40%, transparent 60%, rgba(58,216,216,0.2));
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            opacity: 0; transition: opacity .4s; pointer-events: none;
        }
        .mayor-hero-card:hover::before { opacity: 1 }
        .mayor-hero-card:hover {
            transform: perspective(1000px) rotateY(-1deg) translateY(-4px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 40px rgba(168,61,92,0.08);
        }
        .mayor-photo-section {
            width: 320px; min-height: 300px; flex-shrink: 0; position: relative;
            background: linear-gradient(135deg, rgba(168,61,92,0.08), rgba(20,26,44,0.9));
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .mayor-photo-section::after {
            content:''; position:absolute; inset:0;
            background: linear-gradient(to right, transparent 70%, var(--surface));
        }
        .mayor-avatar-frame {
            position: relative; width: 180px; height: 180px;
        }
        .mayor-avatar-frame .hex-ring {
            position: absolute; inset: -6px;
            border: 2px solid var(--rose);
            border-radius: 50%;
            box-shadow: 0 0 30px var(--rose-glow), inset 0 0 30px rgba(168,61,92,0.1);
            animation: mayorGlow 4s ease-in-out infinite;
        }
        @keyframes mayorGlow {
            0%,100% { box-shadow: 0 0 20px var(--rose-glow), inset 0 0 20px rgba(168,61,92,0.05) }
            50% { box-shadow: 0 0 40px var(--rose-glow), 0 0 80px rgba(168,61,92,0.1), inset 0 0 40px rgba(168,61,92,0.1) }
        }
        .mayor-avatar-frame img {
            width: 180px; height: 180px; border-radius: 50%;
            object-fit: cover; position: relative; z-index: 1;
            background: rgba(30,36,54,0.9);
        }
        .mayor-avatar-frame .initials {
            width: 180px; height: 180px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; font-weight: 700; color: var(--rose-light);
            background: linear-gradient(135deg, rgba(168,61,92,0.2), rgba(58,216,216,0.1));
            position: relative; z-index: 1;
        }
        .mayor-info { flex:1; padding: 36px 40px; display: flex; flex-direction: column; justify-content: center }
        .mayor-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 16px; border-radius: 30px;
            background: rgba(168,61,92,0.12); border: 1px solid rgba(168,61,92,0.25);
            color: var(--rose-light); font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            margin-bottom: 14px; width: fit-content;
        }
        .mayor-badge i { font-size: .55rem }
        .mayor-name {
            font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 10px;
        }
        .mayor-desc {
            font-size: .82rem; color: var(--text-dim); line-height: 1.7; margin-bottom: 16px;
        }
        .mayor-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px }
        .mayor-quote {
            padding: 14px 18px; border-left: 3px solid var(--rose);
            background: rgba(168,61,92,0.06); border-radius: 0 12px 12px 0;
            font-size: .78rem; color: var(--text-dim); font-style: italic; line-height: 1.7;
        }

        /* ── VICE MAYOR ── */
        .vice-card {
            grid-column: 1 / -1;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 20px; padding: 30px 28px;
            display: flex; align-items: center; gap: 22px;
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            transition: all .4s cubic-bezier(.25,.46,.45,.94);
            transform-style: preserve-3d; position: relative; overflow: hidden;
        }
        .vice-card::before {
            content:''; position:absolute; inset:0;
            background: linear-gradient(135deg, rgba(58,216,216,0.03), transparent, rgba(168,61,92,0.03));
            opacity:0; transition:opacity .4s; pointer-events:none;
        }
        .vice-card:hover::before { opacity:1 }
        .vice-card:hover {
            transform: perspective(800px) rotateY(1deg) translateY(-4px);
            box-shadow: 0 16px 50px rgba(0,0,0,0.25), 0 0 30px rgba(58,216,216,0.06);
            border-color: rgba(58,216,216,0.15);
        }
        .vice-avatar {
            width: 90px; height: 90px; border-radius: 50%; flex-shrink: 0;
            border: 2px solid var(--cyan);
            box-shadow: 0 0 20px var(--cyan-glow);
            animation: viceGlow 3.5s ease-in-out infinite;
            overflow: hidden; display: grid; place-items: center;
            background: rgba(58,216,216,0.08); position: relative;
        }
        @keyframes viceGlow {
            0%,100% { box-shadow: 0 0 15px var(--cyan-glow) }
            50% { box-shadow: 0 0 30px var(--cyan-glow), 0 0 60px rgba(58,216,216,0.1) }
        }
        .vice-avatar img { width:100%; height:100%; object-fit:cover }
        .vice-avatar i { font-size: 1.5rem; color: var(--cyan); opacity: .5 }
        .vice-info { flex:1; min-width:0 }
        .vice-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: .58rem; font-weight: 700; color: var(--cyan);
            text-transform: uppercase; letter-spacing: .1em; margin-bottom: 6px;
        }
        .vice-badge i { font-size: .5rem }
        .vice-name { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 6px }
        .vice-desc { font-size: .78rem; color: var(--text-dim); line-height: 1.6; margin-bottom: 10px }
        .vice-tags { display: flex; flex-wrap: wrap; gap: 5px }

        /* ── SHARED TAG ── */
        .tag-pill {
            font-size: .6rem; font-weight: 600; padding: 4px 12px;
            border-radius: 20px; border: 1px solid;
        }

        /* ── COUNCIL SECTION ── */
        .council-section { margin-top: 10px }
        .council-header {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 24px; padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }
        .council-header i { font-size: .9rem; color: var(--rose) }
        .council-header h2 {
            font-size: 1.1rem; font-weight: 700; color: #fff;
        }
        .council-header::after {
            content:''; flex:1; height:1px;
            background: linear-gradient(90deg, rgba(168,61,92,0.2), transparent);
        }
        .council-header .count {
            font-size: .65rem; font-weight: 600; color: var(--text-dim);
            padding: 4px 12px; border-radius: 20px;
            background: rgba(168,61,92,0.08); border: 1px solid rgba(168,61,92,0.15);
        }

        /* ── COUNCIL GRID ── */
        .council-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 20px; perspective: 1200px;
        }
        .council-card {
            position: relative; background: var(--surface);
            border: 1px solid var(--border); border-radius: 18px;
            padding: 28px 22px 24px; text-align: center;
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            transition: all .4s cubic-bezier(.25,.46,.45,.94);
            transform-style: preserve-3d; overflow: hidden;
            opacity: 0; transform: translateY(25px);
        }
        .council-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            background: linear-gradient(90deg, transparent, var(--rose), transparent);
            opacity:0; transition: opacity .4s;
        }
        .council-card::after {
            content:''; position:absolute; inset:0; border-radius:18px; padding:1px;
            background: linear-gradient(135deg, rgba(168,61,92,0.2), transparent 50%, rgba(58,216,216,0.1));
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            opacity:0; transition: opacity .4s; pointer-events:none;
        }
        .council-card:hover {
            transform: perspective(800px) rotateY(2deg) rotateX(-1deg) translateY(-6px);
            border-color: rgba(168,61,92,0.15);
            box-shadow: 0 16px 50px rgba(0,0,0,0.25), 0 0 30px rgba(168,61,92,0.06);
        }
        .council-card:hover::before, .council-card:hover::after { opacity:1 }
        .council-card.revealed { opacity:1; transform:translateY(0) }

        .council-avatar {
            position: relative; width: 88px; height: 88px;
            margin: 0 auto 16px; border-radius: 50%;
        }
        .council-avatar .ring {
            position: absolute; inset: -4px; border-radius: 50%;
            border: 2px solid rgba(168,61,92,0.4);
            box-shadow: 0 0 15px rgba(168,61,92,0.15);
            transition: all .4s;
        }
        .council-card:hover .council-avatar .ring {
            border-color: var(--rose);
            box-shadow: 0 0 25px var(--rose-glow);
        }
        .council-avatar img {
            width: 88px; height: 88px; border-radius: 50%;
            object-fit: cover; position: relative; z-index: 1;
            background: rgba(30,36,54,0.9);
        }
        .council-avatar .initials {
            width: 88px; height: 88px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 700; color: var(--rose-light);
            background: linear-gradient(135deg, rgba(168,61,92,0.2), rgba(58,216,216,0.08));
            position: relative; z-index: 1;
        }
        .council-name {
            font-size: .88rem; font-weight: 700; color: #fff; line-height: 1.3; margin-bottom: 4px;
        }
        .council-pos {
            font-size: .65rem; font-weight: 500; color: var(--rose-light);
            margin-bottom: 10px; text-transform: uppercase; letter-spacing: .06em;
        }
        .council-ordinance {
            font-size: .72rem; color: var(--text-dim); font-style: italic;
            text-align: left; padding: 10px 12px; margin-bottom: 12px;
            border-left: 2px solid rgba(168,61,92,0.3);
            background: rgba(168,61,92,0.03); border-radius: 0 8px 8px 0;
            line-height: 1.55;
        }
        .council-tags { display: flex; flex-wrap: wrap; gap: 4px; justify-content: center }

        /* ── EMPTY ── */
        .empty-state {
            text-align: center; padding: 80px 20px;
        }
        .empty-state i { font-size: 3rem; color: rgba(168,61,92,0.2); margin-bottom: 16px }
        .empty-state h3 { font-size: 1.2rem; color: var(--text-dim); font-weight: 500 }
        .empty-state p { font-size: .85rem; color: var(--text-dim); margin-top: 6px; opacity: .6 }

        /* ── FOOTER ── */
        .footer {
            position: relative; z-index: 2; text-align: center;
            padding: 40px 2rem; border-top: 1px solid var(--border);
            background: rgba(6,10,18,0.9);
        }
        .footer-brand { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px }
        .footer-brand img { height: 26px; width: 26px; object-fit: contain; border-radius: 6px; opacity: .6 }
        .footer-brand span { font-size: .8rem; font-weight: 600; color: var(--text-dim) }
        .footer p { font-size: .68rem; color: var(--text-dim); font-weight: 300; opacity: .6 }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .council-grid { grid-template-columns: repeat(2, 1fr); gap: 16px }
            .mayor-photo-section { width: 260px }
        }
        @media (max-width: 768px) {
            .nav { padding: 0 1.2rem; height: 60px }
            .nav-back span { display: none }
            .nav-back { padding: 8px 14px }
            .hero { padding: 120px 1.2rem 50px }
            .hero h1 { font-size: 2rem }
            .hero-sub { font-size: .88rem }
            .content { padding: 10px 1.2rem 60px }
            .mayor-hero-card { flex-direction: column }
            .mayor-photo-section { width: 100%; min-height: 220px }
            .mayor-info { padding: 24px 22px }
            .mayor-name { font-size: 1.3rem }
            .leader-featured { grid-template-columns: 1fr }
            .vice-card { flex-direction: column; text-align: center }
            .vice-tags { justify-content: center }
            .council-grid { grid-template-columns: 1fr; gap: 14px }
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px }
        ::-webkit-scrollbar-track { background: var(--bg) }
        ::-webkit-scrollbar-thumb { background: rgba(168,61,92,0.3); border-radius: 3px }
        ::-webkit-scrollbar-thumb:hover { background: rgba(168,61,92,0.5) }
    </style>
</head>
<body>

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
            <strong>Koronadal City</strong>
            <small>Online Services</small>
        </div>
    </a>
    <a href="/" class="nav-back">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Portal</span>
    </a>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-particles">
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div>
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
                        <img src="<?= htmlspecialchars($mayorData['image']) ?>" alt="<?= htmlspecialchars($mayorData['name'] ?? '') ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
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
                            <span class="tag-pill" style="background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;color:<?= $c['text'] ?>"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="mayor-quote">"Genuine Service for God and for the People... EPAdayon Ang Kanami Sang Bagong Koronadal"</div>
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
                    <img src="<?= htmlspecialchars($viceMayorData['image']) ?>" alt="<?= htmlspecialchars($viceMayorData['name'] ?? '') ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
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
                            <span class="tag-pill" style="background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;color:<?= $c['text'] ?>"><?= htmlspecialchars($tag) ?></span>
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
                            <img src="<?= $imgPath ?>" alt="<?= $name ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
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
                            <span class="tag-pill" style="background:<?= $c2['bg'] ?>;border-color:<?= $c2['border'] ?>;color:<?= $c2['text'] ?>"><?= htmlspecialchars($tag) ?></span>
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
                if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
        cards.forEach(function(c) { obs.observe(c); });
    } else {
        cards.forEach(function(c) { c.classList.add('revealed'); });
    }

    // 3D tilt on council cards
    cards.forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            var rect = card.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            var cx = rect.width / 2;
            var cy = rect.height / 2;
            var ry = ((x - cx) / cx) * 5;
            var rx = ((cy - y) / cy) * 4;
            card.style.transform = 'perspective(800px) rotateY(' + ry + 'deg) rotateX(' + rx + 'deg) translateY(-6px)';
        });
        card.addEventListener('mouseleave', function() { card.style.transform = ''; });
    });

    // Navbar scroll
    var nav = document.getElementById('nav');
    window.addEventListener('scroll', function() {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });
});
</script>
</body>
</html>
