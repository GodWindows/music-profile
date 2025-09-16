# 🔒 Gestion de la Visibilité du Profil

## 📋 Vue d'ensemble

Cette fonctionnalité permet aux utilisateurs de contrôler la visibilité de leur profil :
- **Profil Privé** : Seul l'utilisateur peut voir son profil complet
- **Profil Public** : Les visiteurs externes peuvent voir certaines informations du profil

## 🗄️ Structure de la Base de Données

### Nouvelle Colonne Ajoutée

```sql
ALTER TABLE `users` 
ADD COLUMN `profile_visibility` ENUM('private', 'public') NOT NULL DEFAULT 'private' 
COMMENT 'Visibilité du profil: private (privé) ou public (public)';
```

### Valeurs Possibles
- `'private'` : Profil privé (par défaut)
- `'public'` : Profil public

## 🚀 Installation et Migration

### 1. Nouvelle Installation
Si vous créez une nouvelle base de données, utilisez le fichier `database.sql` mis à jour.

### 2. Migration d'une Base Existante
Exécutez le fichier de migration `migration_add_profile_visibility.sql` :

```bash
mysql -u username -p database_name < migration_add_profile_visibility.sql
```

### 3. Vérification
Après la migration, vérifiez que la colonne a été ajoutée :

```sql
DESCRIBE users;
```

## 🔧 Fonctions PHP Disponibles

### `update_profile_visibility($email, $visibility)`
Met à jour la visibilité du profil d'un utilisateur.

**Paramètres :**
- `$email` : Email de l'utilisateur
- `$visibility` : 'private' ou 'public'

**Retour :** `boolean` - true si succès, false sinon

### `get_profile_visibility($email)`
Récupère la visibilité actuelle du profil d'un utilisateur.

**Paramètres :**
- `$email` : Email de l'utilisateur

**Retour :** `string` - 'private' ou 'public'

### `get_public_user_data($email)`
Récupère les données publiques d'un utilisateur (seulement si le profil est public).

**Paramètres :**
- `$email` : Email de l'utilisateur

**Retour :** `array|null` - Données publiques ou null si profil privé

## 🌐 API Endpoints

### Mettre à Jour la Visibilité du Profil
**POST** `/api/update_profile_visibility.php`

**Corps de la requête :**
```json
{
    "visibility": "public"
}
```

**Réponse de succès :**
```json
{
    "success": true,
    "message": "Profile visibility updated successfully",
    "visibility": "public"
}
```

**Réponse d'erreur :**
```json
{
    "error": "Invalid visibility value. Must be \"private\" or \"public\""
}
```

## 📱 Intégration Frontend

### Exemple d'Utilisation JavaScript

```javascript
// Mettre à jour la visibilité du profil
function updateProfileVisibility(visibility) {
    fetch('/api/update_profile_visibility.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ visibility: visibility })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Visibilité mise à jour:', data.visibility);
            // Mettre à jour l'interface utilisateur
        } else {
            console.error('Erreur:', data.error);
        }
    })
    .catch(error => {
        console.error('Erreur de connexion:', error);
    });
}

// Utilisation
updateProfileVisibility('public');  // Rendre le profil public
updateProfileVisibility('private'); // Rendre le profil privé
```

### Interface Utilisateur Recommandée

```html
<div class="profile-visibility-controls">
    <label>Visibilité du profil :</label>
    <div class="visibility-options">
        <button class="btn btn-secondary" onclick="updateProfileVisibility('private')">
            <i data-lucide="lock"></i>
            Privé
        </button>
        <button class="btn btn-primary" onclick="updateProfileVisibility('public')">
            <i data-lucide="globe"></i>
            Public
        </button>
    </div>
</div>
```

## 🔒 Sécurité et Validation

### Validation des Données
- Seuls les utilisateurs authentifiés peuvent modifier leur visibilité
- La valeur de visibilité est validée côté serveur
- Les profils existants sont automatiquement mis en privé par défaut

### Contrôle d'Accès
- Les profils privés ne sont accessibles qu'à leur propriétaire
- Les profils publics peuvent être consultés par des visiteurs externes
- La session utilisateur est vérifiée à chaque modification

## 📊 Exemples de Requêtes SQL

### Voir Tous les Profils Publics
```sql
SELECT firstName, bio FROM users WHERE profile_visibility = 'public';
```

### Compter les Profils par Visibilité
```sql
SELECT profile_visibility, COUNT(*) as count 
FROM users 
GROUP BY profile_visibility;
```

### Mettre un Profil Spécifique en Public
```sql
UPDATE users SET profile_visibility = 'public' WHERE email = 'user@example.com';
```

## 🚨 Dépannage

### Problème : Colonne non ajoutée
```sql
-- Vérifier la structure de la table
DESCRIBE users;

-- Si la colonne n'existe pas, l'ajouter manuellement
ALTER TABLE users ADD COLUMN profile_visibility ENUM('private', 'public') NOT NULL DEFAULT 'private';
```

### Problème : Valeurs NULL
```sql
-- Mettre à jour tous les profils existants
UPDATE users SET profile_visibility = 'private' WHERE profile_visibility IS NULL;
```

### Problème : Valeurs invalides
```sql
-- Vérifier les valeurs actuelles
SELECT DISTINCT profile_visibility FROM users;

-- Corriger les valeurs invalides
UPDATE users SET profile_visibility = 'private' WHERE profile_visibility NOT IN ('private', 'public');
```

## 🔮 Évolutions Futures

- [ ] Interface de gestion de la visibilité dans le profil utilisateur
- [ ] Notifications lors des changements de visibilité
- [ ] Historique des changements de visibilité
- [ ] Visibilité granulaire (certaines informations publiques, d'autres privées)
- [ ] Système de demandes d'accès aux profils privés

---

**Note :** Cette fonctionnalité est rétrocompatible. Tous les profils existants seront automatiquement mis en privé lors de la migration.
