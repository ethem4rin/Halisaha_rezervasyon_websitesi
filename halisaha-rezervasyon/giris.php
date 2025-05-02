<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['giris'])) {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    try {
        $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
        $stmt->execute([$email]);
        $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
            $_SESSION['kullanici'] = $kullanici;
            
            if ($kullanici['rol'] == 'admin') {
                header('Location: admin/');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $_SESSION['hata'] = "Geçersiz email veya şifre!";
            header('Location: uyelik.php');
            exit;
        }
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