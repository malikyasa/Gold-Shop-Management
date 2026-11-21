-- Gold Shop Management System - database schema + seed data
-- Import with:  mysql -u root < database/gold_shop.sql   (or via phpMyAdmin "Import")

CREATE DATABASE IF NOT EXISTS gold_shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gold_shop_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS sale_items, sales, purchases, gold_items, customers, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,           -- bcrypt hash (password_hash)
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(120) NOT NULL,
    phone VARCHAR(30),
    email VARCHAR(150),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE gold_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(150) NOT NULL,
    category VARCHAR(80),
    purity VARCHAR(20),
    weight DECIMAL(10,2) NOT NULL DEFAULT 0,
    purchase_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    sale_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_amount DECIMAL(14,2) NOT NULL,
    sale_date DATETIME NOT NULL,
    created_by INT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(14,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES gold_items(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    supplier VARCHAR(150),
    quantity INT NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total_amount DECIMAL(14,2) NOT NULL,
    purchase_date DATETIME NOT NULL,
    created_by INT NULL,
    FOREIGN KEY (item_id) REFERENCES gold_items(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Default logins (bcrypt hashed) - CHANGE THESE after first login
--   admin@goldshop.com / Admin@123   (role: admin)
--   staff@goldshop.com / Staff@123   (role: staff)
INSERT INTO users (full_name, email, password, role) VALUES
('Shop Admin', 'admin@goldshop.com', '$2y$10$iS7A/rb6mR7xMJRvelHI7.0QCpK/W9pV1BVIskzspTzYymbAmOy0q', 'admin'),
('Shop Staff', 'staff@goldshop.com', '$2y$10$2PN.FsCuMICYxCOBEmCT0uXvJ0i9xO5Puqv2Hcgr0Bf4hOpioUh4a', 'staff');

-- Sample data
INSERT INTO customers (customer_name, phone, email, address) VALUES
('Ali Raza', '0300-1234567', 'ali@example.com', 'Saddar, Rawalpindi'),
('Ayesha Khan', '0321-7654321', 'ayesha@example.com', 'Gulberg, Lahore');

INSERT INTO gold_items (item_name, category, purity, weight, purchase_price, sale_price, stock_quantity) VALUES
('Gold Ring', 'Ring', '22K', 5.50, 85000, 92000, 12),
('Gold Necklace', 'Necklace', '22K', 25.00, 390000, 420000, 4),
('Gold Bangle', 'Bangle', '21K', 18.00, 270000, 292000, 8),
('Gold Bar 10g', 'Bullion', '24K', 10.00, 160000, 168000, 20);
