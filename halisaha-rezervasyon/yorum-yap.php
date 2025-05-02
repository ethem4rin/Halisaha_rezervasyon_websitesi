<?php
require_once 'config.php';
session_start();

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['kullanici'])) {
    $_SESSION['hata'] = "Yorum yapmak için giriş yapmalısınız";
    header('Location: giris.php?redirect=' . urlencode($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// POST kontrolü
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['yorum_yap'])) {
    $_SESSION['hata'] = "Geçersiz istek metodu";
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// Veri doğrulama
$saha_id = isset($_POST['saha_id']) ? intval($_POST['saha_id']) : null;
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : null;
$yorum = trim($_POST['yorum'] ?? '');
$puan = isset($_POST['puan']) ? intval($_POST['puan']) : 0;
$kullanici_id = $_SESSION['kullanici']['id'];

// Minimum yorum uzunluğu kontrolü
if (strlen($yorum) < 10) {
    $_SESSION['hata'] = "Yorumunuz en az 10 karakter olmalıdır";
    header("Location: " . ($saha_id ? "saha-detay.php?id=$saha_id" : "macini-bul.php"));
    exit;
}

// Puan aralığı kontrolü (0-5)
if ($puan < 0 || $puan > 5) {
    $_SESSION['hata'] = "Geçersiz puan değeri";
    header("Location: " . ($saha_id ? "saha-detay.php?id=$saha_id" : "macini-bul.php"));
    exit;
}

try {
    // Saha yorumu
    if ($saha_id) {
        // Sahanın varlığını kontrol et
        $stmt = $db->prepare("SELECT id FROM sahalar WHERE id = ?");
        $stmt->execute([$saha_id]);
        if (!$stmt->fetch()) {
            throw new Exception("Geçersiz saha ID'si");
        }

        $stmt = $db->prepare("INSERT INTO yorumlar (kullanici_id, saha_id, yorum, puan, tarih) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$kullanici_id, $saha_id, $yorum, $puan]);
        
        $_SESSION['basari'] = "Saha yorumunuz başarıyla eklendi!";
        header("Location: saha-detay.php?id=$saha_id");
    } 
    // Video yorumu
    elseif ($video_id) {
        // Videoyu kontrol et
        $stmt = $db->prepare("SELECT id FROM videolar WHERE id = ?");
        $stmt->execute([$video_id]);
        if (!$stmt->fetch()) {
            throw new Exception("Geçersiz video ID'si");
        }

        $stmt = $db->prepare("INSERT INTO yorumlar (kullanici_id, video_id, yorum, puan, tarih) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$kullanici_id, $video_id, $yorum, $puan]);
        
        $_SESSION['basari'] = "Video yorumunuz başarıyla eklendi!";
        header("Location: macini-bul.php");
    } 
    else {
        throw new Exception("Geçersiz yorum hedefi!");
    }
    exit;
    
} catch (PDOException $e) {
    $_SESSION['hata'] = "Veritabanı hatası: " . $e->getMessage();
    error_log("Yorum yapma hatası: " . $e->getMessage());
    header("Location: " . ($saha_id ? "saha-detay.php?id=$saha_id" : "macini-bul.php"));
    exit;
} catch (Exception $e) {
    $_SESSION['hata'] = $e->getMessage();
    header("Location: " . ($saha_id ? "saha-detay.php?id=$saha_id" : "macini-bul.php"));
    exit;
}
?>