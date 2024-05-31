<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Poliklinik</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php
                if(!isset($_SESSION)) 
                { 
                    session_start(); 
                } 
        ?>
        <?php
        if (isset($_SESSION['username'])) {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="mainNav">
            <div class="container px-4">
                <!-- <a class="navbar-brand" href="#page-top">Poliklinik</a> -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/bk-poliklinik/admin/">Dashboard</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li> -->
                    </ul>
                <?php
                 } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'dokter') {
                    ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="/bk-poliklinik/dokter/">Dashboard</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li> -->
                        </ul>
                <?php
                 } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'pasien') {
                ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="/bk-poliklinik/pasien/">Dashboard</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li> -->
                        </ul>
                <?php
                 }
                ?>
                </div>
            </div>
        </nav>

        <?php
        }
        ?>
        <!-- Header-->
        <header class="bg-primary bg-gradient text-white">
            <div class="container px-4 text-center">
                <h1 class="fw-bolder" style="font-size: 3rem;">Sistem Temu Janji</h1>
                <h1 class="fw-bolder" style="font-size: 3rem;">Pasien - Dokter</h1>
                <p class="lead">Bimbingan Karier 2024 Bidang Web</p>
                <!-- <a class="btn btn-lg btn-light" href="#about">Start scrolling!</a> -->
            </div>
        </header>
        <!-- Login section-->
        <?php
                if(!isset($_SESSION)) 
                { 
                    session_start(); 
                } 
                if (!isset($_SESSION['username'])) {
                ?>
                <section id="login">
                    <div class="container px-4">
                        <div class="row gx-4 justify-content-center">
                            <div class="col-lg-6">
                                <i class="fa-solid fa-user"></i>
                                <img src="assets/healthy.png" alt="Icon" style="width: 75px; height: 75px;" class="mb-3">
                                <h3 >Login Sebagai Pasien</h3>
                                <p class="lead">Apabila Anda adalah seorang Pasien, silahkan Login terlebih dahulu untuk melakukan pendaftaran sebagai Pasien</p>
                                <a class="lead" href="/bk-poliklinik/auth/login-pasien.php" style="text-decoration: none;">Klik Link Berikut <i class="bi bi-arrow-right"></i></a>
                                <!-- <p class="lead">This is a great place to talk about your webpage. This template is purposefully unstyled so you can use it as a boilerplate or starting point for you own landing page designs! This template features:</p>
                                <ul>
                                    <li>Clickable nav links that smooth scroll to page sections</li>
                                    <li>Responsive behavior when clicking nav links perfect for a one page website</li>
                                    <li>Bootstrap's scrollspy feature which highlights which section of the page you're on in the navbar</li>
                                    <li>Minimal custom CSS so you are free to explore your own unique design options</li>
                                </ul> -->
                            </div>
                            <div class="col-lg-6">
                                <img src="assets/doctor.png" alt="Icon" style="width: 75px; height: 75px;" class="mb-3">
                                <h3 >Login Sebagai Dokter</h3>
                                <p class="lead">Apabila Anda adalah seorang Dokter, silahkan Login terlebih dahulu untuk memulai melayani Pasien</p>
                                <a class="lead" href="/bk-poliklinik/auth/login.php" style="text-decoration: none;">Klik Link Berikut <i class="bi bi-arrow-right"></i></a>
                                <!-- <p class="lead">This is a great place to talk about your webpage. This template is purposefully unstyled so you can use it as a boilerplate or starting point for you own landing page designs! This template features:</p>
                                <ul>
                                    <li>Clickable nav links that smooth scroll to page sections</li>
                                    <li>Responsive behavior when clicking nav links perfect for a one page website</li>
                                    <li>Bootstrap's scrollspy feature which highlights which section of the page you're on in the navbar</li>
                                    <li>Minimal custom CSS so you are free to explore your own unique design options</li>
                                </ul> -->
                            </div>
                        </div>
                    </div>
                </section>
        <?php
                }
                ?>
        <!-- Testimoni section-->
        <section class="bg-light" id="testimoni">
            <div class="container px-4">
                <div class="row gx-4 justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-lg-6">
                        <div class="text-center">
                            <h2 class="fw-bolder">Testimoni Pasien</h2>
                            <p class="lead">Para pasien yang Setia</p>
                        </div>
                    </div>
                </div>
                <div class="row gx-4 justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-lg-6">
                        <div class="border" style="border-color: #808080; background-color: #ffffff; border-radius: 10px; padding: 20px;">
                            <div class="row">
                                <div class="col-lg-2 text-center">
                                    <img src="assets/comments.png" alt="Icon" style="width: 75px; height: 75px;" class="mb-3">
                                </div>
                                <div class="col-lg-10">
                                    <p>Pelayanan di web ini sangat cepat dan mudah. Detail histori tercatat lengkap, termasuk catatan obat. Harga pelayanan terjangkau, Dokter ramah, pokoke mantab pol!</p>
                                    <p style="font-size: 14px; color: #808080;">- Widhi, Semarang</p>
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="row gx-4 justify-content-center" style="margin-bottom: 20px;">
                    <div class="col-lg-6">
                        <div class="border" style="border-color: #808080; background-color: #ffffff; border-radius: 10px; padding: 20px;">
                            <div class="row">
                                <div class="col-lg-2 text-center">
                                    <img src="assets/comments.png" alt="Icon" style="width: 75px; height: 75px;" class="mb-3">
                                </div>
                                <div class="col-lg-10">
                                    <p>Pelayanan di web ini sangat cepat dan mudah. Detail histori tercatat lengkap, termasuk catatan obat. Harga pelayanan terjangkau, Dokter ramah, pokoke mantab pol!</p>
                                    <p style="font-size: 14px; color: #808080;">- Bagus, Semarang</p>
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="row gx-4 justify-content-center">
                    <div class="col-lg-6">
                        <div class="border" style="border-color: #808080; background-color: #ffffff; border-radius: 10px; padding: 20px;">
                            <div class="row">
                                <div class="col-lg-2 text-center">
                                    <img src="assets/comments.png" alt="Icon" style="width: 75px; height: 75px;" class="mb-3">
                                </div>
                                <div class="col-lg-10">
                                    <p>Pelayanan di web ini sangat cepat dan mudah. Detail histori tercatat lengkap, termasuk catatan obat. Harga pelayanan terjangkau, Dokter ramah, pokoke mantab pol!</p>
                                    <p style="font-size: 14px; color: #808080;">- Nugroho, Semarang</p>
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-primary bg-gradient">
            <div class="container px-4"><p class="m-0 text-center text-white">Copyright &copy; Bengkel Koding 2024</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
