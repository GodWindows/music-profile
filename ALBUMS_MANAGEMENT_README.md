# 🎵 Gestion des Albums - Mon Musée Musical

## 📋 Vue d'ensemble

Ce système permet aux utilisateurs de gérer leur collection d'albums musicaux personnelle directement depuis leur profil. Les utilisateurs peuvent ajouter, visualiser et supprimer des albums de leur collection.

## 🗄️ Structure de la Base de Données

### Table `albums`
```sql
CREATE TABLE `albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

### Table `user_albums` (Table de liaison)
```sql
CREATE TABLE `user_albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `album_id` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_album_unique` (`user_id`, `album_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`album_id`) REFERENCES `albums`(`id`) ON DELETE CASCADE
);
```

## 🔧 Fonctions PHP Disponibles

### `get_user_albums($userId)`
Récupère tous les albums d'un utilisateur.

**Paramètres :**
- `$userId` : ID de l'utilisateur

**Retour :** `array` - Liste des albums avec leurs métadonnées

### `add_album_to_user($userId, $albumName)`
Ajoute un album à un utilisateur.

**Paramètres :**
- `$userId` : ID de l'utilisateur
- `$albumName` : Nom de l'album

**Retour :** `int|false` - ID de l'album créé ou false

### `remove_album_from_user($userId, $albumId)`
Supprime un album d'un utilisateur.

**Paramètres :**
- `$userId` : ID de l'utilisateur
- `$albumId` : ID de l'album

**Retour :** `boolean` - true si succès

## 🌐 API Endpoints

### Ajouter un Album
**POST** `/api/add_album.php`

**Corps de la requête :**
```json
{
    "albumName": "Nom de l'album"
}
```

**Réponse de succès :**
```json
{
    "success": true,
    "message": "Album added successfully",
    "albumId": 123,
    "albumName": "Nom de l'album"
}
```

### Supprimer un Album
**POST** `/api/delete_album.php`

**Corps de la requête :**
```json
{
    "albumId": 123
}
```

**Réponse de succès :**
```json
{
    "success": true,
    "message": "Album removed successfully"
}
```

## 📱 Interface Utilisateur

### Section de Gestion des Albums
- **Bouton d'ajout** avec icône "+" et texte "Ajouter un Album"
- **Grille d'albums** affichant tous les albums de l'utilisateur
- **Cartes d'albums** avec icône, nom, date d'ajout et bouton de suppression
- **État vide** si aucun album dans la collection

### Modal d'Ajout d'Album
- **Formulaire simple** avec champ pour le nom de l'album
- **Validation** côté client et serveur
- **Boutons** Sauvegarder et Annuler
- **Fermeture** par clic extérieur ou touche Échap

### Affichage des Albums
- **Grille responsive** qui s'adapte à tous les écrans
- **Cartes d'albums** avec design moderne et animations
- **Icônes Lucide** pour une cohérence visuelle
- **Actions** de suppression avec confirmation

## 🎨 Styles CSS

### Classes Principales
- `.albums-management-section` - Conteneur principal de la section
- `.albums-header` - En-tête avec titre et bouton d'ajout
- `.albums-grid` - Grille responsive des albums
- `.album-card` - Carte individuelle d'album
- `.add-album-modal` - Modal d'ajout d'album

### Design Responsive
- **Mobile-first** approach
- **Grille adaptative** qui passe de 3 colonnes à 1
- **Espacement optimisé** pour tous les écrans
- **Animations fluides** et transitions

## ⚡ JavaScript

### Fonctionnalités Principales
- **Gestion du modal** d'ajout d'album
- **Validation des formulaires** côté client
- **Appels API** pour ajouter/supprimer des albums
- **Mise à jour dynamique** de l'interface
- **Gestion des erreurs** et notifications

### Événements Clés
- **Clic sur bouton d'ajout** → Ouvre le modal
- **Soumission du formulaire** → Valide et envoie à l'API
- **Clic sur bouton de suppression** → Confirme et supprime
- **Fermeture du modal** → Par clic extérieur ou Échap

## 🔒 Sécurité et Validation

### Validation Côté Client
- **Longueur du nom** : 1-255 caractères
- **Champ requis** pour l'ajout
- **Confirmation** avant suppression

### Validation Côté Serveur
- **Authentification** requise pour toutes les opérations
- **Vérification des permissions** utilisateur
- **Validation des données** d'entrée
- **Protection contre les injections SQL**

### Contrôle d'Accès
- **Session valide** obligatoire
- **Propriétaire uniquement** peut modifier sa collection
- **Pas d'accès public** aux collections

## 📊 Exemples de Requêtes SQL

### Voir les Albums d'un Utilisateur
```sql
SELECT a.name, a.created_at, ua.added_at
FROM albums a
INNER JOIN user_albums ua ON a.id = ua.album_id
WHERE ua.user_id = 123
ORDER BY ua.added_at DESC;
```

### Compter les Albums par Utilisateur
```sql
SELECT u.firstName, COUNT(ua.album_id) as album_count
FROM users u
LEFT JOIN user_albums ua ON u.id = ua.user_id
GROUP BY u.id
ORDER BY album_count DESC;
```

### Albums les Plus Populaires
```sql
SELECT a.name, COUNT(ua.user_id) as user_count
FROM albums a
INNER JOIN user_albums ua ON a.id = ua.album_id
GROUP BY a.id
ORDER BY user_count DESC
LIMIT 10;
```

## 🎯 Fonctionnalités Avancées

### Gestion des Doublons
- **Contrainte unique** sur la combinaison user_id + album_id
- **Pas de doublons** dans la collection d'un utilisateur
- **Gestion automatique** des erreurs de contrainte

### Performance
- **Index sur les clés étrangères** pour optimiser les jointures
- **Requêtes optimisées** avec INNER JOIN
- **Tri par date d'ajout** pour un affichage logique

### Extensibilité
- **Structure modulaire** facile à étendre
- **API RESTful** pour les futures fonctionnalités
- **Séparation claire** entre logique métier et présentation

## 🚨 Dépannage

### Problème : Albums non affichés
```sql
-- Vérifier la structure des tables
DESCRIBE albums;
DESCRIBE user_albums;

