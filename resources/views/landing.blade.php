<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRITON SECURITY – Okos otthon automatizáció</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:      #60a5fa;
            --green2:     #3b82f6;
            --green3:     #2563eb;
            --green-glow: #93c5fd;
            --green-dim:  rgba(96,165,250,0.15);
            --beige:      #c9a97a;
            --beige2:     #e8d5b7;
            --beige3:     #a8834a;
            --beige-dim:  rgba(201,169,122,0.12);
            --bg:         #080c14;
            --bg2:        #0c1220;
            --bg3:        #111827;
            --bg4:        #1a2236;
            --text:       #e2e8f0;
            --muted:      #8899aa;
            --mono:       'JetBrains Mono', monospace;
            --radius:     14px;
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; overflow-x: hidden; }

        /* ══════════════════════════════
           CANVAS – particle + circuit
        ══════════════════════════════ */
        #particles-canvas {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
        }

        /* ══════════════════════════════
           FLOATING BINARY / CODE DECO
        ══════════════════════════════ */
        .deco-layer {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            overflow: hidden;
        }
        .bin-float {
            position: absolute;
            font-family: var(--mono);
            font-size: 11px;
            color: var(--green);
            opacity: 0;
            animation: binFall linear infinite;
            white-space: nowrap;
            user-select: none;
        }
        @keyframes binFall {
            0%   { opacity: 0; transform: translateY(-20px); }
            5%   { opacity: 0.18; }
            90%  { opacity: 0.10; }
            100% { opacity: 0; transform: translateY(100vh); }
        }
        .code-snippet {
            position: absolute;
            font-family: var(--mono);
            font-size: 10px;
            color: var(--beige);
            opacity: 0;
            animation: snippetFade ease-in-out infinite;
            white-space: nowrap;
            border-left: 2px solid var(--green3);
            padding-left: 8px;
            user-select: none;
        }
        @keyframes snippetFade {
            0%,100% { opacity: 0; transform: translateX(-6px); }
            20%,80% { opacity: 0.15; transform: translateX(0); }
        }

        /* ══════════════════════════════
           NAVBAR
        ══════════════════════════════ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 70px;
            background: rgba(10,13,8,0.88);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(96,165,250,0.12);
            transition: all 0.3s;
        }
        .navbar.scrolled {
            background: rgba(10,13,8,0.97);
            border-bottom-color: rgba(96,165,250,0.22);
            box-shadow: 0 4px 30px rgba(0,0,0,0.5);
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo-icon {
            width: 40px; height: 40px; border-radius: 11px;
            background: linear-gradient(135deg, var(--beige), var(--green3));
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            box-shadow: 0 0 20px rgba(96,165,250,0.35);
            animation: logoPulse 3s ease-in-out infinite;
            position: relative; overflow: hidden;
        }
        .nav-logo-icon::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--green), var(--beige));
            opacity: 0; transition: opacity 0.4s;
        }
        @keyframes logoPulse {
            0%,100% { box-shadow: 0 0 20px rgba(96,165,250,0.35); }
            50%      { box-shadow: 0 0 40px rgba(201,169,122,0.5); }
        }
        .nav-logo-text { font-size: 15px; font-weight: 800; letter-spacing: 1.5px; color: var(--text); text-transform: uppercase; }
        .nav-logo-text span { background: linear-gradient(90deg, var(--green), var(--beige2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .nav-links { display: flex; align-items: center; gap: 32px; list-style: none; }
        .nav-links a {
            color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500;
            transition: color 0.2s; position: relative;
        }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -4px; left: 0; right: 0;
            height: 2px; background: linear-gradient(90deg, var(--green), var(--beige));
            transform: scaleX(0); transition: transform 0.25s;
        }
        .nav-links a:hover { color: var(--beige2); }
        .nav-links a:hover::after { transform: scaleX(1); }

        /* Ügyfélkapu dropdown */
        .nav-dropdown { position: relative; }
        .nav-dropdown-btn {
            display: flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--green3), var(--beige3));
            color: #fff; border: none; cursor: pointer;
            font-family: inherit; font-size: 14px; font-weight: 700;
            padding: 10px 22px; border-radius: 10px;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: 0 4px 20px rgba(96,165,250,0.3);
            position: relative; overflow: hidden;
        }
        .nav-dropdown-btn::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--beige3), var(--green3));
            opacity: 0; transition: opacity 0.3s;
        }
        .nav-dropdown-btn:hover::before { opacity: 1; }
        .nav-dropdown-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 30px rgba(201,169,122,0.4); }
        .nav-dropdown-btn > * { position: relative; z-index: 1; }
        .nav-dropdown-btn .chevron { transition: transform 0.25s; font-size: 10px; }
        .nav-dropdown.open .nav-dropdown-btn .chevron { transform: rotate(180deg); }

        .nav-dropdown-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 12px);
            background: var(--bg3); border: 1px solid rgba(96,165,250,0.25);
            border-radius: 12px; min-width: 200px;
            box-shadow: 0 16px 50px rgba(0,0,0,0.6), 0 0 0 1px rgba(96,165,250,0.08);
            overflow: hidden; animation: dropIn 0.2s ease;
        }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .nav-dropdown.open .nav-dropdown-menu { display: block; }
        .nav-dropdown-menu a {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 20px; color: var(--text); text-decoration: none;
            font-size: 14px; font-weight: 500; transition: background 0.15s;
        }
        .nav-dropdown-menu a:hover { background: rgba(96,165,250,0.1); }
        .nav-dropdown-menu a i { color: var(--green); width: 16px; }

        /* ══════════════════════════════
           HERO
        ══════════════════════════════ */
        #hero {
            min-height: 100vh; position: relative; overflow: hidden;
            display: flex; align-items: center;
            padding: 130px 48px 100px;
        }
        .hero-bg-gradient {
            position: absolute; inset: 0; z-index: 1;
            background:
                radial-gradient(ellipse 80% 60% at 15% 50%, rgba(96,165,250,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 50% 70% at 85% 20%, rgba(201,169,122,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 90% 80%, rgba(59,130,246,0.05) 0%, transparent 50%);
            animation: bgShift 8s ease-in-out infinite alternate;
        }
        @keyframes bgShift {
            from { opacity: 0.7; } to { opacity: 1; }
        }
        /* Circuit grid background */
        .hero-grid-bg {
            position: absolute; inset: 0; z-index: 1;
            background-image:
                linear-gradient(rgba(96,165,250,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(96,165,250,0.035) 1px, transparent 1px);
            background-size: 44px 44px;
        }
        /* Circuit dot intersections */
        .hero-grid-bg::after {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(96,165,250,0.18) 1px, transparent 1px);
            background-size: 44px 44px;
            background-position: 22px 22px;
        }
        .hero-content { position: relative; z-index: 2; max-width: 660px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 7px 16px; border-radius: 30px; margin-bottom: 28px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
            background: rgba(96,165,250,0.08); border: 1px solid rgba(96,165,250,0.3);
            color: var(--green);
            animation: badgePulse 2.5s ease-in-out infinite;
        }
        @keyframes badgePulse {
            0%,100% { border-color: rgba(96,165,250,0.3); }
            50% { border-color: rgba(201,169,122,0.5); color: var(--beige2); }
        }

        .hero-title {
            font-size: clamp(38px, 5.5vw, 68px);
            font-weight: 900; line-height: 1.08; color: var(--beige2); margin-bottom: 22px;
        }
        .hero-title .grad1 {
            background: linear-gradient(90deg, var(--green), #93c5fd);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            animation: gradShift 4s ease-in-out infinite alternate;
        }
        .hero-title .grad2 {
            background: linear-gradient(90deg, var(--beige), var(--beige2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        @keyframes gradShift {
            from { filter: hue-rotate(0deg); }
            to   { filter: hue-rotate(20deg); }
        }
        .hero-sub { font-size: 18px; color: var(--muted); line-height: 1.75; margin-bottom: 44px; max-width: 520px; }

        /* Inline code highlight */
        .hero-sub code {
            font-family: var(--mono); font-size: 13px;
            background: var(--bg4); color: var(--green);
            padding: 2px 7px; border-radius: 5px;
            border: 1px solid rgba(96,165,250,0.2);
        }

        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 60px; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--green3), var(--beige3));
            color: #fff; text-decoration: none; font-size: 15px; font-weight: 700;
            padding: 15px 30px; border-radius: 12px;
            box-shadow: 0 4px 24px rgba(96,165,250,0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative; overflow: hidden;
        }
        .btn-primary::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--beige3), var(--green3));
            opacity: 0; transition: opacity 0.3s;
        }
        .btn-primary:hover::after { opacity: 1; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(201,169,122,0.35); }
        .btn-primary > * { position: relative; z-index: 1; }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 10px;
            border: 1.5px solid rgba(96,165,250,0.35); color: var(--green);
            text-decoration: none; font-size: 15px; font-weight: 600;
            padding: 15px 30px; border-radius: 12px; transition: all 0.25s;
        }
        .btn-outline:hover { background: rgba(96,165,250,0.08); border-color: var(--green); color: var(--green); }

        .hero-stats { display: flex; gap: 52px; flex-wrap: wrap; }
        .hero-stat { position: relative; }
        .hero-stat-num {
            font-size: 40px; font-weight: 900; line-height: 1;
            background: linear-gradient(135deg, var(--green), var(--beige));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-stat-label { font-size: 13px; color: var(--muted); margin-top: 5px; }

        /* floating cards */
        .hero-float {
            position: absolute; right: 60px; top: 50%; transform: translateY(-50%);
            z-index: 2; display: flex; flex-direction: column; gap: 18px;
        }
        .float-card {
            background: rgba(20,26,14,0.92);
            border: 1px solid rgba(96,165,250,0.15);
            border-radius: 16px; padding: 18px 22px;
            backdrop-filter: blur(16px); min-width: 260px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5);
            position: relative; overflow: hidden;
        }
        /* Circuit corner decoration */
        .float-card::after {
            content: ''; position: absolute; bottom: 0; right: 0;
            width: 60px; height: 60px;
            background: linear-gradient(135deg, transparent 50%, rgba(96,165,250,0.06) 50%);
        }
        .float-card:nth-child(1) { animation: floatA 5s ease-in-out infinite; border-color: rgba(96,165,250,0.2); }
        .float-card:nth-child(2) { animation: floatB 5s ease-in-out infinite; margin-left: 36px; border-color: rgba(201,169,122,0.2); }
        .float-card:nth-child(3) { animation: floatC 5s ease-in-out infinite; border-color: rgba(59,130,246,0.2); }
        @keyframes floatA { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        @keyframes floatB { 0%,100%{transform:translateY(-6px)} 50%{transform:translateY(6px)} }
        @keyframes floatC { 0%,100%{transform:translateY(-4px)} 50%{transform:translateY(-14px)} }
        .float-title { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; }
        .float-row { display: flex; align-items: center; gap: 12px; }
        .float-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .fi-green  { background: rgba(96,165,250,0.15);  color: var(--green); }
        .fi-beige  { background: rgba(201,169,122,0.15); color: var(--beige); }
        .fi-green2 { background: rgba(37,99,235,0.15);   color: var(--green2); }
        .float-val { font-size: 15px; font-weight: 800; color: var(--beige2); }
        .float-sub { font-size: 11px; color: var(--muted); }
        .pulse-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green); display: inline-block; margin-right: 5px; animation: pdot 1.8s infinite; }
        @keyframes pdot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(1.4)} }

        /* ══════════════════════════════
           INLINE CODE BLOCK (deco)
        ══════════════════════════════ */
        .hero-code-block {
            position: absolute; right: 40px; bottom: 80px; z-index: 2;
            font-family: var(--mono); font-size: 11px; line-height: 1.8;
            background: rgba(15,18,9,0.85); backdrop-filter: blur(12px);
            border: 1px solid rgba(96,165,250,0.18); border-radius: 10px;
            padding: 14px 20px; color: var(--muted);
            max-width: 280px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .hero-code-block .ln { color: rgba(96,165,250,0.3); display: inline-block; width: 22px; }
        .hero-code-block .kw { color: var(--green); }
        .hero-code-block .fn { color: var(--beige); }
        .hero-code-block .str { color: #93c5fd; }
        .hero-code-block .cm { color: var(--muted); opacity: 0.6; }
        .hero-code-block .num { color: var(--beige2); }
        .typing-cursor { display: inline-block; width: 7px; height: 13px; background: var(--green); vertical-align: middle; animation: blink 1s step-end infinite; margin-left: 2px; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }

        /* ══════════════════════════════
           SCROLL REVEAL
        ══════════════════════════════ */
        .reveal       { opacity: 0; transform: translateY(40px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-left  { opacity: 0; transform: translateX(-40px); transition: opacity 0.7s, transform 0.7s; }
        .reveal-right { opacity: 0; transform: translateX(40px);  transition: opacity 0.7s, transform 0.7s; }
        .reveal-left.visible, .reveal-right.visible { opacity: 1; transform: translateX(0); }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
        .delay-5 { transition-delay: 0.5s; }
        .delay-6 { transition-delay: 0.6s; }

        /* ══════════════════════════════
           SECTIONS
        ══════════════════════════════ */
        section { padding: 110px 48px; position: relative; }
        .section-badge {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            padding: 6px 14px; border-radius: 20px; margin-bottom: 18px;
        }
        .sb-green  { background: rgba(96,165,250,0.1);  color: var(--green);  border: 1px solid rgba(96,165,250,0.25); }
        .sb-beige  { background: rgba(201,169,122,0.1); color: var(--beige);  border: 1px solid rgba(201,169,122,0.25); }
        .sb-green2 { background: rgba(37,99,235,0.1);   color: var(--green2); border: 1px solid rgba(37,99,235,0.25); }

        .section-title { font-size: clamp(28px, 3.5vw, 44px); font-weight: 900; color: var(--beige2); line-height: 1.15; margin-bottom: 16px; }
        .section-sub { font-size: 16px; color: var(--muted); line-height: 1.75; max-width: 560px; }
        .grad-text-green  { background: linear-gradient(90deg, var(--green), #93c5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .grad-text-beige  { background: linear-gradient(90deg, var(--beige), var(--beige2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* ══════════════════════════════
           SERVICES
        ══════════════════════════════ */
        #szolgaltatasok { background: var(--bg2); }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }
        .service-card {
            background: var(--bg3);
            border: 1px solid rgba(96,165,250,0.08);
            border-radius: 20px;
            padding: 36px 28px 32px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
            transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .service-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: radial-gradient(ellipse at 50% 0%, rgba(96,165,250,0.06) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .service-card:hover {
            border-color: rgba(96,165,250,0.25);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.35);
        }
        .service-card:hover::after { opacity: 1; }

        .service-icon {
            width: 52px; height: 52px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
            margin-bottom: 20px;
        }
        .si-green  { background: rgba(96,165,250,0.1);  color: var(--green); }
        .si-beige  { background: rgba(201,169,122,0.1); color: var(--beige); }
        .si-lime   { background: rgba(147,197,253,0.1);  color: #93c5fd; }
        .si-green2 { background: rgba(59,130,246,0.1);   color: var(--green2); }
        .si-beige2 { background: rgba(232,213,183,0.1); color: var(--beige2); }
        .si-brown  { background: rgba(168,131,74,0.1);  color: var(--beige3); }

        .service-title { font-size: 16px; font-weight: 700; color: var(--beige2); margin-bottom: 10px; line-height: 1.3; }
        .service-tag   { font-size: 12px; color: var(--muted); font-weight: 500; letter-spacing: 0.02em; }

        /* Hover-on részletes leírás */
        .service-desc {
            font-size: 13px; color: var(--muted); line-height: 1.65;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(96,165,250,0.08);
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.35s ease, opacity 0.3s ease, margin-top 0.3s ease;
        }
        .service-card:hover .service-desc {
            max-height: 80px;
            opacity: 1;
        }

        @media (max-width: 900px) {
            .services-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 560px) {
            .services-grid { grid-template-columns: 1fr; }
            .service-card:hover .service-desc { max-height: 80px; opacity: 1; }
        }

        /* ══════════════════════════════
           CODE SHOWCASE SECTION
        ══════════════════════════════ */
        #kod-showcase {
            background: var(--bg); padding: 90px 48px;
            position: relative; overflow: hidden;
        }
        #kod-showcase::before {
            content: ''; position: absolute; inset: 0;
            background:
                linear-gradient(rgba(96,165,250,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(96,165,250,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .showcase-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; position: relative; z-index: 1; }
        .code-terminal {
            background: var(--bg2); border: 1px solid rgba(96,165,250,0.2);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(96,165,250,0.05), 0 0 40px rgba(96,165,250,0.06);
        }
        .terminal-bar {
            background: var(--bg3); padding: 12px 18px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(96,165,250,0.12);
        }
        .t-dot { width: 12px; height: 12px; border-radius: 50%; }
        .td-red    { background: #ff5f57; }
        .td-yellow { background: #ffbd2e; }
        .td-green  { background: #28c840; }
        .t-file { font-family: var(--mono); font-size: 12px; color: var(--muted); margin-left: 12px; }
        .terminal-body { padding: 24px; font-family: var(--mono); font-size: 12.5px; line-height: 2; }
        .tl-ln  { color: rgba(96,165,250,0.25); display: inline-block; width: 28px; text-align: right; margin-right: 14px; user-select: none; }
        .tl-kw  { color: var(--green); font-weight: 700; }
        .tl-fn  { color: var(--beige); }
        .tl-str { color: #93c5fd; }
        .tl-num { color: var(--beige2); }
        .tl-cm  { color: var(--muted); opacity: 0.55; font-style: italic; }
        .tl-op  { color: rgba(232,213,183,0.7); }
        .tl-var { color: var(--text); }
        .tl-line { display: block; }

        .showcase-text { position: relative; z-index: 1; }
        .showcase-text h2 { font-size: clamp(24px, 3vw, 38px); font-weight: 900; color: var(--beige2); margin-bottom: 16px; line-height: 1.2; }
        .showcase-text p  { color: var(--muted); line-height: 1.75; margin-bottom: 28px; }
        .tech-pill-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
        .tech-pill {
            font-family: var(--mono); font-size: 11px; font-weight: 700;
            padding: 6px 14px; border-radius: 8px;
            background: var(--bg3); border: 1px solid rgba(96,165,250,0.2);
            color: var(--green); transition: all 0.2s;
        }
        .tech-pill:hover { background: rgba(96,165,250,0.1); border-color: var(--green); transform: translateY(-2px); }

        /* ══════════════════════════════
           AI SECTION
        ══════════════════════════════ */
        #ai { background: var(--bg2); display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
        .ai-visual {
            background: var(--bg3); border: 1px solid rgba(96,165,250,0.15);
            border-radius: 22px; padding: 32px; position: relative; overflow: hidden;
        }
        .ai-visual::before {
            content: ''; position: absolute; top: -40px; right: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: radial-gradient(circle, rgba(96,165,250,0.1), transparent 70%);
            pointer-events: none;
        }
        /* PCB trace decoration */
        .ai-visual::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 60px;
            background:
                linear-gradient(to right, rgba(96,165,250,0.08) 1px, transparent 1px) 0 0 / 20px 100%,
                linear-gradient(to top, rgba(96,165,250,0.08) 1px, transparent 1px) 0 0 / 100% 20px;
            border-bottom-left-radius: 22px;
        }
        .ai-vis-title { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 22px; display: flex; align-items: center; gap: 8px; }
        .ai-vis-title i { color: var(--green); }
        .ai-node {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px; background: var(--bg4);
            border: 1px solid rgba(96,165,250,0.06);
            border-radius: 12px; margin-bottom: 8px;
            transition: border-color 0.3s, box-shadow 0.3s;
            animation: nodeGlow 4s ease-in-out infinite;
        }
        .ai-node:nth-child(2) { animation-delay: 1s; }
        .ai-node:nth-child(3) { animation-delay: 2s; }
        .ai-node:nth-child(4) { animation-delay: 3s; }
        @keyframes nodeGlow {
            0%,80%,100% { border-color: rgba(96,165,250,0.06); box-shadow: none; }
            40% { border-color: rgba(96,165,250,0.25); box-shadow: 0 0 15px rgba(96,165,250,0.08); }
        }
        .ai-node-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .ai-node-label { font-size: 13px; font-weight: 700; color: var(--beige2); }
        .ai-node-sub { font-size: 11px; color: var(--muted); }
        .ai-node-val { margin-left: auto; font-size: 13px; font-weight: 800; font-family: var(--mono); }
        .ai-connector { width: 2px; height: 18px; margin: 0 0 8px 28px; background: linear-gradient(to bottom, rgba(96,165,250,0.4), transparent); }
        .ai-feat-list { list-style: none; }
        .ai-feat-item { display: flex; align-items: flex-start; gap: 16px; padding: 18px 0; border-bottom: 1px solid rgba(96,165,250,0.06); }
        .ai-feat-item:last-child { border-bottom: none; }
        .ai-feat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; margin-top: 2px; }
        .ai-feat-title { font-size: 15px; font-weight: 800; color: var(--beige2); margin-bottom: 5px; }
        .ai-feat-desc { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ══════════════════════════════
           NUMBERS / MIÉRT MI
        ══════════════════════════════ */
        #miert-mi { background: var(--bg); }
        .why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 60px; }
        .why-card {
            text-align: center; padding: 40px 24px;
            border-radius: 20px; border: 1px solid rgba(96,165,250,0.08);
            position: relative; overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .why-card:hover { transform: translateY(-6px); }
        .why-card::before {
            content: ''; position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%);
            width: 80px; height: 80px; border-radius: 50%;
            filter: blur(25px); opacity: 0.4;
        }
        /* PCB trace top-left corner */
        .why-card::after {
            content: ''; position: absolute; top: 0; left: 0; width: 40px; height: 40px;
            border-top: 2px solid rgba(96,165,250,0.15); border-left: 2px solid rgba(96,165,250,0.15);
            border-top-left-radius: 20px;
        }
        .wc-1 { background: linear-gradient(160deg, rgba(96,165,250,0.07) 0%, var(--bg3) 60%); }
        .wc-1::before { background: var(--green); }
        .wc-1:hover { box-shadow: 0 20px 60px rgba(96,165,250,0.12); }
        .wc-2 { background: linear-gradient(160deg, rgba(201,169,122,0.07) 0%, var(--bg3) 60%); }
        .wc-2::before { background: var(--beige); }
        .wc-2:hover { box-shadow: 0 20px 60px rgba(201,169,122,0.12); }
        .wc-3 { background: linear-gradient(160deg, rgba(59,130,246,0.07) 0%, var(--bg3) 60%); }
        .wc-3::before { background: var(--green2); }
        .wc-3:hover { box-shadow: 0 20px 60px rgba(59,130,246,0.12); }
        .wc-4 { background: linear-gradient(160deg, rgba(232,213,183,0.07) 0%, var(--bg3) 60%); }
        .wc-4::before { background: var(--beige2); }
        .wc-4:hover { box-shadow: 0 20px 60px rgba(232,213,183,0.12); }
        .why-num { font-size: 52px; font-weight: 900; line-height: 1; margin-bottom: 8px; }
        .why-label { font-size: 16px; font-weight: 800; color: var(--beige2); margin-bottom: 8px; }
        .why-desc { font-size: 13px; color: var(--muted); line-height: 1.6; }
        .count-up { display: inline-block; }

        /* ══════════════════════════════
           PROCESS
        ══════════════════════════════ */
        #folyamat { background: var(--bg2); }
        .process-track { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; position: relative; margin-top: 64px; }
        .process-track::before {
            content: ''; position: absolute; top: 35px; left: 12.5%; right: 12.5%;
            height: 2px;
            background: linear-gradient(90deg, var(--green3), var(--green), var(--beige), var(--beige3));
            z-index: 0;
            animation: lineGlow 3s ease-in-out infinite alternate;
        }
        @keyframes lineGlow {
            from { box-shadow: 0 0 8px rgba(96,165,250,0.4); }
            to   { box-shadow: 0 0 20px rgba(201,169,122,0.5); }
        }
        .process-step { text-align: center; padding: 0 20px; }
        .process-num {
            width: 72px; height: 72px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 900; font-family: var(--mono);
            margin: 0 auto 22px; position: relative; z-index: 1;
            transition: transform 0.3s;
        }
        .process-step:hover .process-num { transform: scale(1.1); }
        .pn-1 { background: linear-gradient(135deg, var(--green3), #14532d);  box-shadow: 0 0 25px rgba(96,165,250,0.35); color: #fff; }
        .pn-2 { background: linear-gradient(135deg, var(--green), #16a34a);   box-shadow: 0 0 25px rgba(96,165,250,0.35); color: #000; }
        .pn-3 { background: linear-gradient(135deg, var(--beige3), var(--beige)); box-shadow: 0 0 25px rgba(201,169,122,0.35); color: #fff; }
        .pn-4 { background: linear-gradient(135deg, var(--beige), var(--beige2)); box-shadow: 0 0 25px rgba(232,213,183,0.35); color: #0a0d08; }
        .process-step-title { font-size: 16px; font-weight: 800; color: var(--beige2); margin-bottom: 8px; }
        .process-step-desc { font-size: 13px; color: var(--muted); line-height: 1.65; }

        /* ══════════════════════════════
           CONTACT
        ══════════════════════════════ */
        #kapcsolat { background: var(--bg); display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; }
        .contact-item { display: flex; align-items: center; gap: 18px; padding: 20px 0; border-bottom: 1px solid rgba(96,165,250,0.07); }
        .contact-item:last-child { border-bottom: none; }
        .contact-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .contact-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .contact-val { font-size: 15px; font-weight: 700; color: var(--beige2); margin-top: 3px; }

        .cta-box {
            border-radius: 22px; padding: 44px;
            background: linear-gradient(135deg, rgba(96,165,250,0.06), rgba(201,169,122,0.04));
            border: 1px solid rgba(96,165,250,0.18);
            position: relative; overflow: hidden;
        }
        .cta-box::before {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 200px; height: 200px; border-radius: 50%;
            background: radial-gradient(circle, rgba(96,165,250,0.12), transparent);
            pointer-events: none;
        }
        /* Circuit trace decoration */
        .cta-box::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--green3), var(--green), var(--beige), transparent);
            border-bottom-left-radius: 22px; border-bottom-right-radius: 22px;
        }
        .cta-box h3 { font-size: 26px; font-weight: 900; color: var(--beige2); margin-bottom: 12px; }
        .cta-box p { font-size: 14px; color: var(--muted); margin-bottom: 30px; line-height: 1.75; }
        .cta-login {
            display: inline-flex; align-items: center; gap: 10px;
            font-size: 14px; font-weight: 700; color: var(--green); text-decoration: none;
            margin-top: 28px; padding-top: 24px;
            border-top: 1px solid rgba(96,165,250,0.15); width: 100%;
            transition: color 0.2s;
        }
        .cta-login:hover { color: var(--beige); }

        /* ══════════════════════════════
           FOOTER
        ══════════════════════════════ */
        footer {
            background: var(--bg2);
            border-top: 1px solid rgba(96,165,250,0.1);
            padding: 48px; display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 24px;
            position: relative; overflow: hidden;
        }
        footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--green3), var(--green), var(--beige), var(--beige2), transparent);
        }
        .footer-copy { font-size: 13px; color: var(--muted); }
        .footer-copy span { color: var(--green); font-weight: 600; }
        .footer-mono { font-family: var(--mono); font-size: 11px; color: rgba(96,165,250,0.35); margin-top: 6px; }
        .footer-links { display: flex; gap: 28px; }
        .footer-links a { font-size: 13px; color: var(--muted); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--green); }

        /* ══════════════════════════════
           GLOW ORBS
        ══════════════════════════════ */
        .orb {
            position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0;
            animation: orbFloat 10s ease-in-out infinite alternate;
        }
        @keyframes orbFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -20px) scale(1.1); }
        }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 1024px) {
            .hero-float, .hero-code-block { display: none; }
            #ai, #kapcsolat { grid-template-columns: 1fr; gap: 48px; }
            .showcase-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 0 20px; }
            .nav-links { display: none; }
            section { padding: 70px 24px; }
            footer { padding: 32px 24px; flex-direction: column; text-align: center; }
            .process-track { grid-template-columns: 1fr 1fr; }
            .process-track::before { display: none; }
        }
        @media (max-width: 500px) {
            #hero { padding: 110px 20px 80px; }
            .hero-stats { gap: 30px; }
            .process-track { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- CANVAS --}}
<canvas id="particles-canvas"></canvas>

{{-- FLOATING BINARY & CODE DECORATIONS --}}
<div class="deco-layer" id="decoLayer"></div>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar" id="navbar">
    <a href="#hero" class="nav-logo">
        <div class="nav-logo-icon"><i class="fas fa-shield-halved"></i></div>
        <span class="nav-logo-text">TRITON <span>SECURITY</span></span>
    </a>

    <ul class="nav-links">
        <li><a href="#szolgaltatasok">Szolgáltatások</a></li>
        <li><a href="#ai">AI technológia</a></li>
        <li><a href="#miert-mi">Rólunk</a></li>
        <li><a href="#folyamat">Folyamat</a></li>
        <li><a href="#kapcsolat">Kapcsolat</a></li>
    </ul>

    <div class="nav-dropdown" id="navDropdown">
        <button class="nav-dropdown-btn" id="dropdownBtn" onclick="toggleDropdown(event)">
            <i class="fas fa-user-shield"></i>
            <span>Ügyfélkapu</span>
            <i class="fas fa-chevron-down chevron"></i>
        </button>
        <div class="nav-dropdown-menu" id="dropdownMenu">
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt"></i> Bejelentkezés
            </a>
        </div>
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<section id="hero">
    <div class="hero-bg-gradient"></div>
    <div class="hero-grid-bg"></div>
    <div class="orb" style="width:450px;height:450px;top:-120px;left:-120px;background:rgba(96,165,250,0.05);"></div>
    <div class="orb" style="width:320px;height:320px;bottom:0;right:220px;background:rgba(201,169,122,0.05);animation-delay:-5s;"></div>

    <div class="hero-content">
        <div class="hero-badge"><i class="fas fa-microchip"></i> AI-alapú okos otthon megoldások</div>
        <h1 class="hero-title">
            Az otthonod<br>
            <span class="grad1">intelligens jövője</span><br>
            <span class="grad2">itt kezdődik.</span>
        </h1>
        <p class="hero-sub">A TRITON SECURITY profi biztonsági és okos otthon automatizációs rendszereket tervez és telepít. <code>AI</code>-vezérelt megoldások, amelyek a te igényeidre szabva dolgoznak.</p>
        <div class="hero-actions">
            <a href="#kapcsolat" class="btn-primary"><span><i class="fas fa-paper-plane"></i></span><span>Ingyenes felmérést kérek</span></a>
            <a href="#szolgaltatasok" class="btn-outline"><i class="fas fa-play-circle"></i> Szolgáltatásaink</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><div class="hero-stat-num"><span class="count-up" data-target="500">0</span>+</div><div class="hero-stat-label">Telepített rendszer</div></div>
            <div class="hero-stat"><div class="hero-stat-num"><span class="count-up" data-target="98">0</span>%</div><div class="hero-stat-label">Elégedett ügyfél</div></div>
            <div class="hero-stat"><div class="hero-stat-num"><span class="count-up" data-target="10">0</span>+</div><div class="hero-stat-label">Év tapasztalat</div></div>
        </div>
    </div>

    <div class="hero-float">
        <div class="float-card">
            <div class="float-title">Rendszer állapota</div>
            <div class="float-row">
                <div class="float-icon fi-green"><i class="fas fa-check"></i></div>
                <div><div class="float-val"><span class="pulse-dot"></span>Minden rendszer aktív</div><div class="float-sub">Utolsó ellenőrzés: éppen most</div></div>
            </div>
        </div>
        <div class="float-card">
            <div class="float-title">AI Energiamegtakarítás</div>
            <div class="float-row">
                <div class="float-icon fi-beige"><i class="fas fa-bolt"></i></div>
                <div><div class="float-val">–34% villamos energia</div><div class="float-sub">Havi átlag, AI optimalizálás</div></div>
            </div>
        </div>
        <div class="float-card">
            <div class="float-title">Biztonsági szint</div>
            <div class="float-row">
                <div class="float-icon fi-green2"><i class="fas fa-shield-halved"></i></div>
                <div><div class="float-val">Maximális védelem</div><div class="float-sub">24/7 monitorozás, AI riasztás</div></div>
            </div>
        </div>
    </div>

    {{-- Decorative code block --}}
    <div class="hero-code-block" style="position:absolute;right:48px;bottom:90px;">
        <div><span class="ln">1</span><span class="cm">// triton-ai-core v3.2.1</span></div>
        <div><span class="ln">2</span><span class="kw">const</span> <span class="tl-var">sensor</span> <span class="tl-op">=</span> <span class="fn">SmartHome</span><span class="tl-op">.</span><span class="fn">init</span><span class="tl-op">()</span></div>
        <div><span class="ln">3</span><span class="kw">if</span> <span class="tl-op">(</span><span class="tl-var">temp</span> <span class="tl-op">&gt;</span> <span class="num">22</span><span class="tl-op">)</span> <span class="tl-op">{</span></div>
        <div><span class="ln">4</span>&nbsp;&nbsp;<span class="fn">heating</span><span class="tl-op">.</span><span class="fn">off</span><span class="tl-op">()</span><span class="tl-op">;</span> <span class="cm">// –34% energia</span></div>
        <div><span class="ln">5</span><span class="tl-op">}</span></div>
        <div><span class="ln">6</span><span class="fn">security</span><span class="tl-op">.</span><span class="fn">arm</span><span class="tl-op">(</span><span class="str">"night"</span><span class="tl-op">)</span><span class="typing-cursor"></span></div>
    </div>
</section>

{{-- ═══ SZOLGÁLTATÁSOK ═══ --}}
<section id="szolgaltatasok">
    <div class="reveal" style="text-align:center;max-width:580px;margin:0 auto;">
        <div class="section-badge sb-green"><i class="fas fa-grid-2"></i> Szolgáltatások</div>
        <h2 class="section-title">Amiben <span class="grad-text-green">segítünk neked</span></h2>
        <p class="section-sub" style="margin:0 auto;">Tervezéstől a telepítésen át az utógondozásig – minden egy kézből.</p>
    </div>

    <div class="services-grid reveal">
        <div class="service-card">
            <div class="service-icon si-green"><i class="fas fa-home"></i></div>
            <div class="service-title">Okos otthon automatizáció</div>
            <div class="service-tag">Világítás · Fűtés · Zárak · Hang</div>
            <div class="service-desc">Minden eszközöd egy helyen, appból vagy hanggal vezérelve – egyszerűen és megbízhatóan.</div>
        </div>
        <div class="service-card">
            <div class="service-icon si-beige"><i class="fas fa-shield-halved"></i></div>
            <div class="service-title">Biztonsági rendszerek</div>
            <div class="service-tag">Riasztó · Kamera · Beléptető</div>
            <div class="service-desc">IP-kamera és riasztó rendszer telepítése professzionális 24/7 monitorozással.</div>
        </div>
        <div class="service-card">
            <div class="service-icon si-lime"><i class="fas fa-brain"></i></div>
            <div class="service-title">AI-alapú vezérlés</div>
            <div class="service-tag">Tanul · Optimalizál · Automatizál</div>
            <div class="service-desc">Mesterséges intelligencia, amely megtanulja a szokásaidat és helyetted dönt.</div>
        </div>
        <div class="service-card">
            <div class="service-icon si-green2"><i class="fas fa-solar-panel"></i></div>
            <div class="service-title">Energiaoptimalizálás</div>
            <div class="service-tag">Napelem · Okos mérők · –40% számla</div>
            <div class="service-desc">Napelem integráció és intelligens mérők segítségével akár 40%-kal csökkentjük a rezsiköltséget.</div>
        </div>
        <div class="service-card">
            <div class="service-icon si-beige2"><i class="fas fa-network-wired"></i></div>
            <div class="service-title">Hálózati infrastruktúra</div>
            <div class="service-tag">Wi-Fi 6 · Mesh · NAS · Szerver</div>
            <div class="service-desc">Stabil, gyors alapinfrastruktúra – a megbízható okos otthon alapköve.</div>
        </div>
        <div class="service-card">
            <div class="service-icon si-brown"><i class="fas fa-headset"></i></div>
            <div class="service-title">Karbantartás & Support</div>
            <div class="service-tag">Helyszíni · Távsegítség · Garancia</div>
            <div class="service-desc">Telepítés után is számíthatsz ránk – rendszeres karbantartás és gyors reagálás.</div>
        </div>
    </div>
</section>

{{-- ═══ KÓD SHOWCASE ═══ --}}
<section id="kod-showcase">
    <div class="showcase-grid">
        <div class="code-terminal reveal-left">
            <div class="terminal-bar">
                <span class="t-dot td-red"></span>
                <span class="t-dot td-yellow"></span>
                <span class="t-dot td-green"></span>
                <span class="t-file">triton-smart-home.js</span>
            </div>
            <div class="terminal-body">
                <span class="tl-line"><span class="tl-ln">01</span><span class="tl-cm">// TRITON AI – Smart Home Controller</span></span>
                <span class="tl-line"><span class="tl-ln">02</span><span class="tl-kw">class</span> <span class="tl-fn">SmartHomeAI</span> <span class="tl-op">{</span></span>
                <span class="tl-line"><span class="tl-ln">03</span>&nbsp;&nbsp;<span class="tl-fn">constructor</span><span class="tl-op">(</span><span class="tl-var">config</span><span class="tl-op">) {</span></span>
                <span class="tl-line"><span class="tl-ln">04</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">this</span><span class="tl-op">.</span><span class="tl-var">sensors</span> <span class="tl-op">=</span> <span class="tl-var">config</span><span class="tl-op">.</span><span class="tl-var">sensors</span><span class="tl-op">;</span></span>
                <span class="tl-line"><span class="tl-ln">05</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">this</span><span class="tl-op">.</span><span class="tl-var">model</span> <span class="tl-op">=</span> <span class="tl-kw">new</span> <span class="tl-fn">NeuralNet</span><span class="tl-op">();</span></span>
                <span class="tl-line"><span class="tl-ln">06</span>&nbsp;&nbsp;<span class="tl-op">}</span></span>
                <span class="tl-line"><span class="tl-ln">07</span>&nbsp;</span>
                <span class="tl-line"><span class="tl-ln">08</span>&nbsp;&nbsp;<span class="tl-fn">optimizeEnergy</span><span class="tl-op">(</span><span class="tl-var">data</span><span class="tl-op">) {</span></span>
                <span class="tl-line"><span class="tl-ln">09</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">const</span> <span class="tl-var">prediction</span> <span class="tl-op">=</span> <span class="tl-kw">this</span><span class="tl-op">.</span><span class="tl-var">model</span></span>
                <span class="tl-line"><span class="tl-ln">10</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-op">.</span><span class="tl-fn">predict</span><span class="tl-op">(</span><span class="tl-var">data</span><span class="tl-op">);</span> <span class="tl-cm">// &lt;50ms</span></span>
                <span class="tl-line"><span class="tl-ln">11</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">if</span> <span class="tl-op">(</span><span class="tl-var">prediction</span><span class="tl-op">.</span><span class="tl-var">savings</span> <span class="tl-op">&gt;</span> <span class="tl-num">0.3</span><span class="tl-op">) {</span></span>
                <span class="tl-line"><span class="tl-ln">12</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">this</span><span class="tl-op">.</span><span class="tl-fn">applyProfile</span><span class="tl-op">(</span><span class="tl-str">"eco"</span><span class="tl-op">);</span></span>
                <span class="tl-line"><span class="tl-ln">13</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-op">}</span></span>
                <span class="tl-line"><span class="tl-ln">14</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">return</span> <span class="tl-var">prediction</span><span class="tl-op">;</span></span>
                <span class="tl-line"><span class="tl-ln">15</span>&nbsp;&nbsp;<span class="tl-op">}</span></span>
                <span class="tl-line"><span class="tl-ln">16</span>&nbsp;</span>
                <span class="tl-line"><span class="tl-ln">17</span>&nbsp;&nbsp;<span class="tl-fn">detectAnomaly</span><span class="tl-op">(</span><span class="tl-var">motion</span><span class="tl-op">, </span><span class="tl-var">time</span><span class="tl-op">) {</span></span>
                <span class="tl-line"><span class="tl-ln">18</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tl-kw">return</span> <span class="tl-fn">alert</span><span class="tl-op">(</span><span class="tl-str">"security.arm"</span><span class="tl-op">);</span></span>
                <span class="tl-line"><span class="tl-ln">19</span>&nbsp;&nbsp;<span class="tl-op">}</span></span>
                <span class="tl-line"><span class="tl-ln">20</span><span class="tl-op">}</span></span>
            </div>
        </div>
        <div class="showcase-text reveal-right">
            <div class="section-badge sb-beige"><i class="fas fa-code"></i> Technológia</div>
            <h2>Profi <span class="grad-text-green">szoftvertechnológia</span> az otthonodban</h2>
            <p>Rendszerünk nyílt protokollokon alapul, valós idejű AI feldolgozással. Minden döntés &lt;50 ms alatt születik meg – a komfort és biztonság érdekében.</p>
            <p>Az automatizációs logika moduláris: bővíthető, frissíthető, és teljesen testre szabható az igényeid szerint.</p>
            <div class="tech-pill-grid">
                <span class="tech-pill">KNX</span>
                <span class="tech-pill">Z-Wave</span>
                <span class="tech-pill">MQTT</span>
                <span class="tech-pill">REST API</span>
                <span class="tech-pill">TensorFlow</span>
                <span class="tech-pill">Node.js</span>
                <span class="tech-pill">Python AI</span>
                <span class="tech-pill">WebSocket</span>
                <span class="tech-pill">Zigbee</span>
                <span class="tech-pill">Matter</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══ AI ═══ --}}
<section id="ai" style="padding:110px 48px;">
    <div class="orb" style="width:350px;height:350px;top:50%;left:-100px;transform:translateY(-50%);background:rgba(96,165,250,0.05);"></div>
    <div class="ai-visual reveal-left">
        <div class="ai-vis-title"><i class="fas fa-circle-nodes"></i> AI vezérlési folyamat</div>
        <div class="ai-node">
            <div class="ai-node-icon fi-green"><i class="fas fa-microchip"></i></div>
            <div><div class="ai-node-label">Szenzor adatgyűjtés</div><div class="ai-node-sub">Hőmérséklet, mozgás, fény, áram</div></div>
            <div class="ai-node-val" style="color:var(--green);">1 248 pont/perc</div>
        </div>
        <div class="ai-connector"></div>
        <div class="ai-node">
            <div class="ai-node-icon fi-beige"><i class="fas fa-brain"></i></div>
            <div><div class="ai-node-label">AI feldolgozás</div><div class="ai-node-sub">Mintafelismerés, predikció</div></div>
            <div class="ai-node-val" style="color:var(--beige);">&lt;50 ms</div>
        </div>
        <div class="ai-connector"></div>
        <div class="ai-node">
            <div class="ai-node-icon fi-green2"><i class="fas fa-sliders"></i></div>
            <div><div class="ai-node-label">Automatikus vezérlés</div><div class="ai-node-sub">Fűtés, klíma, redőny, riasztó</div></div>
            <div class="ai-node-val" style="color:var(--green2);">Optimalizálva</div>
        </div>
        <div class="ai-connector"></div>
        <div class="ai-node">
            <div class="ai-node-icon fi-green"><i class="fas fa-chart-line"></i></div>
            <div><div class="ai-node-label">Eredmény & Tanulás</div><div class="ai-node-sub">Visszacsatolás, finomítás</div></div>
            <div class="ai-node-val" style="color:var(--beige3);">–34% energia</div>
        </div>
    </div>
    <div class="reveal-right">
        <div class="section-badge sb-green2"><i class="fas fa-brain"></i> AI Technológia</div>
        <h2 class="section-title">Mesterséges intelligencia <span class="grad-text-green">a te otthonodban</span></h2>
        <p class="section-sub" style="margin-bottom:32px;">Nem csak automatizálunk – a rendszerünk tanul. Az AI minden nappal okosabbá válik.</p>
        <ul class="ai-feat-list">
            <li class="ai-feat-item">
                <div class="ai-feat-icon fi-green"><i class="fas fa-user-clock"></i></div>
                <div><div class="ai-feat-title">Viselkedési profil tanulás</div><div class="ai-feat-desc">Az AI megtanulja, mikor ébredsz, mikor térsz haza – minden automatikusan készen vár rád.</div></div>
            </li>
            <li class="ai-feat-item">
                <div class="ai-feat-icon fi-beige"><i class="fas fa-temperature-half"></i></div>
                <div><div class="ai-feat-title">Prediktív klíma vezérlés</div><div class="ai-feat-desc">Az időjárás és szokásaid alapján előre fűti vagy hűti a helyiségeket – felesleges fogyasztás nélkül.</div></div>
            </li>
            <li class="ai-feat-item">
                <div class="ai-feat-icon fi-green2"><i class="fas fa-triangle-exclamation"></i></div>
                <div><div class="ai-feat-title">Anomália érzékelés</div><div class="ai-feat-desc">Szokatlan mozgás vagy energiafogyasztás esetén azonnal értesítést kapsz – még mielőtt baj lenne.</div></div>
            </li>
            <li class="ai-feat-item">
                <div class="ai-feat-icon fi-green"><i class="fas fa-leaf"></i></div>
                <div><div class="ai-feat-title">Zöld energia optimalizálás</div><div class="ai-feat-desc">Napelemes rendszereknél az AI dönt, mikor tároljuk, mikor adjuk a hálózatra az energiát.</div></div>
            </li>
        </ul>
    </div>
</section>

{{-- ═══ MIÉRT MI ═══ --}}
<section id="miert-mi">
    <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto;">
        <div class="section-badge sb-beige"><i class="fas fa-star"></i> Rólunk</div>
        <h2 class="section-title">Miért a <span class="grad-text-green">TRITON SECURITY</span>?</h2>
        <p class="section-sub" style="margin:0 auto;">10+ éves tapasztalat, több száz sikeres projekt és egy dedikált csapat minden ügyfélnek.</p>
    </div>
    <div class="why-grid">
        <div class="why-card wc-1 reveal delay-1">
            <div class="why-num grad-text-green"><span class="count-up" data-target="500">0</span>+</div>
            <div class="why-label">Telepített rendszer</div>
            <div class="why-desc">Lakásoktól irodákon át ipari létesítményekig minden méretben.</div>
        </div>
        <div class="why-card wc-2 reveal delay-2">
            <div class="why-num grad-text-beige"><span class="count-up" data-target="98">0</span>%</div>
            <div class="why-label">Ügyfél elégedettség</div>
            <div class="why-desc">Folyamatos visszajelzés és utógondozás az eredmény garanciája.</div>
        </div>
        <div class="why-card wc-3 reveal delay-3">
            <div class="why-num grad-text-green" style="font-family:var(--mono)">24/7</div>
            <div class="why-label">Support elérhetőség</div>
            <div class="why-desc">Telefonon, emailben és helyszínen is rendelkezésre állunk.</div>
        </div>
        <div class="why-card wc-4 reveal delay-4">
            <div class="why-num" style="background:linear-gradient(90deg,var(--beige),var(--beige2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-family:var(--mono);">–34%</div>
            <div class="why-label">Átlag energiamegtakarítás</div>
            <div class="why-desc">Ügyfeleink átlagosan 34%-kal alacsonyabb energiaszámlát fizetnek.</div>
        </div>
    </div>
</section>

{{-- ═══ FOLYAMAT ═══ --}}
<section id="folyamat">
    <div class="orb" style="width:400px;height:400px;bottom:-100px;right:-100px;background:rgba(201,169,122,0.04);"></div>
    <div class="reveal" style="text-align:center;max-width:560px;margin:0 auto;">
        <div class="section-badge sb-green"><i class="fas fa-route"></i> Hogyan dolgozunk</div>
        <h2 class="section-title">4 lépés a tökéletes <span class="grad-text-green">okos otthonig</span></h2>
    </div>
    <div class="process-track">
        <div class="process-step reveal delay-1">
            <div class="process-num pn-1">01</div>
            <div class="process-step-title">Ingyenes felmérés</div>
            <div class="process-step-desc">Szakértőnk helyszínen felméri az igényeidet és az ingatlan adottságait.</div>
        </div>
        <div class="process-step reveal delay-2">
            <div class="process-num pn-2">02</div>
            <div class="process-step-title">Testre szabott ajánlat</div>
            <div class="process-step-desc">Részletes árajánlatot és rendszertervet készítünk, 3 munkanapon belül.</div>
        </div>
        <div class="process-step reveal delay-3">
            <div class="process-num pn-3">03</div>
            <div class="process-step-title">Profi telepítés</div>
            <div class="process-step-desc">Csapatunk minimális zavarással, pontosan és precízen végzi el a munkát.</div>
        </div>
        <div class="process-step reveal delay-4">
            <div class="process-num pn-4">04</div>
            <div class="process-step-title">Betanítás & Support</div>
            <div class="process-step-desc">Megmutatjuk hogyan használd, és mindig elérhetők vagyunk ha kérdés merül fel.</div>
        </div>
    </div>
</section>

{{-- ═══ KAPCSOLAT ═══ --}}
<section id="kapcsolat" style="padding:110px 48px;">
    <div class="reveal-left">
        <div class="section-badge sb-beige"><i class="fas fa-envelope"></i> Kapcsolat</div>
        <h2 class="section-title">Vedd fel <span class="grad-text-green">velünk a kapcsolatot</span></h2>
        <p class="section-sub" style="margin-bottom:36px;">Ingyenes helyszíni felmérést kínálunk. Egy rövid egyeztetés után már tudni fogod, mit hozhat az okos otthon.</p>
        <div class="contact-item">
            <div class="contact-icon fi-green"><i class="fas fa-location-dot"></i></div>
            <div><div class="contact-label">Székhely</div><div class="contact-val">1234 Budapest, Minta utca 1.</div></div>
        </div>
        <div class="contact-item">
            <div class="contact-icon fi-green2"><i class="fas fa-phone"></i></div>
            <div><div class="contact-label">Telefon</div><div class="contact-val">+36 1 234 5678</div></div>
        </div>
        <div class="contact-item">
            <div class="contact-icon fi-beige"><i class="fas fa-envelope"></i></div>
            <div><div class="contact-label">Email</div><div class="contact-val">info@tritonsecurity.hu</div></div>
        </div>
        <div class="contact-item">
            <div class="contact-icon fi-green"><i class="fas fa-clock"></i></div>
            <div><div class="contact-label">Nyitvatartás</div><div class="contact-val">H–P: 8:00–17:00</div></div>
        </div>
    </div>
    <div class="reveal-right">
        <div class="cta-box">
            <h3>Kérj ingyenes felmérést!</h3>
            <p>Nincs kötelezettség. Szakértőnk felkeresi az ingatlant, felméri az igényeket és teljesen díjmentesen elkészíti a személyre szabott ajánlatot.</p>
            <a href="mailto:info@tritonsecurity.hu" class="btn-primary" style="display:inline-flex;margin-bottom:12px;">
                <span><i class="fas fa-envelope"></i></span><span>Emailt küldök</span>
            </a>
            <br>
            <a href="tel:+3612345678" class="btn-outline" style="display:inline-flex;margin-top:8px;">
                <i class="fas fa-phone"></i> Felhívom most
            </a>
            <a href="{{ route('login') }}" class="cta-login">
                <i class="fas fa-user-shield"></i> Bejelentkezés az ügyfélkapuba →
            </a>
        </div>
    </div>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer>
    <div>
        <div class="footer-copy">© {{ date('Y') }} <span>TRITON SECURITY KFT.</span> — Minden jog fenntartva.</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Adószám: 12345678-2-42 · Cégjegyzékszám: 01-09-123456</div>
        <div class="footer-mono">01010100 01010010 01001001 01010100 01001111 01001110</div>
    </div>
    <div class="footer-links">
        <a href="#szolgaltatasok">Szolgáltatások</a>
        <a href="#ai">AI</a>
        <a href="#kapcsolat">Kapcsolat</a>
        <a href="{{ route('login') }}">Ügyfélkapu</a>
    </div>
</footer>

<script>
// ── PARTICLES (circuit green + beige) ─────────────────
const canvas = document.getElementById('particles-canvas');
const ctx = canvas.getContext('2d');
let particles = [];
function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
resize(); window.addEventListener('resize', resize);
const COLORS = [
    'rgba(96,165,250,',
    'rgba(59,130,246,',
    'rgba(201,169,122,',
    'rgba(147,197,253,'
];
for (let i = 0; i < 55; i++) {
    particles.push({
        x: Math.random() * window.innerWidth,
        y: Math.random() * window.innerHeight,
        r: Math.random() * 1.5 + 0.3,
        dx: (Math.random() - 0.5) * 0.35,
        dy: (Math.random() - 0.5) * 0.35,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
        alpha: Math.random() * 0.45 + 0.08
    });
}
function drawParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => {
        p.x += p.dx; p.y += p.dy;
        if (p.x < 0) p.x = canvas.width;
        if (p.x > canvas.width) p.x = 0;
        if (p.y < 0) p.y = canvas.height;
        if (p.y > canvas.height) p.y = 0;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = p.color + p.alpha + ')';
        ctx.fill();
    });
    for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 110) {
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                // Circuit-like blue lines
                ctx.strokeStyle = 'rgba(96,165,250,' + (0.07 * (1 - dist/110)) + ')';
                ctx.lineWidth = 0.6;
                ctx.stroke();
            }
        }
    }
    requestAnimationFrame(drawParticles);
}
drawParticles();

