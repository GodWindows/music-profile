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
     
</head>
<body>
    <!-- Music Background Elements -->
    <div class="music-elements">
        <i data-lucide="disc-3" class="music-note"></i>
        <i data-lucide="library" class="music-note"></i>
        <i data-lucide="gallery-horizontal" class="music-note"></i>
        <i data-lucide="album" class="music-note"></i>
    </div>

    <header>
        <div class="container">
            <div class="header-content">
                <div class="header-brand">
                    <div class="brand-icon" style="background:none;border:none;box-shadow:none;">
                        <img src="/img/logo.ico" alt="Logo" style="width:24px;height:24px;">
                    </div>
                    <a href="/" class="brand-text" style="text-decoration:none;color:inherit;"><?= htmlspecialchars($site_title) ?></a>
                </div>
            </div>
        </div>
    </header>

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
