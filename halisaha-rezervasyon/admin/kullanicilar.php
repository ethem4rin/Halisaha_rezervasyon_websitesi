<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kullanıcı Yönetimi</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kullaniciEkleModal">
        <i class="bi bi-plus"></i> Yeni Kullanıcı
    </button>
</div>

<?php
// Kullanıcı ekleme formu işleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kullanici_ekle'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    try {
        $stmt = $db->prepare("INSERT INTO kullanicilar (ad_soyad, email, telefon, sifre, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$ad_soyad, $email, $telefon, $sifre, $rol]);
        echo '<div class="alert alert-success">Kullanıcı başarıyla eklendi!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
    }
}

// Kullanıcı silme işlemi
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    try {
        $stmt = $db->prepare("DELETE FROM kullanicilar WHERE id = ?");
        $stmt->execute([$id]);
        echo '<div class="alert alert-success">Kullanıcı başarıyla silindi!</div>';
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
                <th>Ad Soyad</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Rol</th>
                <th>Kayıt Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("SELECT * FROM kullanicilar ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['ad_soyad']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['telefon']}</td>
                    <td>";
                echo $row['rol'] == 'admin' ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-primary">Kullanıcı</span>';
                echo "</td>
                    <td>{$row['kayit_tarihi']}</td>
                    <td>
                        <a href='?page=kullanici_duzenle&id={$row['id']}' class='btn btn-sm btn-warning'>
                            <i class='bi bi-pencil'></i>
                        </a>
                        <a href='?page=kullanicilar&sil={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu kullanıcıyı silmek istediğinize emin misiniz?\")'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Kullanıcı Ekle Modal -->
<div class="modal fade" id="kullaniciEkleModal" tabindex="-1" aria-labelledby="kullaniciEkleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kullaniciEkleModalLabel">Yeni Kullanıcı Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ad_soyad" class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefon" class="form-label">Telefon</label>
                        <input type="text" class="form-control" id="telefon" name="telefon">
                    </div>
                    <div class="mb-3">
                        <label for="sifre" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="sifre" name="sifre" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="kullanici">Kullanıcı</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary" name="kullanici_ekle">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>