// ── FLOATING BINARY & CODE DECORATIONS ────────────────
(function spawnDecorations() {
    const layer = document.getElementById('decoLayer');
    const binStrings = [
        '01001000 01100001 01111010',
        '10110011 00101010',
        '01010100 01010010',
        '00110101 11001001',
        '11010010 00110101',
        '01001001 01000001',
        '10100110 01010011',
        '01110100 00101111',
        '00110001 10101010',
        '11001100 01010101',
    ];
    const codeStrings = [
        'if(motion.detected) { alarm.trigger(); }',
        'sensor.temp = 21.4°C // optimal',
        'energy.save(profile="eco") → –34%',
        'ai.predict(behavior, time) → OK',
        'security.arm("night_mode");',
        'hvac.setPoint(22, "celsius");',
        'solar.export(kWh=3.2) → grid',
        'auth.verify(token) → granted',
        'mqtt.publish("home/light/on");',
        'anomaly.score = 0.02 // safe',
    ];

    // Spawn binary columns
    for (let i = 0; i < 18; i++) {
        const el = document.createElement('div');
        el.className = 'bin-float';
        el.textContent = binStrings[Math.floor(Math.random() * binStrings.length)];
        el.style.left   = (Math.random() * 95) + '%';
        el.style.top    = (Math.random() * 100) + '%';
        const dur = 12 + Math.random() * 20;
        const delay = Math.random() * 15;
        el.style.animationDuration  = dur + 's';
        el.style.animationDelay     = delay + 's';
        el.style.fontSize = (9 + Math.random() * 4) + 'px';
        el.style.opacity = '0';
        // Some in beige
        if (Math.random() > 0.6) el.style.color = 'rgba(201,169,122,0.7)';
        layer.appendChild(el);
    }

    // Spawn code snippets
    for (let i = 0; i < 10; i++) {
        const el = document.createElement('div');
        el.className = 'code-snippet';
        el.textContent = codeStrings[i % codeStrings.length];
        el.style.left  = (5 + Math.random() * 80) + '%';
        el.style.top   = (10 + Math.random() * 80) + '%';
        const dur = 8 + Math.random() * 12;
        const delay = Math.random() * 20;
        el.style.animationDuration = dur + 's';
        el.style.animationDelay    = delay + 's';
        el.style.opacity = '0';
        layer.appendChild(el);
    }
})();

// ── NAVBAR SCROLL ──────────────────────────────────────
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// ── DROPDOWN (CLICK) ──────────────────────────────────
function toggleDropdown(e) {
    e.stopPropagation();
    const dd = document.getElementById('navDropdown');
    dd.classList.toggle('open');
}
document.getElementById('dropdownMenu').addEventListener('click', function(e) {
    e.stopPropagation();
});
document.addEventListener('click', function(e) {
    if (!e.target.closest('#navDropdown')) {
        document.getElementById('navDropdown').classList.remove('open');
    }
});

// ── SCROLL REVEAL ─────────────────────────────────────
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.12 });
revealEls.forEach(el => observer.observe(el));

// ── COUNT UP ──────────────────────────────────────────
function animateCount(el) {
    const target = +el.dataset.target;
    const duration = 1800;
    const start = performance.now();
    function step(now) {
        const t = Math.min((now - start) / duration, 1);
        el.textContent = Math.floor(t * target);
        if (t < 1) requestAnimationFrame(step);
        else el.textContent = target;
    }
    requestAnimationFrame(step);
}
const countObserver = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animateCount(e.target);
            countObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.count-up').forEach(el => countObserver.observe(el));
</script>
</body>
</html>
