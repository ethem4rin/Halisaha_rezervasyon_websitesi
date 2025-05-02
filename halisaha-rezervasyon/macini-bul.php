<?php
require_once 'config.php'; // config.php'de session_start() olduğu varsayılıyor

// Zamanı geçen planlanan maçları "oynandi" durumuna güncelle
$db->query("UPDATE maclar SET mac_durumu = 'oynandi' WHERE mac_durumu = 'planlanan' AND bitis_zamani < NOW()");

// Filtreleme parametreleri
$saha_id = isset($_GET['saha']) ? intval($_GET['saha']) : null;
$tarih = $_GET['tarih'] ?? null;
$mac_durumu = $_GET['durum'] ?? 'oynandi';

// Tarih format kontrolü
if ($tarih && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tarih)) {
    $tarih = null;
}

// Ana sorgu
$sql = "SELECT 
            m.id,
            m.ev_sahibi_takim,
            m.deplasman_takim,
            m.skor,
            m.mac_durumu,
            m.baslama_zamani,
            m.bitis_zamani,
            s.ad AS saha_ad,
            s.resim AS saha_resim,
            s.konum,
            COUNT(DISTINCT v.id) AS video_sayisi,
            COUNT(DISTINCT y.id) AS yorum_sayisi,
            GROUP_CONCAT(DISTINCT v.dosya_yolu SEPARATOR '|') AS videolar,
            GROUP_CONCAT(DISTINCT v.baslik SEPARATOR '|') AS video_basliklari,
            TIMESTAMPDIFF(MINUTE, NOW(), m.baslama_zamani) AS kalan_dakika
        FROM maclar m
        JOIN rezervasyonlar r ON m.rezervasyon_id = r.id
        JOIN sahalar s ON r.saha_id = s.id
        LEFT JOIN videolar v ON v.mac_id = m.id
        LEFT JOIN yorumlar y ON y.saha_id = s.id
        WHERE s.aktif = TRUE";

$params = [];

// Durum filtresi
if ($mac_durumu === 'planlanan') {
    $sql .= " AND m.mac_durumu = 'planlanan' AND m.baslama_zamani > NOW()";
} elseif ($mac_durumu === 'oynandi') {
    $sql .= " AND m.mac_durumu = 'oynandi'";
} elseif ($mac_durumu === 'iptal') {
    $sql .= " AND m.mac_durumu = 'iptal'";
}

// Saha filtresi
if ($saha_id) {
    $sql .= " AND r.saha_id = ?";
    $params[] = $saha_id;
}

// Tarih filtresi
if ($tarih) {
    $sql .= " AND DATE(m.baslama_zamani) = ?";
    $params[] = $tarih;
}

$sql .= " GROUP BY m.id ORDER BY m.baslama_zamani " . ($mac_durumu === 'oynandi' ? 'DESC' : 'ASC');

