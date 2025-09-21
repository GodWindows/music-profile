<?php
    require __DIR__.  '/vendor/autoload.php';
    require_once __DIR__.  '/env_data.php';
    require_once __DIR__.  '/functions.php';

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);

    // Gestion des profils publics accessibles via /@username (sans .htaccess)
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (preg_match('#/@([A-Za-z0-9_.-]+)$#', $requestPath, $matches) || (isset($_GET['u']) && $_GET['u'] !== "")) {
        $username = isset($matches[1]) ? $matches[1] : trim($_GET['u']);
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Music Background Elements -->
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
                        <div class="brand-icon" style="background: none; border: none; box-shadow: none;">
                            <img src="logo.ico" alt="Logo" style="width: 24px; height: 24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($site_title) ?></a>
                    </div>
                </div>
            </div>
        </header>

        <main class="container">
            <div class="card profile-card" style="text-align: center; max-width: 500px; margin: 4rem auto;">
                <div style="font-size: 4rem; margin-bottom: 1rem; color: var(--gray-400);">
                    <i data-lucide="user-x"></i>
                </div>
                <h1 style="color: var(--error); margin-bottom: 1rem;">Profil introuvable</h1>
                <p style="color: var(--gray-300); margin-bottom: 2rem;">Le profil @<?= htmlspecialchars($username) ?> n'existe pas ou n'est plus disponible.</p>
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Music Background Elements -->
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
                        <div class="brand-icon" style="background: none; border: none; box-shadow: none;">
                            <img src="logo.ico" alt="Logo" style="width: 24px; height: 24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($site_title) ?></a>
                    </div>
                </div>
            </div>
        </header>

        <main class="container">
            <div class="card profile-card" style="text-align: center; max-width: 500px; margin: 4rem auto;">
                <div style="font-size: 4rem; margin-bottom: 1rem; color: var(--gray-400);">
                    <i data-lucide="lock"></i>
                </div>
                <h1 style="color: var(--warning); margin-bottom: 1rem;">Profil privé</h1>
                <p style="color: var(--gray-300); margin-bottom: 2rem;">Ce profil est privé et n'est pas accessible au public.</p>
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

        // Rendu minimal du profil public (nom + bio)
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> — Profil de @<?= htmlspecialchars($publicUser['pseudo']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .public-profile{max-width:720px;margin:4rem auto;padding:var(--space-xl);}
        .public-profile h1{margin-bottom:var(--space-sm);}
        .public-profile .pseudo{color:var(--text-muted);}
        .public-profile .bio{margin-top:var(--space-md);}
    </style>
    </head>
<body>
    <div class="public-profile card">
        <?php $viewer = (isset($_COOKIE['session_token']) && $_COOKIE['session_token'] !== '') ? getUserFromSessionToken($_COOKIE['session_token']) : null; ?>
        <?php if ($viewer): ?>
            <header style="margin: -16px -16px 16px -16px; padding: 16px; border-bottom:1px solid rgba(255,255,255,.1); display:flex; justify-content:space-between; align-items:center;">
                    <div class="header-brand" style="display:flex;align-items:center;gap:.5rem;">
                        <div class="brand-icon"><i data-lucide="music"></i></div>
                        <a href="/" class="brand-text" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($site_title) ?></a>
                    </div>
                <nav class="nav-menu">
                    <button id="logoutBtn" class="btn btn-logout">
                        <i data-lucide="log-out"></i>
                        <span>Déconnexion</span>
                    </button>
                </nav>
            </header>
        <?php endif; ?>
        <h1><?= htmlspecialchars($publicUser['firstName']) ?></h1>
        <div class="pseudo">@<?= htmlspecialchars($publicUser['pseudo']) ?></div>
        <div style="margin-top:8px;">
            <?php $isOwnProfile = $viewer && isset($viewer['pseudo']) && $viewer['pseudo'] === $publicUser['pseudo']; ?>
            <button id="shareProfileBtn" class="btn btn-secondary" data-share-url="<?= htmlspecialchars($site_url) ?>/@<?= htmlspecialchars($publicUser['pseudo']) ?>">
                <i data-lucide="share-2"></i>
                <span><?= $isOwnProfile ? 'Partager mon profil' : 'Partager ce profil' ?></span>
            </button>
        </div>
        <div class="bio">
            <?php if (!empty($publicUser['bio'])): ?>
                <p><?= htmlspecialchars($publicUser['bio']) ?></p>
            <?php else: ?>
                <p class="text-gray-400"><em>Aucune bio disponible</em></p>
            <?php endif; ?>
        </div>
        <?php 
            $publicUserAlbums = get_user_albums($publicUser['id']);
        ?>
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
    <script>
        lucide && lucide.createIcons && lucide.createIcons();
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
        <?php
        exit();
    }

    if (!isset($_COOKIE['session_token']) || $_COOKIE['session_token']== "" ) {
        header('Location: login.php');
    } 
    $user = getUserFromSessionToken($_COOKIE['session_token']);
    if ($user== null) {
        header('Location: login.php');
    }
    
    $user = (getUserFromSessionToken($_COOKIE['session_token']));
    
    // Récupérer les albums de l'utilisateur
    $userAlbums = get_user_albums($user['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$site_title?> — Accueil</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Music Background Elements -->
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
                        <div class="brand-icon" style="background: none; border: none; box-shadow: none;">
                            <img src="logo.ico" alt="Logo" style="width: 24px; height: 24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration: none; color: inherit;"><?=$site_title?></a>
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

        <main class="container">
            <div class="card profile-card">
                <h1 class="text-center">Bienvenue, <?= htmlspecialchars($user['firstName']) ?> 👋</h1>
                <p class="text-center text-accent mb-3">Découvrez votre collection musicale personnalisée</p>
                
                <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Photo de profil" class="profile-avatar">
                
                <div class="profile-info">
                    <div class="bio-section">
                        <div class="bio-header">
                            <strong>Bio :</strong>
                            <button class="btn-edit" id="editBioBtn" title="Modifier la bio">
                                <i data-lucide="edit-3"></i>
                            </button>
                        </div>
                        <div class="bio-content" id="bioContent">
                            <?php if (isset($user['bio']) && !empty($user['bio'])): ?>
                                <p><?= htmlspecialchars($user['bio']) ?></p>
                            <?php else: ?>
                                <p class="text-gray-400"><em>Aucune bio disponible</em></p>
                            <?php endif; ?>
                        </div>
                        <div class="bio-edit-form" id="bioEditForm" style="display: none;">
                            <textarea id="bioTextarea" class="bio-textarea" placeholder="Parlez-nous de vos goûts musicaux..."><?= isset($user['bio']) ? htmlspecialchars($user['bio']) : '' ?></textarea>
                            <div class="bio-actions">
                                <button class="btn btn-primary" id="saveBioBtn">
                                    <i data-lucide="save"></i>
                                    <span>Sauvegarder</span>
                                </button>
                                <button class="btn btn-secondary" id="cancelBioBtn">
                                    <i data-lucide="x"></i>
                                    <span>Annuler</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Visibility Controls -->
                <div class="profile-visibility-section">
                    <div class="visibility-header">
                        <h3>Visibilité du Profil</h3>
                        <div class="visibility-status">
                            <?php if (isset($user['pseudo']) && !empty($user['pseudo'])): ?>
                                <span class="pseudo-display">@<?= htmlspecialchars($user['pseudo']) ?></span>
                            <button id="shareOwnProfileBtn" class="btn btn-secondary" style="margin-left: 8px;"
                                    data-share-url="<?= htmlspecialchars($site_url) ?>/@<?= htmlspecialchars($user['pseudo']) ?>">
                                <i data-lucide="share-2"></i>
                                <span>Partager mon profil</span>
                            </button>
                        <?php else: ?>
                            <button id="shareOwnProfileBtn" class="btn btn-secondary" style="margin-left: 8px;" disabled title="Choisissez un pseudo pour partager votre profil">
                                <i data-lucide="share-2"></i>
                                <span>Partager mon profil</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="visibility-controls">
                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="visibilityToggle" 
                                       <?= ($user['profile_visibility'] === 'public') ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                            <span class="switch-label">
                                <i data-lucide="<?= ($user['profile_visibility'] === 'public') ? 'globe' : 'lock' ?>"></i>
                                <span class="switch-text"><?= ($user['profile_visibility'] === 'public') ? 'Public' : 'Privé' ?></span>
                            </span>
                        </div>
                        
                        <!-- Pseudo Setup Modal -->
                        <div id="pseudoModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Choisir un Pseudo</h3>
                                    <p>Pour rendre votre profil public, vous devez choisir un pseudo unique.</p>
                                </div>
                                
                                <div class="pseudo-form">
                                    <div class="input-group">
                                        <label for="pseudoInput">Pseudo :</label>
                                        <div class="pseudo-input-container">
                                            <span class="pseudo-prefix">@</span>
                                            <input type="text" id="pseudoInput" placeholder="votre_pseudo" 
                                                   maxlength="45" minlength="3">
                                        </div>
                                        <div id="pseudoFeedback" class="feedback"></div>
                                    </div>
                                    
                                    <div class="modal-actions">
                                        <button id="savePseudoBtn" class="btn btn-primary" disabled>
                                            <i data-lucide="save"></i>
                                            <span>Enregistrer</span>
                                        </button>
                                        <button id="cancelPseudoBtn" class="btn btn-secondary">
                                            <i data-lucide="x"></i>
                                            <span>Annuler</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Albums Management Section -->
                <div class="albums-management-section">
                    <div class="albums-header">
                        <h3>Mes albums indispensables</h3>
                        <button class="add-album-btn" id="addAlbumBtn">
                            <i data-lucide="plus"></i>
                            <span>Ajouter un Album</span>
                        </button>
                    </div>
                    
                    <?php if (!empty($userAlbums)): ?>
                        <div class="albums-grid">
                            <?php foreach ($userAlbums as $album): ?>
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
                                            <p class="album-date">Par <?= htmlspecialchars($album['artist_name']) ?> · Ajouté le <?= date('d/m/Y', strtotime($album['added_at'])) ?></p>
                                        <?php else: ?>
                                        <p class="album-date">Ajouté le <?= date('d/m/Y', strtotime($album['added_at'])) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="album-actions">
                                        <button class="album-action-btn" title="Supprimer l'album" onclick="removeAlbum(<?= $album['id'] ?>)">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-albums">
                            <i data-lucide="music-off"></i>
                            <p>Aucun album dans votre collection pour le moment</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Music Collection Preview -->
                <div class="music-collection-preview">
                    <h3 class="text-center mb-2">Votre Collection Musicale</h3>
                    <div class="collection-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= count($userAlbums) ?></div>
                            <div class="stat-label">Albums</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Artistes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Titres</div>
                        </div>
                    </div>
                    <p class="text-center text-gray-400 mt-3">
                        Continuez à enrichir votre collection musicale
                    </p>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Album Modal -->
    <div id="addAlbumModal" class="add-album-modal">
        <div class="add-album-content">
            <div class="add-album-header">
                <h3>Ajouter un Album</h3>
                <p>Ajoutez un nouvel album à votre collection musicale</p>
            </div>
            
            <form class="album-form" id="albumForm">
                <div class="album-input-group">
                    <label for="albumNameInput">Nom de l'album :</label>
                    <input type="text" id="albumNameInput" class="album-input" 
                           placeholder="Ex: Dark Side of the Moon" 
                           maxlength="255" required>
                    <div id="albumSuggestions" class="album-suggestions" style="display:none;"></div>
                </div>
                
                <div class="album-modal-actions">
                    <button type="submit" class="btn btn-primary" id="saveAlbumBtn">
                        <i data-lucide="save"></i>
                        <span>Ajouter</span>
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelAlbumBtn">
                        <i data-lucide="x"></i>
                        <span>Annuler</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>