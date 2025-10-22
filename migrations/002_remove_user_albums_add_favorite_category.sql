-- Migration: Remove user_albums table and add favorite category
-- Date: 2024

-- First, add the new 'favorite' category
INSERT INTO album_categories (name, display_name, description, created_at) 
VALUES ('favorite', 'Mes albums indispensables', 'Albums favoris de l\'utilisateur', NOW())
ON DUPLICATE KEY UPDATE name = name;

-- Drop the user_albums table since it's no longer needed
-- The relationship is now handled directly through user_album_categories
DROP TABLE IF EXISTS user_albums;
