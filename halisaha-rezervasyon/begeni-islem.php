<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['video_id'])) {
    $video_id = intval($_POST['video_id']);
    $kullanici_id = $_SESSION['kullanici']['id'];
    $islem = $_POST['islem']; // 'begen' veya 'begeni_kaldir'

    try {
        if ($islem == 'begen') {
            // Beğeni ekle
            $stmt = $db->prepare("INSERT INTO begeniler (kullanici_id, video_id) VALUES (?, ?)");
            $stmt->execute([$kullanici_id, $video_id]);
            $_SESSION['basari'] = "Video beğenildi!";
        } else {
            // Beğeni kaldır
            $stmt = $db->prepare("DELETE FROM begeniler WHERE kullanici_id = ? AND video_id = ?");
            $stmt->execute([$kullanici_id, $video_id]);
            $_SESSION['basari'] = "Beğeni kaldırıldı!";
        }
        
        // AJAX isteği ise JSON döndür
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $stmt = $db->prepare("SELECT COUNT(*) AS begeni_sayisi FROM begeniler WHERE video_id = ?");
            $stmt->execute([$video_id]);
            $begeni = $stmt->fetch(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'begeni_sayisi' => $begeni['begeni_sayisi']
            ]);
            exit;
        }
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
        
        $_SESSION['hata'] = "Hata: " . $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}