<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../env_data.php';
    require_once __DIR__ . '/../util/functions.php';

    if (!isset($_COOKIE['session_token']) || $_COOKIE['session_token'] == "") {
        header('Location: /login.php');
        exit();
    }

    $user = getUserFromSessionToken($_COOKIE['session_token']);
    if ($user == null) {
        header('Location: /login.php');
        exit();
    }
    $userAlbums = get_user_albums($user['id']);
    $mostPlayedAlbums = get_user_albums_by_category($user['id'], 'most_played');
    $guiltyPleasureAlbums = get_user_albums_by_category($user['id'], 'guilty_pleasure');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

                <div class="albums-management-section">
                    <div class="albums-header">
                        <h3>Mes albums indispensables</h3>
                        <button class="add-album-btn" id="addAlbumBtn">
                            <i data-lucide="plus"></i>
                            <span>Ajouter un Album</span>
                        </button>
                    </div>

                    <?php if (!empty($userAlbums)): ?>
                        <div class="albums-horizontal-scroll">
                            <?php foreach ($userAlbums as $album): ?>
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
                                    <button class="remove-album-btn" title="Supprimer l'album" onclick="removeAlbum(<?= $album['id'] ?>)">
                                        <i data-lucide="trash-2"></i>
                                    </button>
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

                <!-- Section Albums les plus écoutés -->
                <div class="albums-management-section">
                    <div class="albums-header">
                        <h3>Mes albums les plus écoutés</h3>
                        <button class="add-album-btn" id="addMostPlayedBtn">
                            <i data-lucide="plus"></i>
                            <span>Ajouter un Album</span>
                        </button>
                    </div>

                    <?php if (!empty($mostPlayedAlbums)): ?>
                        <div class="albums-horizontal-scroll">
                            <?php foreach ($mostPlayedAlbums as $album): ?>
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
                                    <button class="remove-album-btn" title="Retirer des plus écoutés" onclick="removeAlbumFromCategory(<?= $album['id'] ?>, 'most_played')">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-albums">
                            <i data-lucide="music-off"></i>
                            <p>Aucun album dans vos plus écoutés pour le moment</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Section Albums "pas mon truc de base, mais que j'aime bien" -->
                <div class="albums-management-section">
                    <div class="albums-header">
                        <h3>Pas mon truc de base, mais que j'aime bien</h3>
                        <button class="add-album-btn" id="addGuiltyPleasureBtn">
                            <i data-lucide="plus"></i>
                            <span>Ajouter un Album</span>
                        </button>
                    </div>

                    <?php if (!empty($guiltyPleasureAlbums)): ?>
                        <div class="albums-horizontal-scroll">
                            <?php foreach ($guiltyPleasureAlbums as $album): ?>
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
                                    <button class="remove-album-btn" title="Retirer de cette catégorie" onclick="removeAlbumFromCategory(<?= $album['id'] ?>, 'guilty_pleasure')">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-albums">
                            <i data-lucide="music-off"></i>
                            <p>Aucun album dans cette catégorie pour le moment</p>
                        </div>
                    <?php endif; ?>
                </div>

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

    <div id="addAlbumModal" class="add-album-modal">
        <div class="add-album-content">
            <div class="add-album-header">
                <h3>Ajouter un Album</h3>
                <p>Ajoutez un nouvel album à votre collection musicale</p>
            </div>
            <form class="album-form" id="albumForm">
                <div class="album-input-group">
                    <label for="albumNameInput">Nom de l'album :</label>
                    <input type="text" id="albumNameInput" class="album-input" placeholder="Ex: Dark Side of the Moon" maxlength="255" required>
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

    <!-- Modal pour ajouter aux plus écoutés -->
    <div id="addMostPlayedModal" class="add-album-modal">
        <div class="add-album-content">
            <div class="add-album-header">
                <h3>Ajouter aux Plus Écoutés</h3>
                <p>Ajoutez un album à vos plus écoutés</p>
            </div>
            <form class="album-form" id="mostPlayedForm">
                <div class="album-input-group">
                    <label for="mostPlayedInput">Nom de l'album :</label>
                    <input type="text" id="mostPlayedInput" class="album-input" placeholder="Ex: Dark Side of the Moon" maxlength="255" required>
                    <div id="mostPlayedSuggestions" class="album-suggestions" style="display:none;"></div>
                </div>
                <div class="album-modal-actions">
                    <button type="submit" class="btn btn-primary" id="saveMostPlayedBtn">
                        <i data-lucide="save"></i>
                        <span>Ajouter</span>
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelMostPlayedBtn">
                        <i data-lucide="x"></i>
                        <span>Annuler</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour ajouter aux coups de cœur -->
    <div id="addGuiltyPleasureModal" class="add-album-modal">
        <div class="add-album-content">
            <div class="add-album-header">
                <h3>Ajouter aux Coups de Cœur</h3>
                <p>Ajoutez un album à vos coups de cœur</p>
            </div>
            <form class="album-form" id="guiltyPleasureForm">
                <div class="album-input-group">
                    <label for="guiltyPleasureInput">Nom de l'album :</label>
                    <input type="text" id="guiltyPleasureInput" class="album-input" placeholder="Ex: Dark Side of the Moon" maxlength="255" required>
                    <div id="guiltyPleasureSuggestions" class="album-suggestions" style="display:none;"></div>
                </div>
                <div class="album-modal-actions">
                    <button type="submit" class="btn btn-primary" id="saveGuiltyPleasureBtn">
                        <i data-lucide="save"></i>
                        <span>Ajouter</span>
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelGuiltyPleasureBtn">
                        <i data-lucide="x"></i>
                        <span>Annuler</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/app.js"></script>
    <script src="/js/category-albums.js"></script>
    <script>
        // Initialize category albums management when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof initCategoryAlbumsManagement === 'function') {
                initCategoryAlbumsManagement();
            }
        });
    </script>
</body>
</html>


