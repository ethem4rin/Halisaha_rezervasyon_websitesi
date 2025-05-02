<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: giris.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mac_id = intval($_POST['mac_id'] ?? 0);
    $baslik = $_POST['baslik'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $aciklama = $_POST['aciklama'] ?? '';
    $kullanici_id = $_SESSION['kullanici']['id'];

    try {
        // YouTube URL'sinden embed link oluştur
        if (preg_match('#(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|watch\?v=)|youtu\.be/)([^"&?/ ]{11})#', $video_url, $matches)) {

            $video_id = $matches[1];
            $embed_url = "https://www.youtube.com/embed/$video_id";
            
            // Video bilgilerini kaydet
            $stmt = $db->prepare("
                INSERT INTO videolar 
                (mac_id, kullanici_id, baslik, aciklama, dosya_yolu, yukleme_tarihi) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$mac_id, $kullanici_id, $baslik, $aciklama, $embed_url]);
            
            $_SESSION['basarili'] = "Video başarıyla yüklendi!";
            header("Location: mac-detay.php?id=$mac_id");
            exit;
        } else {
            throw new Exception("Geçersiz YouTube URL formatı!");
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = "Video yüklenirken hata oluştu: " . $e->getMessage();
        header("Location: mac-detay.php?id=$mac_id");
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}