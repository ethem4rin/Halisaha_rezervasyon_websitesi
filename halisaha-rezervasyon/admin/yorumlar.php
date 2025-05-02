<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Yorum Yönetimi</h1>
</div>

<?php
// Yorum silme
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    try {
        $stmt = $db->prepare("DELETE FROM yorumlar WHERE id = ?");
        $stmt->execute([$id]);
        echo '<div class="alert alert-success">Yorum başarıyla silindi!</div>';
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
                <th>Kullanıcı</th>
                <th>Video</th>
                <th>Yorum</th>
                <th>Puan</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("
                SELECT y.id, y.yorum, y.puan, y.tarih, u.ad_soyad, v.baslik AS video_baslik
                FROM yorumlar y
                JOIN kullanicilar u ON y.kullanici_id = u.id
                JOIN videolar v ON y.video_id = v.id
                ORDER BY y.tarih DESC
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['ad_soyad']}</td>
                    <td>{$row['video_baslik']}</td>
                    <td>{$row['yorum']}</td>
                    <td>";
                if ($row['puan']) {
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $row['puan'] ? '★' : '☆';
                    }
                } else {
                    echo 'Puan yok';
                }
                echo "</td>
                    <td>{$row['tarih']}</td>
                    <td>
                        <a href='?page=yorumlar&sil={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu yorumu silmek istediğinize emin misiniz?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>