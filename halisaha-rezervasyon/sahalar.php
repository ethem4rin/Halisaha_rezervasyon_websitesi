<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahalar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        .saha-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .saha-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        .saha-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            padding: 1.5rem;
        }
        .ucret-badge {
            font-size: 1rem;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h1 class="mb-4 text-center">Halı Sahalarımız</h1>
        
        <div class="row">
            <?php
            $stmt = $db->query("SELECT * FROM sahalar WHERE aktif = 1 ORDER BY ad ASC");
            while ($saha = $stmt->fetch(PDO::FETCH_ASSOC)):
                // Resim yolu kontrolü
                $resim_yolu = !empty($saha['resim_yolu']) ? $saha['resim_yolu'] : 'assets/img/default-saha.jpg';
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 saha-card">
                    <img src="<?= htmlspecialchars($resim_yolu) ?>" class="saha-img card-img-top" alt="<?= htmlspecialchars($saha['ad']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($saha['ad']) ?></h5>
                        <p class="card-text text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($saha['konum']) ?>
                        </p>
                        <p class="card-text"><?= htmlspecialchars($saha['aciklama']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary ucret-badge">
                                <?= number_format($saha['ucret'], 2) ?> TL/saat
                            </span>
                            <a href="saha-detay.php?id=<?= $saha['id'] ?>" class="btn btn-outline-primary">
                                Detaylar <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>