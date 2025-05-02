<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['odeme'])) {
    $rezervasyon_id = $_POST['rezervasyon_id'];
    $kart_adi = $_POST['kart_adi'];
    $kart_no = $_POST['kart_no'];
    $son_kullanma = $_POST['son_kullanma'];
    $cvv = $_POST['cvv'];

    try {
        // Rezervasyonu güncelle
        $stmt = $db->prepare("
            UPDATE rezervasyonlar 
            SET odeme_durumu = 'tamamlandi' 
            WHERE id = ? AND kullanici_id = ?
        ");
        $stmt->execute([$rezervasyon_id, $_SESSION['kullanici']['id']]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['basari'] = "Ödeme başarıyla tamamlandı! Rezervasyonunuz oluşturuldu.";
            header('Location: profil.php');
            exit;
        } else {
            throw new Exception("Rezervasyon bulunamadı veya ödeme yapılamadı!");
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = "Ödeme işlemi sırasında hata oluştu: " . $e->getMessage();
        header("Location: odeme.php?rezervasyon_id=$rezervasyon_id");
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>