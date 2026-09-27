CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Awal Tema Running Club & Activewear
INSERT INTO products (name, category, price, stock) VALUES
('Pace-Maker Running Shoes', 'Footwear', 1250000, 15),
('Ultra-Lite Activewear Tee', 'Apparel', 250000, 30),
('Marathon Windbreaker Jacket', 'Apparel', 650000, 8),
('Hydro-Grip Sports Water Bottle', 'Accessories', 120000, 50);
