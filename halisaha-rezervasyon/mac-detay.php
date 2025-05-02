<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: macini-bul.php');
    exit;
}

$mac_id = intval($_GET['id']);

// Maç bilgilerini al
$stmt = $db->prepare("
    SELECT m.*, s.ad AS saha_ad, s.resim AS saha_resim, s.konum, 
           r.tarih, r.baslangic_saati, r.bitis_saati
    FROM maclar m
    JOIN rezervasyonlar r ON m.rezervasyon_id = r.id
    JOIN sahalar s ON r.saha_id = s.id
    WHERE m.id = ?
");
$stmt->execute([$mac_id]);
$mac = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mac) {
    header('Location: macini-bul.php');
    exit;
}

// Maç videolarını al
$stmt = $db->prepare("SELECT * FROM videolar WHERE mac_id = ?");
$stmt->execute([$mac_id]);
$videolar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eğer video yoksa rastgele YouTube futbol videoları
if (empty($videolar)) {
    $rastgeleVideolar = [
        [
            'baslik' => 'Muhteşem Gol!',
            'dosya_yolu' => 'https://www.youtube.com/embed/'.getRandomFootballVideoId()
        ],
        [
            'baslik' => 'Haftanın Maçı',
            'dosya_yolu' => 'https://www.youtube.com/embed/'.getRandomFootballVideoId()
        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mac['ev_sahibi_takim']) ?> vs <?= htmlspecialchars($mac['deplasman_takim']) ?> - Maç Detay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 8px;
        }
        .mac-bilgi {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="mac-bilgi">
                    <h2><?= htmlspecialchars($mac['ev_sahibi_takim']) ?> vs <?= htmlspecialchars($mac['deplasman_takim']) ?></h2>
                    <p class="text-muted"><?= date('d.m.Y H:i', strtotime($mac['baslama_zamani'])) ?></p>
                    
                    <?php if ($mac['mac_durumu'] == 'oynandi' && !empty($mac['skor'])): ?>
                        <div class="skor-display mb-4" style="font-size: 2rem; font-weight: bold;">
                            <?= htmlspecialchars($mac['skor']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Saha:</strong> <?= htmlspecialchars($mac['saha_ad']) ?></p>
                            <p><strong>Konum:</strong> <?= htmlspecialchars($mac['konum']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Durum:</strong> 
                                <span class="badge bg-<?= 
                                    $mac['mac_durumu'] == 'oynandi' ? 'success' : 
                                    ($mac['mac_durumu'] == 'planlanan' ? 'warning' : 'danger')
                                ?>">
                                    <?= $mac['mac_durumu'] == 'oynandi' ? 'Oynandı' : 
                                       ($mac['mac_durumu'] == 'planlanan' ? 'Planlanan' : 'İptal Edildi') ?>
                                </span>
                            </p>
                            <p><strong>Süre:</strong> <?= date('H:i', strtotime($mac['baslangic_saati'])) ?> - <?= date('H:i', strtotime($mac['bitis_saati'])) ?></p>
                        </div>
                    </div>
                    
                    <?php if (!empty($mac['aciklama'])): ?>
                        <div class="mt-3">
                            <h5>Maç Açıklaması</h5>
                            <p><?= nl2br(htmlspecialchars($mac['aciklama'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h3 class="mb-4">Maç Videoları</h3>
                
                <?php if (!empty($videolar)): ?>
                    <?php foreach ($videolar as $video): ?>
                        <div class="mb-4">
                            <h5><?= htmlspecialchars($video['baslik']) ?></h5>
                            <div class="video-container">
                                <iframe src="<?= htmlspecialchars($video['dosya_yolu']) ?>" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                            <p><?= htmlspecialchars($video['aciklama']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        Bu maça ait video bulunamadı. Benzer maç videoları:
                    </div>
                    <?php foreach ($rastgeleVideolar as $video): ?>
                        <div class="mb-4">
                            <h5><?= htmlspecialchars($video['baslik']) ?></h5>
                            <div class="video-container">
                                <iframe src="<?= htmlspecialchars($video['dosya_yolu']) ?>" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Maç İstatistikleri</h5>
                        <?php if ($mac['mac_durumu'] == 'oynandi'): ?>
                            <p>Bu maç oynandı ve sonuçlandı.</p>
                        <?php else: ?>
                            <p>Bu maç henüz oynanmadı.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Video Yükle</h5>
                        <?php if (isLoggedIn()): ?>
                            <form action="video-yukle.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="mac_id" value="<?= $mac_id ?>">
                                <div class="mb-3">
                                    <label class="form-label">Video Başlığı</label>
                                    <input type="text" class="form-control" name="baslik" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">YouTube Video URL</label>
                                    <input type="text" class="form-control" name="video_url" 
                                           placeholder="https://www.youtube.com/watch?v=..." required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Açıklama</label>
                                    <textarea class="form-control" name="aciklama" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Yükle</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Video yüklemek için <a href="giris.php">giriş yapmalısınız</a>.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <?php
    // Rastgele futbol video ID'leri döndüren fonksiyon
    function getRandomFootballVideoId() {
        $videos = [
            'dQw4w9WgXcQ', // Örnek video 1
            '9bZkp7q19f0', // Örnek video 2
            'JGwWNGJdvx8', // Örnek video 3
            'oHg5SJYRHA0', // Örnek video 4
            'DLzxrzFCyOs'  // Örnek video 5
        ];
        return $videos[array_rand($videos)];
    }
    ?>
</body>
</html>