# Mon Musée Musical 🎵

Une plateforme web moderne et responsive pour créer votre musée musical personnel basé sur vos goûts musicaux.

## ✨ Fonctionnalités

- **Design Responsive** : Interface parfaitement adaptée à tous les appareils
- **Authentification Google** : Connexion sécurisée via OAuth2
- **Interface Moderne** : Design glassmorphism avec animations fluides
- **Thème Musical** : Éléments visuels et sonores inspirés de la musique
- **Performance Optimisée** : Chargement rapide et animations fluides
- **Icônes Professionnelles** : Utilisation de Lucide Icons pour une interface cohérente

## 🎨 Design System

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

## 📱 Responsive Design

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

## 🚀 Technologies Utilisées

- **Frontend** : HTML5, CSS3, JavaScript ES6+
- **Backend** : PHP 8+, PDO
- **Base de Données** : MySQL
- **Authentification** : Google OAuth2
- **Design** : CSS Variables, Flexbox, Grid, Animations
- **Icônes** : Lucide Icons (CDN)

## 📁 Structure du Projet

```
music-profile/
├── api/                 # API endpoints
│   ├── logout.php      # Déconnexion
│   └── update_bio.php  # Mise à jour de la bio
├── css/                 # Styles CSS
│   └── styles.css      # Design system principal
├── js/                  # JavaScript
│   └── app.js          # Logique frontend
├── vendor/              # Dépendances Composer
├── index.php            # Page d'accueil
├── login.php            # Page de connexion
├── redirect.php         # Gestion OAuth
├── functions.php        # Fonctions PHP
├── db_config.php        # Configuration base de données
└── README.md            # Documentation
```

## 🎵 Éléments Musicaux

### Arrière-plan Animé
- Icônes de musique Lucide flottantes
- Animations CSS avec keyframes
- Effets de parallaxe subtils

### Interactions
- Clics sur les icônes de musique
- Effets de ripple
- Sons musicaux (Web Audio API)

## 🔧 Installation

1. **Cloner le projet**
   ```bash
   git clone [url-du-repo]
   cd music-profile
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configurer la base de données**
   - Créer la base de données
   - Importer `database.sql`
   - Configurer `db_config.php`

4. **Configurer Google OAuth**
   - Créer un projet Google Cloud
   - Configurer les identifiants OAuth2
   - Créer `env_data.php`

5. **Lancer le serveur**
   ```bash
   php -S localhost:8000
   ```

## 🎨 Personnalisation

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

## 📱 Tests Responsifs

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

## 🚀 Optimisations

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

## 🔮 Roadmap

- [ ] Intégration Spotify/Apple Music API
- [ ] Système de playlists personnalisées
- [ ] Historique d'écoute
- [ ] Recommandations musicales
- [ ] Mode sombre/clair
- [ ] PWA (Progressive Web App)
- [ ] Mode hors ligne
- [ ] Plus d'icônes musicales Lucide

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature
3. Commiter les changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
- Ouvrir une issue sur GitHub
- Consulter la documentation
- Contacter l'équipe de développement

---

**Mon Musée Musical** - Créez votre collection musicale personnelle 🎵✨
