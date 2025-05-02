<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

$kullanici_id = $_SESSION['kullanici']['id'];
$rezervasyon_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Rezervasyon bilgilerini güvenli şekilde al
    $stmt = $db->prepare("
        SELECT 
            r.*, 
            s.ad AS saha_ad, 
            s.resim AS saha_resim, 
            IFNULL(s.konum, 'Belirtilmemiş') AS konum,
            IFNULL(s.telefon, 'Belirtilmemiş') AS saha_telefon,
            IFNULL(s.adres, 'Belirtilmemiş') AS saha_adres,
            IFNULL(s.aciklama, 'Açıklama bulunmamaktadır') AS saha_aciklama,
            k.ad_soyad AS kullanici_adsoyad,
            IFNULL(k.telefon, 'Belirtilmemiş') AS kullanici_telefon
        FROM rezervasyonlar r
        JOIN sahalar s ON r.saha_id = s.id
        JOIN kullanicilar k ON r.kullanici_id = k.id
        WHERE r.id = ? AND r.kullanici_id = ?
    ");
    $stmt->execute([$rezervasyon_id, $kullanici_id]);
    $rezervasyon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$rezervasyon) {
        throw new Exception("Rezervasyon bulunamadı!");
    }
    
    // Varsayılan değerleri atama
    $rezervasyon['sure'] = $rezervasyon['sure'] ?? 'Belirtilmemiş';
    $rezervasyon['not'] = $rezervasyon['not'] ?? 'Not bulunmamaktadır';
    
} catch (Exception $e) {
    $_SESSION['hata'] = $e->getMessage();
    header('Location: profil.php');
    exit;
}

// İptal işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['iptal_et'])) {
    try {
        // İptal kontrolü (24 saat kuralı)
        $rezervasyon_tarihi = strtotime($rezervasyon['tarih'] . ' ' . $rezervasyon['baslangic_saati']);
        $fark_saat = ($rezervasyon_tarihi - time()) / 3600;
        
        if ($fark_saat < 24) {
            throw new Exception("Rezervasyon başlangıcına 24 saatten az kaldığı için iptal edilemez!");
        }
        
        $stmt = $db->prepare("UPDATE rezervasyonlar SET odeme_durumu = 'iptal' WHERE id = ?");
        $stmt->execute([$rezervasyon_id]);
        
        $_SESSION['basari'] = "Rezervasyon başarıyla iptal edildi!";
        header('Location: profil.php');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        header("Location: rezervasyon-detay.php?id=$rezervasyon_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Detay - <?= htmlspecialchars($rezervasyon['saha_ad']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />

<?php 
$css_path = (dirname($_SERVER['PHP_SELF']) === '/') ? 
             'assets/css/styles.css' : 
             dirname($_SERVER['PHP_SELF']).'/assets/css/styles.css';
?>
<link rel="stylesheet" href="<?= $css_path ?>">
    <style>
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .saha-img {
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .info-badge {
            font-size: 0.9rem;
            padding: 8px 12px;
        }
        .cancel-btn {
            transition: all 0.3s ease;
        }
        .cancel-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <?php if (isset($_SESSION['hata'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['hata']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['hata']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="detail-card card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-calendar-event"></i> Rezervasyon Detayları
                            <span class="float-end badge <?= 
                                $rezervasyon['odeme_durumu'] == 'tamamlandi' ? 'bg-success' : 
                                ($rezervasyon['odeme_durumu'] == 'bekliyor' ? 'bg-warning' : 'bg-danger')
                            ?> info-badge">
                                <?= 
                                    $rezervasyon['odeme_durumu'] == 'tamamlandi' ? 'Onaylandı' : 
                                    ($rezervasyon['odeme_durumu'] == 'bekliyor' ? 'Ödeme Bekliyor' : 'İptal Edildi')
                                ?>
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="bi bi-clock-history"></i> Rezervasyon Bilgileri</h5>
                                <hr>
                                <p><strong>Tarih:</strong> <?= date('d.m.Y', strtotime($rezervasyon['tarih'])) ?></p>
                                <p><strong>Saat:</strong> <?= substr($rezervasyon['baslangic_saati'], 0, 5) ?> - <?= substr($rezervasyon['bitis_saati'], 0, 5) ?></p>
                                <p><strong>Süre:</strong> <?= htmlspecialchars($rezervasyon['sure']) ?> dakika</p>
                                <p><strong>Ücret:</strong> <?= number_format($rezervasyon['ucret'], 2) ?> TL</p>
                                <p><strong>Rezervasyon Tarihi:</strong> <?= date('d.m.Y H:i', strtotime($rezervasyon['rezervasyon_tarihi'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="bi bi-person-circle"></i> Kullanıcı Bilgileri</h5>
                                <hr>
                                <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($rezervasyon['kullanici_adsoyad']) ?></p>
                                <p><strong>Telefon:</strong> <?= htmlspecialchars($rezervasyon['kullanici_telefon']) ?></p>
                                <p><strong>Not:</strong> <?= htmlspecialchars($rezervasyon['not']) ?></p>
                            </div>
                        </div>

                        <?php if ($rezervasyon['odeme_durumu'] != 'iptal'): ?>
                            <form method="POST" class="mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="profil.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Geri Dön
                                    </a>
                                    <button type="submit" name="iptal_et" class="btn btn-danger cancel-btn"
                                        onclick="return confirm('Bu rezervasyonu iptal etmek istediğinize emin misiniz?')">
                                        <i class="bi bi-x-circle"></i> Rezervasyonu İptal Et
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="mt-4">
                                <a href="profil.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Geri Dön
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="detail-card card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pin-map"></i> Saha Bilgileri</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($rezervasyon['saha_resim'])): ?>
                            <img src="<?= htmlspecialchars($rezervasyon['saha_resim']) ?>" class="img-fluid saha-img mb-3">
                        <?php endif; ?>
                        
                        <h5><?= htmlspecialchars($rezervasyon['saha_ad']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($rezervasyon['saha_aciklama']) ?></p>
                        
                        <div class="mt-4">
                            <h6><i class="bi bi-info-circle"></i> İletişim Bilgileri</h6>
                            <hr>
                            <p><strong><i class="bi bi-geo-alt"></i> Adres:</strong> <?= htmlspecialchars($rezervasyon['saha_adres']) ?></p>
                            <p><strong><i class="bi bi-telephone"></i> Telefon:</strong> <?= htmlspecialchars($rezervasyon['saha_telefon']) ?></p>
                            <p><strong><i class="bi bi-geo"></i> Konum:</strong> <?= htmlspecialchars($rezervasyon['konum']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"></script>
</body>
</html>