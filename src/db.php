<?php
// src/db.php

$host = getenv("MYSQL_HOST");      // Nama service di docker-compose (sebelumnya MQL_HOST)
$db   = getenv('MYSQL_DB');       // Nama database
$user = getenv("MYSQL_USER");       // <--- Ubah jadi string 'user' (sesuai .env Anda)
$pass = getenv('MYSQL_PASS');   // <--- Ubah jadi string 'userpass' (sesuai .env Anda)
$port = getenv('MYSQL_PORT'); // Ambil port dari ENV atau default ke 3306
$charset = 'utf8mb4';

// Tambahkan 'port=$port;' ke dalam string DSN
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
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
