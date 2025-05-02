<?php 
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: sahalar.php');
    exit;
}

$saha_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM sahalar WHERE id = ?");
$stmt->execute([$saha_id]);
$saha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$saha) {
    header('Location: sahalar.php');
    exit;
}

// Resim yolu kontrolü - sahalar.php'deki mantıkla aynı
$resim_yolu = !empty($saha['resim_yolu']) ? $saha['resim_yolu'] : 'assets/img/default-saha.jpg';

// Varsayılan değerleri ayarla (eski özellikler korundu)
$saha['konum'] = $saha['konum'] ?? 'Belirtilmemiş';
$saha['kapasite'] = $saha['kapasite'] ?? 10;
$saha['zemin_turu'] = $saha['zemin_turu'] ?? 'Sentetik';

// Yorumları çekme (eski kod korundu)
$stmt = $db->prepare("
    SELECT y.*, u.ad_soyad 
    FROM yorumlar y
    JOIN kullanicilar u ON y.kullanici_id = u.id
    WHERE y.saha_id = ?
    ORDER BY y.tarih DESC
");
$stmt->execute([$saha_id]);
$yorumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($saha['ad']) ?> - Detay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h1><?= htmlspecialchars($saha['ad']) ?></h1>
                <!-- Resim yolu güncellendi -->
                <img src="<?= $resim_yolu ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($saha['ad']) ?>">
                <p><?= nl2br(htmlspecialchars($saha['aciklama'])) ?></p>
                
                <div class="saha-ozellikler mb-4">
                    <h4>Özellikler</h4>
                    <ul>
                        <li>Konum: <?= htmlspecialchars($saha['konum']) ?></li>
                        <li>Kapasite: <?= htmlspecialchars($saha['kapasite']) ?> kişi</li>
                        <li>Zemin Türü: <?= htmlspecialchars($saha['zemin_turu']) ?></li>
                    </ul>
                </div>
                
                <h4 class="mt-5">Yorumlar</h4>
                <?php if (empty($yorumlar)): ?>
                    <div class="alert alert-info">Henüz yorum yapılmamış.</div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($yorumlar as $yorum): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <h5><?= htmlspecialchars($yorum['ad_soyad']) ?></h5>
                                <small><?= $yorum['tarih'] ?></small>
                            </div>
                            <p><?= htmlspecialchars($yorum['yorum']) ?></p>
                            <?php if ($yorum['puan']): ?>
                                <div>Puan: <?= str_repeat('★', $yorum['puan']) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Rezervasyon Yap</h5>
                        <form action="rezervasyon-yap.php" method="POST">
                            <input type="hidden" name="saha_id" value="<?= $saha['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tarih</label>
                                    <input type="date" class="form-control" name="tarih" required 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Başlangıç Saati</label>
                                    <input type="time" class="form-control" name="baslangic_saati" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Bitiş Saati</label>
                                    <input type="time" class="form-control" name="bitis_saati" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ev Sahibi Takım</label>
                                    <input type="text" class="form-control" name="ev_sahibi_takim" 
                                           value="<?= htmlspecialchars($_SESSION['kullanici']['takim_adi'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Deplasman Takım</label>
                                    <input type="text" class="form-control" name="deplasman_takim">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted">Tahmini Ücret: <span id="tahmini-ucret">0</span> TL</p>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Rezervasyon Yap</button>
                        </form>
                    </div>
                </div>

                <script>
                // Tahmini ücret hesaplama (eski kod korundu)
                document.addEventListener('DOMContentLoaded', function() {
                    const ucretSaatlik = <?= $saha['ucret'] ?? 0 ?>;
                    const tarihInput = document.querySelector('input[name="tarih"]');
                    const baslangicInput = document.querySelector('input[name="baslangic_saati"]');
                    const bitisInput = document.querySelector('input[name="bitis_saati"]');
                    const tahminiUcretSpan = document.getElementById('tahmini-ucret');
                    
                    function hesaplaUcret() {
                        if (baslangicInput.value && bitisInput.value) {
                            const baslangic = new Date(`2000-01-01T${baslangicInput.value}`);
                            const bitis = new Date(`2000-01-01T${bitisInput.value}`);
                            
                            if (bitis > baslangic) {
                                const saatFarki = (bitis - baslangic) / (1000 * 60 * 60);
                                tahminiUcretSpan.textContent = (saatFarki * ucretSaatlik).toFixed(2);
                            }
                        }
                    }
                    
                    baslangicInput.addEventListener('change', hesaplaUcret);
                    bitisInput.addEventListener('change', hesaplaUcret);
                    
                    // Bugünün tarihini minimum değer olarak ayarla
                    const today = new Date().toISOString().split('T')[0];
                    tarihInput.min = today;
                });
                </script>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Yorum Yap</h5>
                        <?php if (isset($_SESSION['kullanici'])): ?>
                            <form action="yorum-yap.php" method="POST">
                                <input type="hidden" name="yorum_yap" value="1">
                                <input type="hidden" name="saha_id" value="<?= $saha['id'] ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Yorumunuz</label>
                                    <textarea class="form-control" name="yorum" rows="3" required minlength="10" 
                                              placeholder="En az 10 karakter olmalıdır..."></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Puan</label>
                                    <select class="form-select" name="puan" required>
                                        <option value="">Puan seçin</option>
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★</option>
                                        <option value="3">3 ★★★</option>
                                        <option value="2">2 ★★</option>
                                        <option value="1">1 ★</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Gönder</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Yorum yapmak için <a href="giris.php" class="alert-link">giriş yapmalısınız</a>.
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