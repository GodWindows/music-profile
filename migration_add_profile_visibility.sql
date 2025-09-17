-- Migration: Ajout de la visibilité du profil, du pseudo et des albums
-- Date: $(date)
-- Description: Ajoute la colonne profile_visibility, pseudo et les tables albums et user_albums

USE musium;

-- Étape 1: Ajouter la colonne profile_visibility
ALTER TABLE `users` 
ADD COLUMN `profile_visibility` ENUM('private', 'public') NOT NULL DEFAULT 'private' 
COMMENT 'Visibilité du profil: private (privé) ou public (public)';

-- Étape 2: Ajouter la colonne pseudo
ALTER TABLE `users` 
ADD COLUMN `pseudo` varchar(45) DEFAULT NULL;

-- Étape 3: Ajouter une contrainte d'unicité sur le pseudo
ALTER TABLE `users` 
ADD UNIQUE KEY `pseudo` (`pseudo`);

-- Étape 4: Créer la table albums
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Étape 5: Créer la table user_albums
CREATE TABLE IF NOT EXISTS `user_albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `album_id` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_album_unique` (`user_id`, `album_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`album_id`) REFERENCES `albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Étape 6: Mettre à jour tous les profils existants en privé par défaut
UPDATE `users` SET `profile_visibility` = 'private' WHERE `profile_visibility` IS NULL;

-- Étape 7: Vérifier que la migration s'est bien passée
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'musium' 
AND TABLE_NAME = 'users' 
AND COLUMN_NAME IN ('profile_visibility', 'pseudo');

-- Étape 8: Vérifier les données
SELECT 
    id,
    email,
    firstName,
    pseudo,
    profile_visibility
FROM users 
LIMIT 10;

-- Étape 9: Compter les profils par visibilité
SELECT 
    profile_visibility,
    COUNT(*) as count
FROM users 
GROUP BY profile_visibility;

-- Étape 10: Vérifier la contrainte d'unicité
SHOW INDEX FROM users WHERE Key_name = 'pseudo';

-- Étape 11: Vérifier les nouvelles tables
SHOW TABLES LIKE 'albums';
SHOW TABLES LIKE 'user_albums';

-- Étape 12: Vérifier la structure des nouvelles tables
DESCRIBE albums;
DESCRIBE user_albums;

-- Étape 13: Améliorer la table albums pour stocker les métadonnées iTunes
ALTER TABLE `albums`
  ADD COLUMN `external_album_id` varchar(64) NULL AFTER `id`,
  ADD COLUMN `external_artist_id` varchar(64) NULL AFTER `external_album_id`,
  ADD COLUMN `artist_name` varchar(255) NULL AFTER `name`,
  ADD COLUMN `image_url_60` varchar(512) NULL AFTER `artist_name`,
  ADD COLUMN `image_url_100` varchar(512) NULL AFTER `image_url_60`;

-- Index unique pour éviter les doublons par ID externe
CREATE UNIQUE INDEX IF NOT EXISTS `idx_albums_external_album_id`
ON `albums`(`external_album_id`);
