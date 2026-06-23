<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $school->school_name ?? 'School Management System' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f0f4f8; overflow-x: hidden; padding-top: 110px; }
        
        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        
        .animate-on-scroll { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .animate-on-scroll.visible { opacity: 1; transform: translateY(0); }
        
        /* ============================================
           TOP BAR - FIXED
           ============================================ */
        .top-bar {
            background: linear-gradient(135deg, #0f1724, #1a2332);
            color: #e2e8f0;
            padding: 8px 0;
            font-size: 0.7rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .top-bar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .top-bar .contact-info { display: flex; gap: 18px; flex-wrap: wrap; }
        .top-bar .contact-info span { display: flex; align-items: center; gap: 6px; transition: all 0.3s; }
        .top-bar .contact-info span:hover { color: #fbbf24; }
        .top-bar .contact-info i { color: #fbbf24; font-size: 0.65rem; }
        .top-bar .login-buttons { display: flex; gap: 10px; }
        .top-bar .login-buttons a {
            color: white;
            text-decoration: none;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.3px;
        }
        .top-bar .login-buttons a:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .top-bar .login-buttons a.parent-btn { background: #fbbf24; border-color: #fbbf24; color: #0f1724; }
        .top-bar .login-buttons a.parent-btn:hover { background: #f59e0b; border-color: #f59e0b; }
        .top-bar .login-buttons a.admin-btn { background: rgba(255,255,255,0.05); }
        .top-bar .login-buttons a.admin-btn:hover { background: rgba(255,255,255,0.12); }
        
        /* ============================================
           HEADER - FIXED
           ============================================ */
        .main-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 30px rgba(0,0,0,0.06);
            padding: 10px 0;
            position: fixed;
            top: 38px;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .main-header .container {
            max-width: 1200px;
            /*margin-top: 20px;*/
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .main-header .logo { display: flex; align-items: center; gap: 14px; }
        .main-header .logo img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #fbbf24; padding: 2px; }
        .main-header .logo .logo-text h1 { 
            font-size: 1.2rem; font-weight: 800; color: #0f1724;
            background: linear-gradient(135deg, #0f1724, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .main-header .logo .logo-text h1 i { color: #fbbf24; -webkit-text-fill-color: #fbbf24; }
        .main-header .logo .logo-text span { font-size: 0.55rem; color: #64748b; font-weight: 500; letter-spacing: 0.5px; }
        .main-nav { display: flex; gap: 28px; align-items: center; }
        .main-nav a { text-decoration: none; color: #1e293b; font-size: 0.8rem; font-weight: 500; transition: all 0.3s; padding: 5px 0; border-bottom: 2px solid transparent; position: relative; }
        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #fbbf24;
            transition: all 0.3s;
        }
        .main-nav a:hover::after, .main-nav a.active::after { width: 100%; }
        .main-nav a:hover { color: #0f1724; }
        .main-nav a.active { color: #0f1724; font-weight: 600; }
        .mobile-toggle { display: none; background: none; border: none; font-size: 1.5rem; color: #0f1724; cursor: pointer; transition: all 0.3s; }
        .mobile-toggle:hover { color: #fbbf24; }
        
        /* ============================================
           HEADER SCROLL EFFECT
           ============================================ */
        .main-header.scrolled {
            background: rgba(255,255,255,0.98);
            box-shadow: 0 4px 40px rgba(0,0,0,0.08);
            padding: 6px 0;
        }
        .main-header.scrolled .logo img { width: 40px; height: 40px; }
        .main-header.scrolled .logo .logo-text h1 { font-size: 1rem; }
        
        /* ============================================
           HERO
           ============================================ */
        .hero {
            background: linear-gradient(135deg, #0f1724 0%, #1a2332 40%, #2d4a7a 100%);
            color: white;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(251,191,36,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .hero-content h1 { 
            font-size: 3rem; font-weight: 900; line-height: 1.1; margin-bottom: 18px; 
            background: linear-gradient(135deg, #ffffff 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-content h1 span { color: #fbbf24; -webkit-text-fill-color: #fbbf24; }
        .hero-content p { font-size: 1.05rem; opacity: 0.85; margin-bottom: 25px; line-height: 1.8; color: #cbd5e1; }
        .hero-content .hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; }
        .hero-content .hero-buttons a {
            padding: 12px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .hero-content .hero-buttons .btn-primary { 
            background: linear-gradient(135deg, #fbbf24, #f59e0b); 
            color: #0f1724; 
            box-shadow: 0 4px 20px rgba(251,191,36,0.3);
        }
        .hero-content .hero-buttons .btn-primary:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 35px rgba(251,191,36,0.4); 
        }
        .hero-content .hero-buttons .btn-secondary { 
            background: rgba(255,255,255,0.08); 
            color: white; 
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
        }
        .hero-content .hero-buttons .btn-secondary:hover { 
            background: rgba(255,255,255,0.15); 
            transform: translateY(-3px); 
        }
        .hero-image { display: flex; justify-content: center; align-items: center; }
        .hero-image .hero-icon { 
            font-size: 8rem; 
            color: #fbbf24; 
            animation: float 4s ease-in-out infinite;
            filter: drop-shadow(0 10px 40px rgba(251,191,36,0.2));
        }
        
        /* ============================================
           SECTION
           ============================================ */
        .section { padding: 60px 0; }
        .section .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .section-title { text-align: center; margin-bottom: 40px; }
        .section-title .subtitle { 
            display: inline-block;
            color: #fbbf24;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: rgba(251,191,36,0.1);
            padding: 4px 16px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .section-title h2 { 
            font-size: 2.2rem; 
            font-weight: 800; 
            color: #0f1724; 
            display: inline-block; 
            position: relative; 
        }
        .section-title h2::after { 
            content: ''; 
            position: absolute; 
            bottom: -10px; 
            left: 50%; 
            transform: translateX(-50%); 
            width: 60px; 
            height: 4px; 
            background: linear-gradient(90deg, #fbbf24, #f59e0b); 
            border-radius: 2px; 
        }
        .section-title p { color: #64748b; margin-top: 18px; font-size: 0.95rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        
        .about-section { background: white; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-grid .about-image {
            background: linear-gradient(135deg, #0f1724, #2d4a7a);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            color: white;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .about-grid .about-image::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(251,191,36,0.08) 0%, transparent 70%);
        }
        .about-grid .about-image i { font-size: 4rem; color: #fbbf24; margin-bottom: 15px; position: relative; z-index: 1; }
        .about-grid .about-image h3 { font-size: 1.5rem; position: relative; z-index: 1; }
        .about-grid .about-image p { font-size: 0.85rem; opacity: 0.8; position: relative; z-index: 1; }
        .about-grid .about-text h3 { font-size: 1.5rem; color: #0f1724; margin-bottom: 12px; }
        .about-grid .about-text p { color: #475569; line-height: 1.9; font-size: 0.9rem; }
        .about-grid .about-text .about-features { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }
        .about-grid .about-text .about-features span { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            font-size: 0.8rem; 
            color: #1e293b; 
            font-weight: 500;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .about-grid .about-text .about-features span:hover { background: #f1f5f9; transform: translateX(3px); }
        .about-grid .about-text .about-features span i { color: #fbbf24; font-size: 0.9rem; }
        
        .achievements-section { background: #f8fafc; }
        .achievements-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .achievement-card {
            background: white;
            padding: 25px 20px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .achievement-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 12px 40px rgba(0,0,0,0.06); 
            border-color: #fbbf24;
        }
        .achievement-card .icon { 
            font-size: 2.2rem; 
            color: #fbbf24; 
            margin-bottom: 8px;
            display: block;
        }
        .achievement-card .number { 
            font-size: 2.5rem; 
            font-weight: 900; 
            color: #0f1724; 
            background: linear-gradient(135deg, #0f1724, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .achievement-card .label { font-size: 0.75rem; color: #64748b; font-weight: 500; }
        
        /* ============================================
           NOTICES
           ============================================ */
        .notices-section { background: white; }
        .notices-list { display: flex; flex-direction: column; gap: 14px; }
        .notice-item {
            padding: 16px 22px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #fbbf24;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .notice-item:hover {
            background: #f1f5f9;
            transform: translateX(6px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .notice-item .notice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .notice-item .date {
            font-size: 0.6rem;
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .notice-item .date i { color: #3b82f6; }
        .notice-item .badge {
            background: #dcfce7;
            color: #15803d;
            padding: 2px 14px;
            border-radius: 20px;
            font-size: 0.5rem;
            font-weight: 600;
        }
        .notice-item .notice-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f1724;
            margin-bottom: 3px;
        }
        .notice-item .notice-title i { color: #fbbf24; margin-right: 8px; }
        .notice-item .notice-desc {
            font-size: 0.75rem;
            color: #64748b;
            line-height: 1.7;
            padding-left: 28px;
        }
        
        .gallery-section { background: #f8fafc; }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }
        .gallery-item {
            border-radius: 14px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 1/1;
            background: #0f1724;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .gallery-item:hover {
            transform: scale(1.04);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s;
        }
        .gallery-item:hover img { transform: scale(1.05); }
        .gallery-item .placeholder {
            color: white;
            font-size: 2.5rem;
            opacity: 0.3;
        }
        .gallery-item .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 12px;
            background: linear-gradient(transparent, rgba(0,0,0,0.75));
            color: white;
            font-size: 0.65rem;
            text-align: center;
            font-weight: 500;
            transform: translateY(100%);
            transition: all 0.3s;
        }
        .gallery-item:hover .gallery-overlay { transform: translateY(0); }
        
        .contact-section { background: white; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
        .contact-info-card { display: flex; flex-direction: column; gap: 14px; }
        .contact-info-card .info-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 18px;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .contact-info-card .info-item:hover { background: #f1f5f9; transform: translateX(4px); }
        .contact-info-card .info-item i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0f1724, #2d4a7a);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .contact-info-card .info-item .text { font-size: 0.8rem; }
        .contact-info-card .info-item .text strong { display: block; font-size: 0.65rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
        .contact-form {
            background: #f8fafc;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }
        .contact-form .form-group { margin-bottom: 12px; }
        .contact-form .form-group input,
        .contact-form .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.8rem;
            font-family: inherit;
            transition: all 0.3s;
            background: white;
        }
        .contact-form .form-group input:focus,
        .contact-form .form-group textarea:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 4px rgba(251,191,36,0.08);
        }
        .contact-form .form-group textarea { min-height: 90px; resize: vertical; }
        .contact-form .btn-submit {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #0f1724;
            border: none;
            padding: 10px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(251,191,36,0.2);
        }
        .contact-form .btn-submit:hover { 
            background: #f59e0b; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 30px rgba(251,191,36,0.3);
        }
        
        .footer {
            background: #0f1724;
            color: white;
            padding: 40px 0 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .footer .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .footer-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer-grid h4 { 
            font-size: 0.9rem; 
            font-weight: 700; 
            margin-bottom: 14px; 
            color: #fbbf24;
            letter-spacing: 0.5px;
        }
        .footer-grid p, .footer-grid a { 
            font-size: 0.75rem; 
            color: #94a3b8; 
            line-height: 2; 
            text-decoration: none; 
            display: block; 
            transition: all 0.3s; 
        }
        .footer-grid a:hover { color: #fbbf24; padding-left: 4px; }
        .footer-grid .social-links { display: flex; gap: 10px; }
        .footer-grid .social-links a {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 0.9rem;
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .footer-grid .social-links a:hover { 
            background: #fbbf24; 
            color: #0f1724; 
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(251,191,36,0.2);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 16px;
            text-align: center;
            font-size: 0.7rem;
            color: #64748b;
        }
        .footer-bottom .heart { color: #ef4444; }
        
        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .hero .container { grid-template-columns: 1fr; text-align: center; }
            .hero-content .hero-buttons { justify-content: center; }
            .about-grid { grid-template-columns: 1fr; }
            .contact-grid { grid-template-columns: 1fr; }
            .achievements-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            body { padding-top: 100px; }
            .top-bar .container { flex-direction: column; align-items: center; text-align: center; }
            .top-bar .contact-info { justify-content: center; }
            .top-bar .login-buttons { justify-content: center; }
            .mobile-toggle { display: block; }
            .main-nav { display: none; width: 100%; flex-direction: column; gap: 8px; padding: 14px 0; border-top: 1px solid #e2e8f0; }
            .main-nav.open { display: flex; }
            .hero-content h1 { font-size: 2.2rem; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .achievements-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .about-grid .about-text .about-features { grid-template-columns: 1fr; }
            .main-header { top: 65px; }
            .hero { padding: 50px 0 40px; }
            .section { padding: 40px 0; }
            .section-title h2 { font-size: 1.8rem; }
        }
        @media (max-width: 480px) {
            body { padding-top: 120px; }
            .achievement-card{  padding: 9px 10px;}
            .hero-content h1 { font-size: 1.7rem; }
            .hero-content .hero-buttons { flex-direction: column; align-items: center; width: 100%; }
            .hero-content .hero-buttons a { width: 100%; justify-content: center; }
            .achievements-grid { grid-template-columns: 1fr; }
            .gallery-grid { grid-template-columns: 1fr 1fr; }
            .main-header .logo .logo-text h1 { font-size: 0.9rem; }
            .main-header .logo img { width: 35px; height: 35px; }
            .main-header { top: 60px; padding: 6px 0; }
            .top-bar { font-size: 0.55rem; }
            .top-bar .login-buttons a { font-size: 0.55rem; padding: 2px 10px; }
            .hero { padding: 30px 0 25px; margin-top: 15px; }
            .notice-item { padding: 10px 14px; }
            .notice-item .notice-title { font-size: 0.8rem; }
            .notice-item .notice-desc { font-size: 0.65rem; padding-left: 0; }
            .notice-item .notice-header { flex-direction: row; align-items: center; }
            .section-title h2 { font-size: 1.5rem; }
            .contact-form { padding: 16px; }
            .footer-grid { text-align: center; }
            .footer-grid .social-links { justify-content: center; }
            .hero-image .hero-icon { font-size: 5rem; }
        }
        @media (max-width: 380px) {
            .achievement-card{  padding: 9px 10px;}
            .about-grid .about-image{padding: 10px;}
            body { padding-top: 110px; }
            .hero-content h1 { font-size: 1.4rem; }
            .gallery-grid { grid-template-columns: 1fr; }
            .top-bar .contact-info span { font-size: 0.5rem; }
        }
    </style>
</head>
<body>
    <!-- ===== TOP BAR - FIXED ===== -->
    <div class="top-bar">
        <div class="container">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> 1234567890</span>
                <span><i class="fas fa-envelope"></i> school@gmail.com</span>
                <span><i class="fas fa-map-marker-alt"></i> Agra Road New Delhi</span>
            </div>
            <div class="login-buttons">
                <a href="{{ route('parent.login') }}" class="parent-btn"><i class="fas fa-user-graduate"></i> Parent Login</a>
                <a href="{{ route('login') }}" class="admin-btn"><i class="fas fa-user-shield"></i> Admin Login</a>
            </div>
        </div>
    </div>

    <!-- ===== HEADER - FIXED ===== -->
    <header class="main-header" id="mainHeader">
        <div class="container">
            <div class="logo">
                @if($school && $school->logo_1)
                    <img src="{{ asset('storage/' . $school->logo_1) }}" alt="Logo">
                @else
                    <div style="width:48px; height:48px; background:linear-gradient(135deg,#0f1724,#2d4a7a); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:1.3rem; border:2px solid #fbbf24;">
                        <i class="fas fa-school"></i>
                    </div>
                @endif
                <div class="logo-text">
                    <h1>{{ $school->school_name ?? 'School Management' }} <i class="fas fa-graduation-cap"></i></h1>
                    <span>{{ $school->tagline ?? 'Excellence in Education' }}</span>
                </div>
            </div>
            <button class="mobile-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="main-nav" id="mainNav">
                <a href="#home" class="active">Home</a>
                <a href="#about">About</a>
                <a href="#achievements">Achievements</a>
                <a href="#notices">Notices</a>
                <a href="#gallery">Gallery</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to <br><span>{{ $school->school_name ?? 'School Management System' }}</span></h1>
                <p>{{ $school->description ?? 'Empowering young minds with quality education and holistic development.' }}</p>
                <div class="hero-buttons">
                    <a href="#about" class="btn-primary"><i class="fas fa-info-circle"></i> Learn More</a>
                    <a href="#contact" class="btn-secondary"><i class="fas fa-phone"></i> Contact Us</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT ===== -->
    <section class="section about-section" id="about">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <span class="subtitle">About Us</span>
                <h2>About Our School</h2>
                <p>Learn more about our institution and values</p>
            </div>
            <div class="about-grid">
                <div class="about-image animate-on-scroll">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Since {{ $school->established_year ?? '2000' }}</h3>
                    <p>Building the future, one student at a time</p>
                </div>
                <div class="about-text animate-on-scroll">
                    <h3>{{ $school->about_title ?? 'Excellence in Education' }}</h3>
                    <p>{{ $school->about_description ?? 'Our school is committed to providing quality education that nurtures intellectual curiosity, creativity, and character development.' }}</p>
                    <div class="about-features">
                        <span><i class="fas fa-check-circle"></i> Quality Education</span>
                        <span><i class="fas fa-check-circle"></i> Experienced Faculty</span>
                        <span><i class="fas fa-check-circle"></i> Modern Infrastructure</span>
                        <span><i class="fas fa-check-circle"></i> Holistic Development</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ACHIEVEMENTS ===== -->
    <section class="section achievements-section" id="achievements">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <span class="subtitle">Achievements</span>
                <h2>Our Achievements</h2>
                <p>Milestones that define our journey</p>
            </div>
            <div class="achievements-grid">
                <div class="achievement-card animate-on-scroll">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <div class="number">500+</div>
                    <div class="label">Students Enrolled</div>
                </div>
                <div class="achievement-card animate-on-scroll">
                    <span class="icon"><i class="fas fa-trophy"></i></span>
                    <div class="number">50+</div>
                    <div class="label">Awards Won</div>
                </div>
                <div class="achievement-card animate-on-scroll">
                    <span class="icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <div class="number">30+</div>
                    <div class="label">Expert Teachers</div>
                </div>
                <div class="achievement-card animate-on-scroll">
                    <span class="icon"><i class="fas fa-calendar-check"></i></span>
                    <div class="number">99%</div>
                    <div class="label">Passing Percentage</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== NOTICES ===== -->
    <section class="section notices-section" id="notices">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <span class="subtitle">Updates</span>
                <h2>Latest Notices</h2>
                <p>Stay updated with school announcements</p>
            </div>
            <div class="notices-list">
                @if($notices->count() > 0)
                    @foreach($notices as $notice)
                    <div class="notice-item animate-on-scroll">
                        <div class="notice-header">
                            <span class="date"><i class="fas fa-calendar-day"></i> {{ $notice->notice_date ? date('d-m-Y', strtotime($notice->notice_date)) : '-' }}</span>
                            <span class="badge">New</span>
                        </div>
                        <div class="notice-title"><i class="fas fa-bullhorn"></i> {{ $notice->title }}</div>
                        @if($notice->description)
                            <div class="notice-desc">{{ Str::limit($notice->description, 120) }}</div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="notice-item" style="justify-content:center; border-left-color:#94a3b8;">
                        <span style="color:#94a3b8; font-size:0.8rem;">No notices available</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ===== GALLERY ===== -->
    <section class="section gallery-section" id="gallery">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <span class="subtitle">Moments</span>
                <h2>Our Gallery</h2>
                <p>Glimpses of our school life</p>
            </div>
            @if($galleries->count() > 0)
            <div class="gallery-grid animate-on-scroll">
                @foreach($galleries as $gallery)
                <div class="gallery-item" onclick="viewGalleryImage('{{ $gallery->title }}', '{{ asset('storage/' . $gallery->image) }}')">
                    @if($gallery->image)
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}">
                    @else
                        <div class="placeholder"><i class="fas fa-image"></i></div>
                    @endif
                    <div class="gallery-overlay">{{ Str::limit($gallery->title, 20) }}</div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align:center; padding:40px; color:#94a3b8;">
                <i class="fas fa-images" style="font-size:2.5rem; display:block; margin-bottom:12px;"></i>
                No gallery images available
            </div>
            @endif
        </div>
    </section>

    <!-- ===== CONTACT ===== -->
    <section class="section contact-section" id="contact">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <span class="subtitle">Get in Touch</span>
                <h2>Contact Us</h2>
                <p>We would love to hear from you</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info-card animate-on-scroll">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="text"><strong>Address</strong>Agra Road New Delhi</div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div class="text"><strong>Phone</strong>1234567890</div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="text"><strong>Email</strong>school@gmail.com</div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div class="text"><strong>Office Hours</strong>Mon - Fri: 8:00 AM - 3:00 PM</div>
                    </div>
                </div>
                <div class="contact-form animate-on-scroll">
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" id="q_name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="q_email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="q_mobile" placeholder="Mobile Number" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="q_subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea id="q_message" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4><i class="fas fa-school"></i> {{ $school->school_name ?? 'School Management' }}</h4>
                    <p>Agra Road New Delhi</p>
                    <p><i class="fas fa-phone"></i> 1234567890</p>
                    <p><i class="fas fa-envelope"></i> school@gmail.com</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <a href="#home">Home</a>
                    <a href="#about">About Us</a>
                    <a href="#achievements">Achievements</a>
                    <a href="#notices">Notices</a>
                    <a href="#gallery">Gallery</a>
                    <a href="#contact">Contact</a>
                </div>
                <div>
                    <h4>Resources</h4>
                    <a href="{{ route('parent.login') }}">Parent Login</a>
                    <a href="{{ route('login') }}">Admin Login</a>
                    <a href="#contact">Admissions</a>
                    
                </div>
                <div>
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <p style="margin-top:12px;"><i class="fas fa-calendar-alt"></i> Session: {{ $sessionName ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} {{ $school->school_name ?? 'School Management System' }}. All Rights Reserved.
                <span style="display:block; font-size:0.55rem; color:#64748b; margin-top:4px;">
                    Developed with <span class="heart"><i class="fas fa-heart"></i></span> by Your Team
                </span>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('mainNav').classList.toggle('open');
        }
        document.querySelectorAll('.main-nav a').forEach(link => {
            link.addEventListener('click', () => document.getElementById('mainNav').classList.remove('open'));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });

        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.main-nav a');
            
            sections.forEach(section => {
                const top = section.offsetTop - 150;
                const bottom = top + section.offsetHeight;
                const scroll = window.scrollY;
                
                if (scroll >= top && scroll < bottom) {
                    const id = section.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });

        function viewGalleryImage(title, imageUrl) {
            Swal.fire({
                title: title,
                imageUrl: imageUrl,
                imageWidth: 400,
                imageHeight: 300,
                imageAlt: title,
                confirmButtonColor: '#fbbf24',
                confirmButtonText: 'Close'
            });
        }

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                name: document.getElementById('q_name').value,
                email: document.getElementById('q_email').value,
                mobile: document.getElementById('q_mobile').value,
                subject: document.getElementById('q_subject').value,
                message: document.getElementById('q_message').value,
                _token: '{{ csrf_token() }}'
            };
            
            Swal.fire({
                title: 'Sending...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            fetch('{{ route("home.query.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.reset();
                } else {
                    Swal.fire('Error!', response.message || 'Something went wrong', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong!', 'error');
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>