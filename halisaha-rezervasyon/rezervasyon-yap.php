<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: uyelik.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form verilerini al
    $saha_id = intval($_POST['saha_id'] ?? 0);
    $tarih = $_POST['tarih'] ?? '';
    $baslangic_saati = $_POST['baslangic_saati'] ?? '';
    $bitis_saati = $_POST['bitis_saati'] ?? '';
    $ev_sahibi_takim = $_POST['ev_sahibi_takim'] ?? 'Takımım';
    $deplasman_takim = $_POST['deplasman_takim'] ?? 'Rakip Takım';
    $kullanici_id = $_SESSION['kullanici']['id'];

    try {
        // Giriş doğrulama
        if (empty($saha_id) || empty($tarih) || empty($baslangic_saati) || empty($bitis_saati)) {
            throw new Exception("Tüm zorunlu alanları doldurmalısınız!");
        }

        // Saha bilgilerini al
        $stmt = $db->prepare("SELECT ucret, ad FROM sahalar WHERE id = ? AND aktif = 1");
        $stmt->execute([$saha_id]);
        $saha = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$saha) {
            throw new Exception("Saha bulunamadı veya rezervasyona kapalı!");
        }

        // Rezervasyon süresini hesapla
        $baslangic = strtotime($baslangic_saati);
        $bitis = strtotime($bitis_saati);
        
        if ($baslangic >= $bitis) {
            throw new Exception("Başlangıç saati bitiş saatinden önce olmalıdır!");
        }
        
        $sure = ($bitis - $baslangic) / 3600; // Saat cinsinden süre
        $ucret = $sure * $saha['ucret'];

        // Transaction başlat
        $db->beginTransaction();

        try {
            // 1. REZERVASYONU OLUŞTUR
            $stmt = $db->prepare("
                INSERT INTO rezervasyonlar 
                (kullanici_id, saha_id, tarih, baslangic_saati, bitis_saati, ucret, odeme_durumu) 
                VALUES (?, ?, ?, ?, ?, ?, 'bekliyor')
            ");
            $stmt->execute([
                $kullanici_id, 
                $saha_id, 
                $tarih, 
                $baslangic_saati, 
                $bitis_saati, 
                $ucret
            ]);
            $rezervasyon_id = $db->lastInsertId();

            // 2. OTOMATİK MAÇ KAYDI OLUŞTUR
            $mac_baslangic = "$tarih $baslangic_saati:00";
            $mac_bitis = "$tarih $bitis_saati:00";
            
            $stmt = $db->prepare("
                INSERT INTO maclar 
                (rezervasyon_id, ev_sahibi_takim, deplasman_takim, mac_durumu, baslama_zamani, bitis_zamani) 
                VALUES (?, ?, ?, 'planlanan', ?, ?)
            ");
            $stmt->execute([
                $rezervasyon_id,
                $ev_sahibi_takim,
                $deplasman_takim,
                $mac_baslangic,
                $mac_bitis
            ]);
            $mac_id = $db->lastInsertId();

            // 3. KULLANICIYI MAÇA EKLE (EV SAHİBİ TAKIM KAPTANI OLARAK)
            $stmt = $db->prepare("
                INSERT INTO mac_katilimcilari 
                (mac_id, kullanici_id, takim_adi, rol) 
                VALUES (?, ?, ?, 'kaptan')
            ");
            $stmt->execute([$mac_id, $kullanici_id, $ev_sahibi_takim]);

            $db->commit();

            // Ödeme sayfasına yönlendir
            header("Location: odeme.php?rezervasyon_id=$rezervasyon_id");
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception("İşlem sırasında hata: " . $e->getMessage());
        }

    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        header("Location: index.php");
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>