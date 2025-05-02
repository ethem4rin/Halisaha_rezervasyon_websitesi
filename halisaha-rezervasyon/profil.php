<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

$kullanici_id = $_SESSION['kullanici']['id'];

// Kullanıcı bilgilerini al
try {
    $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$kullanici_id]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$kullanici) {
        throw new Exception("Kullanıcı bulunamadı!");
    }
} catch (Exception $e) {
    $_SESSION['hata'] = $e->getMessage();
    header('Location: index.php');
    exit;
}

// Kullanıcının rezervasyonlarını al (güncellenmiş sorgu)
try {
    $stmt = $db->prepare("
        SELECT r.*, s.ad AS saha_ad, s.resim AS saha_resim, s.konum
        FROM rezervasyonlar r
        JOIN sahalar s ON r.saha_id = s.id
        WHERE r.kullanici_id = ?
        ORDER BY r.tarih DESC, r.baslangic_saati DESC
        LIMIT 50
    ");
    $stmt->execute([$kullanici_id]);
    $rezervasyonlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $rezervasyonlar = [];
    $_SESSION['hata'] = "Rezervasyon bilgileri alınamadı: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- Bu kodu tüm sayfalarda kullanabilirsiniz -->
<?php 
$css_path = (dirname($_SERVER['PHP_SELF']) === '/') ? 
             'assets/css/styles.css' : 
             dirname($_SERVER['PHP_SELF']).'/assets/css/styles.css';
?>
<link rel="stylesheet" href="<?= $css_path ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Halı Saha Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />

    <style>
        .profile-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            
        }
        .reservation-card:hover {
            background-color: #f8f9fa;
        }
        .saha-img {
            height: 60px;
            width: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-4">
        <!-- Mesaj gösterim alanı -->
        <?php if (isset($_SESSION['basari'])): ?>
            <div class="alert alert-success"><?= $_SESSION['basari'] ?></div>
            <?php unset($_SESSION['basari']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['hata'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['hata'] ?></div>
            <?php unset($_SESSION['hata']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>Profil Bilgileri</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($kullanici['ad_soyad']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($kullanici['email']) ?></p>
                        <p><strong>Telefon:</strong> <?= htmlspecialchars($kullanici['telefon'] ?? 'Belirtilmemiş') ?></p>
                        <a href="profil-duzenle.php" class="btn btn-primary">Profili Düzenle</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Rezervasyonlarım</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($rezervasyonlar)): ?>
                            <div class="alert alert-info">Henüz rezervasyonunuz bulunmamaktadır.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Saha</th>
                                            <th>Tarih</th>
                                            <th>Saat</th>
                                            <th>Durum</th>
                                            <th>İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rezervasyonlar as $rez): ?>
                                            <tr class="reservation-card">
                                                <td>
                                                    <?php if (!empty($rez['saha_resim'])): ?>
                                                        <img src="<?= htmlspecialchars($rez['saha_resim']) ?>" class="saha-img me-2" alt="<?= htmlspecialchars($rez['saha_ad']) ?>">
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($rez['saha_ad']) ?>
                                                </td>
                                                <td><?= date('d.m.Y', strtotime($rez['tarih'])) ?></td>
                                                <td><?= substr($rez['baslangic_saati'], 0, 5) ?> - <?= substr($rez['bitis_saati'], 0, 5) ?></td>
                                                <td>
                                                    <?php if ($rez['odeme_durumu'] == 'tamamlandi'): ?>
                                                        <span class="badge bg-success">Onaylandı</span>
                                                    <?php elseif ($rez['odeme_durumu'] == 'bekliyor'): ?>
                                                        <span class="badge bg-warning">Ödeme Bekliyor</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">İptal Edildi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="rezervasyon-detay.php?id=<?= $rez['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        Detay
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>