-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 02 May 2025, 08:17:28
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `halisaha`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `begeniler`
--

DROP TABLE IF EXISTS `begeniler`;
CREATE TABLE IF NOT EXISTS `begeniler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `video_id` int NOT NULL,
  `tarih` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullanici_id` (`kullanici_id`,`video_id`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sifre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','kullanici') COLLATE utf8mb4_general_ci DEFAULT 'kullanici',
  `kayit_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `email`, `telefon`, `sifre`, `rol`, `kayit_tarihi`) VALUES
(5, 'Okan Özkaya', '1230505057@ogr.klu.edu.tr', '05538351195', '$2y$10$sMdsCVChMxFRoc8zKIhCk.W3x2ZprrPgzZ/pREnwGF9w1ZmE9Y3Kq', 'kullanici', '2025-05-02 11:10:51'),
(3, 'Admin', 'admin@example.com', '+905551234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-04-30 00:25:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `maclar`
--

DROP TABLE IF EXISTS `maclar`;
CREATE TABLE IF NOT EXISTS `maclar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rezervasyon_id` int NOT NULL,
  `ev_sahibi_takim` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deplasman_takim` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `skor` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mac_durumu` enum('planlanan','oynandi','iptal') COLLATE utf8mb4_general_ci DEFAULT 'planlanan',
  `baslama_zamani` datetime NOT NULL,
  `bitis_zamani` datetime NOT NULL,
  `aciklama` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `rezervasyon_id` (`rezervasyon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `maclar`
--

INSERT INTO `maclar` (`id`, `rezervasyon_id`, `ev_sahibi_takim`, `deplasman_takim`, `skor`, `mac_durumu`, `baslama_zamani`, `bitis_zamani`, `aciklama`) VALUES
(1, 4, 'adaşlar1', 'adaşlar2', NULL, 'planlanan', '2025-05-02 13:00:00', '2025-05-02 14:00:00', NULL),
(2, 5, 'adaşlar1', 'adaşlar2', NULL, 'oynandi', '2025-05-01 14:51:00', '2025-05-01 14:52:00', NULL),
(3, 6, '', '', NULL, 'oynandi', '2025-05-01 15:00:00', '2025-05-01 16:00:00', NULL),
(4, 7, 'adaşlar1', 'adaşlar2', NULL, 'oynandi', '2025-05-02 09:00:00', '2025-05-02 10:00:00', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mac_katilimcilari`
--

DROP TABLE IF EXISTS `mac_katilimcilari`;
CREATE TABLE IF NOT EXISTS `mac_katilimcilari` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mac_id` int NOT NULL,
  `kullanici_id` int NOT NULL,
  `takim_adi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('kaptan','oyuncu') COLLATE utf8mb4_general_ci DEFAULT 'oyuncu',
  `katilim_durumu` enum('onayli','beklemede','reddedildi') COLLATE utf8mb4_general_ci DEFAULT 'beklemede',
  `kayit_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mac_id` (`mac_id`),
  KEY `kullanici_id` (`kullanici_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `mac_katilimcilari`
--

INSERT INTO `mac_katilimcilari` (`id`, `mac_id`, `kullanici_id`, `takim_adi`, `rol`, `katilim_durumu`, `kayit_tarihi`) VALUES
(1, 1, 4, 'adaşlar1', 'kaptan', 'beklemede', '2025-05-01 14:46:35'),
(2, 2, 4, 'adaşlar1', 'kaptan', 'beklemede', '2025-05-01 14:50:36'),
(3, 3, 4, '', 'kaptan', 'beklemede', '2025-05-01 23:16:02'),
(4, 4, 5, 'adaşlar1', 'kaptan', 'beklemede', '2025-05-02 11:12:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rezervasyonlar`
--

DROP TABLE IF EXISTS `rezervasyonlar`;
CREATE TABLE IF NOT EXISTS `rezervasyonlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `saha_id` int NOT NULL,
  `tarih` date NOT NULL,
  `baslangic_saati` time NOT NULL,
  `bitis_saati` time NOT NULL,
  `ucret` decimal(10,2) NOT NULL,
  `odeme_durumu` enum('bekliyor','tamamlandi','iptal') COLLATE utf8mb4_general_ci DEFAULT 'bekliyor',
  `rezervasyon_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `saha_id` (`saha_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `rezervasyonlar`
--

INSERT INTO `rezervasyonlar` (`id`, `kullanici_id`, `saha_id`, `tarih`, `baslangic_saati`, `bitis_saati`, `ucret`, `odeme_durumu`, `rezervasyon_tarihi`) VALUES
(1, 4, 1, '2025-05-01', '13:00:00', '14:00:00', 100.00, 'tamamlandi', '2025-05-01 14:35:37'),
(2, 4, 1, '2025-05-01', '13:00:00', '14:00:00', 100.00, 'tamamlandi', '2025-05-01 14:36:10'),
(3, 4, 1, '2025-06-01', '13:00:00', '14:00:00', 100.00, 'tamamlandi', '2025-05-01 14:39:59'),
(4, 4, 1, '2025-05-02', '13:00:00', '14:00:00', 100.00, 'tamamlandi', '2025-05-01 14:46:35'),
(5, 4, 1, '2025-05-01', '14:51:00', '14:52:00', 1.67, 'iptal', '2025-05-01 14:50:36'),
(6, 4, 4, '2025-05-01', '15:00:00', '16:00:00', 2200.00, 'tamamlandi', '2025-05-01 23:16:02'),
(7, 5, 5, '2025-05-02', '09:00:00', '10:00:00', 2200.00, 'tamamlandi', '2025-05-02 11:12:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sahalar`
--

DROP TABLE IF EXISTS `sahalar`;
CREATE TABLE IF NOT EXISTS `sahalar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `adres` text COLLATE utf8mb4_general_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_general_ci,
  `resim` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ucret` decimal(10,2) NOT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `konum` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Belirtilmemiş',
  `telefon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kapasite` int DEFAULT '10',
  `zemin_turu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Sentetik',
  `resim_yolu` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'saha.jpg',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `sahalar`
--

INSERT INTO `sahalar` (`id`, `ad`, `adres`, `aciklama`, `resim`, `ucret`, `aktif`, `konum`, `telefon`, `kapasite`, `zemin_turu`, `resim_yolu`) VALUES
(5, 'Olimpiyat Halı Saha', 'Bademlik, Bademlik Cd. No:58, 39100 Kırklareli Merkez/Kırklareli', 'Sizlere en iyi hizmeti sunmaktan keyif alıyoruz.', NULL, 2200.00, 1, 'Bademlik, Bademlik Cd. No:58, 39100 Kırklareli Merkez/Kırklareli', '0546 289 39 39', 18, 'Sentetik', 'assets/img/sahalar/68147a62e731f.jpg'),
(6, 'Kırmızı Beyaz Halı Saha', 'İstasyon mah, 5. Yurt Sk. No:18, Kırklareli/merkez', 'Kırklareli halkına hak ettiği hizmeti sunmaktan mutluluk duyarız', NULL, 2200.00, 1, 'İstasyon mah, 5. Yurt Sk. No:18, Kırklareli/merkez', '0546 289 39 39', 20, 'Sentetik', 'assets/img/sahalar/68147aaf59252.jpg'),
(7, 'Kaptanlar Spor Tesisleri', 'Atatürk, Kofcaz Yolu, 39100 Karakoç/Kırklareli Merkez/Kırklareli', 'KYK öğrencilerine %50 indirimli ', NULL, 2200.00, 1, 'Atatürk, Kofcaz Yolu, 39100 Karakoç/Kırklareli Merkez/Kırklareli', '0552 123 45 67', 14, 'Sentetik', 'assets/img/sahalar/68147b2ce4b0e.jpg'),
(8, 'Özer Halı Saha', 'İstasyon, 39020 Kırklareli Merkez/Kırklareli', 'kırklareli halkına hak ettiği hizmeti sunmaktan mutluluk duyarız', NULL, 2200.00, 1, 'İstasyon, 39020 Kırklareli Merkez/Kırklareli', '0553 789 45 61', 16, 'Sentetik', 'assets/img/sahalar/68147d9c54140.jpg'),
(9, 'Royal Bilgiç Halı Saha', 'Karakaş mh. üzeri, Karakaş, Babaeski Yolu 1.km, 39100 Kırklareli Merkez/Kırklareli', 'Aradığınız kalite burada', NULL, 2200.00, 1, 'Karakaş mh. üzeri, Karakaş, Babaeski Yolu 1.km, 39100 Kırklareli Merkez/Kırklareli', '0530 145 15 00', 18, 'Sentetik', 'assets/img/sahalar/68147de790f7e.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `takimlar`
--

DROP TABLE IF EXISTS `takimlar`;
CREATE TABLE IF NOT EXISTS `takimlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `kurulus_tarihi` year DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `videolar`
--

DROP TABLE IF EXISTS `videolar`;
CREATE TABLE IF NOT EXISTS `videolar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `baslik` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_general_ci,
  `dosya_yolu` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `yukleme_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  `goruntulenme` int DEFAULT '0',
  `mac_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `mac_id` (`mac_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `videolar`
--

INSERT INTO `videolar` (`id`, `kullanici_id`, `baslik`, `aciklama`, `dosya_yolu`, `yukleme_tarihi`, `goruntulenme`, `mac_id`) VALUES
(1, 1, 'Muhteşem Gol!', 'Haftasonu maçımızdan harika bir gol', 'videos/mac1.mp4', '2025-05-01 13:23:16', 125, NULL),
(2, 2, 'Haftanın Maçı', 'Çekişmeli geçen maçımızdan kareler', 'videos/mac2.mp4', '2025-05-01 13:23:16', 89, NULL),
(10, 5, 'penaltı', 'osimhen', 'https://www.youtube.com/embed/1MIB6NEuk1Q', '2025-05-02 11:14:26', 0, 4),
(4, 0, 'Haftanın Golleri', 'Muhteşem gole izleyin!', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '2025-05-01 14:58:07', 1250, 1),
(5, 0, 'Maç Özeti', 'Tüm önemli anlar', 'https://www.youtube.com/embed/9bZkp7q19f0', '2025-05-01 14:58:07', 890, 1),
(6, 0, 'Penaltı Anı', 'Kritik penaltı vuruşu', 'https://www.youtube.com/embed/JGwWNGJdvx8', '2025-05-01 14:58:07', 1500, 2),
(7, 0, 'Serbest Vuruş', 'Mükemmel frikik golü', 'https://www.youtube.com/embed/oHg5SJYRHA0', '2025-05-01 14:58:07', 2100, 3),
(8, 4, 'okan', 'osimhennn', 'https://www.youtube.com/embed/_DH3Bwd1v5o', '2025-05-01 20:58:22', 0, 2),
(9, 4, 'osimhen', 'osimhen', 'https://www.youtube.com/embed/z6YcXEAjc_o', '2025-05-01 22:41:10', 0, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

DROP TABLE IF EXISTS `yorumlar`;
CREATE TABLE IF NOT EXISTS `yorumlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `saha_id` int NOT NULL,
  `video_id` int NOT NULL,
  `yorum` text COLLATE utf8mb4_general_ci NOT NULL,
  `puan` tinyint DEFAULT NULL,
  `tarih` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `video_id` (`video_id`),
  KEY `fk_yorumlar_saha` (`saha_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `kullanici_id`, `saha_id`, `video_id`, `yorum`, `puan`, `tarih`) VALUES
(1, 4, 1, 0, 'saha çok iyi sitede muhteşem bu siteyi hangi yakışıklılar yaptı', 5, '2025-05-01 13:40:37'),
(2, 5, 5, 0, 'Saha gerçekten çok kaliteli', 5, '2025-05-02 11:14:59');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
