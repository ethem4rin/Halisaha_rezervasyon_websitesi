<?php 
require_once 'config.php';

if (!isset($_SESSION['kullanici'])) {
    header('Location: uyelik.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: rezervasyonlarim.php');
    exit;
}

$rezervasyon_id = $_GET['id'];
$kullanici_id = $_SESSION['kullanici']['id'];

$stmt = $db->prepare("
    SELECT r.*, s.ad AS saha_ad 
    FROM rezervasyonlar r
    JOIN sahalar s ON r.saha_id = s.id
    WHERE r.id = ? AND r.kullanici_id = ?
");
$stmt->execute([$rezervasyon_id, $kullanici_id]);
$rezervasyon = $stmt->fetch();

if (!$rezervasyon || $rezervasyon['odeme_durumu'] != 'bekliyor') {
    header('Location: rezervasyonlarim.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Ödeme Yap</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Rezervasyon Bilgileri</h5>
                            <p>Saha: <?= $rezervasyon['saha_ad'] ?></p>
                            <p>Tarih: <?= $rezervasyon['tarih'] ?></p>
                            <p>Saat: <?= substr($rezervasyon['baslangic_saati'], 0, 5) ?> - <?= substr($rezervasyon['bitis_saati'], 0, 5) ?></p>
                            <p>Toplam Ücret: <?= number_format($rezervasyon['ucret'], 2) ?> TL</p>
                        </div>
                        
                        <form action="odeme-islem.php" method="POST">
                            <input type="hidden" name="rezervasyon_id" value="<?= $rezervasyon['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="kart_adi" class="form-label">Kart Üzerindeki İsim</label>
                                <input type="text" class="form-control" id="kart_adi" name="kart_adi" required>
                            </div>
                            <div class="mb-3">
                                <label for="kart_no" class="form-label">Kart Numarası</label>
                                <input type="text" class="form-control" id="kart_no" name="kart_no" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="son_kullanma" class="form-label">Son Kullanma Tarihi</label>
                                    <input type="text" class="form-control" id="son_kullanma" name="son_kullanma" placeholder="AA/YY" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                                </div>
                            </div>
                            <button type="submit" name="odeme" class="btn btn-success w-100">Ödemeyi Tamamla</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>