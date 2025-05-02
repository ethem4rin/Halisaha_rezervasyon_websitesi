<?php 
require_once 'config.php';

if (!isset($_SESSION['kullanici'])) {
    header('Location: uyelik.php');
    exit;
}

if (!isset($_GET['saha_id']) && !isset($_GET['video_id'])) {
    header('Location: index.php');
    exit;
}

$saha_id = isset($_GET['saha_id']) ? $_GET['saha_id'] : null;
$video_id = isset($_GET['video_id']) ? $_GET['video_id'] : null;
$kullanici_id = $_SESSION['kullanici']['id'];

try {
    // Favoriler tablosu oluşturalım (eğer yoksa)
    $db->exec("
        CREATE TABLE IF NOT EXISTS favoriler (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kullanici_id INT NOT NULL,
            saha_id INT,
            video_id INT,
            eklenme_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id),
            FOREIGN KEY (saha_id) REFERENCES sahalar(id),
            FOREIGN KEY (video_id) REFERENCES videolar(id)
        )
    ");
    
    // Favori ekleme
    if ($saha_id) {
        $stmt = $db->prepare("INSERT INTO favoriler (kullanici_id, saha_id) VALUES (?, ?)");
        $stmt->execute([$kullanici_id, $saha_id]);
        $_SESSION['basari'] = "Saha favorilere eklendi!";
        header("Location: saha-detay.php?id=$saha_id");
    } else {
        $stmt = $db->prepare("INSERT INTO favoriler (kullanici_id, video_id) VALUES (?, ?)");
        $stmt->execute([$kullanici_id, $video_id]);
        $_SESSION['basari'] = "Video favorilere eklendi!";
        header("Location: macini-bul.php");
    }
    exit;
} catch (PDOException $e) {
    $_SESSION['hata'] = "Hata: " . $e->getMessage();
    header("Location: " . ($saha_id ? "saha-detay.php?id=$saha_id" : "macini-bul.php"));
    exit;
}
?>