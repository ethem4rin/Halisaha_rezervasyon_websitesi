<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['video_kirp'])) {
    $video_id = $_POST['video_id'];
    $baslangic = $_POST['baslangic'];
    $bitis = $_POST['bitis'];
    
    try {
        // Video bilgilerini al
        $stmt = $db->prepare("SELECT dosya_yolu FROM videolar WHERE id = ? AND kullanici_id = ?");
        $stmt->execute([$video_id, $_SESSION['kullanici']['id']]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$video) {
            throw new Exception("Video bulunamadı veya yetkiniz yok!");
        }
        
        // FFmpeg komutu oluştur (sunucuda FFmpeg kurulu olmalı)
        $orjinal_yol = $video['dosya_yolu'];
        $yeni_yol = 'uploads/videos/kirpilmis_' . basename($orjinal_yol);
        $sure = $bitis - $baslangic;
        
        $komut = "ffmpeg -i {$orjinal_yol} -ss {$baslangic} -t {$sure} -c copy {$yeni_yol}";
        exec($komut, $cikti, $donus_kodu);
        
        if ($donus_kodu !== 0) {
            throw new Exception("Video kırpma işlemi başarısız oldu!");
        }
        
        // Yeni videoyu veritabanına kaydet
        $stmt = $db->prepare("
            INSERT INTO videolar 
            (kullanici_id, baslik, dosya_yolu) 
            VALUES (?, ?, ?)
        ");
        $baslik = "Kırpılmış Video - " . date('d.m.Y H:i');
        $stmt->execute([
            $_SESSION['kullanici']['id'],
            $baslik,
            $yeni_yol
        ]);
        
        $_SESSION['basari'] = "Video başarıyla kırpıldı!";
        header('Location: profil.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        header('Location: video-editor.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>