<?php
// src/db.php

$host = 'mysql';      // Nama service di docker-compose
$db   = 'mydb';       // Nama database
$user = 'user';       // <--- Ubah jadi string 'user' (sesuai .env Anda)
$pass = 'userpass';   // <--- Ubah jadi string 'userpass' (sesuai .env Anda)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Buat tabel otomatis jika belum ada
    $sql = "CREATE TABLE IF NOT EXISTS transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255) NOT NULL,
        amount DECIMAL(15,2) NOT NULL,
        type ENUM('pemasukan', 'pengeluaran') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
} catch (\PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>