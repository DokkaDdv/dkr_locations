-- Schema PostgreSQL - DKR Locations
-- Compatible Supabase (PostgreSQL)

-- Table users
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'client',
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(20),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id SERIAL PRIMARY KEY,
    marque VARCHAR(100),
    modele VARCHAR(100),
    immatriculation VARCHAR(20),
    tarif DECIMAL(10, 2),
    kilometrage INT,
    statut VARCHAR(50) DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table location
CREATE TABLE IF NOT EXISTS location (
    id_location SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    vehicle_id INT REFERENCES vehicles(id),
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table facture
CREATE TABLE IF NOT EXISTS facture (
    id SERIAL PRIMARY KEY,
    montant_total DECIMAL(10, 2),
    moyen_paiement VARCHAR(50),
    id_location INT REFERENCES location(id_location)
);

-- Utilisateurs par defaut
INSERT INTO users (email, password, role) VALUES
('admin@dkr.com', MD5('admin123'), 'admin'),
('commercial@dkr.com', MD5('commercial123'), 'commercial')
ON CONFLICT (email) DO NOTHING;

-- Vehicules de demo
INSERT INTO vehicles (marque, modele, immatriculation, tarif, kilometrage, statut) VALUES
('Renault', 'Clio', 'AB-123-CD', 35.00, 50000, 'disponible'),
('Peugeot', '208', 'EF-456-GH', 40.00, 30000, 'disponible'),
('Citroen', 'C3', 'IJ-789-KL', 32.00, 75000, 'disponible'),
('BMW', 'Serie 3', 'MN-012-OP', 80.00, 20000, 'disponible'),
('Mercedes', 'Classe A', 'QR-345-ST', 75.00, 15000, 'disponible'),
('Toyota', 'Yaris', 'UV-678-WX', 38.00, 40000, 'disponible'),
('Volkswagen', 'Golf', 'YZ-890-AB', 45.00, 55000, 'disponible'),
('Audi', 'A3', 'CD-123-EF', 70.00, 25000, 'disponible'),
('Nissan', 'Micra', 'GH-456-IJ', 30.00, 80000, 'disponible'),
('Ford', 'Focus', 'YZ-901-AB', 42.00, 60000, 'disponible')
ON CONFLICT DO NOTHING;


-- ==================================================
-- MIGRATION (si users et vehicles existent deja)
-- Executer seulement ces lignes sur la base existante
-- ==================================================

-- ALTER TABLE users ADD COLUMN IF NOT EXISTS nom VARCHAR(100);
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS prenom VARCHAR(100);
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS telephone VARCHAR(20);

-- CREATE TABLE IF NOT EXISTS location (
--     id_location SERIAL PRIMARY KEY,
--     user_id INT REFERENCES users(id),
--     vehicle_id INT REFERENCES vehicles(id),
--     date_debut DATE NOT NULL,
--     date_fin DATE NOT NULL,
--     created_at TIMESTAMP DEFAULT NOW()
-- );

-- CREATE TABLE IF NOT EXISTS facture (
--     id SERIAL PRIMARY KEY,
--     montant_total DECIMAL(10, 2),
--     moyen_paiement VARCHAR(50),
--     id_location INT REFERENCES location(id_location)
-- );
