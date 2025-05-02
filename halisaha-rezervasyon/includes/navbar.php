<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/img/logo.jpg"  width="40" height="40" class="d-inline-block align-top">
            FUTBOLİST
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="sahalar.php">Sahalar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="macini-bul.php">Maçını Bul</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="haftanin-enleri.php">Haftanın Enleri</a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="hakkimizda.php">Hakkımızda</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['kullanici'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">Profilim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cikis.php">Çıkış Yap</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="uyelik.php">Giriş Yap / Üye Ol</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>