-- Vérifier les données
SELECT * FROM user_albums LIMIT 5;
SELECT * FROM albums LIMIT 5;
```

### Problème : Erreur lors de l'ajout
```sql
-- Vérifier les contraintes
SHOW CREATE TABLE user_albums;

-- Vérifier les permissions utilisateur
SELECT * FROM users WHERE id = votre_user_id;
```

### Problème : Modal ne s'ouvre pas
```javascript
// Vérifier la console pour les erreurs JavaScript
console.log('Modal elements:', {
    addAlbumBtn: document.getElementById('addAlbumBtn'),
    addAlbumModal: document.getElementById('addAlbumModal')
});
```

## 🔮 Évolutions Futures

- [ ] **Métadonnées d'album** (artiste, année, genre)
- [ ] **Covers d'albums** et images
- [ ] **Système de notation** et avis
- [ ] **Recherche et filtres** dans la collection
- [ ] **Import/Export** de collections
- [ ] **Partage de collections** entre utilisateurs
- [ **Intégration API** Spotify/Apple Music
- [ ] **Statistiques d'écoute** et recommandations

## 📝 Notes Techniques

### Architecture
- **MVC simplifié** avec séparation des responsabilités
- **API RESTful** pour la communication frontend-backend
- **Base de données normalisée** avec relations appropriées

### Performance
- **Requêtes optimisées** avec index appropriés
- **Chargement différé** des données si nécessaire
- **Cache côté client** pour une meilleure UX

### Maintenance
- **Code modulaire** facile à maintenir
- **Gestion d'erreurs** complète et logging
- **Documentation** détaillée pour les développeurs

---

**Note :** Ce système est conçu pour être simple mais extensible. Vous pouvez facilement ajouter de nouvelles fonctionnalités comme des artistes, des genres, des notes, etc.

