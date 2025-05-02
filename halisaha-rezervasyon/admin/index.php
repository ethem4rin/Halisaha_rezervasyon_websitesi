<?php
require_once '../config.php';

// Admin kontrolü
if (!isAdmin()) {
    header('Location: ../uyelik.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Halı Saha Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Admin Paneli</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>" href="?page=dashboard">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'kullanicilar' ? 'active' : '' ?>" href="?page=kullanicilar">
                                <i class="bi bi-people me-2"></i> Kullanıcılar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'sahalar' ? 'active' : '' ?>" href="?page=sahalar">
                                <i class="bi bi-house-door me-2"></i> Sahalar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'rezervasyonlar' ? 'active' : '' ?>" href="?page=rezervasyonlar">
                                <i class="bi bi-calendar-check me-2"></i> Rezervasyonlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'videolar' ? 'active' : '' ?>" href="?page=videolar">
                                <i class="bi bi-camera-video me-2"></i> Videolar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'yorumlar' ? 'active' : '' ?>" href="?page=yorumlar">
                                <i class="bi bi-chat-left-text me-2"></i> Yorumlar
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="../cikis.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <?php
                $adminPages = [
                    'dashboard' => 'dashboard.php',
                    'kullanicilar' => 'kullanicilar.php',
                    'sahalar' => 'sahalar.php',
                    'rezervasyonlar' => 'rezervasyonlar.php',
                    'videolar' => 'videolar.php',
                    'yorumlar' => 'yorumlar.php'
                ];

                if (array_key_exists($page, $adminPages)) {
                    include $adminPages[$page];
                } else {
                    include 'dashboard.php';
                }
                ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>