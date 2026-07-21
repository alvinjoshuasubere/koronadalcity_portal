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
    <meta name="theme-color" content="#1A1A2E" />
    <title>Koronadal City — Online Services</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
    /* ===== THEME ===== */
    :root,
    [data-theme="light"] {
        --bone: #F5F3F0;
        --paper: #FAFAF8;
        --card: #FFF;
        --white: #FFF;
        --ink: #1A1A2E;
        --ink-soft: #5C5C6F;
        --ink-faint: #9E9EB0;
        --line: rgba(26, 26, 46, .07);
        --line-strong: rgba(26, 26, 46, .13);
        --rose: #A83D5C;
        --rose-d: #8C2848;
        --rose-l: rgba(168, 61, 92, .08);
        --rose-wash: #F9EDF1;
        --navy: #1A1A2E;
        --navy-l: rgba(26, 26, 46, .05);
        --shadow-sm: 0 1px 3px rgba(26, 26, 46, .04), 0 1px 6px rgba(26, 26, 46, .03);
        --shadow-md: 0 4px 20px rgba(26, 26, 46, .07), 0 1px 4px rgba(26, 26, 46, .04);
        --shadow-lg: 0 16px 48px rgba(26, 26, 46, .12);
        --r: 12px;
        --r-s: 8px;
        --r-xs: 6px;
        --r-l: 16px;
        --r-xl: 22px;
        --r-full: 999px;
        --font: "Sora", ui-sans-serif, system-ui, sans-serif;
        --app-w: 1100px;
        --nav-h: 56px;
        --bottom-h: 60px;
        --safe-b: env(safe-area-inset-bottom, 0px);
        --safe-t: env(safe-area-inset-top, 0px);
        --glow-rose: rgba(168, 61, 92, 0.4);
        --glass-bg: rgba(255, 255, 255, 0.6);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    [data-theme="dark"] {
        --bone: #0B0E14;
        --paper: #10141C;
        --card: #161B26;
        --white: #1C2230;
        --ink: #E2E4EA;
        --ink-soft: #9DA2B0;
        --ink-faint: #626878;
        --line: rgba(226, 228, 234, .06);
        --line-strong: rgba(226, 228, 234, .11);
        --rose: #D06882;
        --rose-d: #DA7E96;
        --rose-l: rgba(208, 104, 130, .1);
        --rose-wash: rgba(208, 104, 130, .05);
        --navy: #0B0E14;
        --navy-l: rgba(15, 20, 30, .3);
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, .35), 0 1px 6px rgba(0, 0, 0, .25);
        --shadow-md: 0 4px 20px rgba(0, 0, 0, .45), 0 1px 4px rgba(0, 0, 0, .3);
        --shadow-lg: 0 16px 48px rgba(0, 0, 0, .55);
        --glow-rose: rgba(208, 104, 130, 0.35);
        --glass-bg: rgba(22, 27, 38, 0.7);
        --glass-border: rgba(226, 228, 234, 0.08);
    }

    /* ===== RESET ===== */
    *,
    *::before,
    *::after {
        margin: 0;
        padding: 0;
        box-sizing: border-box
    }

    html {
        scroll-behavior: smooth;
        scroll-padding-top: calc(var(--nav-h) + 16px)
    }

    body {
        font-family: var(--font);
        background: var(--bone);
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        line-height: 1.6;
        font-size: 14px;
        overflow-x: hidden;
        font-variant-numeric: tabular-nums;
        min-height: 100dvh
    }

    body::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        background: radial-gradient(ellipse at 80% 10%, rgba(168, 61, 92, .03), transparent 55%)
    }

    body::after {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        opacity: .3;
        mix-blend-mode: multiply;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E")
    }

    ::selection {
        background: var(--rose);
        color: #fff
    }

    a {
        text-decoration: none;
        color: inherit
    }

    img {
        display: block;
        max-width: 100%
    }

    button {
        font-family: var(--font);
        cursor: pointer
    }

    body.nav-open {
        overflow: hidden
    }

    /* ===== 3D ANIMATED BACKGROUND ORBS ===== */
    .floating-orbs {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        overflow: hidden
    }

    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        animation: floatOrb 20s ease-in-out infinite alternate
    }

    .orb-1 {
        width: 400px;
        height: 400px;
        background: var(--rose);
        top: -100px;
        right: -100px;
        animation-delay: 0s;
        animation-duration: 22s
    }

    .orb-2 {
        width: 300px;
        height: 300px;
        background: #4A6CF7;
        bottom: 20%;
        left: -80px;
        animation-delay: -5s;
        animation-duration: 18s
    }

    .orb-3 {
        width: 250px;
        height: 250px;
        background: var(--rose-d);
        top: 50%;
        right: 10%;
        animation-delay: -10s;
        animation-duration: 25s;
        opacity: 0.08
    }

    @keyframes floatOrb {
        0% {
            transform: translate(0, 0) scale(1)
        }

        33% {
            transform: translate(30px, -40px) scale(1.05)
        }

        66% {
            transform: translate(-20px, 30px) scale(0.95)
        }

        100% {
            transform: translate(15px, -20px) scale(1.02)
        }
    }

    /* ===== APP SHELL ===== */
    .app {
        position: relative;
        z-index: 1;
        max-width: var(--app-w);
        margin: 0 auto;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        padding-top: var(--nav-h)
    }

    .app-pad {
        padding: 0 16px
    }

    /* ===== VIEW TOGGLE ===== */
    .view-toggle {
        position: fixed;
        bottom: calc(var(--bottom-h) + var(--safe-b) + 16px);
        right: 0;
        z-index: 90;
        display: flex;
        align-items: center;
        gap: 0
    }

    .view-toggle-menu {
        display: flex;
        gap: 4px;
        opacity: 0;
        pointer-events: none;
        transform: translateX(8px);
        transition: all .25s cubic-bezier(.4, 0, .2, 1);
        padding-right: 6px
    }

    .view-toggle.open .view-toggle-menu {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(0)
    }

    .view-toggle-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 6px 10px;
        border-radius: var(--r-full);
        font-size: .55rem;
        font-weight: 600;
        color: var(--ink-soft);
        border: 1px solid var(--line);
        background: var(--glass-bg);
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
        font-family: var(--font);
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px)
    }

    .view-toggle-btn.active {
        background: var(--rose);
        color: #fff;
        border-color: var(--rose)
    }

    .view-toggle-btn:hover:not(.active) {
        background: var(--rose-l);
        color: var(--rose);
        border-color: var(--rose-l)
    }

    .view-toggle-btn i {
        font-size: .45rem
    }

    .view-toggle-trigger {
        width: 30px;
        height: 30px;
        border-radius: var(--r-xs) 0 0 var(--r-xs);
        background: var(--rose);
        color: #fff;
        border: none;
        display: grid;
        place-items: center;
        font-size: .55rem;
        cursor: pointer;
        box-shadow: 0 0 15px var(--glow-rose);
        transition: all .2s cubic-bezier(.4, 0, .2, 1);
        flex-shrink: 0
    }

    .view-toggle-trigger:hover {
        box-shadow: 0 0 25px var(--glow-rose)
    }

    .view-toggle.open .view-toggle-trigger {
        background: var(--ink);
        box-shadow: var(--shadow-sm)
    }

    @media(min-width:769px) {
        .view-toggle {
            bottom: 20px;
            right: 20px
        }
    }

    /* ===== TOP NAV (desktop only) ===== */
    .topnav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        height: var(--nav-h);
        background: var(--glass-bg);
        border-bottom: 1px solid var(--glass-border);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px)
    }

    .topnav-in {
        max-width: var(--app-w);
        margin: 0 auto;
        padding: 0 16px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 8px
    }

    .brand-img {
        width: 36px;
        height: 36px;
        border-radius: var(--r-xs);
        object-fit: contain;
        background: var(--card);
        padding: 3px;
        border: 1px solid var(--line);
        box-shadow: 0 0 10px var(--glow-rose)
    }

    .brand-text {
        display: flex;
        flex-direction: column
    }

    .brand-text strong {
        font-size: .95rem;
        font-weight: 700;
        line-height: 1.15;
        color: var(--ink)
    }

    .brand-text small {
        font-size: .6rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--rose)
    }

    .nav-links {
        display: flex;
        gap: 2px;
        list-style: none
    }

    .nav-links a {
        font-size: .75rem;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: var(--r-s);
        color: var(--ink-soft);
        transition: all .15s
    }

    .nav-links a:hover {
        color: var(--ink);
        background: var(--rose-wash)
    }

    .nav-links a.on {
        color: var(--rose);
        background: var(--rose-l)
    }

    .topnav-r {
        display: flex;
        align-items: center;
        gap: 8px
    }

    .theme-btn {
        width: 34px;
        height: 34px;
        border-radius: var(--r-s);
        border: 1px solid var(--line);
        background: var(--glass-bg);
        color: var(--ink-soft);
        display: grid;
        place-items: center;
        font-size: .75rem;
        transition: all .15s;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px)
    }

    .theme-btn:hover {
        border-color: var(--rose);
        color: var(--rose);
        box-shadow: 0 0 12px var(--glow-rose)
    }

    .nav-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: var(--r-s);
        background: var(--rose);
        color: #fff;
        font-size: .72rem;
        font-weight: 700;
        border: none;
        transition: all .15s;
        box-shadow: 0 0 12px var(--glow-rose)
    }

    .nav-cta:hover {
        background: var(--rose-d);
        transform: translateY(-1px);
        box-shadow: 0 0 20px var(--glow-rose), var(--shadow-md)
    }

    .burger {
        display: none;
        width: 34px;
        height: 34px;
        border-radius: var(--r-s);
        border: 1px solid var(--line);
        background: var(--glass-bg);
        color: var(--ink-soft);
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px)
    }

    /* ===== MOBILE DRAWER ===== */
    .mnav-overlay {
        position: fixed;
        inset: 0;
        z-index: 199;
        background: rgba(0, 0, 0, .45);
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s;
        -webkit-backdrop-filter: blur(4px);
        backdrop-filter: blur(4px)
    }

    .mnav-overlay.on {
        opacity: 1;
        pointer-events: auto
    }

    .mnav {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: min(300px, 82vw);
        z-index: 200;
        background: var(--paper);
        flex-direction: column;
        transform: translateX(100%);
        transition: transform .35s cubic-bezier(.4, 0, .2, 1);
        box-shadow: -8px 0 30px rgba(0, 0, 0, .15);
        padding-bottom: var(--safe-b)
    }

    .mnav.on {
        transform: translateX(0)
    }

    .mnav-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--line)
    }

    .mnav-x {
        width: 32px;
        height: 32px;
        border-radius: var(--r-s);
        border: 1px solid var(--line);
        background: var(--card);
        color: var(--ink-soft);
        display: grid;
        place-items: center;
        font-size: .75rem
    }

    .mnav-links {
        flex: 1;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 2px
    }

    .mnav-links a {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: .82rem;
        font-weight: 600;
        color: var(--ink-soft);
        padding: 11px 14px;
        border-radius: var(--r);
        transition: all .15s;
        transform: translateX(14px);
        opacity: 0
    }

    .mnav.on .mnav-links a {
        transform: translateX(0);
        opacity: 1
    }

    .mnav.on .mnav-links a:nth-child(1) {
        transition-delay: .04s
    }

    .mnav.on .mnav-links a:nth-child(2) {
        transition-delay: .08s
    }

    .mnav.on .mnav-links a:nth-child(3) {
        transition-delay: .12s
    }

    .mnav.on .mnav-links a:nth-child(4) {
        transition-delay: .16s
    }

    .mnav.on .mnav-links a:nth-child(5) {
        transition-delay: .20s
    }

    .mnav.on .mnav-links a:nth-child(6) {
        transition-delay: .24s
    }

    .mnav-links a:hover,
    .mnav-links a.on {
        background: var(--rose-l);
        color: var(--rose)
    }

    .mnav-links a i {
        width: 18px;
        text-align: center;
        font-size: .72rem;
        opacity: .5
    }

    .mnav-foot {
        padding: 12px 16px;
        border-top: 1px solid var(--line)
    }

    .mnav-foot a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px;
        border-radius: var(--r);
        background: var(--rose);
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        transition: all .15s
    }

    .mnav-foot a:active {
        transform: scale(.97);
        opacity: .9
    }

    /* ===== HERO ===== */
    .hero {
        position: relative;
        overflow: hidden
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, #1A1A2E 0%, #151528 55%, #1E2040 100%);
        z-index: 0
    }

    [data-theme="dark"] .hero-bg {
        background: linear-gradient(160deg, #060810 0%, #0A0E18 55%, #0E1420 100%)
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(168, 61, 92, .2), transparent 50%)
    }

    .hero-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        opacity: .04;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M0 30h60M30 0v60' stroke='%23A83D5C' stroke-width='.3'/%3E%3Ccircle cx='30' cy='30' r='1.5' fill='%23A83D5C' opacity='.3'/%3E%3C/svg%3E");
        background-size: 60px 60px;
        animation: gridPulse 8s ease-in-out infinite
    }

    @keyframes gridPulse {

        0%,
        100% {
            opacity: .03
        }

        50% {
            opacity: .07
        }
    }

    .hero-glow-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 300px;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        border: 1px solid rgba(168, 61, 92, .15);
        box-shadow: 0 0 60px rgba(168, 61, 92, .1), inset 0 0 60px rgba(168, 61, 92, .05);
        animation: glowRing 4s ease-in-out infinite;
        z-index: 1
    }

    @keyframes glowRing {

        0%,
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: .5
        }

        50% {
            transform: translate(-50%, -50%) scale(1.1);
            opacity: .8
        }
    }

    .hero-inner {
        position: relative;
        z-index: 2;
        padding: 28px 16px 32px;
        perspective: 800px
    }

    .hero-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px
    }

    .hero-brand img {
        width: 40px;
        height: 40px;
        border-radius: var(--r-s);
        background: rgba(255, 255, 255, .1);
        padding: 3px;
        border: 1px solid rgba(255, 255, 255, .15);
        box-shadow: 0 0 20px rgba(168, 61, 92, .3);
        animation: logoPulse 3s ease-in-out infinite
    }

    @keyframes logoPulse {

        0%,
        100% {
            box-shadow: 0 0 20px rgba(168, 61, 92, .3)
        }

        50% {
            box-shadow: 0 0 35px rgba(168, 61, 92, .5)
        }
    }

    .hero-brand-text {
        display: flex;
        flex-direction: column
    }

    .hero-brand-text strong {
        font-size: .7rem;
        font-weight: 700;
        color: rgba(255, 255, 255, .9);
        letter-spacing: .02em
    }

    .hero-brand-text small {
        font-size: .5rem;
        font-weight: 500;
        color: rgba(255, 255, 255, .4);
        text-transform: uppercase;
        letter-spacing: .08em
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255, 255, 255, .07);
        border: 1px solid rgba(255, 255, 255, .08);
        padding: 5px 12px;
        border-radius: var(--r-full);
        font-size: .55rem;
        font-weight: 600;
        color: rgba(255, 255, 255, .5);
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 14px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px)
    }

    .hero-pill i {
        font-size: .4rem;
        opacity: .6
    }

    .hero h1 {
        font-size: clamp(1.5rem, 5vw, 2.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.08;
        letter-spacing: -.03em;
        margin-bottom: 8px;
        text-shadow: 0 0 30px rgba(168, 61, 92, .2)
    }

    .hero h1 .hl {
        background: linear-gradient(135deg, var(--rose), #D06882, #E8919F);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 20px rgba(168, 61, 92, .3));
        animation: gradientText 5s ease-in-out infinite alternate
    }

    @keyframes gradientText {
        0% {
            filter: drop-shadow(0 0 20px rgba(168, 61, 92, .3))
        }

        100% {
            filter: drop-shadow(0 0 30px rgba(168, 61, 92, .5))
        }
    }

    .hero-sub {
        font-size: .78rem;
        color: rgba(255, 255, 255, .5);
        max-width: 360px;
        line-height: 1.65;
        margin-bottom: 20px
    }

    .hero-btns {
        display: flex;
        gap: 8px
    }

    .hb {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: var(--r-s);
        font-size: .74rem;
        font-weight: 600;
        transition: all .15s;
        border: none;
        cursor: pointer;
        font-family: var(--font);
        box-shadow: var(--shadow-sm)
    }

    .hb:active {
        transform: scale(.97);
        opacity: .9
    }

    .hb-w {
        background: var(--ink);
        color: var(--paper)
    }

    .hb-w:hover {
        box-shadow: var(--shadow-md), 0 0 20px rgba(168, 61, 92, .2);
        transform: translateY(-1px)
    }

    .hb-o {
        background: rgba(255, 255, 255, .07);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .1);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px)
    }

    .hb-o:hover {
        background: rgba(255, 255, 255, .12)
    }

    /* Hero search */
    .hero-search {
        position: relative;
        z-index: 2;
        margin: 20px 16px 0;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 20px;
        box-shadow: var(--shadow-md), 0 0 30px rgba(168, 61, 92, .05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px)
    }

    .hs-label {
        font-size: .5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--rose);
        margin-bottom: 4px
    }

    .hs-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 12px
    }

    .hs-input {
        display: flex;
        border: 1px solid var(--line-strong);
        border-radius: var(--r-s);
        overflow: hidden;
        transition: all .15s;
        background: var(--white);
        box-shadow: var(--shadow-sm)
    }

    [data-theme="dark"] .hs-input {
        background: var(--card)
    }

    .hs-input:focus-within {
        border-color: var(--rose);
        box-shadow: 0 0 0 1px var(--rose-wash), 0 0 15px var(--glow-rose)
    }

    .hs-input input {
        flex: 1;
        border: none;
        outline: none;
        padding: 11px 14px;
        font-size: .82rem;
        font-family: var(--font);
        color: var(--ink);
        min-width: 0;
        background: transparent
    }

    .hs-input input::placeholder {
        color: var(--ink-faint)
    }

    .hs-input button {
        background: var(--rose);
        color: #fff;
        border: none;
        padding: 0 16px;
        font-size: .82rem;
        transition: background .15s
    }

    .hs-input button:active {
        background: var(--rose-d)
    }

    .hs-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 12px
    }

    .hs-tags span {
        font-size: .6rem;
        color: var(--ink-faint);
        font-weight: 600
    }

    .ht {
        font-size: .6rem;
        font-weight: 500;
        color: var(--ink-soft);
        padding: 4px 10px;
        border-radius: var(--r-xs);
        background: var(--bone);
        border: 1px solid var(--line);
        transition: all .15s
    }

    .ht:active {
        background: var(--rose-l);
        color: var(--rose);
        transform: scale(.95)
    }

    /* Stats */
    .stats {
        position: relative;
        z-index: 2;
        margin-top: -16px;
        padding: 0 16px 20px
    }

    .stats-bar {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        box-shadow: var(--shadow-md), 0 0 20px rgba(168, 61, 92, .05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transform: perspective(500px) rotateX(1deg);
        transition: transform .3s ease
    }

    .stats-bar:hover {
        transform: perspective(500px) rotateX(0deg)
    }

    .st-item {
        text-align: center;
        padding: 8px 4px;
        border-radius: var(--r-s);
        transition: all .15s
    }

    .st-item:hover {
        background: var(--rose-wash)
    }

    .st-num {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--rose);
        line-height: 1;
        text-shadow: 0 0 10px var(--glow-rose)
    }

    .st-label {
        font-size: .54rem;
        color: var(--ink-faint);
        font-weight: 600;
        margin-top: 3px;
        text-transform: uppercase;
        letter-spacing: .04em
    }

    /* ===== QUICK LINKS CAROUSEL ===== */
    .qnav {
        padding: 12px 0;
        border-bottom: 1px solid var(--line);
        background: var(--paper)
    }

    .qnav-row {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding: 0 16px
    }

    .qnav-row::-webkit-scrollbar {
        display: none
    }

    .qnav-lbl {
        font-size: .5rem;
        font-weight: 700;
        color: var(--ink-faint);
        text-transform: uppercase;
        letter-spacing: .08em;
        white-space: nowrap;
        align-self: center;
        margin-right: 4px;
        flex-shrink: 0
    }

    .qpill {
        scroll-snap-align: start;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 11px;
        border-radius: var(--r-full);
        font-size: .62rem;
        font-weight: 500;
        color: var(--ink-soft);
        background: var(--card);
        border: 1px solid var(--line);
        white-space: nowrap;
        transition: all .15s;
        flex-shrink: 0
    }

    .qpill:active {
        transform: scale(.95);
        background: var(--rose-l);
        color: var(--rose);
        border-color: var(--rose-l)
    }

    .qpill i {
        font-size: .48rem
    }

    /* ===== SECTION LABEL ===== */
    .sec-pad {
        padding: 0 16px
    }

    .sec-head {
        margin-bottom: 22px
    }

    .sec-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .56rem;
        font-weight: 700;
        color: var(--rose);
        text-transform: uppercase;
        letter-spacing: .12em;
        margin-bottom: 6px
    }

    .sec-label i {
        font-size: .5rem
    }

    .sec-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--line), transparent)
    }

    .sec-head h2 {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: var(--ink);
        margin-bottom: 4px
    }

    .sec-head p {
        font-size: .76rem;
        color: var(--ink-soft)
    }

    /* ===== SERVICES ===== */
    .svc {
        padding: 32px 0 0
    }

    .svc-filters {
        display: flex;
        gap: 5px;
        margin-bottom: 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 2px
    }

    .svc-filters::-webkit-scrollbar {
        display: none
    }

    .fb {
        scroll-snap-align: start;
        padding: 7px 13px;
        border-radius: var(--r-s);
        font-size: .66rem;
        font-weight: 600;
        border: 1px dashed var(--line-strong);
        background: var(--glass-bg);
        color: var(--ink-faint);
        transition: all .15s;
        white-space: nowrap;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
        opacity: .7;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px)
    }

    .fb:active {
        transform: scale(.95)
    }

    .fb.on {
        opacity: 1;
        border-style: solid;
        border-color: var(--rose);
        color: var(--ink);
        box-shadow: 0 0 0 1px var(--rose-wash), 0 0 12px var(--glow-rose)
    }

    .scnt {
        font-size: .62rem;
        color: var(--ink-faint);
        margin-bottom: 14px;
        font-weight: 500
    }

    .scnt b {
        color: var(--rose);
        font-weight: 700
    }

    /* ===== PORTAL CARDS (Desktop) ===== */
    .portals {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 32px;
        perspective: 1000px
    }

    .portal {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-left: 3px solid var(--rose);
        border-radius: var(--r);
        overflow: hidden;
        transition: all .35s cubic-bezier(.4, 0, .2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transform-style: preserve-3d
    }

    .portal:hover {
        transform: perspective(800px) rotateY(-2deg) rotateX(1deg) translateY(-5px) translateZ(10px);
        box-shadow: 0 10px 40px rgba(168, 61, 92, .15), 0 0 20px var(--glow-rose);
        border-left-color: var(--rose-d)
    }

    .portal:active {
        transform: scale(.98)
    }

    .portal-accent {
        display: none
    }

    .portal-body {
        padding: 24px 22px 22px;
        flex: 1;
        display: flex;
        flex-direction: column
    }

    .portal-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px
    }

    .portal-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: 1.1rem;
        transition: all .25s cubic-bezier(.4, 0, .2, 1)
    }

    .portal-icon.rose {
        background: var(--rose-l);
        color: var(--rose)
    }

    .portal:hover .portal-icon.rose {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 15px var(--glow-rose)
    }

    .portal-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .48rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: 4px 10px;
        border-radius: var(--r-full);
        background: var(--rose-l);
        color: var(--rose)
    }

    .portal-badge i {
        font-size: .3rem
    }

    .portal h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: 6px
    }

    .portal-desc {
        font-size: .75rem;
        color: var(--ink-soft);
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1
    }

    .portal-features {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 16px
    }

    .portal-feat {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 5px 11px;
        border-radius: var(--r-xs);
        font-size: .6rem;
        font-weight: 500;
        border: 1px solid var(--line);
        background: var(--bone);
        color: var(--ink-soft)
    }

    .portal-feat i {
        font-size: .44rem;
        opacity: .5
    }

    .portal-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 11px;
        border-radius: var(--r-s);
        font-size: .74rem;
        font-weight: 600;
        border: 1px solid var(--line-strong);
        cursor: pointer;
        transition: all .2s cubic-bezier(.4, 0, .2, 1);
        text-decoration: none;
        font-family: var(--font);
        color: var(--ink-soft);
        background: transparent
    }

    .portal-cta:active {
        transform: scale(.97)
    }

    .portal-cta.rose {
        background: transparent;
        color: var(--rose);
        border-color: var(--rose-l)
    }

    .portal-cta.rose:hover {
        background: var(--rose);
        color: #fff;
        border-color: var(--rose);
        box-shadow: 0 4px 20px var(--glow-rose)
    }

    .portal-cta i {
        font-size: .6rem;
        transition: transform .2s
    }

    .portal:hover .portal-cta i {
        transform: translateX(2px)
    }

    /* ===== SECONDARY SERVICES (Desktop) ===== */
    .svc-sec-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px
    }

    .svc-sec-head h3 {
        font-size: .72rem;
        font-weight: 700;
        color: var(--ink)
    }

    .svc-sec-head::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--line)
    }

    .svc-sec {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        perspective: 1200px
    }

    .svc-sec-item {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .35s cubic-bezier(.4, 0, .2, 1);
        text-decoration: none;
        color: inherit;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transform-style: preserve-3d
    }

    .svc-sec-item:hover {
        border-color: var(--rose-l);
        box-shadow: var(--shadow-md), 0 0 20px var(--glow-rose);
        transform: perspective(800px) rotateY(-3deg) rotateX(1deg) translateY(-4px) translateZ(6px)
    }

    .svc-sec-item:active {
        transform: scale(.98)
    }

    .svc-sec-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: .75rem;
        flex-shrink: 0
    }

    .svc-sec-icon.rose {
        background: var(--rose-l);
        color: var(--rose)
    }

    .svc-sec-body {
        flex: 1;
        min-width: 0
    }

    .svc-sec-body h4 {
        font-size: .75rem;
        font-weight: 600;
        color: var(--ink);
        line-height: 1.3;
        margin-bottom: 2px
    }

    .svc-sec-body p {
        font-size: .64rem;
        color: var(--ink-faint);
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .svc-sec-arrow {
        color: var(--ink-faint);
        font-size: .55rem;
        flex-shrink: 0;
        transition: all .2s
    }

    .svc-sec-item:hover .svc-sec-arrow {
        color: var(--rose);
        transform: translateX(2px)
    }

    /* ===== FEATURED SERVICES ===== */
    .svc-featured {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 28px;
        perspective: 1200px
    }

    .svc-feat-card {
        position: relative;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-l);
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition: all .4s cubic-bezier(.4, 0, .2, 1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: var(--shadow-md), 0 0 0 1px var(--rose-wash);
        transform-style: preserve-3d
    }

    .svc-feat-glow {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity .4s;
        pointer-events: none;
        border-radius: var(--r-l)
    }

    .svc-feat-emergency .svc-feat-glow {
        background: radial-gradient(circle at 30% 50%, rgba(168, 61, 92, .08), transparent 70%)
    }

    .svc-feat-officials .svc-feat-glow {
        background: radial-gradient(circle at 70% 50%, rgba(168, 61, 92, .08), transparent 70%)
    }

    .svc-feat-card:hover .svc-feat-glow {
        opacity: 1
    }

    .svc-feat-card:hover {
        transform: perspective(800px) rotateY(-2deg) rotateX(1deg) translateY(-4px) translateZ(8px);
        box-shadow: var(--shadow-lg), 0 0 30px var(--glow-rose), 0 0 60px rgba(168, 61, 92, .08);
        border-color: var(--rose-l)
    }

    .svc-feat-card:active {
        transform: scale(.98)
    }

    .svc-feat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--r);
        display: grid;
        place-items: center;
        font-size: 1rem;
        background: var(--rose-l);
        color: var(--rose);
        flex-shrink: 0;
        transition: all .3s;
        position: relative;
        z-index: 1;
        box-shadow: 0 0 15px var(--glow-rose)
    }

    .svc-feat-card:hover .svc-feat-icon {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 25px var(--glow-rose)
    }

    .svc-feat-body {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 1
    }

    .svc-feat-body h4 {
        font-size: .82rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
        margin-bottom: 3px
    }

    .svc-feat-body p {
        font-size: .64rem;
        color: var(--ink-faint);
        line-height: 1.4
    }

    .svc-feat-badge {
        width: 30px;
        height: 30px;
        border-radius: var(--r-full);
        display: grid;
        place-items: center;
        font-size: .55rem;
        background: var(--rose);
        color: #fff;
        flex-shrink: 0;
        transition: all .3s;
        position: relative;
        z-index: 1;
        box-shadow: 0 0 12px var(--glow-rose)
    }

    .svc-feat-card:hover .svc-feat-badge {
        transform: translateX(3px);
        box-shadow: 0 0 20px var(--glow-rose)
    }

    /* ===== MOBILE: PORTALS + LIST ===== */
    .svc-list {
        display: none
    }

    /* ===== EMERGENCY CONTACTS ===== */
    .emergency {
        padding: 32px 0
    }

    .emergency .sec-head h2 {
        text-shadow: 0 0 20px var(--glow-rose)
    }

    .emergency-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        perspective: 1000px
    }

    .emergency-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transform-style: preserve-3d
    }

    .emergency-card::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: var(--r);
        border: 1px solid transparent;
        background: linear-gradient(135deg, rgba(168, 61, 92, .2), transparent 50%) border-box;
        -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0;
        transition: opacity .3s
    }

    .emergency-card:hover::before {
        opacity: 1
    }

    .emergency-card:hover {
        transform: perspective(800px) rotateX(2deg) translateY(-4px);
        box-shadow: var(--shadow-md), 0 0 25px var(--glow-rose)
    }

    .em-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: .85rem;
        background: var(--rose-l);
        color: var(--rose);
        transition: all .25s
    }

    .emergency-card:hover .em-icon {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 15px var(--glow-rose)
    }

    .em-name {
        font-size: .82rem;
        font-weight: 700;
        color: var(--ink)
    }

    .em-phone {
        font-size: .78rem;
        font-weight: 600;
        color: var(--rose);
        transition: all .15s
    }

    .em-phone:hover {
        text-shadow: 0 0 10px var(--glow-rose)
    }

    .em-desc {
        font-size: .65rem;
        color: var(--ink-faint);
        line-height: 1.5
    }

    .emergency-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 16px;
        color: var(--ink-faint);
        font-size: .78rem
    }

    .emergency-link {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px
    }

    .emergency-link a {
        font-size: .68rem;
        font-weight: 600;
        color: var(--ink-soft);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: var(--r-s);
        border: 1px solid var(--line);
        background: var(--glass-bg);
        transition: all .15s;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px)
    }

    .emergency-link a:hover {
        color: var(--rose);
        border-color: var(--rose-l);
        box-shadow: 0 0 10px var(--glow-rose)
    }

    /* ===== QUICK ACCESS ===== */
    .qacc {
        padding: 32px 0
    }

    .qgrid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        perspective: 1200px
    }

    .qc {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 11px;
        text-decoration: none;
        color: inherit;
        transition: all .35s cubic-bezier(.4, 0, .2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transform-style: preserve-3d
    }

    .qc::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--rose);
        opacity: 0;
        transition: opacity .15s
    }

    .qc:hover {
        border-color: var(--rose-l);
        box-shadow: 0 0 0 1px var(--rose-wash), 0 0 20px var(--glow-rose);
        transform: perspective(800px) rotateY(2deg) rotateX(-1deg) translateY(-4px) translateZ(8px)
    }

    .qc:hover::before {
        opacity: 1
    }

    .qc:active {
        transform: scale(.98)
    }

    .qc-ico {
        width: 36px;
        height: 36px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: .72rem;
        background: var(--rose-l);
        color: var(--rose);
        flex-shrink: 0;
        transition: all .15s
    }

    .qc:hover .qc-ico {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 12px var(--glow-rose)
    }

    .qc-body {
        flex: 1;
        min-width: 0
    }

    .qc-body strong {
        font-size: .72rem;
        font-weight: 700;
        color: var(--ink);
        display: block;
        line-height: 1.2
    }

    .qc-body small {
        font-size: .58rem;
        color: var(--ink-faint);
        font-weight: 500
    }

    .qc-arrow {
        color: var(--ink-faint);
        font-size: .55rem;
        flex-shrink: 0;
        transition: all .15s;
        opacity: .5
    }

    .qc:hover .qc-arrow {
        color: var(--rose);
        opacity: 1;
        transform: translateX(2px)
    }

    /* ===== CITY LEADERSHIP ===== */
    .leadership {
        padding: 32px 0;
        background: var(--paper);
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line)
    }

    /* Mayor — Banner Card */
    .leader-mayor {
        border-radius: var(--r-l);
        overflow: hidden;
        position: relative;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: all .4s cubic-bezier(.4, 0, .2, 1);
        margin-bottom: 20px
    }

    .leader-mayor:hover {
        box-shadow: var(--shadow-lg), 0 0 30px var(--glow-rose)
    }

    .leader-mayor-img {
        width: 100%;
        aspect-ratio: 16/7;
        overflow: hidden;
        position: relative
    }

    .leader-mayor-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top
    }

    .leader-mayor-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(26, 26, 46, .85) 0%, transparent 60%)
    }

    [data-theme="dark"] .leader-mayor-overlay {
        background: linear-gradient(to top, rgba(11, 14, 20, .9) 0%, transparent 60%)
    }

    .leader-mayor-body {
        padding: 20px 24px 22px;
        margin-top: -40px;
        position: relative;
        z-index: 2
    }

    .leader-mayor-photo-float {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--rose);
        box-shadow: 0 0 25px var(--glow-rose), 0 4px 20px rgba(0, 0, 0, .2);
        position: absolute;
        top: -45px;
        right: 24px;
        background: var(--rose-l);
        display: grid;
        place-items: center;
        animation: mayorFloatGlow 3s ease-in-out infinite;
        z-index: 3
    }

    .leader-mayor-photo-float img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .leader-mayor-photo-float i {
        font-size: 1.8rem;
        color: var(--rose);
        opacity: .6
    }

    @keyframes mayorFloatGlow {

        0%,
        100% {
            box-shadow: 0 0 20px var(--glow-rose), 0 4px 20px rgba(0, 0, 0, .2)
        }

        50% {
            box-shadow: 0 0 35px var(--glow-rose), 0 4px 30px rgba(0, 0, 0, .25)
        }
    }

    .leader-mayor-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .5rem;
        font-weight: 700;
        color: var(--rose);
        text-transform: uppercase;
        letter-spacing: .12em;
        padding: 4px 12px;
        border-radius: var(--r-full);
        background: var(--rose-l);
        border: 1px solid var(--rose-wash);
        margin-bottom: 8px
    }

    .leader-mayor-badge i {
        font-size: .4rem
    }

    .leader-mayor-name {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.15;
        margin-bottom: 8px
    }

    .leader-mayor-desc {
        font-size: .74rem;
        color: var(--ink-soft);
        line-height: 1.65;
        margin-bottom: 10px;
        max-width: 600px
    }

    .leader-mayor-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 12px
    }

    .leader-mayor-quote {
        padding: 12px 14px;
        background: var(--rose-l);
        border-left: 3px solid var(--rose);
        border-radius: 0 var(--r-s) var(--r-s) 0;
        font-size: .7rem;
        color: var(--ink-soft);
        font-style: italic;
        line-height: 1.65
    }

    /* Shared tag pill */
    .leader-tag {
        font-size: .52rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: var(--r-full);
        background: var(--rose-l);
        color: var(--rose);
        border: 1px solid var(--rose-wash)
    }

    /* Vice Mayor — Featured Card */
    .leader-vice {
        margin-bottom: 24px
    }

    .leader-vice-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 22px 24px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-l);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: var(--shadow-sm);
        transition: all .35s cubic-bezier(.4, 0, .2, 1);
        perspective: 800px;
        transform-style: preserve-3d
    }

    .leader-vice-card:hover {
        transform: perspective(800px) rotateY(-2deg) translateY(-3px);
        box-shadow: var(--shadow-md), 0 0 25px var(--glow-rose)
    }

    .leader-vice-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 3px solid var(--rose);
        box-shadow: 0 0 20px var(--glow-rose);
        display: grid;
        place-items: center;
        background: var(--rose-l);
        animation: viceGlow 3s ease-in-out infinite
    }

    @keyframes viceGlow {

        0%,
        100% {
            box-shadow: 0 0 15px var(--glow-rose)
        }

        50% {
            box-shadow: 0 0 30px var(--glow-rose), 0 0 60px rgba(168, 61, 92, .1)
        }
    }

    .leader-vice-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .leader-vice-photo i {
        font-size: 1.6rem;
        color: var(--rose);
        opacity: .6
    }

    .leader-vice-body {
        flex: 1;
        min-width: 0
    }

    .leader-vice-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .48rem;
        font-weight: 700;
        color: var(--rose);
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-bottom: 4px
    }

    .leader-vice-badge i {
        font-size: .42rem
    }

    .leader-vice-name {
        font-size: 1rem;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: 5px
    }

    .leader-vice-desc {
        font-size: .72rem;
        color: var(--ink-soft);
        line-height: 1.6;
        margin-bottom: 8px
    }

    .leader-vice-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px
    }

    /* Councilors — Grid */
    .leader-council-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .6rem;
        font-weight: 700;
        color: var(--rose);
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-bottom: 14px
    }

    .leader-council-label i {
        font-size: .55rem
    }

    .leader-council-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--line)
    }

    .leader-council-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        perspective: 1200px
    }

    .leader-council-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 18px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 10px;
        transition: all .35s cubic-bezier(.4, 0, .2, 1);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: var(--shadow-sm);
        transform-style: preserve-3d
    }

    .leader-council-card:hover {
        transform: perspective(800px) rotateY(2deg) rotateX(-1deg) translateY(-4px) translateZ(6px);
        box-shadow: var(--shadow-md), 0 0 20px var(--glow-rose)
    }

    .leader-council-photo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--rose-l);
        box-shadow: 0 0 12px var(--glow-rose);
        display: grid;
        place-items: center;
        background: var(--rose-l);
        transition: all .3s
    }

    .leader-council-card:hover .leader-council-photo {
        border-color: var(--rose);
        box-shadow: 0 0 20px var(--glow-rose)
    }

    .leader-council-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .leader-council-photo i {
        font-size: 1.2rem;
        color: var(--rose);
        opacity: .4
    }

    .leader-council-body {
        min-width: 0
    }

    .leader-council-name {
        font-size: .72rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: 2px
    }

    .leader-council-pos {
        font-size: .56rem;
        color: var(--ink-faint);
        font-weight: 500;
        margin-bottom: 6px
    }

    .leader-council-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        justify-content: center
    }

    /* ===== CULTURE ===== */
    .culture {
        padding: 20px 0;
        background: var(--navy);
        position: relative;
        overflow: hidden
    }

    .culture::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: .06;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48'%3E%3Cpath d='M24 0L48 24L24 48L0 24Z' fill='none' stroke='%23A83D5C' stroke-width='1.2'/%3E%3C/svg%3E");
        background-size: 48px 48px
    }

    [data-theme="dark"] .culture {
        background: #0B0E14
    }

    .culture-in {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-align: center;
        padding: 0 16px
    }

    .culture-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--rose);
        flex-shrink: 0
    }

    .culture-txt h3 {
        font-size: .78rem;
        font-weight: 700;
        color: rgba(255, 255, 255, .65);
        margin-bottom: 1px
    }

    .culture-txt p {
        font-size: .64rem;
        color: rgba(255, 255, 255, .22)
    }

    /* ===== FOOTER ===== */
    .footer {
        background: var(--navy);
        color: rgba(255, 255, 255, .3);
        padding: 40px 16px 16px;
        margin-top: auto;
        position: relative
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--rose), transparent);
        opacity: .4
    }

    [data-theme="dark"] .footer {
        background: #0B0E14
    }

    .fgrid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px
    }

    .f-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px
    }

    .f-brand img {
        width: 24px;
        height: 24px;
        border-radius: var(--r-xs);
        background: rgba(255, 255, 255, .05);
        padding: 2px;
        box-shadow: 0 0 8px var(--glow-rose)
    }

    .f-brand strong {
        font-size: .8rem;
        font-weight: 800;
        color: #fff
    }

    .f-desc {
        font-size: .66rem;
        line-height: 1.8;
        margin-bottom: 10px;
        color: rgba(255, 255, 255, .18)
    }

    .f-soc {
        display: flex;
        gap: 6px
    }

    .f-soc a {
        width: 30px;
        height: 30px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, .04);
        color: rgba(255, 255, 255, .22);
        font-size: .68rem;
        transition: all .15s
    }

    .f-soc a:active {
        transform: scale(.9)
    }

    .f-soc a:hover {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 15px var(--glow-rose)
    }

    .fcol-t {
        font-size: .56rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: rgba(255, 255, 255, .25);
        margin-bottom: 8px
    }

    .fcol ul {
        list-style: none
    }

    .fcol li {
        margin-bottom: 5px
    }

    .fcol a {
        font-size: .68rem;
        color: rgba(255, 255, 255, .18);
        transition: color .15s
    }

    .fcol a:hover {
        color: #fff;
        text-shadow: 0 0 8px var(--glow-rose)
    }

    .f-bottom {
        border-top: 1px solid rgba(255, 255, 255, .04);
        padding-top: 14px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 4px
    }

    .f-copy {
        font-size: .6rem;
        color: rgba(255, 255, 255, .08)
    }

    .f-motto {
        font-size: .62rem;
        color: rgba(255, 255, 255, .05);
        font-style: italic
    }

    /* ===== BOTTOM NAV ===== */
    .bottomnav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        background: var(--glass-bg);
        border-top: 1px solid var(--glass-border);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding-bottom: var(--safe-b)
    }

    .bn-row {
        display: flex;
        align-items: center;
        justify-content: space-around;
        height: var(--bottom-h);
        padding: 0 4px;
        position: relative
    }

    .bn-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
        padding: 6px 10px;
        border-radius: var(--r-s);
        transition: all .2s;
        text-decoration: none;
        color: var(--ink-faint);
        font-size: .5rem;
        font-weight: 600;
        position: relative;
        min-width: 48px
    }

    .bn-item i {
        font-size: .85rem;
        transition: all .2s
    }


    .bn-item:hover {
        color: var(--rose)
    }

    .bn-item.active {
        color: var(--rose)
    }

    .bn-item.active i {
        transform: scale(1.1)
    }

    .bn-item:active {
        transform: scale(.9)
    }

    .bn-cta {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--rose);
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 1rem;
        box-shadow: 0 4px 20px var(--glow-rose);
        margin-top: -16px;
        border: 3px solid var(--paper);
        transition: all .15s;
        position: relative;
        z-index: 2
    }

    .bn-cta:active {
        transform: scale(.9);
        box-shadow: 0 2px 10px var(--glow-rose)
    }

    /* ===== DIVIDER ===== */
    .tn {
        height: 2px;
        width: 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='2'%3E%3Cpath d='M0 1L5 0L10 1L15 0L20 1' fill='none' stroke='%23E0521B' stroke-width='.5' opacity='.3'/%3E%3C/svg%3E");
        background-size: 20px 2px;
        background-repeat: repeat-x
    }

    /* ===== 3D INTERACTIVE FEATURES ===== */

    /* Hero mouse parallax layers */
    .hero-parallax {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        perspective: 800px;
        overflow: hidden
    }

    .hp-layer {
        position: absolute;
        transition: transform .15s ease-out;
        will-change: transform
    }

    .hp-shape {
        border: 1px solid rgba(168, 61, 92, .12);
        background: rgba(168, 61, 92, .04);
        backdrop-filter: blur(4px)
    }

    .hp-shape.hex {
        width: 60px;
        height: 60px;
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        animation: hexSpin 12s linear infinite
    }

    .hp-shape.diamond {
        width: 40px;
        height: 40px;
        transform: rotate(45deg);
        animation: diamondFloat 8s ease-in-out infinite
    }

    .hp-shape.circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid rgba(168, 61, 92, .1);
        background: radial-gradient(circle, rgba(168, 61, 92, .06), transparent);
        animation: circlePulse 6s ease-in-out infinite
    }

    .hp-shape.tri {
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 34px solid rgba(168, 61, 92, .08);
        background: none;
        border-top: none;
        animation: triFloat 10s ease-in-out infinite
    }

    .hp-shape.dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(168, 61, 92, .3);
        box-shadow: 0 0 8px rgba(168, 61, 92, .2)
    }

    @keyframes hexSpin {
        from {
            transform: rotate(0deg) scale(1)
        }

        to {
            transform: rotate(360deg) scale(1)
        }
    }

    @keyframes diamondFloat {

        0%,
        100% {
            transform: rotate(45deg) translateY(0)
        }

        50% {
            transform: rotate(45deg) translateY(-12px)
        }
    }

    @keyframes circlePulse {

        0%,
        100% {
            transform: scale(1);
            opacity: .6
        }

        50% {
            transform: scale(1.15);
            opacity: 1
        }
    }

    @keyframes triFloat {

        0%,
        100% {
            transform: translateY(0) rotate(0deg)
        }

        50% {
            transform: translateY(-10px) rotate(5deg)
        }
    }

    /* City Overview 3D Glass Card */
    .city-overview {
        padding: 28px 0 12px;
        perspective: 1200px
    }

    .co-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-l);
        padding: 28px 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: var(--shadow-md), 0 0 0 1px var(--rose-wash);
        transition: all .4s cubic-bezier(.4, 0, .2, 1);
        transform-style: preserve-3d;
        position: relative;
        overflow: hidden
    }

    .co-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(168, 61, 92, .04), transparent 40%, transparent 60%, rgba(74, 108, 247, .03));
        pointer-events: none
    }

    .co-card:hover {
        transform: perspective(1000px) rotateX(2deg) rotateY(-1deg) translateY(-4px) translateZ(8px);
        box-shadow: var(--shadow-lg), 0 0 40px var(--glow-rose)
    }

    .co-left {
        position: relative;
        z-index: 1
    }

    .co-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--rose);
        padding: 4px 10px;
        border-radius: var(--r-full);
        background: var(--rose-l);
        border: 1px solid var(--rose-wash);
        margin-bottom: 10px
    }

    .co-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: 6px
    }

    .co-desc {
        font-size: .72rem;
        color: var(--ink-soft);
        line-height: 1.65;
        margin-bottom: 14px
    }

    .co-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px
    }

    .co-right {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        position: relative;
        z-index: 1
    }

    .co-stat {
        background: var(--bone);
        border: 1px solid var(--line);
        border-radius: var(--r);
        padding: 14px 12px;
        text-align: center;
        transition: all .3s;
        transform-style: preserve-3d;
        cursor: default
    }

    .co-stat:hover {
        transform: perspective(600px) rotateX(4deg) translateY(-3px) translateZ(6px);
        border-color: var(--rose-l);
        box-shadow: 0 8px 25px rgba(168, 61, 92, .1)
    }

    .co-stat-num {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--rose);
        line-height: 1;
        margin-bottom: 3px;
        text-shadow: 0 0 10px var(--glow-rose)
    }

    .co-stat-lbl {
        font-size: .52rem;
        font-weight: 600;
        color: var(--ink-faint);
        text-transform: uppercase;
        letter-spacing: .04em
    }

    @media(max-width:600px) {
        .co-card {
            grid-template-columns: 1fr
        }

        .co-right {
            grid-template-columns: repeat(4, 1fr)
        }
    }

    /* 3D City Feature Cards */
    .city-features {
        padding: 24px 0;
        perspective: 1200px
    }

    .cf-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px
    }

    .cf-card {
        position: relative;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 22px 18px 18px;
        text-align: center;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: var(--shadow-sm);
        transition: all .4s cubic-bezier(.4, 0, .2, 1);
        transform-style: preserve-3d;
        cursor: default;
        overflow: hidden
    }

    .cf-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--rose), transparent);
        opacity: 0;
        transition: opacity .3s
    }

    .cf-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: var(--r);
        padding: 1px;
        background: linear-gradient(135deg, rgba(168, 61, 92, .2), transparent 50%, rgba(74, 108, 247, .1));
        -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity .3s;
        pointer-events: none
    }

    .cf-card:hover {
        transform: perspective(800px) rotateY(-3deg) rotateX(2deg) translateY(-6px) translateZ(10px);
        box-shadow: 0 14px 40px rgba(168, 61, 92, .12), 0 0 25px var(--glow-rose);
        border-color: var(--rose-l)
    }

    .cf-card:hover::before,
    .cf-card:hover::after {
        opacity: 1
    }

    .cf-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.2rem;
        margin: 0 auto 12px;
        background: var(--rose-l);
        color: var(--rose);
        transition: all .3s;
        box-shadow: 0 0 15px rgba(168, 61, 92, .15);
        position: relative;
        z-index: 1
    }

    .cf-card:hover .cf-icon {
        background: var(--rose);
        color: #fff;
        box-shadow: 0 0 25px var(--glow-rose);
        transform: translateZ(12px) scale(1.05)
    }

    .cf-name {
        font-size: .82rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 4px;
        position: relative;
        z-index: 1
    }

    .cf-desc {
        font-size: .64rem;
        color: var(--ink-faint);
        line-height: 1.5;
        position: relative;
        z-index: 1
    }

    @media(max-width:768px) {
        .cf-grid {
            grid-template-columns: repeat(2, 1fr)
        }
    }

    @media(max-width:480px) {
        .cf-grid {
            grid-template-columns: 1fr
        }
    }

    /* City Motto 3D Parallax */
    .city-motto {
        padding: 32px 0;
        perspective: 800px;
        overflow: hidden;
        position: relative
    }

    .cm-card {
        background: linear-gradient(135deg, rgba(168, 61, 92, .06), rgba(74, 108, 247, .03));
        border: 1px solid var(--rose-wash);
        border-radius: var(--r-l);
        padding: 32px 28px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all .4s;
        transform-style: preserve-3d
    }

    .cm-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 0%, rgba(168, 61, 92, .08), transparent 60%);
        pointer-events: none
    }

    .cm-card:hover {
        transform: perspective(800px) rotateX(2deg) translateY(-3px);
        box-shadow: var(--shadow-md), 0 0 30px rgba(168, 61, 92, .08)
    }

    .cm-quote {
        font-size: clamp(.85rem, 2.5vw, 1.15rem);
        font-weight: 700;
        color: var(--ink);
        line-height: 1.5;
        margin-bottom: 12px;
        font-style: italic;
        position: relative;
        z-index: 1
    }

    .cm-quote .hl {
        color: var(--rose);
        text-shadow: 0 0 15px var(--glow-rose)
    }

    .cm-attr {
        font-size: .65rem;
        color: var(--ink-faint);
        font-weight: 500;
        position: relative;
        z-index: 1
    }

    .cm-deco {
        position: absolute;
        width: 80px;
        height: 80px;
        border: 1px solid rgba(168, 61, 92, .08);
        border-radius: 50%;
        pointer-events: none
    }

    .cm-deco-1 {
        top: -20px;
        right: -20px;
        animation: cmSpin 15s linear infinite
    }

    .cm-deco-2 {
        bottom: -15px;
        left: -15px;
        width: 60px;
        height: 60px;
        animation: cmSpin 20s linear infinite reverse
    }

    @keyframes cmSpin {
        from {
            transform: rotate(0deg)
        }

        to {
            transform: rotate(360deg)
        }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes rise {
        from {
            opacity: 0;
            transform: translateY(8px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-8px)
        }

        to {
            opacity: 1;
            transform: translateX(0)
        }
    }

    @keyframes borderGlow {

        0%,
        100% {
            box-shadow: 0 0 5px var(--glow-rose)
        }

        50% {
            box-shadow: 0 0 20px var(--glow-rose)
        }
    }

    .a {
        opacity: 0;
        transform: translateY(8px)
    }

    .a.show {
        animation: rise .4s cubic-bezier(.4, 0, .2, 1) forwards
    }

    .a.d1 {
        animation-delay: .04s
    }

    .a.d2 {
        animation-delay: .1s
    }

    .a.d3 {
        animation-delay: .16s
    }

    .a.d4 {
        animation-delay: .22s
    }

    /* ===== VIEW MODE OVERRIDES ===== */
    html.force-mobile .topnav {
        display: block !important
    }

    html.force-mobile .bottomnav {
        display: block !important
    }

    html.force-mobile .app {
        padding-bottom: calc(var(--bottom-h) + var(--safe-b) + 4px)
    }

    /* Mobile width on desktop screens */
    @media(min-width:769px) {
        html.force-mobile body {
            max-width: 420px;
            margin: 0 auto;
            position: relative
        }

        html.force-mobile .topnav {
            display: block !important;
            position: relative !important;
            background: var(--paper);
            border-bottom: 1px solid var(--line)
        }

        html.force-mobile .topnav-in {
            max-width: 420px;
            margin: 0 auto
        }

        html.force-mobile .burger {
            display: flex !important
        }

        html.force-mobile .nav-links {
            display: none !important
        }

        html.force-mobile .nav-cta {
            display: none !important
        }

        html.force-mobile .bottomnav {
            position: fixed !important;
            left: 50% !important;
            transform: translateX(-50%);
            width: 420px;
            max-width: 100vw
        }

        html.force-mobile .view-toggle {
            bottom: calc(var(--bottom-h) + 12px);
            right: 0
        }

        html.force-mobile .hero-bg {
            border-radius: 0
        }

        html.force-mobile .hero h1 {
            font-size: 1.4rem
        }

        html.force-mobile .hero-brand img {
            width: 36px;
            height: 36px
        }

        html.force-mobile .hero-btns {
            flex-direction: column
        }

        html.force-mobile .hb {
            justify-content: center;
            width: 100%
        }

        html.force-mobile .hero-search {
            margin: 16px 16px 0
        }

        html.force-mobile .stats-bar {
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            padding: 12px 10px
        }

        html.force-mobile .svc-sec-head {
            display: none !important
        }

        html.force-mobile .svc-sec {
            display: none !important
        }

        html.force-mobile .svc-featured {
            display: none !important
        }

        html.force-mobile .svc-list {
            display: flex !important;
            flex-direction: column;
            gap: 0
        }

        html.force-mobile .portals {
            display: none !important
        }

        html.force-mobile .emergency-grid {
            grid-template-columns: 1fr !important
        }

        html.force-mobile .qgrid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important
        }

        html.force-mobile .qacc-row {
            display: none !important
        }

        html.force-mobile .fgrid {
            grid-template-columns: 1fr !important
        }

        html.force-mobile .f-brand {
            justify-content: center
        }

        html.force-mobile .f-desc {
            text-align: center
        }

        html.force-mobile .f-soc {
            justify-content: center
        }

        html.force-mobile .f-bottom {
            flex-direction: column;
            align-items: center;
            text-align: center
        }
    }

    /* Mobile overrides for narrow screens (no width constraint needed) */
    @media(max-width:768px) {
        html.force-mobile .topnav {
            display: none !important
        }
    }

    html.force-desktop .topnav {
        display: block !important
    }

    html.force-desktop .bottomnav {
        display: none !important
    }

    html.force-desktop .app {
        padding-bottom: 0
    }

    html.force-desktop .portals {
        display: grid !important
    }

    html.force-desktop .svc-sec-head {
        display: flex !important
    }

    html.force-desktop .svc-sec {
        display: grid !important
    }

    html.force-desktop .svc-featured {
        display: grid !important
    }

    html.force-desktop .svc-list {
        display: none !important
    }

    html.force-desktop .qgrid {
        display: grid !important
    }

    html.force-desktop .fgrid {
        grid-template-columns: 2fr 1fr 1fr 1fr !important
    }

    html.force-desktop .hero-bg {
        border-radius: var(--r-l)
    }

    html.force-desktop .hero h1 {
        font-size: clamp(1.5rem, 5vw, 2.2rem)
    }

    html.force-desktop .hero-btns {
        flex-direction: row
    }

    html.force-desktop .hb {
        width: auto
    }

    html.force-desktop .hero-search {
        margin: 20px 16px 0
    }

    html.force-desktop .stats-bar {
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        padding: 14px
    }

    html.force-desktop .svc-portal {
        display: none !important
    }

    html.force-desktop .svc-list-divider {
        display: none !important
    }

    html.force-desktop .svc-row {
        display: none !important
    }

    html.force-desktop .qacc-row {
        display: none !important
    }

    /* ===== RESPONSIVE — TABLET ===== */
    @media(max-width:900px) {
        .portals {
            grid-template-columns: 1fr
        }

        .svc-sec {
            grid-template-columns: repeat(2, 1fr)
        }

        .emergency-grid {
            grid-template-columns: repeat(2, 1fr)
        }

        .qgrid {
            grid-template-columns: repeat(2, 1fr)
        }

        .fgrid {
            grid-template-columns: 1fr 1fr
        }

        .cf-grid {
            grid-template-columns: repeat(2, 1fr)
        }

        .co-card {
            grid-template-columns: 1fr
        }

        .co-right {
            grid-template-columns: repeat(4, 1fr)
        }
    }

    /* ===== MOBILE SERVICES SHARED ===== */
    .svc-portal {
        display: block;
        text-decoration: none;
        color: inherit;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
        margin-bottom: 10px;
        transition: all .15s;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px)
    }

    .svc-portal:active {
        transform: scale(.98);
        opacity: .85
    }

    .svc-portal-accent {
        height: 3px;
        width: 100%
    }

    .svc-portal-accent.rose {
        background: linear-gradient(90deg, var(--rose), var(--rose-d))
    }

    .svc-portal-inner {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px
    }

    .svc-portal-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: 1rem;
        flex-shrink: 0
    }

    .svc-portal-icon.rose {
        background: var(--rose-l);
        color: var(--rose)
    }

    .svc-portal-body {
        flex: 1;
        min-width: 0
    }

    .svc-portal-body h4 {
        font-size: .84rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
        margin-bottom: 2px
    }

    .svc-portal-body p {
        font-size: .68rem;
        color: var(--ink-faint);
        line-height: 1.4
    }

    .svc-portal-arrow {
        color: var(--ink-faint);
        font-size: .6rem;
        flex-shrink: 0
    }

    .svc-list-divider {
        font-size: .6rem;
        font-weight: 700;
        color: var(--ink-faint);
        text-transform: uppercase;
        letter-spacing: .1em;
        padding: 18px 0 8px;
        border-bottom: 1px solid var(--line);
        margin-bottom: 0
    }

    .svc-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid var(--line);
        transition: all .15s;
        text-decoration: none;
        color: inherit
    }

    .svc-row:active {
        opacity: .7
    }

    .svc-row:last-child {
        border-bottom: none
    }

    .svc-row-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--r-s);
        display: grid;
        place-items: center;
        font-size: .8rem;
        flex-shrink: 0
    }

    .svc-row-icon.rose {
        background: var(--rose-l);
        color: var(--rose)
    }

    .svc-row-body {
        flex: 1;
        min-width: 0
    }

    .svc-row-body h4 {
        font-size: .82rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
        margin-bottom: 2px
    }

    .svc-row-body p {
        font-size: .68rem;
        color: var(--ink-faint);
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .svc-row-arrow {
        color: var(--ink-faint);
        font-size: .65rem;
        flex-shrink: 0;
        transition: transform .15s
    }

    .svc-row:active .svc-row-arrow {
        transform: translateX(3px);
        color: var(--rose)
    }

    /* Mobile services header panel */
    html.force-mobile .svc .sec-head,
    .svc-mobile-head {
        background: linear-gradient(135deg, var(--rose), var(--rose-d));
        margin: 0 -16px 16px;
        padding: 20px 16px;
        border-radius: 0 0 var(--r-l) var(--r-l)
    }

    html.force-mobile .svc .sec-head .sec-label,
    .svc-mobile-head .sec-label {
        color: rgba(255, 255, 255, .7);
        margin-bottom: 8px
    }

    html.force-mobile .svc .sec-head .sec-label::after,
    .svc-mobile-head .sec-label::after {
        background: rgba(255, 255, 255, .2)
    }

    html.force-mobile .svc .sec-head h2,
    .svc-mobile-head h2 {
        color: #fff;
        font-size: 1.1rem;
        margin-bottom: 4px
    }

    html.force-mobile .svc .sec-head p,
    .svc-mobile-head p {
        color: rgba(255, 255, 255, .7);
        font-size: .7rem
    }

    html.force-mobile .svc-filters {
        margin-bottom: 10px
    }

    html.force-mobile .fb {
        padding: 6px 12px;
        font-size: .62rem
    }

    html.force-mobile .scnt {
        font-size: .6rem;
        margin-bottom: 12px
    }

    /* ===== RESPONSIVE — MOBILE ===== */
    @media(max-width:768px) {
        :root {
            --nav-h: 46px
        }

        .topnav {
            display: block;
            position: fixed;
            background: var(--paper);
            border-bottom: 1px solid var(--line)
        }

        .topnav-in {
            padding: 0 12px
        }

        .brand-img {
            width: 28px;
            height: 28px
        }

        .brand-text strong {
            font-size: .78rem
        }

        .brand-text small {
            font-size: .5rem
        }

        .nav-links {
            display: none !important
        }

        .nav-cta {
            display: none !important
        }

        .burger {
            display: flex !important
        }

        .bottomnav {
            display: block
        }

        .app {
            padding-bottom: calc(var(--bottom-h) + var(--safe-b) + 4px)
        }

        /* Hero: full bleed, no radius */
        .hero-bg {
            border-radius: 0
        }

        .hero {
            margin-top: calc(var(--nav-h) * -1)
        }

        .hero-inner {
            padding: 60px 16px 20px
        }

        .hero-brand {
            margin-bottom: 8px
        }

        .hero-brand img {
            width: 36px;
            height: 36px
        }

        .hero h1 {
            font-size: 1.4rem
        }

        .hero-sub {
            font-size: .74rem
        }

        .hero-btns {
            flex-direction: column
        }

        .hb {
            justify-content: center;
            width: 100%;
            padding: 11px 16px
        }

        .hero-search {
            margin: 16px 16px 0
        }

        /* Stats */
        .stats {
            margin-top: -12px;
            padding: 0 16px 16px
        }

        .stats-bar {
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            padding: 12px 10px
        }

        .st-num {
            font-size: 1rem
        }

        .st-label {
            font-size: .48rem
        }

        /* Emergency mobile */
        .emergency-grid {
            grid-template-columns: 1fr
        }

        /* Leadership mobile */
        .leader-mayor-img {
            aspect-ratio: 16/5
        }

        .leader-mayor-name {
            font-size: 1rem
        }

        .leader-vice-card {
            flex-direction: column;
            text-align: center
        }

        .leader-mayor-photo-float {
            width: 70px;
            height: 70px;
            top: -35px;
            right: 16px
        }

        .leader-council-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px
        }

        /* Portals + secondary: HIDE desktop, SHOW mobile list */
        .portals {
            display: none
        }

        .svc-sec-head {
            display: none
        }

        .svc-sec {
            display: none
        }

        .svc-featured {
            display: none
        }

        .svc-list {
            display: flex;
            flex-direction: column;
            gap: 0
        }

        .svc-list .scnt {
            margin-bottom: 10px
        }

        .svc .sec-head {
            background: linear-gradient(135deg, var(--rose), var(--rose-d));
            margin: 0 -16px 16px;
            padding: 20px 16px;
            border-radius: 0 0 var(--r-l) var(--r-l)
        }

        .svc .sec-head .sec-label {
            color: rgba(255, 255, 255, .7);
            margin-bottom: 8px
        }

        .svc .sec-head .sec-label::after {
            background: rgba(255, 255, 255, .2)
        }

        .svc .sec-head h2 {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 4px
        }

        .svc .sec-head p {
            color: rgba(255, 255, 255, .7);
            font-size: .7rem
        }

        .svc-filters {
            margin-bottom: 10px
        }

        .fb {
            padding: 6px 12px;
            font-size: .62rem
        }

        .scnt {
            font-size: .6rem;
            margin-bottom: 12px
        }

        /* Quick links grid — mobile 2x3 */
        .qgrid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px
        }

        .svc-row-arrow {
            color: var(--ink-faint);
            font-size: .65rem;
            flex-shrink: 0;
            transition: transform .15s
        }

        .svc-row:active .svc-row-arrow {
            transform: translateX(3px);
            color: var(--rose)
        }

        .qacc-row {
            display: none
        }

        .qc {
            min-width: 0;
            padding: 12px
        }

        .qc-body small {
            display: block;
            margin-top: 2px
        }

        /* Quick Access section */
        .qacc {
            padding: 24px 0 32px
        }

        /* City Overview mobile */
        .city-overview {
            padding: 20px 0 8px
        }

        .co-card {
            padding: 20px 16px
        }

        .co-right {
            grid-template-columns: repeat(2, 1fr)
        }

        .co-stat {
            padding: 10px 8px
        }

        .co-stat-num {
            font-size: 1.1rem
        }

        .co-title {
            font-size: .95rem
        }

        .co-desc {
            font-size: .68rem
        }

        /* City Features mobile */
        .city-features {
            padding: 16px 0
        }

        .cf-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px
        }

        .cf-card {
            padding: 16px 12px 14px
        }

        .cf-icon {
            width: 44px;
            height: 44px;
            font-size: 1rem;
            margin-bottom: 8px
        }

        .cf-name {
            font-size: .75rem
        }

        .cf-desc {
            font-size: .6rem
        }

        /* City Motto mobile */
        .city-motto {
            padding: 20px 0
        }

        .cm-card {
            padding: 22px 16px
        }

        .cm-quote {
            font-size: .85rem
        }

        .cm-deco {
            width: 50px;
            height: 50px
        }

        /* Hero parallax mobile — smaller shapes */
        .hp-shape.hex {
            width: 35px;
            height: 35px
        }

        .hp-shape.diamond {
            width: 22px;
            height: 22px
        }

        .hp-shape.circle {
            width: 30px;
            height: 30px
        }

        .hp-shape.tri {
            border-left-width: 12px;
            border-right-width: 12px;
            border-bottom-width: 20px
        }

        /* Footer compact */
        .fgrid {
            grid-template-columns: 1fr;
            gap: 16px
        }

        .f-brand {
            justify-content: center
        }

        .f-desc {
            text-align: center
        }

        .f-soc {
            justify-content: center
        }

        .f-bottom {
            flex-direction: column;
            align-items: center;
            text-align: center
        }

        .hero-glow-ring {
            width: 200px;
            height: 200px
        }
    }

    /* Small mobile */
    @media(max-width:400px) {
        .stats-bar {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px
        }

        .svc-row-body p {
            font-size: .6rem
        }

        .cf-grid {
            grid-template-columns: 1fr
        }

        .co-right {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px
        }

        .co-stat-num {
            font-size: 1rem
        }

        .cm-quote {
            font-size: .78rem
        }
    }

    /* Touch */
    @media(hover:none) and (pointer:coarse) {
        .portal:hover {
            transform: none;
            box-shadow: none;
            border-color: var(--line)
        }

        .svc-sec-item:hover {
            transform: none;
            box-shadow: var(--shadow-sm);
            border-color: var(--line)
        }

        .portal:active {
            transform: scale(.98);
            box-shadow: var(--shadow-md)
        }

        .svc-sec-item:active {
            transform: scale(.97);
            border-color: var(--rose-l)
        }

        .qc:hover {
            transform: none;
            box-shadow: var(--shadow-sm);
            border-color: var(--line)
        }

        .qc:hover::before {
            opacity: 0
        }

        .co-card:hover {
            transform: none;
            box-shadow: var(--shadow-md)
        }

        .co-stat:hover {
            transform: none;
            box-shadow: none
        }

        .cf-card:hover {
            transform: none;
            box-shadow: var(--shadow-sm)
        }

        .cf-card:hover .cf-icon {
            transform: none
        }

        .cm-card:hover {
            transform: none;
            box-shadow: none
        }

        .svc-feat-card:hover {
            transform: none;
            box-shadow: var(--shadow-md)
        }
    }

    .qc:active {
        transform: scale(.98);
        border-color: var(--rose-l)
    }

    .emergency-card:hover {
        transform: none
    }

    .leader-council-card:hover {
        transform: none;
        box-shadow: var(--shadow-sm)
    }

    .leader-vice-card:hover {
        transform: none;
        box-shadow: var(--shadow-sm)
    }
    }
    </style>
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
                <li><a href="#services" data-nav="services">Services</a></li>
                <li><a href="/emergency-contacts.php">Emergency</a></li>
                <li><a href="/city-officials.php">Officials</a></li>
                <li><a href="#quick" data-nav="quick">Quick Access</a></li>
                <li><a href="https://koronadal.gov.ph/" target="_blank">LGU Website</a></li>
            </ul>
            <div class="topnav-r">
                <button class="theme-btn" id="themeBtn" aria-label="Toggle theme"><i class="fas fa-moon"></i></button>
                <a href="#services" class="nav-cta"><i class="fas fa-th-large" style="font-size:.5rem"></i> Browse</a>
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
            <a href="#services"><i class="fas fa-th-large"></i> Services</a>
            <a href="/emergency-contacts.php"><i class="fas fa-phone-alt"></i> Emergency</a>
            <a href="/city-officials.php"><i class="fas fa-users"></i> Officials</a>
            <a href="#quick"><i class="fas fa-link"></i> Quick Access</a>
            <a href="https://koronadal.gov.ph/" target="_blank"><i class="fas fa-globe"></i> LGU Website</a>
        </div>
        <div class="mnav-foot">
            <a href="#services"><i class="fas fa-arrow-pointer" style="font-size:.6rem"></i> Browse Services</a>
        </div>
    </div>

    <!-- APP CONTENT -->
    <div class="app">

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
                        style="width:5px;height:5px;background:rgba(74,108,247,.3);box-shadow:0 0 8px rgba(74,108,247,.2)">
                    </div>
                </div>
            </div>
            <div class="hero-inner">
                <h1>Maayung Adlaw<br /><span class="hl">Koronadale&ntilde;o!</span></h1>
                <p class="hero-sub">Access government services online.</p>
                <div class="hero-btns">
                    <a href="#services" class="hb hb-w"><i class="fas fa-arrow-pointer" style="font-size:.6rem"></i>
                        View Services</a>
                </div>
            </div>
        </section>

        <!-- CITY OVERVIEW — 3D Glass Card -->
        <section class="city-overview a d1">
            <div class="sec-pad">
                <div class="co-card">
                    <div class="co-left">
                        <div class="co-label"><i class="fas fa-location-dot"></i> South Cotabato, Philippines</div>
                        <h2 class="co-title">The Spring Garden City</h2>
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
                            <div class="co-stat-num">165K+</div>
                            <div class="co-stat-lbl">Population</div>
                        </div>
                        <div class="co-stat">
                            <div class="co-stat-num">268km²</div>
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
                                <div class="portal-icon rose"><i class="fas fa-car-crash"></i></div>

                            </div>
                            <h3>MTOP Checker</h3>
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

        <!-- CITY LEADERSHIP -->
        <section class="leadership" id="officials">
            <div class="sec-pad">
                <div class="sec-head a">
                    <div class="sec-label"><i class="fas fa-landmark"></i> City Mayor</div>
                    <h2>Building a Smarter Koronadal</h2>
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

                <!-- MAYOR — Featured Banner -->
                <div class="leader-mayor a d1">
                    <div class="leader-mayor-img">
                        <img src="Mayor_bg.png" alt="<?= htmlspecialchars($mayorData['name'] ?? 'City Mayor') ?>" />
                        <div class="leader-mayor-overlay"></div>
                    </div>
                    <div class="leader-mayor-body">
                        <div class="leader-mayor-photo-float">
                            <?php if (!empty($mayorData['image'])): ?>
                            <img src="<?= htmlspecialchars($mayorData['image']) ?>"
                                alt="<?= htmlspecialchars($mayorData['name'] ?? '') ?>" loading="lazy" />
                            <?php else: ?>
                            <i class="fas fa-user-tie"></i>
                            <?php endif; ?>
                        </div>
                        <div class="leader-mayor-badge"><i class="fas fa-star"></i> City Mayor</div>
                        <h2 class="leader-mayor-name">
                            <?= htmlspecialchars($mayorData['name'] ?? 'Hon. Erlinda "Bing" Pabi-Araquil') ?></h2>
                        <?php if (!empty($mayorData['ordinance'])): ?>
                        <p class="leader-mayor-desc"><?= htmlspecialchars($mayorData['ordinance']) ?></p>
                        <?php else: ?>
                        <p class="leader-mayor-desc">Leading Koronadal City's digital transformation — bringing
                            government services closer to every resident through technology, transparency, and
                            community-driven governance.</p>
                        <?php endif; ?>
                        <?php if (!empty($mayorData['committee'])): ?>
                        <div class="leader-mayor-tags">
                            <?php foreach (array_map('trim', explode(',', $mayorData['committee'])) as $tag): ?>
                            <span class="leader-tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="leader-mayor-quote">"Genuine Service for God and for the People... EPAdayon Ang
                            Kanami Sang Bagong Koronadal"</div>
                    </div>
                </div>

            </div>
        </section>

        <!-- QUICK ACCESS -->
        <section class="qacc" id="quick">
            <div class="sec-pad">
                <div class="sec-head a">
                    <div class="sec-label"><i class="fas fa-link"></i> Quick Links</div>
                    <h2>Explore Koronadal City</h2>
                </div>
            </div>
            <!-- Desktop grid -->
            <div class="sec-pad">
                <div class="qgrid">
                    <a href="https://koronadal.gov.ph/" target="_blank" class="qc a d1">
                        <div class="qc-ico"><i class="fas fa-home"></i></div>
                        <div class="qc-body"><strong>Official Website</strong><small>City homepage</small></div><i
                            class="fas fa-chevron-right qc-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/city-history/" target="_blank" class="qc a d1">
                        <div class="qc-ico"><i class="fas fa-landmark"></i></div>
                        <div class="qc-body"><strong>City History</strong><small>Heritage &amp; origins</small></div><i
                            class="fas fa-chevron-right qc-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/vision-mission/" target="_blank" class="qc a d2">
                        <div class="qc-ico"><i class="fas fa-bullseye"></i></div>
                        <div class="qc-body"><strong>Vision &amp; Mission</strong><small>Goals &amp; direction</small>
                        </div><i class="fas fa-chevron-right qc-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" class="qc a d2">
                        <div class="qc-ico"><i class="fas fa-map-pin"></i></div>
                        <div class="qc-body"><strong>27 Barangays</strong><small>Districts &amp; areas</small></div><i
                            class="fas fa-chevron-right qc-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/geography/" target="_blank" class="qc a d3">
                        <div class="qc-ico"><i class="fas fa-globe-asia"></i></div>
                        <div class="qc-body"><strong>Geography</strong><small>Location &amp; climate</small></div><i
                            class="fas fa-chevron-right qc-arrow"></i>
                    </a>
                    <a href="https://koronadal.gov.ph/awards/" target="_blank" class="qc a d3">
                        <div class="qc-ico"><i class="fas fa-trophy"></i></div>
                        <div class="qc-body"><strong>Awards</strong><small>Recognitions</small></div><i
                            class="fas fa-chevron-right qc-arrow"></i>
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
            <a href="#home" class="bn-item" data-section="home"><i class="fas fa-house"></i><span>Home</span></a>
            <a href="#services" class="bn-item" data-section="services">
                <i class="fas fa-gears"></i><span>Services</span>
            </a>
            <a href="#quick" class="bn-item" data-section="quick"><i class="fas fa-link"></i><span>Quick</span></a>
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
    var html = document.documentElement,
        themeBtn = document.getElementById('themeBtn');

    function setTheme(t) {
        html.setAttribute('data-theme', t);
        themeBtn.innerHTML = t === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        try {
            localStorage.setItem('kdc-theme', t)
        } catch (e) {}
    }
    (function() {
        var s = null;
        try {
            s = localStorage.getItem('kdc-theme')
        } catch (e) {}
        setTheme(s === 'dark' ? 'dark' : 'light')
    })();
    themeBtn.addEventListener('click', function() {
        setTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark')
    });

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
    var bnItems = document.querySelectorAll('.bn-item[data-section]');
    var sections = ['home', 'services', 'quick'];
    var sectionEls = sections.map(function(id) {
        return document.getElementById(id)
    }).filter(Boolean);
    var bnObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                var id = e.target.id;
                bnItems.forEach(function(b) {
                    b.classList.toggle('active', b.dataset.section === id);
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
    document.querySelectorAll('.bn-item[data-section]').forEach(function(b) {
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