-- Migration pour ajouter les catégories d'albums
-- Date: 2024-01-01
-- Description: Ajoute les tables pour gérer les albums "les plus écoutés" et "pas mon truc de base, mais que j'aime bien"

-- Table pour les catégories d'albums
CREATE TABLE IF NOT EXISTS album_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de liaison pour associer les albums des utilisateurs à des catégories
CREATE TABLE IF NOT EXISTS user_album_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    album_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES album_categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_album_category (user_id, album_id, category_id)
);

-- Insérer les catégories par défaut
INSERT INTO album_categories (name, description) VALUES 
('most_played', 'Mes albums les plus écoutés'),
('guilty_pleasure', 'Pas mon truc de base, mais que j''aime bien')
ON DUPLICATE KEY UPDATE name = name;

-- Index pour optimiser les requêtes
CREATE INDEX idx_user_album_categories_user_id ON user_album_categories(user_id);
CREATE INDEX idx_user_album_categories_category_id ON user_album_categories(category_id);
CREATE INDEX idx_user_album_categories_album_id ON user_album_categories(album_id);

