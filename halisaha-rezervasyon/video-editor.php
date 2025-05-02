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
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Video Düzenle</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="ratio ratio-16x9 mb-4">
                    <video id="editor-video" controls>
                        <source src="<?= $video['dosya_yolu'] ?>" type="video/mp4">
                    </video>
                </div>
                
                <form action="video-islem.php" method="POST">
                    <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="baslangic" class="form-label">Başlangıç (saniye)</label>
                            <input type="number" class="form-control" id="baslangic" name="baslangic" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label for="bitis" class="form-label">Bitiş (saniye)</label>
                            <input type="number" class="form-control" id="bitis" name="bitis" min="1" value="<?= floor($video['sure']) ?>">
                        </div>
                    </div>
                    
                    <button type="submit" name="video_kirp" class="btn btn-primary">Videoyu Kırp</button>
                    <a href="video-paylas.php?id=<?= $video['id'] ?>" class="btn btn-success">Paylaş</a>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Video süresini al ve bitiş zamanını ayarla
        document.getElementById('editor-video').addEventListener('loadedmetadata', function() {
            const video = this;
            const duration = Math.floor(video.duration);
            document.getElementById('bitis').value = duration;
            document.getElementById('bitis').max = duration;
        });
    </script>
</body>
</html>