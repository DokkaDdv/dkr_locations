-- Base de donnees DKR Locations

CREATE DATABASE IF NOT EXISTS dkr_locations;

USE dkr_locations;

-- Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table vehicles
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(100),
    modele VARCHAR(100),
    immatriculation VARCHAR(20),
    tarif DECIMAL(10, 2),
    kilometrage INT,
    statut VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin par defaut
INSERT INTO users (email, password, role) VALUES 
('admin@dkr.com', MD5('admin123'), 'admin'),
('commercial@dkr.com', MD5('commercial123'), 'commercial');

-- Vehicles
INSERT INTO vehicles (marque, modele, immatriculation, tarif, kilometrage, statut) VALUES
('Renault', 'Clio', 'AB-123-CD', 35.00, 50000, 'disponible'),
('Peugeot', '208', 'EF-456-GH', 40.00, 30000, 'disponible'),
('Citroen', 'C3', 'IJ-789-KL', 32.00, 75000, 'reserve'),
('BMW', 'Serie 3', 'MN-012-OP', 80.00, 20000, 'en_location'),
('Mercedes', 'Classe A', 'QR-345-ST', 75.00, 15000, 'maintenance'),
('Toyota', 'Yaris', 'UV-678-WX', 38.00, 40000, 'disponible'),
('Volkswagen', 'Golf', 'YZ-890-AB', 45.00, 55000, 'disponible'),
('Audi', 'A3', 'CD-123-EF', 70.00, 25000, 'reserve'),
('Nissan', 'Micra', 'GH-456-IJ', 30.00, 80000, 'disponible'),
('Ford', 'Focus', 'YZ-901-AB', 42.00, 60000, 'disponible');
