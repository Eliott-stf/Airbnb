-- Active: 1763998184806@@127.0.0.1@3306@airbnb
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS type;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS equipment;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS available;
DROP TABLE IF EXISTS booking;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS equipment_post;

INSERT INTO type (label) VALUES
('Maison'),
('Appartement'),
('Chambre d\'hôte'),
('Bateau'),
('Cabane'),
('Caravane'),
('Château'),
('Tente'),
('Maison d\'hôtes'),
('Hôtel');

INSERT INTO category (label) VALUES
('Electroménager'),
('Sécurité'),
('Loisir'),
('Extérieur'),
('Accessibilité'),
('Confort'),
('Connectivité');

INSERT INTO equipment (label, category_id) VALUES

('Lave-linge', 1),
('Sèche-linge', 1),
('Lave-vaisselle', 1),
('Micro-ondes', 1),
('Four', 1),
('Machine à café', 1),
('Réfrigérateur', 1),
('Grille-pain', 1),
('Fer à repasser', 1),


('Digicode', 2),
('Alarme incendie', 2),
('Extincteur', 2),
('Détecteur de fumée', 2),
('Caméra de surveillance', 2),
('Serrure sécurisée', 2),
('Interphone', 2),


('TV', 3),
('Console de jeux', 3),
('Bibliothèque', 3),
('Table de ping-pong', 3),
('Instruments de musique', 3),
('Home cinéma', 3),


('Piscine', 4),
('Jardin', 4),
('Balcon', 4),
('Terrasse', 4),
('BBQ', 4),
('Transats', 4),
('Hamac', 4),


('Ascenseur', 5),
('Accès handicapé', 5),
('Rampe d’accès', 5),
('WC adaptés', 5),

('Climatisation', 6),
('Chauffage', 6),
('Ventilateur', 6),
('Cheminée', 6),
('Jacuzzi', 6),
('Bureau', 6),

('Wifi', 7),
('Routeur supplémentaire', 7),
('Enceinte connectée', 7),
('Téléphone fixe', 7);

ALTER TABLE post
ADD COLUMN media_path VARCHAR(255) NULL AFTER created_at,
ADD COLUMN updated_at DATETIME NULL AFTER media_path;


CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    created_at DATETIME NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL,
    category_id INT DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price_day INT NOT NULL,
    country VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code INT NOT NULL,
    city VARCHAR(150) NOT NULL,
    created_at DATETIME NULL,
    bed_count INT NOT NULL,
    max_capacity INT NOT NULL,
    user_id INT NOT NULL,
    type_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (type_id) REFERENCES type(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE available (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_in DATE NOT NULL,
    date_out DATE NOT NULL,
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_in DATE NOT NULL,
    date_out DATE NOT NULL,
    guest_count INT DEFAULT 1 NOT NULL,
    post_id INT NOT NULL, 
    user_id INT NOT NULL, 
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE equipment_post (
    post_id INT NOT NULL,
    equipment_id INT NOT NULL,
    PRIMARY KEY (post_id, equipment_id),
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE image (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    media_path TEXT NOT NULL,
    post_id INT DEFAULT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

