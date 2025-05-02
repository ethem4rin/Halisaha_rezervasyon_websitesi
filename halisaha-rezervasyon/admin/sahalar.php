<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Saha Yönetimi</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sahaEkleModal">
        <i class="bi bi-plus"></i> Yeni Saha
    </button>
</div>
<link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
<?php
// Saha ekleme formu işleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saha_ekle'])) {
    $ad = $_POST['ad'];
    $adres = $_POST['adres'];
    $aciklama = $_POST['aciklama'];
    $ucret = $_POST['ucret'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $konum = $_POST['konum'];
    $telefon = $_POST['telefon'];
    $kapasite = $_POST['kapasite'];
    $zemin_turu = $_POST['zemin_turu'];
    
    // Resim yükleme işlemi
    $resim_yolu = null;
    if ($_FILES['resim']['error'] == UPLOAD_ERR_OK) {
        $hedefKlasor = __DIR__ . '/../assets/img/sahalar/';
        if (!file_exists($hedefKlasor)) {
            mkdir($hedefKlasor, 0777, true);
        }
        
        $dosyaUzantisi = pathinfo($_FILES['resim']['name'], PATHINFO_EXTENSION);
        $resimAdi = uniqid() . '.' . $dosyaUzantisi;
        $hedefDosya = $hedefKlasor . $resimAdi;
        
        if (move_uploaded_file($_FILES['resim']['tmp_name'], $hedefDosya)) {
            $resim_yolu = 'assets/img/sahalar/' . $resimAdi;
        } else {
            echo '<div class="alert alert-danger">Resim yüklenirken hata oluştu!</div>';
        }
    }

    try {
        $stmt = $db->prepare("INSERT INTO sahalar 
            (ad, adres, aciklama, ucret, aktif, konum, telefon, kapasite, zemin_turu, resim_yolu) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $ad, $adres, $aciklama, $ucret, $aktif, 
            $konum, $telefon, $kapasite, $zemin_turu, $resim_yolu
        ]);
        echo '<div class="alert alert-success">Saha başarıyla eklendi!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
    }
}

// Saha silme işlemi
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    try {
        // Önce resmi sil
        $stmt = $db->prepare("SELECT resim_yolu FROM sahalar WHERE id = ?");
        $stmt->execute([$id]);
        $saha = $stmt->fetch();
        
        if ($saha && $saha['resim_yolu'] && file_exists(__DIR__ . '/../' . $saha['resim_yolu'])) {
            unlink(__DIR__ . '/../' . $saha['resim_yolu']);
        }
        
        // Sonra sahayı sil
        $stmt = $db->prepare("DELETE FROM sahalar WHERE id = ?");
        $stmt->execute([$id]);
        echo '<div class="alert alert-success">Saha başarıyla silindi!</div>';
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
                <th>Ad</th>
                <th>Konum</th>
                <th>Telefon</th>
                <th>Ücret</th>
                <th>Kapasite</th>
                <th>Zemin Türü</th>
                <th>Resim</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("SELECT * FROM sahalar ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['ad']}</td>
                    <td>{$row['konum']}</td>
                    <td>{$row['telefon']}</td>
                    <td>{$row['ucret']} TL</td>
                    <td>{$row['kapasite']}</td>
                    <td>{$row['zemin_turu']}</td>
                    <td>";
                if ($row['resim_yolu']) {
                    echo "<img src='{$row['resim_yolu']}' style='max-width: 100px; max-height: 60px;' class='img-thumbnail'>";
                } else {
                    echo "<span class='text-muted'>Resim yok</span>";
                }
                echo "</td>
                    <td>";
                echo $row['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>';
                echo "</td>
                    <td>
                        <a href='?page=saha_duzenle&id={$row['id']}' class='btn btn-sm btn-warning'>
                            <i class='bi bi-pencil'></i>
                        </a>
                        <a href='?page=sahalar&sil={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu sahayı silmek istediğinize emin misiniz?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Saha Ekle Modal -->
<div class="modal fade" id="sahaEkleModal" tabindex="-1" aria-labelledby="sahaEkleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sahaEkleModalLabel">Yeni Saha Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ad" class="form-label">Saha Adı*</label>
                                <input type="text" class="form-control" id="ad" name="ad" required>
                            </div>
                            <div class="mb-3">
                                <label for="adres" class="form-label">Adres*</label>
                                <textarea class="form-control" id="adres" name="adres" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="konum" class="form-label">Konum*</label>
                                <input type="text" class="form-control" id="konum" name="konum" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefon" class="form-label">Telefon*</label>
                                <input type="text" class="form-control" id="telefon" name="telefon" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ucret" class="form-label">Saatlik Ücret (TL)*</label>
                                <input type="number" class="form-control" id="ucret" name="ucret" step="0.01" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="kapasite" class="form-label">Kapasite*</label>
                                <input type="number" class="form-control" id="kapasite" name="kapasite" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="zemin_turu" class="form-label">Zemin Türü*</label>
                                <select class="form-select" id="zemin_turu" name="zemin_turu" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Sentetik">Sentetik</option>
                                    <option value="Toprak">Toprak</option>
                                    <option value="Çim">Çim</option>
                                    <option value="Hibrit">Hibrit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="resim" class="form-label">Saha Resmi</label>
                                <input type="file" class="form-control" id="resim" name="resim" accept="image/*">
                                <small class="text-muted">JPEG, PNG veya JPG formatında (max 2MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="aciklama" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="aciklama" name="aciklama" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" checked>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary" name="saha_ekle">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>