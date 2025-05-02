<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rezervasyon Yönetimi</h1>
</div>

<?php
// Rezervasyon durum güncelleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['durum_guncelle'])) {
    $rezervasyon_id = $_POST['rezervasyon_id'];
    $durum = $_POST['durum'];

    try {
        $stmt = $db->prepare("UPDATE rezervasyonlar SET odeme_durumu = ? WHERE id = ?");
        $stmt->execute([$durum, $rezervasyon_id]);
        echo '<div class="alert alert-success">Rezervasyon durumu güncellendi!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
    }
}

// Rezervasyon silme
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    try {
        $stmt = $db->prepare("DELETE FROM rezervasyonlar WHERE id = ?");
        $stmt->execute([$id]);
        echo '<div class="alert alert-success">Rezervasyon başarıyla silindi!</div>';
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
                <th>Saha</th>
                <th>Tarih</th>
                <th>Saat</th>
                <th>Ücret</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("
                SELECT r.*, u.ad_soyad, s.ad AS saha_ad 
                FROM rezervasyonlar r
                JOIN kullanicilar u ON r.kullanici_id = u.id
                JOIN sahalar s ON r.saha_id = s.id
                ORDER BY r.tarih DESC
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['ad_soyad']}</td>
                    <td>{$row['saha_ad']}</td>
                    <td>{$row['tarih']}</td>
                    <td>{$row['baslangic_saati']} - {$row['bitis_saati']}</td>
                    <td>{$row['ucret']} TL</td>
                    <td>
                        <form method='POST' class='d-inline'>
                            <input type='hidden' name='rezervasyon_id' value='{$row['id']}'>
                            <select name='durum' class='form-select form-select-sm' onchange='this.form.submit()'>
                                <option value='bekliyor' " . ($row['odeme_durumu'] == 'bekliyor' ? 'selected' : '') . ">Bekliyor</option>
                                <option value='tamamlandi' " . ($row['odeme_durumu'] == 'tamamlandi' ? 'selected' : '') . ">Tamamlandı</option>
                                <option value='iptal' " . ($row['odeme_durumu'] == 'iptal' ? 'selected' : '') . ">İptal</option>
                            </select>
                            <input type='hidden' name='durum_guncelle'>
                        </form>
                    </td>
                    <td>
                        <a href='?page=rezervasyonlar&sil={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu rezervasyonu silmek istediğinize emin misiniz?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>