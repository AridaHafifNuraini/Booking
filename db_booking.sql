CREATE DATABASE IF NOT EXISTS db_booking;
USE db_booking;

CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS PlayStation (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_playstation VARCHAR(100) NOT NULL,
  harga_per_jam DECIMAL(10,2) NOT NULL,
  deskripsi TEXT,
  status ENUM('tersedia','tidak tersedia') DEFAULT 'tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  gambar VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS booking (
  id_booking INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_PlayStation INT NOT NULL,
  tanggal DATE NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  total_harga DECIMAL(10,2) NOT NULL,
  proof_payment VARCHAR(255) DEFAULT NULL,
  ypayment_status ENUM('pending','accepted','rejected','dibayar') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_PlayStation) REFERENCES PlayStation(id) ON DELETE CASCADE
);

INSERT INTO users (nama, email, password, role)
VALUES ('Admin Booking', 'admin@booking.com', MD5('adminbarokah'), 'admin');
