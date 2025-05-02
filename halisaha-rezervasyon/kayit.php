<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kayit'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    try {
        // Email kontrolü
        $stmt = $db->prepare("SELECT id FROM kullanicilar WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['hata'] = "Bu email adresi zaten kayıtlı!";
            header('Location: uyelik.php');
            exit;
        }

        // Yeni kullanıcı ekle
        $stmt = $db->prepare("INSERT INTO kullanicilar (ad_soyad, email, telefon, sifre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ad_soyad, $email, $telefon, $sifre]);
        
        $_SESSION['basari'] = "Kayıt başarılı! Giriş yapabilirsiniz.";
        header('Location: uyelik.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['hata'] = "Bir hata oluştu: " . $e->getMessage();
        header('Location: uyelik.php');
        exit;
    }
} else {
    header('Location: uyelik.php');
    exit;
}
?>