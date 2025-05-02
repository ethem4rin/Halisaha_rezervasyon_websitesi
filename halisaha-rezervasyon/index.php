<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halı Saha Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/logo.jpg" type="image/x-icon" />
    <style>
        /* Reklam Panosu Stilleri */
        .reklam-panosu {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #eaeaea;
        }
        .reklam-item {
            padding: 8px;
            text-align: center;
            margin: 0 5px;
        }
        .reklam-item img {
            max-width: 100%;
            height: 120px;
            width: auto;
            border-radius: 5px;
            object-fit: contain;
            transition: transform 0.3s ease;
            border: 1px solid #eee;
            padding: 5px;
            background: white;
        }
        .reklam-item img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .reklam-item p {
            margin-top: 8px;
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        .reklam-baslik {
            text-align: center;
            margin-bottom: 12px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eaeaea;
        }
        
        /* Yan Reklam Stili */
        .yan-reklam {
            margin-bottom: 20px;
        }
        .yan-reklam img {
            max-width: 100%;
            height: auto;
            max-height: 150px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        /* Slick Carousel Düzenlemeleri */
        .slick-dots {
            bottom: -25px;
        }
        .slick-dots li button:before {
            font-size: 10px;
        }
        
        /* Saha Kartları */
        .saha-karti {
            transition: all 0.3s ease;
            height: 100%;
        }
        .saha-karti:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .saha-resim {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .saha-ucret {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Reklam Panosu -->
                <div class="reklam-panosu">
                    <h4 class="reklam-baslik"><i class="bi bi-megaphone"></i> SPONSORLARIMIZ</h4>
                    <div class="reklam-slider">
                        <div class="reklam-item">
                            <a href="https://www.adidas.com.tr/futbol" target="_blank" rel="noopener noreferrer">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" alt="Adidas Futbol Malzemeleri">
                                <p>Adidas Futbol Ekipmanları</p>
                            </a>
                        </div>
                        <div class="reklam-item">
                            <a href="https://www.nike.com/tr/" target="_blank" rel="noopener noreferrer">
                                <img src="https://shopigo.com/cdn/shop/collections/nike.webp?v=1741268702&width=740" alt="Nike Futbol Ayakkabıları">
                                <p>Nike Futbol Ayakkabıları</p>
                            </a>
                        </div>
                        <div class="reklam-item">
                            <a href="https://www.puma.com/tr/" target="_blank" rel="noopener noreferrer">
                                <img src="https://www.forummersin.com/media/image/9XHQ8QS3WN0BRW.jpg" alt="Puma Spor Giyim">
                                <p>Puma Spor Giyim</p>
                            </a>
                        </div>
                        <div class="reklam-item">
                            <a href="https://www.decathlon.com.tr/" target="_blank" rel="noopener noreferrer">
                                <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAKgAtAMBIgACEQEDEQH/xAAbAAEAAwEBAQEAAAAAAAAAAAAABQYHBAMCAf/EAEMQAAEDAgIGBQgGCgIDAAAAAAEAAgMEBRESBhMWIZPRMUFRUlQHFDZhcXOBwRUiNVWh0jJCcnSRo7GywvBi4SMzU//EABoBAQADAQEBAAAAAAAAAAAAAAACAwQBBgX/xAAyEQACAQICCQMDBAIDAAAAAAAAAQIDBBFRBRIUFSExUqHRE0FxImGRMrHB8DOBROHx/9oADAMBAAIRAxEAPwDcUREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAVzbaw+Kk4LuSbbWHxUnBdyWTovSbooZvt4PK77uMl38msbbWHxUnBdyTbaw+Kk4LuSydE3RQzfbwN93GS7+TWNtrD4qTgu5JttYfFScF3JZOibooZvt4G+7jJd/JrG21h8VJwXck22sPipOC7ksnRN0UM328Dfdxku/k1jbaw+Kk4LuSbbWHxUnBdyWTom6KGb7eBvu4yXfyaxttYfFScF3JNtrD4qTgu5LJ0TdFDN9vA33cZLv5NY22sPipOC7km21h8VJwXclk6Juihm+3gb7uMl38msbbWHxUnBdyTbaw+Kk4LuSydE3RQzfbwN93GS7+TWNtrD4qTgu5JttYfFScF3JZOibooZvt4G+7jJd/JrG21h8VJwXck22sPipOC7ksnRN0UM328Dfdxku/k1jbaw+Kk4LuSbbWHxUnBdyWTom6KGb7eBvu4yXfyaxttYfFScF3JFk6Juihm+3gb7uMl38k9sdpB93HjR/mXJXaP3e3xGWroJWRjpeMHAeslpOHxWsXu5x2e3SVs0b5GMLQWswx3nDrUbYdLaG9VRpY45YZsCWtkAwfh04EHpWaGkbqUfU1E4rn/cTVU0XZxmqeu1J8uXgzC3W+quVR5vQwmWbKXZQ4DcPWSFJ7H6QfdzuNH+ZX2GwRUGk0dxomBkU0T2ysHQ124gj24H/Su2/XqmsdKypq2SvY+QRgRAE44E9ZHYpT0pUlNKik8fzj+SNPRFKMJSryawy5Yfgyq5WG6WunbPX0phic/IHGRjt+BOG4nsK4qamnq5hDSwyTSnoYxpJVv0j0gptKYKW2W2CobUPqWka1rQOhw6ie1XC2W636N2x2DmRsY3NNUP3Fx7T8grp39SlSXqR+t+xRDRtKtWfpS+he5m50Rv4Zn+jn4YY4CRhP8M2Kh54JaeUxVEUkUg6WSNLSPgVp23dk1oZmqMv/ANNV9Xn+C59LXUV8goKSgbFU1lU/GGZhx1MYP1nHDq6sD6+sLlK+uFNKtDBP35Ha2jrZwboVMWvbg/2KPbbDdLpC6agpHSxtdlLs7WjHs3kLo2VvhnMAoDrWsDy3Wx9BJAP6XqK1a20MNtoYaOmbhHE3Aes9ZPrJ3qBv+kEFhvYdPBLKJqVoGrw3YOd2+1Ux0nWqzcaUU8v7iaJaJt6VNSqyazy/YpWx2kH3c7jR/mXxLopfIsmsoC3O4Mb/AOaPeT0D9JatbKxtwoIKyNjmNmYHBrukKsx6W0t1uVHQRUs8b/OWnM8jDdj2FKekLqbeEVw5/wBxFTRlnBRxm/q5cuPYpNx0eutsp/OK6k1UWYNzaxjt59hK5LdQVVyqPN6GHWzZS7LmA3D2kLSPKN6OH37Pmqr5OiNpB7h/yWujeTnayrNLFYmKvY06d5Ggm8HgcL9Fr2yeKF1CRJKCWN1rN+HT+t6wue52O5WqNklwptSx7srTrGuxPwJWrVv27a/dz/0aq75UPs6h9+f7Vnt9I1alWEGlx/7+/wBjTc6Lo0qM5xbxj8fb7GdKXpdGL3VxiSG3S5D0F5azH4OIVp8n2j8Xm4u9XGHPcT5u13Q0Ddmw7cej/tTN30wtVrqnU0hmnlacHiBoOU9hJI3qyvf1PVdKhHFrmVW+jafpKrcT1U+RmNfba23PDK6llhJ6C4bj7D0Fe1ssVzusT5bfSmaNjsrnB7W4HDHrI7VqtLVWvSS2vDMtRTu+q+N4wLT6x1HsK5NE7S6zR19ISXM85zRPPS5ha3D5j4Kp6UkqbxjhNexctDwdWLUsYP3MsrqKpt9S6mrIXRTNwxacD+I3Fdlu0futzpzUUNIZYg7Lm1jG7/iQtC01sH0xQa6maPPYBiz/AJt628vX7V4+Tjdo+8EEEVL8QercFN6SbtvUilrJ4NFcdFJXXpTb1WsUzNaulmoqmSmqY8k0Zwe3EHA/Dcik9L/Sa4e9+QRfTpSc6cZP3SPk1oKFSUF7Nl+0/wDRep/bj/vCoGiGfaa3av8AS1v4ZTj+GK1K+Wxl4tslFJK6Jr3NJc0YnccVGWDRCis1X522aSeYAhheAAzHpIHavP213TpWs6cubx7o9Ld2VWteQqR/SsOzLEvGqdTNYDWOhDMd2tIwx+Kh4r/FW6TR22ikD4oonvme3oc4YAAH1Yn/AELr0gssF9pI6aplkjYyTWAx4Yk4EdYPasPpakoqpwx4n0fV14SdPi1wPKofbn19rFM+mdJ5y4gRFpP/AKZOnDqUR5TNd9C04ZjqjUDWYfsnDH1f9KJv+j8GikVHdLfUTvmZUtAbKW4YYOPUB2YfFXKhrLfpHaiWhssEgyywu6WnsPYfX8QtmCoyhXh9UUYtaVeNS3n9Mn/f/TGVp2gNi+j6Hz+pZhVVLdwI3sj6QPaek/BdFJoTZaapE+rllwOLY5ZMWj4Yb/jipC6aQWu0yNirqoMkcMQxrS4gesAblfeXzuY+lQT48zNY6PVpJ1q7XDkKK6+eXmro4mEQ0zG4yEH67yTiB6hu/FUvym/a1J+7/wCRV2tN9t93fIy3zukdEAX4xubhj7R6lSfKb9rUn7v/AJFVWCcbtJxw4fwXaRkpWUmpY8ef+y5aJ+jlu9w1Zto36W0f7yfmtJ0T9HLd7hqzbRv0to/3k/NXWnO4/wB/yUXv/G+V/Bq9bNSQQ566SGOLMBmmIDcfiuejq7TPPloaiikmwJwhe0uw6+hQ/lG9HT79nzVU8nXpIPcP+Sy0bRTtZVseWJrr3rp3caGrzw4mgVv27a/2J/6NVd8qH2dQ+/P9qsVb9u2v3c/9Gr40gsVPfYIoamWWMRPzAx4Yndh1gqFvUjSq05y5Lyy25pSq0akI834R+2HHZmh83wzeZsy/tZeaxt2fMdZmz4/WzdOPXitntlLT2Shp6E1TnMzlkRmIxJOJyjD4qNuuhlquVW6pJmp5HnGTUuADj24EHf7Fps7ynQqT1uUuTMd9Y1bilBR5x5orPkyMv0xVBuOp83+v2Y5hl/y/FaSoujo7Zo1bpDHlggb9aSWQ4lx9Z6z2D+C5tFLs68sr6sgtj85yxNP6rA1uHzPxWe7k7icq0V9KwNVnBWsI0JPGTxJSnroairqqVhwmpXNEjT2FoIPs3/gV9UtHDSGcwNyiaUyvA6MxABI9uGPtxWeXy6zWfTypq4sS0FjZWd9hY3Ef71rRKSphrKWKpp354pWhzXdoKhcW8qMYyXKSRO2uY15yg/1RbXcyXTD0muHvB/QImmPpPcPeD+0IvT2/+GHwv2PI3X+efy/3PXbHSD7x/kR/lXNXaR3iviMVVXyujIwLWgMB9uUDFRSIreinioL8IO6ryWDm/wAs6rdcKq2VPnFDLqpspbmyh24+ogqU2x0g+8f5Ef5VAouzo0pvGUU38EYXFamsISaX2ZJ3O/3S6wNgr6rXRNfnDdWxv1sCMdwHaVx0dZU0Mwlo55IZO8x2GPt7V4IpKnCMdVJYEZVakpa7k8cybk0tv0kZjdcn5SMMWsY0/wAQMVDSPfI9z5Hue9xxc5xxJPrK+USFKEP0RS+DtStUqfrk38s7rXdq60vkfb6gwukADzka7ED2gr5ud0rbrM2a4T657G5WnI1uA6eoDtXGienDW18Fjn7nPVqamprPDL2Jmk0ovVHTR09NXZIY25WN1TDgPaW4qNpquelq2VdPJknY7M1+AOB9h3LwRcVKmscIrjz4czsq1WWGMnw5ceRKXHSG63On83rqvWxZg7Lq2N3j2AFctuuFVbKnzmhl1U2UtzZQ7cfaCuVF1UqajqqKwyDrVJS13J4548SZfpVe5J4p31xMsQcGO1Ue7Hp/V9QXrtjpB94/yI/yqBRQ2aj0L8Is2u463+WSdzv90utOILhVa6Jrs4bq2N39GO4DtK9KXSe90serhuMuQdAkDZMPi4FRCLvoUtXV1Vh8Edpra2trvH5Z13C511yeHV1VLOR0Bx3D2Abgve2X652qF8NvqtTG92dw1bHYnDDrB7FGopOlBx1XFYZEVWqKWupPHPHie9dWVFwqn1VZJrJpMMzsoGOAwG4bugLtt2kN2tlP5vQ1joocxdkLGuAJ6cMQcFFojpwlHVaWAjVqRlrxk08z2q6qatqZKmqfrJpDi92AGJ+G5F4opJJLBFbbbxZqOwNm71VxByTYGzd6q4g5K1IvJbbcdbPb7BbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQck2Bs3equIOStSJttx1sbBbdCKrsDZu9VcQckVqRNtuOtjYLboQREWU1hERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREAREQBERAEREB//9k=
                                " alt="Decathlon Spor Malzemeleri">
                                <p>Decathlon Spor Malzemeleri</p>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Hoş Geldiniz</h2>
                        <p class="card-text">En kaliteli halı sahalara kolayca rezervasyon yapabilirsiniz.</p>
                        <a href="sahalar.php" class="btn btn-primary">Sahaları Görüntüle</a>
                    </div>
                </div>
                
                <h3 class="mb-3">Popüler Sahalar</h3>
                <div class="row">
                    <?php
                    $stmt = $db->query("SELECT * FROM sahalar WHERE aktif = 1 ORDER BY RAND() LIMIT 3");
                    while ($saha = $stmt->fetch(PDO::FETCH_ASSOC)):
                        $resim_yolu = !empty($saha['resim_yolu']) ? $saha['resim_yolu'] : 'assets/img/default-saha.jpg';
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 saha-karti">
                            <img src="<?= htmlspecialchars($resim_yolu) ?>" class="card-img-top saha-resim" alt="<?= htmlspecialchars($saha['ad']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($saha['ad']) ?></h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($saha['konum']) ?>
                                </p>
                                <p class="card-text"><?= substr(htmlspecialchars($saha['aciklama']), 0, 100) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="saha-ucret"><?= number_format($saha['ucret'], 2) ?> TL/saat</span>
                                    <a href="saha-detay.php?id=<?= $saha['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Hızlı Rezervasyon Formu -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Hızlı Rezervasyon</h5>
                        <form action="rezervasyon-yap.php" method="POST">
                            <input type="hidden" name="yorum_yap" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label">Saha Seçin</label>
                                <select class="form-select" name="saha_id" required>
                                    <option value="">Saha Seçin</option>
                                    <?php
                                    $stmt = $db->query("SELECT id, ad FROM sahalar WHERE aktif = 1");
                                    while ($saha = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <option value="<?= $saha['id'] ?>"><?= htmlspecialchars($saha['ad']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tarih</label>
                                <input type="date" class="form-control" name="tarih" required 
                                       min="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Başlangıç Saati</label>
                                    <input type="time" class="form-control" name="baslangic_saati" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bitiş Saati</label>
                                    <input type="time" class="form-control" name="bitis_saati" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ev Sahibi Takım</label>
                                <input type="text" class="form-control" name="ev_sahibi_takim" 
                                       value="<?= isset($_SESSION['kullanici']['takim_adi']) ? htmlspecialchars($_SESSION['kullanici']['takim_adi']) : '' ?>"
                                       placeholder="Takım adınız">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deplasman Takım</label>
                                <input type="text" class="form-control" name="deplasman_takim" 
                                       placeholder="Rakip takım adı">
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted">Tahmini Ücret: <span id="tahmini-ucret">0</span> TL</p>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Rezervasyon Yap</button>
                        </form>
                    </div>
                </div>
                
                <!-- Yan Reklam -->
                <div class="card yan-reklam">
                    <div class="card-body text-center">
                        <h5 class="card-title">Özel Kampanyalar</h5>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAFwAXAMBIgACEQEDEQH/xAAbAAACAwEBAQAAAAAAAAAAAAAFBgADBAcCAf/EADcQAAIBAwEEBwYFBAMAAAAAAAECAwAEEQUSITFBBhMiUWFxgRQykbHB8CNSodHhFTNCYoKDkv/EABkBAAIDAQAAAAAAAAAAAAAAAAMFAAECBP/EACARAAICAgIDAQEAAAAAAAAAAAECAAMRIRIxBBNxQRT/2gAMAwEAAhEDEQA/AO41KlSpJJVU9xDbrtTSKg/2NL2u9JhbvJbafh5kXLyHeqUlnpDK0pd0M9x+aQkqPJefmfhRkoZ9iCe5U7nTP6pCx/CV3H5gMCqptUdN62pcDj+IBSBD0jl283gkcHgu2U+WKL9HdbiubxrNmOJAWi2uOea/Df8AGo1DqCZkeQhIGe42Q6kkgB6thn9KvjvIXOC+w3c+6kXUukKDVJEQlooCUwGxtNzO7x+VVr0ikbICiSP8rAsB68f1qx49mM4lf015xmdGqUlaZ0hZJVSHadDxiY5x5H6fOm2zu4ryESQtkcxzFDetk7hUdXGRNFSpUrE3JQTpDfyKvsVq2zNIO0/5F/ei88qwQvK/uqMmlIs91JLIzEO+cN3VWcSRT6QSCOL2S2BEK9pmJ3ytzYnnz+8VbocCCxR1IJY5dgMnjvHw+YrL0nt7q3Ma3EeVC7KOo7LDefr98aGW884jFvFIVU7yBzJ+/nTWqvlXoxVdaEt2Ia1yWMokZVWuG3kj/Hf9/Dzqro5ZS3Os2yK7xlW6wuvFQN/8etaNI0sTuOsdVfP9yRsY9DvPP1pl6LWSxvc3eyct+Em1x2RvP0+FU9orrKiUtJtsDGJw06ZbuW2kHbjcqxIOMg/Zo5phi9kxDGEZQdpcHeeXjg0Y1HTmbVxJFlVnUbbAbgRu3+Hu0J1W2G0otnWJ4uat7w86r2+0AGWKfTkiZtWiEbxTJ2HY4IG4+HrTHoN28iCYbrhN0g5Sr3+f3zpHnuJrjY69yxQ8+6mroyZZQnVJ2I2JeQ7uIGV8eH3zzfWRXua8a0NYcR3hkWWNXQ5DCvdC9Lus3M9q5XaU5XHdx+O+ilL4yBzBPSOXYsQg4yNj0H2KWZr32QYSFpGC7R34A8zRbpjLsSWQLYXayR37xSdrV4YbqdOK5UEf8f5rddfJhMu/FZ51TpDdTRPbvFbGJ9xUptfM8a99G/6BfFYb206m6O4MZnCSfruPhQ7+lSzQvJI/4zDsqPrVFgvbIdMKvvg/fGmQVPWQpx8ixmf2ZYZ+zpEeiacm5bbH/Y/717u9q2jSG1UDJGwDNsbTbWSM8c4yfjWHS5ri2uTZGVblAhZDneoxkb+7eu7lnjW65uWYFChTB3/iLv47v0pcQ3LZzGA4ldDE2WUjMpZizYdgCy7OR4Y4jlnnWS60TTZyzywEZ3k9Yw+tW287HEYjLbOASHG773/ChWoyzXouA8yRwwkqYgcEnOBk89/pUQNy0cSOV47GYA1g6NbTBLCGSbZbtsZTsEdw5nzoxpfSeAosBsDCiDAELAgem7FA7u3624ECRHrG3DHzqowSafcI7nMech1PyPfTFq0dAG2fsWK71uSuh8jtZqJNRbUEI6ohQuMgnA37Q8N+PA+VMlKWm3hl06abIVuux4AkLn601RkmNT3gUscEHBjZCCMiLPTuJjZwSrwUsD64/auc6nce0XyzHeGCbYHeAAflXXekFr7XpUyYyVG2PTj+ma5rHpvtVo4iH40DbaY/yQ8R6Yz610UMAMmCuUnQmqFxJEJIyCOIIP15UEnkMeqTMThS3du4Vc9qREGjZ02m8QDVVtaT3F0LeBC8pPLeD4n9666woycziu5HGI7dEkMkE122TtsI0J/Kv3j0rddanEtx7NbRiec5zgZC+feaVG1t4rCHT7EdTGiYeQe854kjuGT5/KvtnfrbhViZo1I7WwccvCudvHLMWMMvkBVCiOenX8F4pEY6uUe9GePmO8UA6Rq1tqbHtCKeMPx/yG7+fWh0uobbiRHfrRvD535860XmoyaraIjRFrmDLGRRuKcyR3jd/FWlJRuX5KsuDrx/RKdFSSe4maTf2Rs7uAz3Vq1yALZNttntKVPM/ffWSxjuS4FqX2ieQ41sutPup5o0Z1kkc7I7Wdk8893fRHIFgJMEgJrIxM+nTk2cFnEMmW4L8eQUKPr8K6Qo2VCjkMUm6Bp8b62xjXMcHBsccbs+p+dOlcl5BbU7qFIXch3iki4sG0nViixuYJCTCyqSNk8VPkcY9Kd6purdLmIxyehHEHvoQbEMRmJl3pXWlhFM0MchzKq8/KrJIYNM0m59kQIxjI2ubMdwyfM0RnjktZhFcDG0exIPdf8AY+FU3dr7TbtFtEBsHOO45qBzoHqUUGyO4itbiOPLb+YAo9Z28Map1UattYyx4gEfZz4V41O1uYQWMGyuMM8Y21Pj3ihNpqctvlCBMgI2Qx8aY5Ni5EXlVrbcL6lawJCrFAkjHB2c7u/P6170JHg1SBxjZOV+IP1rJbXE13OGkbaPBUAzj0pjt7X+y3VdUsTbeW948OXpQ7H4LxM0iB25CaGsYw7zW0aBmGHi4Kd/Lu+VfXVbaMnCI7dkbA3DPd9791fZZpPaIkWAyRybRd9oAJjgMcSSe7x395Sys9nEkwGeKr3eNcXInudwUDqfNGsRZ2xLDEkhy3h3CiFSpVE5mgMSVKlSqklc8Mc8bRzIHRuINBtQ0m6S3k/p8oLYyiyHBz3Z/SjtfDVgyTn17f6raNsz2d/B/v1QkX/0oIHxoa6z38iykwMc5yY0Unz3fOup18aNG95FPmKMt4HSwDU8uzOd2l9NBItvtxh8bo4YgSfRRvpksLe6uCCYnjjxxlQIT6caYQoUYUADwqCsvYG/JtKyv7M9tZxwdrG0/wCY8vKtNSpQoSSpUqVJJ//Z
                            " alt="Halı Saha Topu">
                            <p class="text-primary mt-2">%20 İndirimli Halı Saha Topları</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Reklam sliderını başlat
            $('.reklam-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            dots: false
                        }
                    }
                ]
            });
            
            // Tarih inputuna bugünün tarihini minimum değer olarak ayarla
            $('input[type="date"]').attr('min', new Date().toISOString().split('T')[0]);
        });

        // Tahmini ücret hesaplama
        document.addEventListener('DOMContentLoaded', function() {
            const sahaSelect = document.querySelector('select[name="saha_id"]');
            const tarihInput = document.querySelector('input[name="tarih"]');
            const baslangicInput = document.querySelector('input[name="baslangic_saati"]');
            const bitisInput = document.querySelector('input[name="bitis_saati"]');
            const tahminiUcretSpan = document.getElementById('tahmini-ucret');
            
            // Saha ücretlerini saklamak için
            const sahaUcretleri = {};
            <?php
            $ucretStmt = $db->query("SELECT id, ucret FROM sahalar WHERE aktif = 1");
            while ($ucret = $ucretStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "sahaUcretleri[{$ucret['id']}] = {$ucret['ucret']};";
            }
            ?>
            
            function hesaplaUcret() {
                const selectedSahaId = sahaSelect.value;
                const baslangic = baslangicInput.value;
                const bitis = bitisInput.value;
                
                if (selectedSahaId && baslangic && bitis) {
                    const baslangicParts = baslangic.split(':');
                    const bitisParts = bitis.split(':');
                    
                    const baslangicDakika = parseInt(baslangicParts[0]) * 60 + parseInt(baslangicParts[1]);
                    const bitisDakika = parseInt(bitisParts[0]) * 60 + parseInt(bitisParts[1]);
                    
                    if (bitisDakika > baslangicDakika) {
                        const saatFarki = (bitisDakika - baslangicDakika) / 60;
                        const ucret = saatFarki * sahaUcretleri[selectedSahaId];
                        tahminiUcretSpan.textContent = ucret.toFixed(2);
                    }
                }
            }
            
            sahaSelect.addEventListener('change', hesaplaUcret);
            baslangicInput.addEventListener('change', hesaplaUcret);
            bitisInput.addEventListener('change', hesaplaUcret);
        });
    </script>
</body>
</html>