# Universon

Une plateforme web moderne et responsive pour créer et partager votre univers musical personnel.

**Déployé sur : [universon.fr](https://universon.fr)**

## Fonctionnalités

- **Design Responsive** : Interface parfaitement adaptée à tous les appareils
- **Authentification Google** : Connexion sécurisée via OAuth2
- **Gestion d'Albums par Catégories** : Organisez vos albums préférés dans des catégories personnalisables
- **Profils Publics/Privés** : Partagez votre collection musicale avec un pseudo unique (format `@username`)
- **Recherche d'Albums** : Recherchez et ajoutez des albums à votre collection
- **Interface Moderne** : Design glassmorphism avec animations fluides
- **Thème Musical** : Éléments visuels animés inspirés de la musique
- **Performance Optimisée** : Chargement rapide et animations fluides
- **Icônes Professionnelles** : Utilisation de Lucide Icons pour une interface cohérente
- **Partage de Profil** : Partagez facilement votre profil musical avec un lien direct

## Design System

### Palette de Couleurs
- **Primaire** : Indigo (#6366f1) - Pour les éléments principaux
- **Accent** : Rose (#ec4899) - Pour les éléments d'accent
- **Secondaire** : Orange (#f59e0b) - Pour les éléments secondaires
- **Neutres** : Échelle de gris pour le texte et les arrière-plans

### Typographie
- **Police Principale** : Inter (Google Fonts)
- **Hiérarchie** : Système de tailles responsive avec `clamp()`
- **Contraste** : Optimisé pour l'accessibilité

### Icônes
- **Librairie** : Lucide Icons (https://lucide.dev)
- **Style** : Icônes vectorielles modernes et cohérentes
- **Intégration** : Chargement via CDN avec initialisation automatique
- **Responsive** : Icônes qui s'adaptent à tous les écrans

### Composants
- **Cartes** : Design glassmorphism avec backdrop-filter
- **Boutons** : États hover, focus et loading avec icônes
- **Navigation** : Header sticky avec blur effect
- **Statistiques** : Grille responsive pour les métriques
- **Édition Bio** : Interface intuitive avec icônes d'action

## Responsive Design

### Breakpoints
- **Mobile** : < 480px
- **Tablet** : 480px - 768px
- **Desktop** : > 768px

### Fonctionnalités Responsives
- Grille flexible qui s'adapte à la taille d'écran
- Typographie qui s'ajuste automatiquement
- Navigation qui se réorganise sur mobile
- Espacement adaptatif avec variables CSS
- Icônes qui s'adaptent aux différentes tailles d'écran

## Technologies Utilisées

- **Frontend** : HTML5, CSS3, JavaScript ES6+
- **Backend** : PHP 8+, PDO
- **Base de Données** : MySQL
- **Authentification** : Google OAuth2
- **Design** : CSS Variables, Flexbox, Grid, Animations
- **Icônes** : Lucide Icons (CDN)

## Structure du Projet

```
universon/
├── api/                              # API endpoints
│   ├── add_album.php                 # Ajouter un album à la collection
│   ├── add_album_to_category.php     # Ajouter un album à une catégorie
│   ├── check_pseudo.php              # Vérifier la disponibilité d'un pseudo
│   ├── delete_album.php              # Supprimer un album
│   ├── get_albums_by_category.php    # Récupérer les albums par catégorie
│   ├── get_categories.php            # Récupérer toutes les catégories
│   ├── logout.php                    # Déconnexion
│   ├── remove_album_from_category.php # Retirer un album d'une catégorie
│   ├── search_albums.php             # Rechercher des albums
│   ├── update_bio.php                # Mise à jour de la bio
│   ├── update_profile_visibility.php # Changer la visibilité du profil
│   └── update_pseudo.php             # Changer le pseudo
├── css/                              # Styles CSS
│   └── styles.css                    # Design system principal
├── js/                               # JavaScript
│   └── app.js                        # Logique frontend
├── migrations/                       # Scripts de migration de base de données
├── pages/                            # Pages principales
│   ├── dashboard.php                 # Tableau de bord utilisateur
│   ├── login.php                     # Page de connexion
│   └── public_profile.php            # Profil public (/@username)
├── util/                             # Utilitaires
│   ├── functions.php                 # Fonctions PHP
│   └── redirect.php                  # Gestion OAuth callbacks
├── vendor/                           # Dépendances Composer
├── .htaccess                         # Configuration Apache
├── index.php                         # Point d'entrée principal
├── composer.json                     # Dépendances PHP
├── package.json                      # Dépendances Node.js
└── README.md                         # Documentation
```

## Éléments Musicaux

### Arrière-plan Animé
- Icônes de musique Lucide flottantes
- Animations CSS avec keyframes
- Effets de parallaxe subtils

### Interactions
- Clics sur les icônes de musique
- Effets de ripple
- Sons musicaux (Web Audio API)

## Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/votre-username/universon.git
   cd universon
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install  # Optionnel, pour JSLint
   ```

3. **Configurer la base de données**
   - Créer une base de données MySQL/MariaDB
   - Exécuter les scripts de migration dans le dossier `migrations/`
   - Créer un fichier `env_data.php` à la racine avec vos informations de BDD :
   ```php
   <?php
   // Configuration de la base de données
   $db_host = 'localhost';
   $db_name = 'votre_db';
   $db_user = 'votre_user';
   $db_pass = 'votre_password';
   
   // Configuration Google OAuth2
   $google_client_id = 'votre_client_id';
   $google_client_secret = 'votre_client_secret';
   $google_redirect_uri = 'https://universon.fr/util/redirect.php';
   
   // Configuration du site
   $site_title = 'Universon';
   $site_url = 'https://universon.fr';
   ```

4. **Configurer Google OAuth**
   - Créer un projet sur [Google Cloud Console](https://console.cloud.google.com/)
   - Activer l'API Google+ ou People API
   - Créer des identifiants OAuth 2.0
   - Ajouter les URI de redirection autorisées
   - Copier le Client ID et Client Secret dans `env_data.php`

5. **Configurer le serveur web**
   - Pour Apache : Le fichier `.htaccess` est déjà configuré
   - Pour Nginx : Configurer les redirections pour les profils publics `/@username`
   - S'assurer que `mod_rewrite` est activé (Apache)

6. **Lancer le serveur en local (développement)**
   ```bash
   php -S localhost:8000
   ```
   Puis accéder à `http://localhost:8000`

## Personnalisation

### Modifier les Couleurs
Les couleurs sont définies dans `:root` en CSS :
```css
:root {
    --primary: #6366f1;
    --accent: #ec4899;
    --secondary: #f59e0b;
}
```

### Ajouter de Nouvelles Animations
```css
@keyframes nouvelle-animation {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
```

### Modifier la Typographie
```css
:root {
    --font-primary: 'Votre-Police', sans-serif;
}
```

### Utiliser d'Autres Icônes Lucide
```html
<!-- Exemple d'utilisation d'icônes Lucide -->
<i data-lucide="heart"></i>
<i data-lucide="star"></i>
<i data-lucide="user"></i>
```

## Tests Responsifs

### Outils Recommandés
- **Chrome DevTools** : Mode responsive
- **Firefox Responsive Design Mode**
- **BrowserStack** : Tests multi-appareils

### Points de Test
- Navigation sur mobile
- Lisibilité du texte
- Taille des boutons tactiles
- Performance sur appareils lents
- Affichage des icônes sur différents écrans

## Optimisations

### Performance
- Images optimisées et lazy loading
- CSS et JS minifiés
- Animations optimisées avec `transform` et `opacity`
- Debouncing des événements scroll
- Icônes vectorielles légères (Lucide)

### Accessibilité
- Contraste des couleurs optimisé
- Navigation au clavier
- Focus states visibles
- Textes alternatifs pour les images
- Icônes avec attributs ARIA appropriés

## Fonctionnalités Principales

### Gestion d'Albums
- Recherche d'albums via une API musicale
- Ajout d'albums à votre collection
- Organisation par catégories (Favoris, Écoute fréquente, etc.)
- Défilement horizontal pour une navigation fluide
- Affichage des pochettes et informations

### Profil Utilisateur
- Choix d'un pseudo unique (@username)
- Profil public ou privé
- Bio personnalisable
- Partage de profil via URL (/@username)
- Statistiques de collection

### Catégories Dynamiques
- Gestion de catégories d'albums
- Ajout/suppression d'albums dans les catégories
- Vue organisée de votre collection

## Roadmap

- [ ] Intégration API Spotify/Apple Music
- [ ] Système de playlists personnalisées
- [ ] Statistiques avancées (artistes les plus écoutés, genres, etc.)
- [ ] Recommandations musicales basées sur les goûts
- [ ] Mode sombre/clair
- [ ] PWA (Progressive Web App)
- [ ] Système de followers/following
- [ ] Commentaires et likes sur les profils

## Contribution

1. Fork le projet
2. Créer une branche feature
3. Commiter les changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème :
- Ouvrir une issue sur GitHub
- Consulter la documentation
- Contacter l'équipe de développement

---

**Universon** - Créez et partagez votre univers musical

Déployé sur **[universon.fr](https://universon.fr)**
