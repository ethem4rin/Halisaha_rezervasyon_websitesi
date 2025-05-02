<?php
// Veritabanı bağlantı bilgileri
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Varsayılan kullanıcı adı
define('DB_PASS', ''); // Varsayılan şifre (XAMPP için boş)
define('DB_NAME', 'halisaha');

// Bağlantı oluştur
try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES 'utf8mb4'");
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Oturum başlat
session_start();

// Temel URL ayarı
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

// Admin kontrol fonksiyonu
function isAdmin() {
    return isset($_SESSION['kullanici']) && $_SESSION['kullanici']['rol'] == 'admin';
}

// Giriş kontrol fonksiyonu
function isLoggedIn() {
    return isset($_SESSION['kullanici']);
}
?>