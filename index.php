<?php
session_start();

require "action.php";

if (!isset($_SESSION["logged"]))
    header("location: login.php");

if (first_register_socio($_SESSION["username"]) == "registrazione")
    header("location: register_socio.php");

if ($_SESSION['gruppo'] == "socio")
    header("location: indexSocio.php");

?>

<!doctype html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CT Sharing</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="icon" href="img/favicon.ico">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/magnific-popup.css" rel="stylesheet">

        <link href="css/pages/templatemo-first-portfolio-style.css" rel="stylesheet">

        <script src="https://kit.fontawesome.com/78eabc9a0f.js" crossorigin="anonymous"></script>

    </head>

    <body>

        <section class="preloader">
            <div class="spinner">
                <span class="spinner-rotate"></span>
            </div>
        </section>

        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a href="#" class="navbar-brand mx-auto mx-lg-0">CT Sharing</a>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-5">
                        <li class="nav-item">
                            <a class="nav-link" href="#section_1">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#section_2">Veicoli</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#section_3">Info</a>
                        </li>
                    </ul>

                    <div class="d-lg-flex align-items-center d-none ms-auto">
                        <a class="custom-btn btn" href="modify.php">
                            <i class="bi-person"></i> <?=$_SESSION['username'];?>
                        </a>
                    <div class="d-lg-flex align-items-center d-none ms-auto m-lg-2">
                        <a class="custom-btn btn" href="logout.php">
                            <i class="bi-door-closed"></i> Logout <!--bi-box-arrow-right-->
                        </a>

                    </div>
                </div>
            </div>
        </nav>

        <main>

            <section class="hero d-flex justify-content-center align-items-center" id="section_1">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-7 col-12">
                            <div class="hero-text">
                                <div class="hero-title-wrap d-flex align-items-center mb-4">
                                    <img src="img/cono.png" class=" avatar-image-large img-fluid" alt="">

                                    <h1 class="hero-title ms-3 mb-0">Ciao <?php echo $_SESSION['username'] ?></h1>
                                </div>

                                <h2 class="mb-4">Ecco i veicoli vicino a te.</h2>
                                <p class="mb-4"><a class="custom-btn btn" style="background-color: var(--primary-color);" href="#section_2">Vai ai veicoli</a></p>
                            </div>
                        </div>

<!--                        <div class="col-lg-5 col-12 position-relative">-->
<!--                            <div class="hero-image-wrap"></div>-->
<!--                            <img src="img/exchange.png" class="hero-image img-fluid" alt="suca">-->
<!--                        </div>-->

                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#535da1" fill-opacity="1" d="M0,160L24,160C48,160,96,160,144,138.7C192,117,240,75,288,64C336,53,384,75,432,106.7C480,139,528,181,576,208C624,235,672,245,720,240C768,235,816,213,864,186.7C912,160,960,128,1008,133.3C1056,139,1104,181,1152,202.7C1200,224,1248,224,1296,197.3C1344,171,1392,117,1416,90.7L1440,64L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z"></path></svg>
            </section>

            <section class="services section-padding" id="section_2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-12 mx-auto">
                            <div class="section-title-wrap d-flex justify-content-center align-items-center mb-5">
                                <img src="img/veicolo.png" class="avatar-image img-fluid" alt="">

                                <h2 class="text-white ms-4 mb-0">Veicoli Disponibili</h2>
                            </div>

                            <div class="row pt-lg-5">
                            <?php
                            $veicoli = getVeicoli();
                            foreach ($veicoli as $veicolo) {
                                $posizione = getPosizioneVeicolo($veicolo["id_posizione"]);
                                extract($posizione);
                                extract($veicolo);
                                switch($tipo){
                                    case "auto":
                                        $icon = '<i class="services-icon fa fa-car"></i>';
                                        break;
                                    case "moto":
                                        $icon = '<i class="services-icon fa fa-motorcycle"></i>';
                                        break;
                                    case "bicicletta":
                                        $icon = '<i class="services-icon fa fa-bicycle"></i>';
                                        break;
                                    case "monopattino":
                                        $icon = '<div style=""><img style="" src="img/electric-scooter.png" alt=""></div>';
                                        break;
                                    default:
                                        $icon = '<i class="services-icon fa fa-fighter-jet"></i>';

                                }

                                echo '
                                    <div class="col-lg-6 col-12">
                                        <div class="services-thumb" style="height: 500px;">
                                            <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                                <h3 class="mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 350px">'.$modello.'</h3>

                                                <div class="services-price-wrap ms-auto">
                                                    <p class="services-price-text mb-0">'.$prezzo_ora.'€/h</p>
                                                    <div class="services-price-overlay"></div>
                                                </div>
                                            </div>

                                            <p>Stato: disponibile </p>
                                            <p>Posizione attuale: '.$nazione.', '.$provincia.', '.$citta.'</p>
                                            <p>Alimenzazione: '.$alimentazione.' </p>

                                            <a href="#" class="custom-btn custom-border-btn btn mt-3">Discover More</a>

                                            <div style="" class="services-icon-wrap d-flex justify-content-center align-items-center">
                                                '.$icon.'
                                            </div>
                                        </div>
                                    </div>';
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="contact section-padding" id="section_3">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-3 col-md-6 col-12 pe-lg-0">
                            <div class="contact-info contact-info-border-start d-flex flex-column">
                                <strong class="site-footer-title d-block mb-3">Servizi</strong>
                                <ul class="footer-menu">
                                    <li class="footer-menu-item"><a href="#" onclick="return false;"  class="footer-menu-link">Sharing</a></li>
                                    <li class="footer-menu-item"><a href="#" onclick="return false;"  class="footer-menu-link">Rental</a></li>
                                    <li class="footer-menu-item"><a href="#" onclick="return false;"  class="footer-menu-link">Support</a></li>
                                </ul>
                                <strong class="site-footer-title d-block mt-4 mb-3">Resta connesso</strong>
                                <ul class="social-icon">
                                    <li class="social-icon-item"><a href="#" onclick="return false;" class="social-icon-link bi-twitter"></a></li>
                                    <li class="social-icon-item"><a href="#" onclick="return false;" class="social-icon-link bi-instagram"></a></li>
                                    <li class="social-icon-item"><a href="#" onclick="return false;" class="social-icon-link bi-pinterest"></a></li>
                                    <li class="social-icon-item"><a href="#" onclick="return false;" class="social-icon-link bi-youtube"></a></li>
                                </ul>
                                <strong class="site-footer-title d-block mt-4 mb-3">Lavora con noi</strong>
                                <p class="mb-0">Contividi i tuoi veicoli con noi!</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 ps-lg-0">
                            <div class="contact-info d-flex flex-column">
                                <strong class="site-footer-title d-block mb-3">Informazioni</strong>
                                <p class="mb-2">Creato per voi da Lorenzo Turrini e Marco Celino</p>
                                <strong class="site-footer-title d-block mt-4 mb-3">Email</strong>
                                <p><a href="mailto:support@ctsharing.it">support@ctsharing.it</a></p>
                                <strong class="site-footer-title d-block mt-4 mb-3">Telefono</strong>
                                <p class="mb-0"><a href="tel: 338-708-0425">338 708 0425</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>



        </main>

        <footer class="site-footer">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <div class="copyright-text-wrap">
                            <p class="mb-0">
                                <span class="copyright-text">Copyright © 2023 <a href="#">CT Sharing</a> Company. All rights reserved.</span>
                                Design:
                                <a href="#" onclick="return false;">CT</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </footer>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/magnific-popup-options.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>

<script>
</script>