CREATE DATABASE db_uas_251011700828;

USE db_uas_251011700828;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE aset_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_aset VARCHAR(30) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    kategori ENUM('Elektronik', 'Mebel', 'Kendaraan', 'Peralatan Kantor', 'Lainnya') NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    kondisi ENUM('Baik', 'Rusak') NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    tgl_perolehan DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- USER
INSERT INTO users (id, username, password, nama_lengkap, created_at) VALUES
(1, 'herlina45', '$2y$10$9NR23tD.mLs/gwcz54l3Ou6V32BUk8lXPCKIAXSLBd6S0MSopGMXe', 'Herlina Triansah', CURRENT_TIMESTAMP); --PW: admin123

-- ASSET DUMMY DATA
INSERT INTO aset_barang (id, kode_aset, nama_barang, kategori, jumlah, kondisi, foto, tgl_perolehan, created_at) VALUES
(1, 'AST-251011700828', 'Laptop Asus ROG - Herlina Triansah', 'Elektronik', 1, 'Baik', 'sample_rog.png', '2026-01-10', CURRENT_TIMESTAMP),
(2, 'AST-002011700001', 'Kursi Kerja Ergonomis Matcha', 'Mebel', 15, 'Baik', 'sample_chair.png', '2026-02-14', CURRENT_TIMESTAMP),
(3, 'AST-003011700002', 'Air Conditioner LG Dual Inverter', 'Peralatan Kantor', 5, 'Baik', 'sample_ac.png', '2025-08-20', CURRENT_TIMESTAMP),
(4, 'AST-004011700003', 'Printer Epson L3110 Multiguna', 'Elektronik', 3, 'Rusak', 'sample_printer.png', '2025-11-05', CURRENT_TIMESTAMP),
(5, 'AST-005011700004', 'Mobil Box Pengiriman Isuzu', 'Kendaraan', 2, 'Baik', 'sample_car.png', '2024-05-17', CURRENT_TIMESTAMP);