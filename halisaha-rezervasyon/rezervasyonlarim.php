<?php 
require_once 'config.php';

if (!isset($_SESSION['kullanici'])) {
    header('Location: uyelik.php');
    exit;
}

$kullanici_id = $_SESSION['kullanici']['id'];
$stmt = $db->prepare("
    SELECT r.*, s.ad AS saha_ad 
    FROM rezervasyonlar r
    JOIN sahalar s ON r.saha_id = s.id
    WHERE r.kullanici_id = ?
    ORDER BY r.tarih DESC
");
$stmt->execute([$kullanici_id]);
$rezervasyonlar = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyonlarım</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Rezervasyonlarım</h1>
        
        <?php if (empty($rezervasyonlar)): ?>
            <div class="alert alert-info">Henüz rezervasyonunuz bulunmamaktadır.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Saha</th>
                            <th>Tarih</th>
                            <th>Saat</th>
                            <th>Ücret</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rezervasyonlar as $rez): ?>
                        <tr>
                            <td><?= $rez['saha_ad'] ?></td>
                            <td><?= $rez['tarih'] ?></td>
                            <td><?= substr($rez['baslangic_saati'], 0, 5) ?> - <?= substr($rez['bitis_saati'], 0, 5) ?></td>
                            <td><?= number_format($rez['ucret'], 2) ?> TL</td>
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
                                <?php if ($rez['odeme_durumu'] == 'bekliyor'): ?>
                                    <a href="odeme.php?id=<?= $rez['id'] ?>" class="btn btn-sm btn-primary">Ödeme Yap</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>