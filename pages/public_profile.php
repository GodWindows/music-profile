<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../env_data.php';
    require_once __DIR__ . '/../functions.php';

    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $username = null;
    if (preg_match('#/@([A-Za-z0-9_.-]+)$#', $requestPath, $matches)) {
        $username = $matches[1];
    } elseif (isset($_GET['u']) && $_GET['u'] !== '') {
        $username = trim($_GET['u']);
    }

    if (!$username) {
        http_response_code(400);
        echo 'Requête invalide.';
        exit();
    }

    $publicUser = get_user_public_min_by_pseudo($username);
    if ($publicUser === null) {
        http_response_code(404);
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> — Profil introuvable</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>#globeViz{display:none}</style>
    </head>
<body>
    <div class="music-elements">
        <i data-lucide="music" class="music-note"></i>
        <i data-lucide="music-2" class="music-note"></i>
        <i data-lucide="music-3" class="music-note"></i>
        <i data-lucide="music-4" class="music-note"></i>
    </div>

    <div class="page-wrapper">
        <header>
            <div class="container">
                <div class="header-content">
                    <div class="header-brand">
                        <div class="brand-icon" style="background:none;border:none;box-shadow:none;">
                            <img src="/logo.ico" alt="Logo" style="width:24px;height:24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration:none;color:inherit;"><?= htmlspecialchars($site_title) ?></a>
                    </div>
                </div>
            </div>
        </header>

        <main class="container">
            <div class="card profile-card" style="text-align:center;max-width:500px;margin:4rem auto;">
                <div style="font-size:4rem;margin-bottom:1rem;color:var(--gray-400);">
                    <i data-lucide="user-x"></i>
                </div>
                <h1 style="color:var(--error);margin-bottom:1rem;">Profil introuvable</h1>
                <p style="color:var(--gray-300);margin-bottom:2rem;">Le profil @<?= htmlspecialchars($username) ?> n'existe pas ou n'est plus disponible.</p>
                <a href="/" class="btn btn-primary">
                    <i data-lucide="home"></i>
                    <span>Retour à l'accueil</span>
                </a>
            </div>
        </main>
    </div>
    <script>lucide && lucide.createIcons && lucide.createIcons();</script>
</body>
</html>
        <?php
        exit();
    }

    if ($publicUser['profile_visibility'] !== 'public') {
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> — Profil privé</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>#globeViz{display:none}</style>
</head>
<body>
    <div class="music-elements">
        <i data-lucide="music" class="music-note"></i>
        <i data-lucide="music-2" class="music-note"></i>
        <i data-lucide="music-3" class="music-note"></i>
        <i data-lucide="music-4" class="music-note"></i>
    </div>

    <div class="page-wrapper">
        <header>
            <div class="container">
                <div class="header-content">
                    <div class="header-brand">
                        <div class="brand-icon" style="background:none;border:none;box-shadow:none;">
                            <img src="/logo.ico" alt="Logo" style="width:24px;height:24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration:none;color:inherit;"><?= htmlspecialchars($site_title) ?></a>
                    </div>
                </div>
            </div>
        </header>

        <main class="container">
            <div class="card profile-card" style="text-align:center;max-width:500px;margin:4rem auto;">
                <div style="font-size:4rem;margin-bottom:1rem;color:var(--gray-400);">
                    <i data-lucide="lock"></i>
                </div>
                <h1 style="color:var(--warning);margin-bottom:1rem;">Profil privé</h1>
                <p style="color:var(--gray-300);margin-bottom:2rem;">Ce profil est privé et n'est pas accessible au public.</p>
                <a href="/" class="btn btn-primary">
                    <i data-lucide="home"></i>
                    <span>Retour à l'accueil</span>
                </a>
            </div>
        </main>
    </div>
    <script>lucide && lucide.createIcons && lucide.createIcons();</script>
</body>
</html>
        <?php
        exit();
    }

    $viewer = (isset($_COOKIE['session_token']) && $_COOKIE['session_token'] !== '') ? getUserFromSessionToken($_COOKIE['session_token']) : null;
    $publicUserAlbums = get_user_albums($publicUser['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> — Profil de @<?= htmlspecialchars($publicUser['pseudo']) ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>#globeViz{display:none}</style>
</head>
<body>
    <div class="music-elements">
        <i data-lucide="music" class="music-note"></i>
        <i data-lucide="music-2" class="music-note"></i>
        <i data-lucide="music-3" class="music-note"></i>
        <i data-lucide="music-4" class="music-note"></i>
    </div>

    <div class="page-wrapper">
        <?php if ($viewer): ?>
            <header>
                <div class="container">
                    <div class="header-content">
                        <div class="header-brand">
                            <div class="brand-icon" style="background:none;border:none;box-shadow:none;">
                                <img src="/logo.ico" alt="Logo" style="width:24px;height:24px;">
                            </div>
                            <a href="/" class="brand-text" style="text-decoration:none;color:inherit;"><?= htmlspecialchars($site_title) ?></a>
                        </div>
                        <nav class="nav-menu">
                            <button id="logoutBtn" class="btn btn-logout">
                                <i data-lucide="log-out"></i>
                                <span>Déconnexion</span>
                            </button>
                        </nav>
                    </div>
                </div>
            </header>
        <?php endif; ?>

        <main class="container">
            <div class="card profile-card">
                <h1 class="text-center"><?= htmlspecialchars($publicUser['firstName']) ?></h1>
                <div class="pseudo">@<?= htmlspecialchars($publicUser['pseudo']) ?></div>
                <div style="margin-top:8px;">
                    <?php $isOwnProfile = $viewer && isset($viewer['pseudo']) && $viewer['pseudo'] === $publicUser['pseudo']; ?>
                    <button id="shareProfileBtn" class="btn btn-secondary" data-share-url="<?= htmlspecialchars($site_url) ?>/@<?= htmlspecialchars($publicUser['pseudo']) ?>">
                        <i data-lucide="share-2"></i>
                        <span><?= $isOwnProfile ? 'Partager mon profil' : 'Partager ce profil' ?></span>
                    </button>
                </div>
                <div class="bio" style="margin-top:12px;">
                    <?php if (!empty($publicUser['bio'])): ?>
                        <p><?= htmlspecialchars($publicUser['bio']) ?></p>
                    <?php else: ?>
                        <p class="text-gray-400"><em>Aucune bio disponible</em></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($publicUserAlbums)): ?>
                    <div class="albums-grid" style="margin-top:24px;">
                        <?php foreach ($publicUserAlbums as $album): ?>
                            <div class="album-card" data-album-id="<?= $album['id'] ?>">
                                <div class="album-icon">
<?php if (!empty($album['image_url_60']) || !empty($album['image_url_100'])): ?>
    <img src="<?= htmlspecialchars(isset($album['image_url_60']) && $album['image_url_60'] ? $album['image_url_60'] : $album['image_url_100']) ?>" alt="Cover" style="width:50px;height:50px;border-radius:50%;object-fit:cover;" onerror="this.closest('.album-icon').querySelector('i').style.display='flex'; this.remove();">
    <i data-lucide="disc-3" style="display:none;"></i>
<?php else: ?>
    <i data-lucide="disc-3"></i>
<?php endif; ?>
                                </div>
                                <div class="album-info">
                                    <h3 class="album-title"><?= htmlspecialchars($album['name']) ?></h3>
                                    <?php if (!empty($album['artist_name'])): ?>
                                        <p class="album-date">Par <?= htmlspecialchars($album['artist_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-400 mt-3">Aucun album public à afficher</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>lucide && lucide.createIcons && lucide.createIcons();</script>
    <script>
        (function(){
            var btn = document.getElementById('shareProfileBtn');
            if (!btn) return;
            btn.addEventListener('click', function(){
                var url = btn.getAttribute('data-share-url');
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function(){
                        alert('Lien copié: ' + url);
                    }).catch(function(){
                        prompt('Copiez le lien', url);
                    });
                } else {
                    prompt('Copiez le lien', url);
                }
            });
        })();
    </script>
</body>
</html>


