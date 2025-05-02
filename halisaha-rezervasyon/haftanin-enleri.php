<?php
require_once 'config.php';

// Gelişmiş YouTube embed URL fonksiyonu
function getYouTubeEmbedUrl($url) {
    // URL'yi temizle ve standartlaştır
    $url = trim(urldecode($url));
    $url = str_replace(['http://', 'm.youtube.com'], ['https://', 'www.youtube.com'], $url);
    
    // Tüm olası YouTube URL formatlarını kontrol et
    $patterns = [
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/',                           // youtu.be/ID
        '/[?&]v=([a-zA-Z0-9_-]{11})/',                                // ?v=ID veya &v=ID
        '/\/embed\/([a-zA-Z0-9_-]{11})/',                             // /embed/ID
        '/\/v\/([a-zA-Z0-9_-]{11})/',                                 // /v/ID
        '/youtube\.com\/watch\?.*?vi?=([a-zA-Z0-9_-]{11})/',           // youtube.com/watch?vi=ID
        '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',                // youtube.com/shorts/ID
        '/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/'                   // youtube.com/live/ID
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return "https://www.youtube.com/embed/".$matches[1];
        }
    }
    
    return false;
}

try {
    $stmt = $db->query("SELECT v.*, u.ad_soyad, COUNT(b.id) AS begeni_sayisi 
                       FROM videolar v
                       JOIN kullanicilar u ON v.kullanici_id = u.id
                       LEFT JOIN begeniler b ON v.id = b.video_id
                       GROUP BY v.id
                       ORDER BY begeni_sayisi DESC
                       LIMIT 10");
    $videolar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haftanın Enleri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }
        
        .video-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            background: #fff;
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            overflow: hidden;
        }
        
        .video-container iframe,
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-info {
            padding: 15px;
        }
        
        .video-title {
            font-size: 1.1rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .video-author {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .video-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.8rem;
        }
        
        .video-likes {
            color: #dc3545;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .debug-info {
            display: none;
            background: #f8f9fa;
            padding: 10px;
            margin-top: 5px;
            font-size: 0.8rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <h1 class="text-center mb-4">Haftanın En Çok Beğenilen Videoları</h1>
        
        <?php if (empty($videolar)): ?>
            <div class="alert alert-info text-center">
                Henüz video paylaşılmamış.
            </div>
        <?php else: ?>
            <div class="video-grid">
                <?php foreach ($videolar as $video): ?>
                    <?php
                    $video_path = trim($video['dosya_yolu']);
                    $is_youtube = (strpos($video_path, 'youtube.com') !== false) || 
                                 (strpos($video_path, 'youtu.be') !== false);
                    $embed_url = $is_youtube ? getYouTubeEmbedUrl($video_path) : false;
                    ?>
                    
                    <div class="video-card">
                        <div class="video-container">
                            <?php if ($is_youtube && $embed_url): ?>
                                <iframe src="<?= $embed_url ?>?rel=0&enablejsapi=1" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                                
                                <div class="debug-info">
                                    <strong>Debug Info:</strong><br>
                                    Original: <?= htmlspecialchars($video_path) ?><br>
                                    Embed URL: <?= $embed_url ?><br>
                                    Video ID: <?= basename($embed_url) ?>
                                </div>
                            <?php elseif ($is_youtube): ?>
                                <div class="alert alert-warning m-2">
                                    Geçersiz YouTube linki: <?= htmlspecialchars($video_path) ?>
                                </div>
                            <?php else: ?>
                                <!-- Normal video gösterimi -->
                                <?php
                                if (!filter_var($video_path, FILTER_VALIDATE_URL)) {
                                    $video_path = 'uploads/' . ltrim($video_path, '/');
                                }
                                ?>
                                <video controls playsinline>
                                    <source src="<?= htmlspecialchars($video_path) ?>" type="video/mp4">
                                    Tarayıcınız video öğesini desteklemiyor.
                                </video>
                            <?php endif; ?>
                        </div>
                        
                        <div class="video-info">
                            <h3 class="video-title"><?= htmlspecialchars($video['baslik']) ?></h3>
                            <p class="video-author"><?= htmlspecialchars($video['ad_soyad']) ?></p>
                            <div class="video-footer">
                                <span><?= date('d.m.Y H:i', strtotime($video['yukleme_tarihi'])) ?></span>
                                <span class="video-likes">
                                    <i class="bi bi-heart-fill"></i>
                                    <?= $video['begeni_sayisi'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Hata ayıklama bilgilerini göstermek için (isteğe bağlı)
    document.addEventListener('DOMContentLoaded', function() {
        const debugToggle = document.createElement('button');
        debugToggle.textContent = 'Debug Mod';
        debugToggle.style.position = 'fixed';
        debugToggle.style.bottom = '20px';
        debugToggle.style.right = '20px';
        debugToggle.style.zIndex = '1000';
        debugToggle.classList.add('btn', 'btn-sm', 'btn-secondary');
        
        debugToggle.addEventListener('click', function() {
            document.querySelectorAll('.debug-info').forEach(el => {
                el.style.display = el.style.display === 'block' ? 'none' : 'block';
            });
        });
        
        document.body.appendChild(debugToggle);
    });
    </script>
</body>
</html>