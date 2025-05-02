<?php 
require_once 'config.php';

if (!isset($_SESSION['kullanici'])) {
    header('Location: uyelik.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: macini-bul.php');
    exit;
}

$video_id = $_GET['id'];
$kullanici_id = $_SESSION['kullanici']['id'];

$stmt = $db->prepare("SELECT * FROM videolar WHERE id = ? AND kullanici_id = ?");
$stmt->execute([$video_id, $kullanici_id]);
$video = $stmt->fetch();

if (!$video) {
    header('Location: macini-bul.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paylas'])) {
    $baslik = $_POST['baslik'];
    $aciklama = $_POST['aciklama'];
    
    try {
        $stmt = $db->prepare("UPDATE videolar SET baslik = ?, aciklama = ?, paylasildi = 1 WHERE id = ?");
        $stmt->execute([$baslik, $aciklama, $video_id]);
        
        $_SESSION['basari'] = "Video başarıyla paylaşıldı!";
        header('Location: macini-bul.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['hata'] = "Hata: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Paylaş</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Video Paylaş</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="ratio ratio-16x9 mb-4">
                    <video controls>
                        <source src="<?= $video['dosya_yolu'] ?>" type="video/mp4">
                    </video>
                </div>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="baslik" class="form-label">Başlık</label>
                        <input type="text" class="form-control" id="baslik" name="baslik" value="<?= $video['baslik'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="aciklama" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="aciklama" name="aciklama" rows="3"><?= $video['aciklama'] ?></textarea>
                    </div>
                    <button type="submit" name="paylas" class="btn btn-primary">Paylaş</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>