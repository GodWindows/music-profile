<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../env_data.php';
    require_once __DIR__ . '/../util/functions.php';

    if (!isset($_COOKIE['session_token']) || $_COOKIE['session_token'] == "") {
        echo "no cookie";
        header('Location: /pages/login.php');
        exit();
    }

    $user = getUserFromSessionToken($_COOKIE['session_token']);
    if ($user == null) {
        echo "no user";
        header('Location: /pages/login.php');
        exit();
    }
    // Get all categories from database
    $conn = connect_database();
    $categories = [];
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT name, description FROM album_categories ORDER BY name ASC");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
        }
    }
    
    // Get albums for each category dynamically
    $categoriesAlbums = [];
    foreach ($categories as $category) {
        $categoriesAlbums[$category['name']] = get_user_albums_by_category($user['id'], $category['name']);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Universon - Créez et partagez votre univers musical personnel. Organisez vos albums préférés par catégories et partagez votre profil musical avec la communauté.">
    <meta name="keywords" content="universon, musique, albums, collection musicale, profil musical, partage musique, spotify, apple music, playlist">
    <meta name="author" content="Universon">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://universon.fr/">
    <meta property="og:title" content="Universon - Votre univers musical personnel">
    <meta property="og:description" content="Créez et partagez votre collection musicale personnalisée avec Universon.">
    <meta property="og:site_name" content="Universon">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://universon.fr/">
    <meta name="twitter:title" content="Universon - Votre univers musical personnel">
    <meta name="twitter:description" content="Créez et partagez votre collection musicale personnalisée avec Universon.">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    
    <title><?= htmlspecialchars($site_title) ?> — Accueil</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../img/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
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
                            <img src="/img/logo.ico" alt="Logo" style="width:24px;height:24px;">
                        </div>
                        <a href="/" class="brand-text" style="text-decoration: none; color: inherit;"><?= htmlspecialchars($site_title) ?></a>
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
                                <button id="shareOwnProfileBtn" class="btn btn-secondary" style="margin-left: 8px;" title="Choisissez un pseudo pour partager votre profil">
                                    <i data-lucide="share-2"></i>
                                    <span>Partager mon profil</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="visibility-controls">
                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" id="visibilityToggle" <?= ($user['profile_visibility'] === 'public') ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                            <span class="switch-label">
                                <i data-lucide="<?= ($user['profile_visibility'] === 'public') ? 'globe' : 'lock' ?>"></i>
                                <span class="switch-text"><?= ($user['profile_visibility'] === 'public') ? 'Public' : 'Privé' ?></span>
                            </span>
                        </div>

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
                                            <input type="text" id="pseudoInput" placeholder="votre_pseudo" maxlength="45" minlength="3">
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

                <!-- Dynamic Categories Sections -->
                <?php foreach ($categories as $category): ?>
                    <div class="albums-management-section">
                        <div class="albums-header">
                            <h3><?= htmlspecialchars($category['description']) ?></h3>
                            <button class="add-album-btn" id="add<?= ucfirst(str_replace('_', '', $category['name'])) ?>Btn">
                                <i data-lucide="plus"></i>
                                <span>Ajouter un Album</span>
                            </button>
                        </div>

                        <?php 

                        $categoryAlbums = $categoriesAlbums[$category['name']] ?? [];
                        if (!empty($categoryAlbums)): 
                        ?>
                            <div class="albums-horizontal-scroll">
                                <?php foreach ($categoryAlbums as $album): ?>
                                    <div class="album-card-horizontal" data-album-id="<?= $album['id'] ?>">
                                        <div class="album-cover">
                                            <?php if (!empty($album['image_url_60']) || !empty($album['image_url_100'])): ?>
                                                <img src="<?= htmlspecialchars(isset($album['image_url_60']) && $album['image_url_60'] ? $album['image_url_60'] : $album['image_url_100']) ?>" alt="Cover" onerror="this.closest('.album-cover').querySelector('i').style.display='flex'; this.remove();">
                                                <i data-lucide="disc-3" style="display:none;"></i>
                                            <?php else: ?>
                                                <i data-lucide="disc-3"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="album-info-horizontal">
                                            <h4 class="album-title"><?= htmlspecialchars($album['name']) ?></h4>
                                            <?php if (!empty($album['artist_name'])): ?>
                                                <p class="album-artist"><?= htmlspecialchars($album['artist_name']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <button class="remove-album-btn" title="Retirer de cette catégorie" onclick="removeAlbumFromCategory(<?= $album['id'] ?>, '<?= $category['name'] ?>')">
                                            <i data-lucide="x"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-albums">
                                <i data-lucide="check-circle"></i>
                                <p>Aucun album dans cette catégorie pour le moment</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="music-collection-preview">
                    <!-- <h3 class="text-center mb-2">Votre Collection Musicale</h3>
                    <div class="collection-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= array_sum(array_map('count', $categoriesAlbums)) ?></div>
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
                    </div> -->
                    <p class="text-center text-gray-400 mt-3">
                        Continuez à enrichir votre collection musicale
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script src="/js/app.js"></script>
    <script>
        // Dynamic category handling is now managed by app.js
    </script>
</body>
</html>


