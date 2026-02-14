<?php
    require __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../env_data.php';
    require_once __DIR__ . '/../util/functions.php';

    $client = new Google\Client;
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile");

    $url = $client->createAuthUrl();
    
    // Check if user is logged in
    $isLoggedIn = isset($_COOKIE['session_token']) && $_COOKIE['session_token'] !== '';
    $buttonUrl = $isLoggedIn ? '/pages/dashboard.php' : $url;
    $buttonText = $isLoggedIn ? 'Accéder à votre profil' : 'Se connecter avec Google';
    $showGoogleIcon = !$isLoggedIn;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Universon transforme votre collection musicale en une exposition artistique. Créez votre musée musical personnel et partagez vos albums préférés comme des œuvres d'art.">
    <meta name="keywords" content="universon, musique, collection musicale, exposition artistique, albums, musée musical, galerie musicale">
    <meta name="author" content="Universon">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $site_url ?>">
    <meta property="og:title" content="Universon - Exposez vos albums comme des œuvres d'art">
    <meta property="og:description" content="Transformez votre collection musicale en une exposition artistique et créez votre musée musical personnel.">
    <meta property="og:site_name" content="Universon">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= $site_url ?>">
    <meta name="twitter:title" content="Universon - Exposez vos albums comme des œuvres d'art">
    <meta name="twitter:description" content="Transformez votre collection musicale en une exposition artistique et créez votre musée musical personnel.">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    
    <title>Universon — Votre Musée Musical Personnel</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/img/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        /* Museum Color Palette */
        :root {
            /* Primaires */
            --museum-ink: #0A0E1A;
            --deep-slate: #1A1F2E;
            --gallery-ivory: #F5F1E8;
            
            /* Accents */
            --artistic-copper: #C87941;
            --artistic-copper-light: #D68A54;
            --imperial-rose: #A6416D;
            
            /* Neutres */
            --warm-light-gray: #C4BCAE;
            --balanced-gray: #8B8679;
            --cool-dark-gray: #3D4354;
            
            /* Utilité */
            --white-pure: #FFFFFF;
        }

        /* Override body background */
        body {
            background: linear-gradient(135deg, var(--museum-ink) 0%, var(--deep-slate) 100%);
        }

        /* Landing Page Specific Styles */
        .landing-hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: var(--space-3xl) var(--space-md);
            position: relative;
            background: radial-gradient(ellipse at center, rgba(26, 31, 46, 0.4) 0%, transparent 70%);
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            z-index: 10;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-lg);
            background: rgba(166, 65, 109, 0.15);
            border: 1px solid rgba(166, 65, 109, 0.3);
            border-radius: 50px;
            color: var(--gallery-ivory);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: var(--space-xl);
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
            color: var(--imperial-rose);
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: var(--space-lg);
            background: linear-gradient(135deg, var(--gallery-ivory) 0%, var(--warm-light-gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.5rem);
            color: var(--warm-light-gray);
            line-height: 1.6;
            margin-bottom: var(--space-2xl);
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .hero-cta {
            display: flex;
            gap: var(--space-md);
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }

        .btn-hero {
            padding: var(--space-lg) var(--space-2xl);
            font-size: 1.1rem;
            font-weight: 600;
            min-height: 56px;
            box-shadow: 0 8px 24px rgba(200, 121, 65, 0.25),
                        0 2px 6px rgba(10, 14, 26, 0.3);
            border-radius: var(--radius-lg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-hero:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 36px rgba(200, 121, 65, 0.35),
                        0 4px 12px rgba(10, 14, 26, 0.4);
        }

        .btn-google-landing {
            background: var(--gallery-ivory);
            color: var(--museum-ink);
            display: inline-flex;
            align-items: center;
            gap: var(--space-md);
            border: 1px solid rgba(200, 121, 65, 0.15);
        }

        .btn-google-landing:hover {
            background: var(--white-pure);
            border-color: rgba(200, 121, 65, 0.3);
        }

        .google-icon,
        .btn-google-landing svg,
        .btn-google-landing i {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        
        /* User icon in logged-in state */
        .btn-google-landing i {
            color: var(--artistic-copper);
        }

        /* Features Section */
        .features-section {
            padding: var(--space-3xl) var(--space-md);
            background: var(--deep-slate);
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto var(--space-3xl);
        }

        .section-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-md);
            background: rgba(166, 65, 109, 0.12);
            border: 1px solid rgba(166, 65, 109, 0.35);
            border-radius: 50px;
            color: var(--imperial-rose);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: var(--space-md);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: var(--space-md);
            color: var(--gallery-ivory);
        }

        .section-description {
            font-size: 1.2rem;
            color: var(--warm-light-gray);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-xl);
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--museum-ink);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(61, 67, 84, 0.4);
            border-radius: var(--radius-xl);
            padding: var(--space-2xl);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(200, 121, 65, 0.06),
                        0 1px 3px rgba(10, 14, 26, 0.4);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--artistic-copper), var(--imperial-rose));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(200, 121, 65, 0.35);
            background: rgba(26, 31, 46, 0.6);
            box-shadow: 0 12px 28px rgba(200, 121, 65, 0.18),
                        0 4px 8px rgba(10, 14, 26, 0.5);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(200, 121, 65, 0.2), rgba(166, 65, 109, 0.2));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--space-lg);
        }

        .feature-icon svg {
            width: 28px;
            height: 28px;
            color: var(--artistic-copper);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gallery-ivory);
            margin-bottom: var(--space-md);
        }

        .feature-description {
            font-size: 1rem;
            color: var(--balanced-gray);
            line-height: 1.7;
        }

        /* How It Works Section */
        .how-it-works-section {
            padding: var(--space-3xl) var(--space-md);
            background: var(--museum-ink);
        }

        .how-it-works-section .section-badge {
            background: rgba(200, 121, 65, 0.12);
            border-color: var(--artistic-copper);
            color: var(--artistic-copper);
        }

        .steps-container {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            gap: var(--space-2xl);
        }

        .step-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: var(--space-xl);
            align-items: start;
        }

        .step-number {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--artistic-copper), var(--imperial-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--gallery-ivory);
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(200, 121, 65, 0.3),
                        0 2px 6px rgba(10, 14, 26, 0.3);
        }

        .step-content h3 {
            font-size: 1.75rem;
            margin-bottom: var(--space-sm);
            color: var(--gallery-ivory);
        }

        .step-content p {
            font-size: 1.1rem;
            color: var(--warm-light-gray);
            line-height: 1.7;
        }

        /* CTA Section */
        .cta-section {
            padding: var(--space-3xl) var(--space-md);
            background: var(--deep-slate);
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: var(--space-3xl);
            background: rgba(26, 31, 46, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(200, 121, 65, 0.2);
            border-radius: var(--radius-2xl);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(200, 121, 65, 0.08);
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;;
            height: 200%;
            background: radial-gradient(circle, rgba(200, 121, 65, 0.05) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: var(--space-md);
            color: var(--gallery-ivory);
            text-shadow: 0 2px 8px rgba(200, 121, 65, 0.1);
        }

        .cta-description {
            font-size: 1.2rem;
            color: var(--warm-light-gray);
            margin-bottom: var(--space-2xl);
        }

        .cta-container .btn-hero {
            background: linear-gradient(135deg, var(--artistic-copper) 0%, var(--artistic-copper-light) 100%);
            color: var(--gallery-ivory);
            border: none;
            box-shadow: 0 10px 40px rgba(200, 121, 65, 0.25);
        }

        .cta-container .btn-hero:hover {
            background: linear-gradient(135deg, var(--artistic-copper-light) 0%, var(--artistic-copper) 100%);
            box-shadow: 0 16px 48px rgba(200, 121, 65, 0.35);
        }

        /* Footer */
        .landing-footer {
            padding: var(--space-2xl) var(--space-md);
            text-align: center;
            border-top: 1px solid rgba(200, 121, 65, 0.15);
            background: var(--museum-ink);
        }

        .footer-text {
            color: var(--balanced-gray);
            font-size: 0.875rem;
        }

        .footer-text a {
            color: var(--artistic-copper);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-text a:hover {
            color: var(--imperial-rose);
        }

        /* Music Background Elements */
        .music-elements .music-note svg {
            color: rgba(200, 121, 65, 0.08);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .landing-hero {
                padding: var(--space-2xl) var(--space-md);
            }

            .hero-cta {
                flex-direction: column;
                width: 100%;
            }

            .btn-hero {
                width: 100%;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: var(--space-lg);
            }

            .step-item {
                grid-template-columns: 1fr;
                gap: var(--space-md);
                text-align: center;
            }

            .step-number {
                margin: 0 auto;
            }

            .cta-container {
                padding: var(--space-2xl);
            }
        }
    </style>
</head>
<body>
    <!-- Music Background Elements -->
    <div class="music-elements">
        <i data-lucide="disc-3" class="music-note"></i>
        <i data-lucide="library" class="music-note"></i>
        <i data-lucide="gallery-horizontal" class="music-note"></i>
        <i data-lucide="album" class="music-note"></i>
    </div>

    <!-- Hero Section -->
    <section class="landing-hero">
        <div class="hero-content">
            <div class="section-badge">
                <i data-lucide="sparkles"></i>
                <span>Votre Musée Musical Personnel</span>
            </div>
            
            <h1 class="hero-title">
                Exposez vos albums comme des œuvres d'art
            </h1>
            
            <p class="hero-subtitle">
                Universon transforme votre collection musicale en une exposition artistique captivante. 
                Créez votre galerie personnelle et partagez vos albums préférés comme des chefs-d'œuvre.
            </p>
            
            <div class="hero-cta">
                <a href="<?= $buttonUrl ?>" class="btn btn-hero btn-google-landing">
                    <?php if ($showGoogleIcon): ?>
                    <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <?php else: ?>
                    <i data-lucide="user" style="width: 24px; height: 24px;"></i>
                    <?php endif; ?>
                    <span><?= $buttonText ?></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="section-header">
            <span class="section-badge">Fonctionnalités</span>
            <h2 class="section-title">Une galerie musicale unique</h2>
            <p class="section-description">
                Découvrez toutes les possibilités pour mettre en valeur votre univers musical
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="gallery-horizontal"></i>
                </div>
                <h3 class="feature-title">Exposition Artistique</h3>
                <p class="feature-description">
                    Présentez vos albums préférés dans une galerie élégante et immersive, 
                    comme des tableaux dans un musée d'art contemporain.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="palette"></i>
                </div>
                <h3 class="feature-title">Personnalisation Totale</h3>
                <p class="feature-description">
                    Organisez votre collection selon vos goûts. Ajoutez des descriptions 
                    et créez l'atmosphère parfaite pour votre univers musical.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="share-2"></i>
                </div>
                <h3 class="feature-title">Partage Simplifié</h3>
                <p class="feature-description">
                    Partagez votre profil musical avec vos amis et le monde entier. 
                    Inspirez les autres avec vos découvertes et coups de cœur.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="search"></i>
                </div>
                <h3 class="feature-title">Recherche Spotify</h3>
                <p class="feature-description">
                    Accédez à des millions d'albums grâce à l'intégration Spotify. 
                    Trouvez et ajoutez facilement vos albums favoris à votre collection.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="sparkles"></i>
                </div>
                <h3 class="feature-title">Design Moderne</h3>
                <p class="feature-description">
                    Profitez d'une interface élégante et intuitive qui met en valeur 
                    vos albums avec des effets visuels captivants.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="link"></i>
                </div>
                <h3 class="feature-title">Profil Public</h3>
                <p class="feature-description">
                    Obtenez votre URL personnalisée (@username) pour partager facilement 
                    votre musée musical avec qui vous voulez.
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section">
        <div class="section-header">
            <span class="section-badge">Comment ça marche</span>
            <h2 class="section-title">Trois étapes simples</h2>
            <p class="section-description">
                Créez votre musée musical en quelques minutes
            </p>
        </div>
        
        <div class="steps-container">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Connectez-vous</h3>
                    <p>
                        Créez votre compte en quelques secondes avec Google. 
                        Simple, rapide et sécurisé.
                    </p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Ajoutez vos albums</h3>
                    <p>
                        Recherchez vos albums favoris via Spotify et ajoutez-les à votre collection. 
                        Organisez-les selon vos envies et ajoutez vos notes personnelles.
                    </p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Partagez votre univers</h3>
                    <p>
                        Partagez votre profil public avec le monde entier et inspirez 
                        d'autres passionnés de musique avec vos découvertes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <div class="cta-content">
                <h2 class="cta-title">
                    Prêt à créer votre musée musical ?
                </h2>
                <p class="cta-description">
                    Rejoignez Universon dès maintenant et commencez à exposer vos albums préférés.
                </p>
                <a href="<?= $buttonUrl ?>" class="btn btn-hero btn-google-landing">
                    <?php if ($showGoogleIcon): ?>
                    <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <?php else: ?>
                    <i data-lucide="user" style="width: 24px; height: 24px;"></i>
                    <?php endif; ?>
                    <span><?= $buttonText ?></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <p class="footer-text">
            © <?= date('Y') ?> Universon. Tous droits réservés. 
            <br>
            Transformez votre passion musicale en art.
        </p>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
