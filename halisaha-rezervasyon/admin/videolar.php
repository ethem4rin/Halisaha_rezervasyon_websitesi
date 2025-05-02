<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Video Yönetimi</h1>
</div>

<?php
// Video silme
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    try {
        // Önce dosyayı bul
        $stmt = $db->prepare("SELECT dosya_yolu FROM videolar WHERE id = ?");
        $stmt->execute([$id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($video) {
            // Dosyayı sil
            if (file_exists($video['dosya_yolu'])) {
                unlink($video['dosya_yolu']);
            }
            
            // Veritabanından sil
            $stmt = $db->prepare("DELETE FROM videolar WHERE id = ?");
            $stmt->execute([$id]);
            echo '<div class="alert alert-success">Video başarıyla silindi!</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Kullanıcı</th>
                <th>Yükleme Tarihi</th>
                <th>Görüntülenme</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("
                SELECT v.id, v.baslik, v.yukleme_tarihi, v.goruntulenme, u.ad_soyad 
                FROM videolar v
                JOIN kullanicilar u ON v.kullanici_id = u.id
                ORDER BY v.yukleme_tarihi DESC
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['baslik']}</td>
                    <td>{$row['ad_soyad']}</td>
                    <td>{$row['yukleme_tarihi']}</td>
                    <td>{$row['goruntulenme']}</td>
                    <td>
                        <a href='?page=videolar&sil={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu videoyu silmek istediğinize emin misiniz?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
