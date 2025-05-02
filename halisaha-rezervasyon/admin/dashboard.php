<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Toplam Kullanıcı</h5>
                <?php
                $stmt = $db->query("SELECT COUNT(*) FROM kullanicilar");
                $count = $stmt->fetchColumn();
                ?>
                <h2 class="card-text"><?= $count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Aktif Rezervasyon</h5>
                <?php
                $stmt = $db->query("SELECT COUNT(*) FROM rezervasyonlar WHERE odeme_durumu = 'tamamlandi'");
                $count = $stmt->fetchColumn();
                ?>
                <h2 class="card-text"><?= $count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Toplam Saha</h5>
                <?php
                $stmt = $db->query("SELECT COUNT(*) FROM sahalar WHERE aktif = 1");
                $count = $stmt->fetchColumn();
                ?>
                <h2 class="card-text"><?= $count ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Son Rezervasyonlar
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Saha</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->query("
                            SELECT r.id, s.ad, r.tarih, r.odeme_durumu 
                            FROM rezervasyonlar r
                            JOIN sahalar s ON r.saha_id = s.id
                            ORDER BY r.rezervasyon_tarihi DESC LIMIT 5
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <td>{$row['ad']}</td>
                                <td>{$row['tarih']}</td>
                                <td>";
                            if ($row['odeme_durumu'] == 'tamamlandi') {
                                echo '<span class="badge bg-success">Tamamlandı</span>';
                            } elseif ($row['odeme_durumu'] == 'bekliyor') {
                                echo '<span class="badge bg-warning">Bekliyor</span>';
                            } else {
                                echo '<span class="badge bg-danger">İptal</span>';
                            }
                            echo "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Son Kullanıcılar
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Email</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->query("SELECT ad_soyad, email, rol FROM kullanicilar ORDER BY kayit_tarihi DESC LIMIT 5");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <td>{$row['ad_soyad']}</td>
                                <td>{$row['email']}</td>
                                <td>";
                            echo $row['rol'] == 'admin' ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-primary">Kullanıcı</span>';
                            echo "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>