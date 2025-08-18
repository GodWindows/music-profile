CREATE DATABASE `musium` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

use musium;

CREATE TABLE `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_token` varchar(255) NOT NULL,
  `google_access_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_token` (`session_token`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `firstName` varchar(45) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT 'Hey, bienvenue dans mon musée',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
