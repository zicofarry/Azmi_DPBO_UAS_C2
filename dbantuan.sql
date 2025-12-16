/*
 * Database Schema for Humanitarian Assistance System
 * Host: localhost
 * Database: dbantuan
 */

CREATE DATABASE IF NOT EXISTS dbantuan;
USE dbantuan;

-- Table 1: Incoming Assistance (tbantuanmasuk)
CREATE TABLE IF NOT EXISTS tbantuanmasuk (
    id VARCHAR(20) PRIMARY KEY,
    donatur VARCHAR(100),
    isibantuan TEXT,
    tanggalmasuk DATE,
    nilai DECIMAL(15,2),
    daerahsalur VARCHAR(100),
    status VARCHAR(20) DEFAULT 'masuk'
);

-- Table 2: Distributed Assistance (tbantuansalur)
CREATE TABLE IF NOT EXISTS tbantuansalur (
    id VARCHAR(20) PRIMARY KEY,
    donatur VARCHAR(100),
    isibantuan TEXT,
    tanggalmasuk DATE,
    nilai DECIMAL(15,2),
    daerahsalur VARCHAR(100),
    status VARCHAR(20) DEFAULT 'tersalur'
);

/* * DUMMY DATA FOR TESTING
 * Execute these lines to populate tables with sample data
 */

-- Dummy Data for tbantuanmasuk
INSERT INTO tbantuanmasuk (id, donatur, tanggalmasuk, nilai, daerahsalur, isibantuan, status) VALUES 
('KOL1', 'Ana', '2025-12-04', 12000.00, 'Aceh Tamian', 'Baju, kompor, makanan', 'masuk'),
('POK1', 'Ani', '2025-12-05', 11000.00, 'Sibolga', 'Makanan kaleng', 'verifikasi');

-- Dummy Data for tbantuansalur
INSERT INTO tbantuansalur (id, donatur, tanggalmasuk, nilai, daerahsalur, isibantuan, status) VALUES 
('WRT1', 'Ina', '2025-12-04', 16000.00, 'Medan', 'Selimut, jas hujan', 'tersalur'),
('WRT2', 'Tina', '2025-12-12', 40000.00, 'Aceh Tamiang', 'Tenda', 'hilang');
