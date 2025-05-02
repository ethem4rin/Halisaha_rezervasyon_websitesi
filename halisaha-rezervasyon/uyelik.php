<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap / Üye Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#giris">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kayit">Üye Ol</a>
                    </li>
                </ul>
                
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="giris">
                        <form action="giris.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="sifre" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="sifre" name="sifre" required>
                            </div>
                            <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="kayit">
                        <form action="kayit.php" method="POST">
                            <div class="mb-3">
                                <label for="ad_soyad" class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_kayit" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_kayit" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="telefon" name="telefon">
                            </div>
                            <div class="mb-3">
                                <label for="sifre_kayit" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="sifre_kayit" name="sifre" required>
                            </div>
                            <button type="submit" name="kayit" class="btn btn-primary">Üye Ol</button>
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