try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $maclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maçını Bul - Halı Saha Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        .mac-karti {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .mac-karti.oynandi { border-left-color: #28a745; }
        .mac-karti.planlanan { border-left-color: #ffc107; }
        .mac-karti.iptal { border-left-color: #dc3545; }
        .mac-karti.zamani-gecmis { border-left-color: #6c757d; }
        
        .takimlar {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .skor {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .kalan-zaman {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Maçını Bul</h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Filtrele</h5>
                        <form method="GET">
                            <div class="mb-3">
                                <label class="form-label">Saha</label>
                                <select class="form-select" name="saha">
                                    <option value="">Tüm Sahalar</option>
                                    <?php
                                    $sahaStmt = $db->query("SELECT id, ad FROM sahalar WHERE aktif = TRUE");
                                    while ($saha = $sahaStmt->fetch()):
                                    ?>
                                    <option value="<?= $saha['id'] ?>" <?= $saha['id'] == $saha_id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($saha['ad']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarih</label>
                                <input type="date" class="form-control" name="tarih" value="<?= htmlspecialchars($tarih ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Maç Durumu</label>
                                <select class="form-select" name="durum">
                                    <option value="oynandi" <?= $mac_durumu == 'oynandi' ? 'selected' : '' ?>>Oynanan Maçlar</option>
                                    <option value="planlanan" <?= $mac_durumu == 'planlanan' ? 'selected' : '' ?>>Planlanan Maçlar</option>
                                    <option value="iptal" <?= $mac_durumu == 'iptal' ? 'selected' : '' ?>>İptal Edilenler</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Filtrele</button>
                            <a href="macini-bul.php" class="btn btn-secondary w-100 mt-2">Sıfırla</a>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <?php if (empty($maclar)): ?>
                    <div class="alert alert-info">
                        Filtreleme kriterlerinize uygun maç bulunamadı.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($maclar as $mac): 
                            $mac_zamani_gecmis = $mac['mac_durumu'] == 'planlanan' && $mac['kalan_dakika'] < 0;
                            $durum_class = $mac_zamani_gecmis ? 'zamani-gecmis' : $mac['mac_durumu'];
                        ?>
                            <div class="list-group-item mac-karti <?= $durum_class ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?php if (!empty($mac['saha_resim'])): ?>
                                            <img src="<?= htmlspecialchars($mac['saha_resim']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($mac['saha_ad']) ?>">
                                        <?php endif; ?>
                                        <h5><?= htmlspecialchars($mac['saha_ad']) ?></h5>
                                        <p><?= htmlspecialchars($mac['konum']) ?></p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="takimlar text-end flex-grow-1">
                                                <?= htmlspecialchars($mac['ev_sahibi_takim']) ?>
                                            </div>
                                            <div class="skor mx-3">
                                                <?= $mac['mac_durumu'] == 'oynandi' ? htmlspecialchars($mac['skor'] ?? '-') : 'VS' ?>
                                            </div>
                                            <div class="takimlar text-start flex-grow-1">
                                                <?= htmlspecialchars($mac['deplasman_takim']) ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <p><i class="bi bi-calendar"></i> <?= date('d.m.Y', strtotime($mac['baslama_zamani'])) ?></p>
                                            <p><i class="bi bi-clock"></i> <?= date('H:i', strtotime($mac['baslama_zamani'])) ?> - <?= date('H:i', strtotime($mac['bitis_zamani'])) ?></p>
                                            
                                            <span class="badge bg-<?= 
                                                $mac['mac_durumu'] == 'oynandi' ? 'success' : 
                                                ($mac['mac_durumu'] == 'planlanan' ? 
                                                    ($mac_zamani_gecmis ? 'secondary' : 'warning') : 
                                                    'danger')
                                            ?>">
                                                <?= $mac['mac_durumu'] == 'oynandi' ? 'Oynandı' : 
                                                   ($mac['mac_durumu'] == 'planlanan' ? 
                                                       ($mac_zamani_gecmis ? 'Zamanı Geçti' : 'Planlanan') : 
                                                       'İptal Edildi') ?>
                                            </span>
                                            
                                            <?php if ($mac['mac_durumu'] == 'planlanan' && !$mac_zamani_gecmis): ?>
                                                <span class="kalan-zaman ms-2">
                                                    (<?= floor($mac['kalan_dakika'] / 1440) ?> gün <?= floor(($mac['kalan_dakika'] % 1440) / 60) ?> saat <?= $mac['kalan_dakika'] % 60 ?> dakika kaldı)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($mac['videolar'])): ?>
                                            <div class="mt-3">
                                                <h5>Maç Videoları</h5>
                                                <?php 
                                                $videolar = explode('|', $mac['videolar']);
                                                $basliklar = explode('|', $mac['video_basliklari']);
                                                ?>
                                                <div class="row">
                                                    <?php foreach ($videolar as $index => $video): ?>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="video-container">
                                                                <iframe src="<?= htmlspecialchars($video) ?>" 
                                                                        title="<?= htmlspecialchars($basliklar[$index] ?? 'Maç Videosu') ?>"
                                                                        allowfullscreen></iframe>
                                                            </div>
                                                            <p class="mt-2"><?= htmlspecialchars($basliklar[$index] ?? 'Maç Videosu') ?></p>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <?php if ($mac['video_sayisi'] > 0): ?>
                                                    <span class="badge bg-primary me-2">
                                                        <i class="bi bi-camera-video"></i> <?= $mac['video_sayisi'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($mac['yorum_sayisi'] > 0): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-chat-left-text"></i> <?= $mac['yorum_sayisi'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="mac-detay.php?id=<?= $mac['id'] ?>" class="btn btn-sm btn-primary">
                                                Detaylar <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bugünün tarihini varsayılan olarak ayarla
        document.querySelector('input[type="date"]').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>