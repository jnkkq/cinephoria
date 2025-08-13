-- Base de données : cinephoria
CREATE DATABASE IF NOT EXISTS cinephoria;
USE cinephoria;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('utilisateur', 'employe', 'admin') DEFAULT 'utilisateur',
    confirme BOOLEAN DEFAULT FALSE
);

CREATE TABLE cinemas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    horaires TEXT NOT NULL
);

CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    age_minimum INT NOT NULL,
    affiche VARCHAR(255) NOT NULL,
    coup_de_coeur BOOLEAN DEFAULT FALSE
);

CREATE TABLE film_genre (
    film_id INT,
    genre_id INT,
    PRIMARY KEY (film_id, genre_id),
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);

CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    capacite INT NOT NULL,
    qualite_projection ENUM('2D', '3D', '4K', '4DX') NOT NULL,
    cinema_id INT NOT NULL,
    FOREIGN KEY (cinema_id) REFERENCES cinemas(id) ON DELETE CASCADE
);

CREATE TABLE sieges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    `accessible` BOOLEAN DEFAULT FALSE,
    salle_id INT NOT NULL,
    FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE CASCADE
);

CREATE TABLE seances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    salle_id INT NOT NULL,
    date DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    qualite ENUM('2D', '3D', '4K', '4DX') NOT NULL,
    prix DECIMAL(6,2) NOT NULL,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE CASCADE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    seance_id INT NOT NULL,
    nombre_places INT NOT NULL,
    total_prix DECIMAL(6,2) NOT NULL,
    statut ENUM('valide', 'annule', 'utilise') DEFAULT 'valide',
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (seance_id) REFERENCES seances(id) ON DELETE CASCADE
);

CREATE TABLE places_reservees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    siege_id INT NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (siege_id) REFERENCES sieges(id) ON DELETE CASCADE
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    film_id INT NOT NULL,
    note INT CHECK (note BETWEEN 0 AND 5),
    commentaire TEXT,
    valide BOOLEAN DEFAULT FALSE,
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
);

CREATE TABLE incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salle_id INT NOT NULL,
    description TEXT NOT NULL,
    date_incident DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE CASCADE
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100),
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_contact DATETIME DEFAULT CURRENT_TIMESTAMP
);
