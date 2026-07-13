<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Koronadal City — Online Services Portal</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root{
      --rose:#e11d48;--rose-d:#be123c;--rose-l:#fff1f2;--rose-xl:#fdf2f4;
      --dark:#09090b;--dark2:#18181b;--dark3:#27272a;--dark4:#3f3f46;
      --zinc:#71717a;--zinc-l:#a1a1aa;--zinc-xl:#d4d4d8;--zinc-xxl:#e4e4e7;
      --slate:#f4f4f5;--white:#ffffff;
      --sh-xs:0 1px 2px rgba(0,0,0,.04);
      --sh-s:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
      --sh:0 4px 6px -1px rgba(0,0,0,.07),0 2px 4px -2px rgba(0,0,0,.05);
      --sh-m:0 10px 15px -3px rgba(0,0,0,.08),0 4px 6px -4px rgba(0,0,0,.04);
      --sh-l:0 20px 25px -5px rgba(0,0,0,.08),0 8px 10px -6px rgba(0,0,0,.04);
      --sh-xl:0 25px 50px -12px rgba(0,0,0,.18);
      --sh-glow:0 0 40px rgba(225,29,72,.12);
      --r-xs:6px;--r-s:10px;--r:14px;--r-l:20px;--r-xl:28px;--r-full:9999px;
    }

    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
    html{scroll-behavior:smooth;scroll-padding-top:72px}
    body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--white);color:var(--dark);-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;line-height:1.6;font-size:15px}
    ::selection{background:var(--rose);color:#fff}
    a{text-decoration:none;color:inherit}
    img{display:block;max-width:100%}
    button{font-family:inherit;cursor:pointer}
    .wrap{max-width:1200px;margin:0 auto;padding:0 24px}

    /* ===== NAV ===== */
    .nav{position:fixed;top:0;left:0;right:0;z-index:100;height:72px;transition:all .4s cubic-bezier(.4,0,.2,1)}
    .nav.at-top{background:transparent}
    .nav.scrolled{background:rgba(255,255,255,.92);backdrop-filter:blur(24px) saturate(180%);-webkit-backdrop-filter:blur(24px) saturate(180%);box-shadow:0 1px 0 rgba(0,0,0,.04)}
    .nav-in{display:flex;align-items:center;justify-content:space-between;height:100%}
    .brand{display:flex;align-items:center;gap:11px}
    .brand-img{width:34px;height:34px;border-radius:var(--r-s);object-fit:contain;transition:background .4s;padding:2px}
    .nav.at-top .brand-img{background:rgba(255,255,255,.15)}
    .nav.scrolled .brand-img{background:var(--slate)}
    .brand-text{display:flex;flex-direction:column}
    .brand-text strong{font-size:.85rem;font-weight:800;line-height:1.2;transition:color .4s}
    .nav.at-top .brand-text strong{color:#fff}
    .nav.scrolled .brand-text strong{color:var(--dark)}
    .brand-text small{font-size:.58rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;transition:color .4s}
    .nav.at-top .brand-text small{color:rgba(255,255,255,.7)}
    .nav.scrolled .brand-text small{color:var(--rose)}
    .nav-center{display:flex;gap:2px;list-style:none}
    .nav-center a{font-size:.8rem;font-weight:500;padding:7px 14px;border-radius:var(--r-s);transition:all .2s}
    .nav.at-top .nav-center a{color:rgba(255,255,255,.65)}
    .nav.at-top .nav-center a:hover{color:#fff;background:rgba(255,255,255,.1)}
    .nav.at-top .nav-center a.on{color:#fff;background:rgba(255,255,255,.12)}
    .nav.scrolled .nav-center a{color:var(--zinc)}
    .nav.scrolled .nav-center a:hover{color:var(--dark);background:var(--slate)}
    .nav.scrolled .nav-center a.on{color:var(--rose);background:var(--rose-l)}
    .nav-right{display:flex;align-items:center;gap:10px}
    .btn{display:inline-flex;align-items:center;gap:7px;font-size:.8rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;border-radius:var(--r-s);font-family:inherit}
    .btn-rose{padding:9px 20px}
    .nav.at-top .btn-rose{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.2)}
    .nav.at-top .btn-rose:hover{background:rgba(255,255,255,.2)}
    .nav.scrolled .btn-rose{background:var(--rose);color:#fff}
    .nav.scrolled .btn-rose:hover{background:var(--rose-d);box-shadow:var(--sh-glow);transform:translateY(-1px)}
    .burger{display:none;border:none;border-radius:var(--r-s);width:38px;height:38px;align-items:center;justify-content:center;font-size:.9rem;transition:all .2s}
    .nav.at-top .burger{background:rgba(255,255,255,.1);color:#fff}
    .nav.scrolled .burger{background:var(--slate);color:var(--dark)}

    /* ===== MOBILE NAV ===== */
    .mnav{display:none;position:fixed;inset:0;z-index:200;background:var(--white);flex-direction:column;justify-content:center;align-items:center;gap:1.6rem;opacity:0;pointer-events:none;transition:opacity .3s cubic-bezier(.4,0,.2,1)}
    .mnav.on{opacity:1;pointer-events:auto}
    .mnav a{font-size:1.2rem;font-weight:700;color:var(--dark);transition:all .4s cubic-bezier(.4,0,.2,1);transform:translateY(12px);opacity:0;filter:blur(4px)}
    .mnav.on a{transform:translateY(0);opacity:1;filter:blur(0)}
    .mnav.on a:nth-child(2){transition-delay:.06s}
    .mnav.on a:nth-child(3){transition-delay:.12s}
    .mnav.on a:nth-child(4){transition-delay:.18s}
    .mnav.on a:nth-child(5){transition-delay:.24s}
    .mnav a:hover{color:var(--rose)}
    .mnav-x{position:absolute;top:16px;right:20px;background:var(--slate);border:none;width:40px;height:40px;border-radius:var(--r-s);display:flex;align-items:center;justify-content:center;font-size:1rem;color:var(--dark);transition:transform .3s}
    .mnav.on .mnav-x{transform:rotate(90deg)}

    /* ===== HERO ===== */
    .hero{padding:120px 0 0;position:relative;overflow:hidden}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(160deg,var(--rose) 0%,var(--rose-d) 55%,#881337 100%);z-index:0}
    .hero-bg::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 75% 20%,rgba(255,255,255,.1),transparent 50%),radial-gradient(circle at 20% 80%,rgba(255,255,255,.06),transparent 40%)}
    .hero-bg::after{content:'';position:absolute;top:-50%;right:-20%;width:80%;height:200%;background:radial-gradient(circle,rgba(255,255,255,.04) 0%,transparent 60%);pointer-events:none}
    .hero-grid{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:end;padding-bottom:72px}
    .hero-left{padding-bottom:24px}
    .hero-pill{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.18);padding:5px 14px;border-radius:var(--r-full);font-size:.66rem;font-weight:600;color:rgba(255,255,255,.9);letter-spacing:.05em;text-transform:uppercase;margin-bottom:20px}
    .hero-pill i{font-size:.5rem;opacity:.7}
    .hero h1{font-size:clamp(2.2rem,4.5vw,3.4rem);font-weight:900;color:#fff;line-height:1.05;letter-spacing:-.04em;margin-bottom:14px}
    .hero h1 .hl{position:relative;display:inline}
    .hero h1 .hl::after{content:'';position:absolute;left:-4px;right:-4px;bottom:4px;height:10px;background:rgba(255,255,255,.18);border-radius:4px;z-index:-1}
    .hero-sub{font-size:.92rem;color:rgba(255,255,255,.75);max-width:400px;line-height:1.7;margin-bottom:28px}
    .hero-btns{display:flex;gap:10px;flex-wrap:wrap}
    .hb{display:inline-flex;align-items:center;gap:7px;padding:11px 22px;border-radius:var(--r-s);font-size:.82rem;font-weight:600;transition:all .2s;border:none;cursor:pointer;font-family:inherit}
    .hb-w{background:#fff;color:var(--rose)}
    .hb-w:hover{box-shadow:var(--sh-xl);transform:translateY(-2px)}
    .hb-o{background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2)}
    .hb-o:hover{background:rgba(255,255,255,.18)}

    /* hero card */
    .hero-card{background:var(--white);border-radius:var(--r-xl);box-shadow:var(--sh-xl);overflow:hidden}
    .hc-top{padding:24px 24px 0}
    .hc-top h3{font-size:1rem;font-weight:800;color:var(--dark);margin-bottom:4px}
    .hc-top p{font-size:.78rem;color:var(--zinc);margin-bottom:16px}
    .hc-search{display:flex;border:1.5px solid var(--zinc-xxl);border-radius:var(--r);overflow:hidden;transition:all .2s}
    .hc-search:focus-within{border-color:var(--rose);box-shadow:0 0 0 3px rgba(225,29,72,.08)}
    .hc-search input{flex:1;border:none;outline:none;padding:13px 16px;font-size:.85rem;font-family:inherit;color:var(--dark);min-width:0}
    .hc-search input::placeholder{color:var(--zinc-l)}
    .hc-search button{background:var(--rose);color:#fff;border:none;padding:0 18px;font-size:.85rem;transition:background .15s}
    .hc-search button:hover{background:var(--rose-d)}
    .hc-tags{display:flex;flex-wrap:wrap;gap:6px;padding:16px 24px 20px}
    .hc-tags span{font-size:.68rem;color:var(--zinc-l);font-weight:500}
    .ht{font-size:.68rem;font-weight:500;color:var(--zinc);padding:4px 10px;border-radius:var(--r-xs);background:var(--slate);cursor:pointer;transition:all .15s}
    .ht:hover{background:var(--rose-l);color:var(--rose)}

    /* hero stat bar */
    .hero-stats{position:relative;z-index:2;margin-top:-36px;padding-bottom:40px}
    .hs-bar{background:var(--white);border:1px solid var(--zinc-xxl);border-radius:var(--r-l);padding:20px 32px;display:grid;grid-template-columns:repeat(4,1fr);gap:16px;box-shadow:var(--sh-m)}
    .hs-item{text-align:center;position:relative}
    .hs-item:not(:last-child)::after{content:'';position:absolute;right:0;top:50%;transform:translateY(-50%);width:1px;height:28px;background:var(--zinc-xxl)}
    .hs-num{font-size:1.6rem;font-weight:900;color:var(--rose);line-height:1;letter-spacing:-.02em}
    .hs-label{font-size:.7rem;color:var(--zinc);font-weight:500;margin-top:2px}

    /* ===== QUICK NAV ===== */
    .qnav{padding:14px 0;background:var(--white);border-bottom:1px solid var(--zinc-xxl)}
    .qnav-row{display:flex;align-items:center;gap:8px;overflow-x:auto;scrollbar-width:none}
    .qnav-row::-webkit-scrollbar{display:none}
    .qnav-lbl{font-size:.62rem;font-weight:700;color:var(--zinc-l);text-transform:uppercase;letter-spacing:.1em;white-space:nowrap}
    .qpill{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:var(--r-xs);font-size:.72rem;font-weight:500;color:var(--dark4);background:var(--slate);white-space:nowrap;transition:all .15s}
    .qpill:hover{background:var(--rose-l);color:var(--rose)}
    .qpill i{font-size:.6rem}

    /* ===== SERVICES ===== */
    .services{padding:72px 0;background:var(--white)}
    .sec-head{text-align:center;margin-bottom:40px}
    .sec-label{display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;color:var(--rose);text-transform:uppercase;letter-spacing:.14em;margin-bottom:8px}
    .sec-label i{font-size:.5rem}
    .sec-head h2{font-size:1.9rem;font-weight:900;letter-spacing:-.03em;color:var(--dark);margin-bottom:8px}
    .sec-head p{font-size:.88rem;color:var(--zinc);max-width:420px;margin:0 auto}

    .filter-row{display:flex;justify-content:center;gap:5px;margin-bottom:12px;flex-wrap:wrap}
    .fb{padding:6px 14px;border-radius:var(--r-full);font-size:.72rem;font-weight:600;border:1.5px solid var(--zinc-xxl);background:var(--white);color:var(--zinc);transition:all .15s}
    .fb:hover{border-color:var(--rose-l);color:var(--rose)}
    .fb.on{background:var(--rose);color:#fff;border-color:var(--rose)}
    .scnt{text-align:center;font-size:.72rem;color:var(--zinc-l);margin-bottom:24px;font-weight:500}
    .scnt b{color:var(--rose);font-weight:700}

    .sgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}

    .sc{background:var(--white);border:1.5px solid var(--zinc-xxl);border-radius:var(--r);padding:22px;display:flex;flex-direction:column;transition:all .25s;position:relative;overflow:hidden}
    .sc::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--rose);transform:scaleX(0);transition:transform .25s;transform-origin:left}
    .sc:hover{border-color:var(--rose-l);box-shadow:var(--sh-glow);transform:translateY(-2px)}
    .sc:hover::before{transform:scaleX(1)}
    .sc-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px}
    .sc-ico{width:40px;height:40px;border-radius:var(--r-s);display:flex;align-items:center;justify-content:center;font-size:.9rem;background:var(--rose-l);color:var(--rose);transition:all .25s}
    .sc:hover .sc-ico{background:var(--rose);color:#fff}
    .sc-badge{display:inline-flex;align-items:center;gap:4px;font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#16a34a;background:#f0fdf4;padding:3px 8px;border-radius:var(--r-full)}
    .sc-badge i{font-size:.35rem}
    .sc h3{font-size:.88rem;font-weight:700;color:var(--dark);margin-bottom:5px;line-height:1.3}
    .sc p{font-size:.78rem;color:var(--zinc);line-height:1.6;flex:1;margin-bottom:14px}
    .sc-a{display:inline-flex;align-items:center;gap:4px;font-size:.74rem;font-weight:600;color:var(--rose);transition:gap .2s}
    .sc-a:hover{gap:7px}
    .sc-a i{font-size:.55rem}

    /* featured */
    .sc.feat{grid-column:span 2;grid-row:span 2;background:linear-gradient(135deg,var(--rose) 0%,var(--rose-d) 60%,#881337 100%);color:#fff;border:none;padding:32px;justify-content:space-between}
    .sc.feat::before{display:none}
    .sc.feat .sc-ico{background:rgba(255,255,255,.12);color:#fff}
    .sc.feat .sc-badge{background:rgba(255,255,255,.12);color:#fff}
    .sc.feat h3{font-size:1.5rem;font-weight:900;line-height:1.1;margin-bottom:10px;letter-spacing:-.01em}
    .sc.feat p{font-size:.88rem;color:rgba(255,255,255,.78)}
    .sc.feat .sc-a{color:#fff;font-size:.82rem}
    .sc.feat:hover{box-shadow:0 24px 60px rgba(225,29,72,.3)}
    .feat-top{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.4);margin-bottom:8px}
    .feat-pills{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap}
    .feat-pill{display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r-s);background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);font-size:.72rem;font-weight:500;color:rgba(255,255,255,.7)}
    .feat-pill i{font-size:.65rem;color:rgba(255,255,255,.4)}

    /* ===== MAYOR ===== */
    .mayor{padding:80px 0;background:var(--slate)}
    .mayor-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
    .mayor-visual{position:relative}
    .mayor-img-wrap{border-radius:var(--r-xl);overflow:hidden;box-shadow:var(--sh-l)}
    .mayor-img-wrap img{width:100%;aspect-ratio:4/3;object-fit:cover;object-position:top}
    .mayor-float{position:absolute;bottom:-20px;right:-20px;background:var(--white);border-radius:var(--r);padding:14px 18px;box-shadow:var(--sh-l);display:flex;align-items:center;gap:10px}
    .mayor-float img{width:42px;height:42px;border-radius:var(--r-s);object-fit:cover}
    .mayor-float-text strong{display:block;font-size:.72rem;font-weight:800;color:var(--dark)}
    .mayor-float-text small{font-size:.62rem;color:var(--zinc);font-weight:500}
    .mayor-content .mtag{font-size:.64rem;font-weight:700;color:var(--rose);text-transform:uppercase;letter-spacing:.14em;margin-bottom:8px}
    .mayor-content h2{font-size:1.9rem;font-weight:900;color:var(--dark);line-height:1.1;letter-spacing:-.02em;margin-bottom:14px}
    .mayor-content h2 em{font-style:normal;color:var(--rose)}
    .mayor-content .mdesc{font-size:.85rem;color:var(--zinc);line-height:1.75;margin-bottom:20px}
    .mayor-quote{padding:16px 20px;background:var(--rose-l);border-left:3px solid var(--rose);border-radius:0 var(--r-s) var(--r-s) 0;font-size:.8rem;color:var(--dark3);font-style:italic;line-height:1.7}

    /* ===== CULTURE ===== */
    .culture{padding:36px 0;background:var(--dark);position:relative;overflow:hidden}
    .culture::before{content:'';position:absolute;inset:0;opacity:.06;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48'%3E%3Cpath d='M24 0 L48 24 L24 48 L0 24Z' fill='none' stroke='%23e11d48' stroke-width='1.2'/%3E%3Cpath d='M24 12 L36 24 L24 36 L12 24Z' fill='none' stroke='%23e11d48' stroke-width='.6'/%3E%3C/svg%3E");background-size:48px 48px}
    .culture-in{position:relative;z-index:2;display:flex;align-items:center;justify-content:center;gap:16px;text-align:center}
    .culture-dot{width:6px;height:6px;border-radius:50%;background:var(--rose);flex-shrink:0}
    .culture-txt h3{font-size:.88rem;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:1px}
    .culture-txt p{font-size:.74rem;color:rgba(255,255,255,.3)}

    /* ===== QUICK ACCESS ===== */
    .quick{padding:72px 0;background:var(--white)}
    .qgrid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px}
    .qc{background:var(--white);border:1.5px solid var(--zinc-xxl);border-radius:var(--r);padding:24px 12px;text-align:center;transition:all .25s;position:relative;overflow:hidden}
    .qc::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--rose);transform:scaleX(0);transition:transform .25s;transform-origin:left}
    .qc:hover{border-color:var(--rose-l);box-shadow:var(--sh-glow);transform:translateY(-2px)}
    .qc:hover::before{transform:scaleX(1)}
    .qc-ico{width:44px;height:44px;border-radius:var(--r-s);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:.9rem;background:var(--rose-l);color:var(--rose);transition:all .25s}
    .qc:hover .qc-ico{background:var(--rose);color:#fff}
    .qc span{font-size:.75rem;font-weight:600;color:var(--dark)}

    /* ===== FOOTER ===== */
    .footer{background:var(--dark);color:rgba(255,255,255,.4);padding:52px 0 20px}
    .fgrid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:28px;margin-bottom:28px}
    .f-brand{display:flex;align-items:center;gap:9px;margin-bottom:8px}
    .f-brand img{width:28px;height:28px;border-radius:var(--r-xs)}
    .f-brand strong{font-size:.85rem;font-weight:800;color:#fff}
    .f-desc{font-size:.75rem;line-height:1.8;margin-bottom:12px;color:rgba(255,255,255,.28)}
    .f-soc{display:flex;gap:7px}
    .f-soc a{width:30px;height:30px;border-radius:var(--r-xs);display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.05);color:rgba(255,255,255,.3);font-size:.72rem;transition:all .2s}
    .f-soc a:hover{background:var(--rose);color:#fff}
    .fcol-t{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.3);margin-bottom:10px}
    .fcol ul{list-style:none}
    .fcol li{margin-bottom:6px}
    .fcol a{font-size:.76rem;color:rgba(255,255,255,.28);transition:color .15s}
    .fcol a:hover{color:#fff}
    .f-bottom{border-top:1px solid rgba(255,255,255,.05);padding-top:16px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:6px}
    .f-copy{font-size:.68rem;color:rgba(255,255,255,.15)}
    .f-motto{font-size:.7rem;color:rgba(255,255,255,.08);font-style:italic}

    /* ===== DIVIDER ===== */
    .tn{height:4px;width:100%;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='4'%3E%3Cpath d='M0 2 L2.5 0 L5 2 L7.5 0 L10 2 L12.5 0 L15 2 L17.5 0 L20 2' fill='none' stroke='%23e11d48' stroke-width='.8'/%3E%3Cpath d='M0 2 L2.5 4 L5 2 L7.5 4 L10 2 L12.5 4 L15 2 L17.5 4 L20 2' fill='none' stroke='%23fda4af' stroke-width='.4' opacity='.5'/%3E%3C/svg%3E");background-size:20px 4px;background-repeat:repeat-x}

    /* ===== ANIMATIONS ===== */
    .a{opacity:0;transform:translateY(16px);transition:opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1)}
    .a.show{opacity:1;transform:translateY(0)}

    /* ===== RESPONSIVE ===== */
    @media(max-width:1024px){
      .hero-grid{grid-template-columns:1fr;text-align:center;padding-bottom:56px}
      .hero-sub{margin:0 auto 28px}
      .hero-btns{justify-content:center}
      .hero-left{display:flex;flex-direction:column;align-items:center}
      .sgrid{grid-template-columns:repeat(2,1fr)}
      .sc.feat{grid-column:span 2;grid-row:span 1}
      .mayor-grid{grid-template-columns:1fr;gap:32px}
      .fgrid{grid-template-columns:1fr 1fr}
    }
    @media(max-width:768px){
      .nav-center,.nav-right .btn{display:none}
      .burger{display:flex}
      .nav.at-top .burger{background:rgba(255,255,255,.1);color:#fff}
      .nav.scrolled .burger{background:var(--slate);color:var(--dark)}
      .hero{padding:120px 0 40px}
      .hero h1{font-size:1.8rem}
      .hs-bar{grid-template-columns:repeat(2,1fr);gap:12px;padding:16px 24px}
      .hs-item:not(:last-child)::after{display:none}
      .sgrid{grid-template-columns:1fr}
      .sc.feat{grid-column:span 1;grid-row:span 1}
      .sc.feat h3{font-size:1.15rem}
      .qgrid{grid-template-columns:repeat(3,1fr)}
      .fgrid{grid-template-columns:1fr}
      .sec-head h2{font-size:1.4rem}
      .culture-in{flex-direction:column;gap:8px}
      .mayor-float{bottom:12px;right:12px}
    }
    @media(max-width:480px){
      .qgrid{grid-template-columns:repeat(2,1fr)}
      .hero-btns{flex-direction:column;align-items:center}
      .hb{width:100%;justify-content:center}
      .filter-row{gap:3px}
      .fb{padding:5px 10px;font-size:.68rem}
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav class="nav" id="nav">
  <div class="wrap">
    <div class="nav-in">
      <a class="brand" href="#">
        <img src="Logo.png" alt="Koronadal City" class="brand-img" />
        <div class="brand-text"><strong>Koronadal City</strong><small>Online Services</small></div>
      </a>
      <ul class="nav-center">
        <li><a href="#" class="on">Home</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#quick">Quick Access</a></li>
        <li><a href="https://koronadal.gov.ph/" target="_blank">LGU Website</a></li>
      </ul>
      <div class="nav-right">
        <a href="#services" class="btn btn-rose"><i class="fas fa-grid-2" style="font-size:.55rem"></i> Browse Services</a>
        <button class="burger" onclick="toggleNav()"><i class="fas fa-bars"></i></button>
      </div>
    </div>
  </div>
</nav>

<!-- MOBILE NAV -->
<div class="mnav" id="mnav">
  <button class="mnav-x" onclick="toggleNav()"><i class="fas fa-times"></i></button>
  <a href="#" onclick="toggleNav()">Home</a>
  <a href="#services" onclick="toggleNav()">Services</a>
  <a href="#quick" onclick="toggleNav()">Quick Access</a>
  <a href="https://koronadal.gov.ph/" target="_blank" onclick="toggleNav()">LGU Website</a>
</div>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="wrap">
    <div class="hero-grid">
      <div class="hero-left">
        <div class="hero-pill"><i class="fas fa-shield-halved"></i> Official Government Portal</div>
        <h1>All of Koronadal's<br/>Services. <span class="hl">One Place.</span></h1>
        <p class="hero-sub">Apply for permits, check violations, find jobs, and access every city government service — all online, anytime.</p>
        <div class="hero-btns">
          <a href="#services" class="hb hb-w"><i class="fas fa-arrow-pointer" style="font-size:.7rem"></i> Explore Services</a>
          <a href="https://koronadal.gov.ph/" target="_blank" class="hb hb-o"><i class="fas fa-globe" style="font-size:.7rem"></i> Visit LGU Website</a>
        </div>
      </div>
      <div style="display:flex;justify-content:flex-end;align-items:end">
        <div class="hero-card">
          <div class="hc-top">
            <h3>How can we help you?</h3>
            <p>Find the service you need in seconds</p>
            <div class="hc-search">
              <input type="text" placeholder="Type a service name..." id="heroSearch" />
              <button><i class="fas fa-arrow-right" style="font-size:.8rem"></i></button>
            </div>
          </div>
          <div class="hc-tags">
            <span>Popular:</span>
            <a href="https://koronadalcityportal.com/v2/login" target="_blank" class="ht">Business Permit</a>
            <a href="https://traffic.koronadalcityonlineservices.com/" target="_blank" class="ht">Traffic Violation</a>
            <a href="https://koronadal.gov.ph/job-vacancy/" target="_blank" class="ht">Job Openings</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="hero-stats">
  <div class="wrap">
    <div class="hs-bar">
      <div class="hs-item"><div class="hs-num">27</div><div class="hs-label">Barangays Connected</div></div>
      <div class="hs-item"><div class="hs-num">12+</div><div class="hs-label">Online Services</div></div>
      <div class="hs-item"><div class="hs-num">24/7</div><div class="hs-label">Always Accessible</div></div>
      <div class="hs-item"><div class="hs-num">100%</div><div class="hs-label">Transparency</div></div>
    </div>
  </div>
</div>

<!-- QUICK NAV -->
<div class="qnav">
  <div class="wrap">
    <div class="qnav-row">
      <span class="qnav-lbl">Quick Links</span>
      <a href="https://koronadal.gov.ph/" target="_blank" class="qpill"><i class="fas fa-home"></i> Homepage</a>
      <a href="https://koronadal.gov.ph/city-history/" target="_blank" class="qpill"><i class="fas fa-landmark"></i> City History</a>
      <a href="https://koronadal.gov.ph/vision-mission/" target="_blank" class="qpill"><i class="fas fa-bullseye"></i> Vision & Mission</a>
      <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" class="qpill"><i class="fas fa-map-pin"></i> 27 Barangays</a>
      <a href="https://koronadal.gov.ph/geography/" target="_blank" class="qpill"><i class="fas fa-globe-asia"></i> Geography</a>
      <a href="https://koronadal.gov.ph/awards/" target="_blank" class="qpill"><i class="fas fa-trophy"></i> Awards</a>
    </div>
  </div>
</div>
<div class="tn"></div>

<!-- SERVICES -->
<section class="services" id="services">
  <div class="wrap">
    <div class="sec-head a">
      <div class="sec-label"><i class="fas fa-bolt"></i> Online Services</div>
      <h2>What Would You Like to Do?</h2>
      <p>Select a service below. Everything is available online — no need to visit City Hall.</p>
    </div>
    <div class="filter-row a">
      <button class="fb on" data-f="all">All Services</button>
      <button class="fb" data-f="permits">Permits</button>
      <button class="fb" data-f="info">Information</button>
      <button class="fb" data-f="jobs">Jobs & Bids</button>
      <button class="fb" data-f="safety">Safety & Health</button>
      <button class="fb" data-f="trans">Transparency</button>
    </div>
    <div class="scnt">Showing <b id="cnt">12</b> services</div>

    <div class="sgrid">
      <!-- FEATURED -->
      <div class="sc feat a" data-c="permits">
        <div>
          <div class="sc-top">
            <div class="sc-ico"><i class="fas fa-file-contract"></i></div>
            <div class="sc-badge"><i class="fas fa-circle"></i> Online</div>
          </div>
          <div class="feat-top">Most Accessed</div>
          <h3>Business Permits &<br/>Licensing</h3>
          <p>Apply for new permits, renew existing ones, and complete your compliance requirements — all online. Skip the line at City Hall.</p>
          <a href="https://koronadalcityportal.com/v2/login" target="_blank" class="sc-a">Start Application <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="feat-pills">
          <div class="feat-pill"><i class="fas fa-user-plus"></i> New Application</div>
          <div class="feat-pill"><i class="fas fa-sync"></i> Renewal</div>
          <div class="feat-pill"><i class="fas fa-credit-card"></i> Online Payment</div>
        </div>
      </div>

      <div class="sc a" data-c="safety">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-car-crash"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Traffic Violation Check</h3>
        <p>Look up and settle traffic violations quickly online.</p>
        <a href="https://traffic.koronadalcityonlineservices.com/" target="_blank" class="sc-a">Check Now <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="info">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-book-open"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Citizen's Charter</h3>
        <p>Step-by-step guides, requirements, and timelines for every service.</p>
        <a href="https://koronadal.gov.ph/citizens-charter/" target="_blank" class="sc-a">View Guide <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="jobs">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-briefcase"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Job Openings</h3>
        <p>Browse current vacancies and apply directly online.</p>
        <a href="https://koronadal.gov.ph/job-vacancy/" target="_blank" class="sc-a">View Jobs <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="jobs">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-gavel"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Bids & Awards</h3>
        <p>Open procurement opportunities and bid invitations.</p>
        <a href="https://koronadal.gov.ph/bids-and-awards/" target="_blank" class="sc-a">View Bids <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="trans">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-receipt"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Financial Reports</h3>
        <p>Access public budget allocations and transparency reports.</p>
        <a href="https://koronadal.gov.ph/full-disclosure/" target="_blank" class="sc-a">View Reports <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="safety">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-phone-alt"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Emergency Contacts</h3>
        <p>Quick access to police, fire, and hospital hotlines.</p>
        <a href="https://koronadal.gov.ph/emergency-hotlines/" target="_blank" class="sc-a">View Hotlines <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="info">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-money-bill-wave"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Fare Rates</h3>
        <p>Official fare matrix for public transport routes.</p>
        <a href="https://koronadal.gov.ph/fare-matrix/" target="_blank" class="sc-a">See Rates <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="safety">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-heartbeat"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Health Services</h3>
        <p>Find hospitals, health centers, and healthcare programs.</p>
        <a href="https://koronadal.gov.ph/culture-copy/" target="_blank" class="sc-a">Find Facilities <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="trans">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-scale-balanced"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>City Ordinances</h3>
        <p>Browse local laws and resolutions from the City Council.</p>
        <a href="https://koronadal.gov.ph/ordinance/" target="_blank" class="sc-a">Browse Laws <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="info">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-palette"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Tourism & Culture</h3>
        <p>Explore attractions, heritage, and upcoming events.</p>
        <a href="https://koronadal.gov.ph/culture/" target="_blank" class="sc-a">Explore <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="sc a" data-c="info">
        <div class="sc-top"><div class="sc-ico"><i class="fas fa-address-book"></i></div><div class="sc-badge"><i class="fas fa-circle"></i> Online</div></div>
        <h3>Officials Directory</h3>
        <p>Contact info for elected officials and departments.</p>
        <a href="https://koronadal.gov.ph/city-officials/" target="_blank" class="sc-a">View Directory <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>
<div class="tn"></div>

<!-- MAYOR -->
<section class="mayor">
  <div class="wrap">
    <div class="mayor-grid">
      <div class="mayor-visual a">
        <div class="mayor-img-wrap">
          <img src="Mayor_bg.png" alt="Mayor Erlinda Pabi-Araquil" />
        </div>
        <div class="mayor-float">
          <div class="mayor-float-text">
            <strong>Hon. Erlinda "Bing" Pabi-Araquil</strong>
            <small>City Mayor</small>
          </div>
        </div>
      </div>
      <div class="mayor-content a">
        <div class="mtag">City Leadership</div>
        <h2>Building a Smarter<br/>Koronadal <em>Together</em></h2>
        <p class="mdesc">
          Mayor Erlinda "Bing" Pabi-Araquil leads Koronadal City's digital transformation —
          bringing government services closer to every resident through technology,
          transparency, and community-driven governance.
        </p>
        <div class="mayor-quote">
          "Genuine Service for God and for the People... EPAdayon Ang Kanami Sang Bagong Koronadal"
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CULTURE -->
<div class="culture">
  <div class="wrap">
    <div class="culture-in">
      <div class="culture-dot"></div>
      <div class="culture-txt">
        <h3>Inspired by T'nalak — The Heritage Weave of South Cotabato</h3>
        <p>Rooted in tradition, driven by progress</p>
      </div>
      <div class="culture-dot"></div>
    </div>
  </div>
</div>

<!-- QUICK ACCESS -->
<section class="quick" id="quick">
  <div class="wrap">
    <div class="sec-head a">
      <div class="sec-label"><i class="fas fa-link"></i> Quick Links</div>
      <h2>Explore Koronadal City</h2>
      <p>Learn more about our city — history, geography, and more.</p>
    </div>
    <div class="qgrid">
      <a href="https://koronadal.gov.ph/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-home"></i></div><span>City Homepage</span></a>
      <a href="https://koronadal.gov.ph/city-history/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-landmark"></i></div><span>History</span></a>
      <a href="https://koronadal.gov.ph/vision-mission/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-bullseye"></i></div><span>Vision & Mission</span></a>
      <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-map-pin"></i></div><span>Barangays</span></a>
      <a href="https://koronadal.gov.ph/geography/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-globe-asia"></i></div><span>Geography</span></a>
      <a href="https://koronadal.gov.ph/awards/" target="_blank" class="qc a"><div class="qc-ico"><i class="fas fa-trophy"></i></div><span>Awards</span></a>
    </div>
  </div>
</section>
<div class="tn"></div>

<!-- FOOTER -->
<footer class="footer">
  <div class="wrap">
    <div class="fgrid">
      <div>
        <div class="f-brand"><img src="Logo.png" alt="Logo" /><strong>Koronadal City</strong></div>
        <p class="f-desc">Your one-stop digital portal for all Koronadal City government services. Making public service accessible, fast, and transparent for every resident.</p>
        <div class="f-soc">
          <a href="https://www.facebook.com/CityGovernmentofKoronadal" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="mailto:info.koronadalcity@gmail.com"><i class="fas fa-envelope"></i></a>
          <a href="tel:(083)2286095"><i class="fas fa-phone"></i></a>
        </div>
      </div>
      <div class="fcol">
        <div class="fcol-t">Services</div>
        <ul>
          <li><a href="https://koronadalcityportal.com/v2/login" target="_blank">Business Permits</a></li>
          <li><a href="https://traffic.koronadalcityonlineservices.com/" target="_blank">Traffic Violations</a></li>
          <li><a href="https://koronadal.gov.ph/job-vacancy/" target="_blank">Job Openings</a></li>
          <li><a href="https://koronadal.gov.ph/bids-and-awards/" target="_blank">Bids & Awards</a></li>
          <li><a href="https://koronadal.gov.ph/emergency-hotlines/" target="_blank">Emergency Contacts</a></li>
        </ul>
      </div>
      <div class="fcol">
        <div class="fcol-t">Government</div>
        <ul>
          <li><a href="https://koronadal.gov.ph/city-officials/" target="_blank">Officials Directory</a></li>
          <li><a href="https://koronadal.gov.ph/citizens-charter/" target="_blank">Citizen's Charter</a></li>
          <li><a href="https://koronadal.gov.ph/full-disclosure/" target="_blank">Financial Reports</a></li>
          <li><a href="https://koronadal.gov.ph/ordinance/" target="_blank">City Ordinances</a></li>
          <li><a href="https://koronadal.gov.ph/mayors-message/" target="_blank">Mayor's Message</a></li>
        </ul>
      </div>
      <div class="fcol">
        <div class="fcol-t">City Info</div>
        <ul>
          <li><a href="https://koronadal.gov.ph/culture/" target="_blank">Tourism</a></li>
          <li><a href="https://koronadal.gov.ph/culture-copy/" target="_blank">Health Facilities</a></li>
          <li><a href="https://koronadal.gov.ph/fare-matrix/" target="_blank">Transport Fares</a></li>
          <li><a href="https://koronadal.gov.ph/27-barangay/" target="_blank">Barangays</a></li>
          <li><a href="https://koronadal.gov.ph/geography/" target="_blank">About the City</a></li>
        </ul>
      </div>
    </div>
    <div class="f-bottom">
      <span class="f-copy">&copy; <?php echo date('Y'); ?> City Government of Koronadal. All rights reserved.</span>
      <span class="f-motto">One People, One Big Dream, One Koronadal</span>
    </div>
  </div>
</footer>

<script>
  // Nav — seamless transparent → solid transition
  const nav=document.getElementById('nav');
  function updateNav(){
    if(scrollY<40){nav.classList.add('at-top');nav.classList.remove('scrolled')}
    else{nav.classList.remove('at-top');nav.classList.add('scrolled')}
  }
  updateNav();
  window.addEventListener('scroll',updateNav,{passive:true});

  // Mobile nav
  function toggleNav(){const m=document.getElementById('mnav');m.classList.toggle('on');document.body.style.overflow=m.classList.contains('on')?'hidden':''}

  // Reveal on scroll
  const obs=new IntersectionObserver(entries=>{entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('show'),i*40);obs.unobserve(e.target)}})},{threshold:.05,rootMargin:'0px 0px -16px 0px'});
  document.querySelectorAll('.a').forEach(el=>obs.observe(el));

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(a=>{a.addEventListener('click',function(e){const t=document.querySelector(this.getAttribute('href'));if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})}})});

  // Filters
  document.querySelectorAll('.fb').forEach(b=>{b.addEventListener('click',function(){document.querySelectorAll('.fb').forEach(x=>x.classList.remove('on'));this.classList.add('on');const f=this.dataset.f;let n=0;document.querySelectorAll('.sgrid .sc').forEach(c=>{if(f==='all'||c.dataset.c===f){c.style.display='';n++}else{c.style.display='none'}});document.getElementById('cnt').textContent=n})});

  // Hero search
  document.getElementById('heroSearch').addEventListener('keydown',function(e){if(e.key==='Enter'){const q=this.value.toLowerCase().trim();document.querySelectorAll('.fb').forEach(b=>b.classList.remove('on'));document.querySelector('.fb[data-f="all"]').classList.add('on');let n=0;document.querySelectorAll('.sgrid .sc').forEach(c=>{c.style.display='';const t=(c.querySelector('h3')?.textContent||'').toLowerCase();const d=(c.querySelector('p')?.textContent||'').toLowerCase();if(q&&!t.includes(q)&&!d.includes(q))c.style.display='none';else n++});document.getElementById('cnt').textContent=n;if(q)document.getElementById('services').scrollIntoView({behavior:'smooth'})}});
</script>
</body>
</html>