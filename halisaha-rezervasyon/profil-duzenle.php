<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

$kullanici_id = $_SESSION['kullanici']['id'];

// Form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guncelle'])) {
    $ad_soyad = trim($_POST['ad_soyad']);
    $telefon = trim($_POST['telefon']);
    $mevcut_sifre = $_POST['mevcut_sifre'];
    $yeni_sifre = $_POST['yeni_sifre'];
    
    try {
        // Kullanıcı bilgilerini al
        $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
        $stmt->execute([$kullanici_id]);
        $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$kullanici) {
            throw new Exception("Kullanıcı bulunamadı!");
        }
        
        // Şifre değişikliği yapılacak mı?
        $sifre_guncelle = !empty($yeni_sifre);
        
        if ($sifre_guncelle) {
            if (empty($mevcut_sifre)) {
                throw new Exception("Mevcut şifrenizi giriniz!");
            }
            
            if (!password_verify($mevcut_sifre, $kullanici['sifre'])) {
                throw new Exception("Mevcut şifreniz yanlış!");
            }
            
            if (strlen($yeni_sifre) < 6) {
                throw new Exception("Yeni şifre en az 6 karakter olmalıdır!");
            }
        }
        
        // Güncelleme sorgusu
        if ($sifre_guncelle) {
            $sifre_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, telefon = ?, sifre = ? WHERE id = ?");
            $stmt->execute([$ad_soyad, $telefon, $sifre_hash, $kullanici_id]);
        } else {
            $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, telefon = ? WHERE id = ?");
            $stmt->execute([$ad_soyad, $telefon, $kullanici_id]);
        }
        
        $_SESSION['basari'] = "Profil bilgileriniz başarıyla güncellendi!";
        header('Location: profil.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        header('Location: profil-duzenle.php');
        exit;
    }
}

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
    header('Location: profil.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profili Düzenle - Halı Saha Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        .card {
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Profili Düzenle</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['hata'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['hata'] ?></div>
                            <?php unset($_SESSION['hata']); ?>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="ad_soyad" class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" 
                                       value="<?= htmlspecialchars($kullanici['ad_soyad']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" 
                                       value="<?= htmlspecialchars($kullanici['email']) ?>" disabled>
                                <small class="text-muted">Email adresinizi değiştirmek için lütfen yöneticiyle iletişime geçin.</small>
                            </div>
                            <div class="mb-3">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="telefon" name="telefon" 
                                       value="<?= htmlspecialchars($kullanici['telefon']) ?>" required>
                            </div>
                            
                            <hr>
                            <h5 class="mb-3">Şifre Değiştir</h5>
                            <div class="mb-3">
                                <label for="mevcut_sifre" class="form-label">Mevcut Şifre</label>
                                <input type="password" class="form-control" id="mevcut_sifre" name="mevcut_sifre">
                                <small class="text-muted">Şifrenizi değiştirmek istemiyorsanız boş bırakabilirsiniz.</small>
                            </div>
                            <div class="mb-3">
                                <label for="yeni_sifre" class="form-label">Yeni Şifre</label>
                                <input type="password" class="form-control" id="yeni_sifre" name="yeni_sifre">
                                <small class="text-muted">En az 6 karakter olmalıdır.</small>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="profil.php" class="btn btn-secondary me-md-2">İptal</a>
                                <button type="submit" name="guncelle" class="btn btn-primary">Güncelle</button>
                            </div>
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