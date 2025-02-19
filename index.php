<?php 
require_once 'class/class.php';
$Obj = new mainClass();
$pagina = basename($_SERVER['PHP_SELF']);
$metaTags=$Obj->DatosSeo($pagina);

// vemos si el usuario quiere desloguar
if (!empty($_GET['salir'])) {
    // Desconfigura todos los valores de sesión.
    $_SESSION = array();
     
    // Obtiene los parámetros de sesión.
    $params = session_get_cookie_params();
     
    // Borra el cookie actual.
    setcookie(session_name(), '', time() - 42000, 
            $params["path"], 
            $params["domain"], 
            $params["secure"], 
            $params["httponly"]);
     
    // Destruye sesión.
    session_unset();
    session_destroy();

    header( 'Location: index.php' );
}

?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $metaTags['seo_titulo'] ?></title>
        <meta name="description" content="<?php echo $metaTags['seo_descripcion'] ?>" />
        <meta name="keywords" content="<?php echo $metaTags['seo_keywords'] ?>">
        <link rel="canonical" href="<?php echo WEB_ROOT ?><?php echo $pagina ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <?php require_once("include/css.php") ?>    
        <?php require_once("include/favicon.php") ?>
        <?php require_once("include/scripts-head.php"); ?>
    </head>

    <body>
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header.php"); ?>
        <?php require_once("cart/cart.php"); ?>

        <div class="carousel slide carouselHome" data-ride="carousel">
            <?php $Obj->SlideHome(); ?>
        </div>

        

        <section class="mezclador padding-section" id="mezclador">
            <div class="container-fluid bg-mezclador">
                <div class="row">
                    <div class="col-12 col-xl-10 offset-xl-1">
                        <div class="row">
                            <div class="col-12 pb-3">
                                <h2 class="main_title">
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/icon-mezclador.svg" height="80" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/icon-mezclador.svg" alt="Mezclador" height="60" class="animate__animated animate__tada animate__infinite">
                                    </picture>
                                    Mezclador</h2>
                            </div>
                        </div>
                        <div class="row justify-content-center wrap-block">
                            <div class="col-12 col-lg-10 wrap-fila">
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <!-- <img src="img/poster-video.jpg" width="100%"> -->
                                        <div class="container-iframe">
                                            <iframe src="https://www.youtube.com/embed/EfeKXqXZElA" title="YouTube video player" frameborder="0" allowfullscreen class="video"></iframe>
                                        </div>
                                        <img src="img/info-nutricional.png" class="info mt-3">
                                    </div>
                                    <div class="col-12 col-lg-6 block-text-mixer">
                                        <h3>Creá tu Mix</h3>
                                        <p>Podés armar tu Mix de Granola, Frutos secos o Semillas. Es facil y rápido, mirá el video y enterate cómo armar el mix que más te guste.</p>
                                        <a href="mixer/" class="btn btn-primary btn-lg rounded-pill">Ir al mezclador</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 pb-5">
                                <h2 class="title-packs">Nuestros packs</h2>
                            </div>
                            <div class="col-12 pt-5 text-center wrap-car-packs">
                                <div id="car-packs" class="owl-carousel owl-theme pt-5">
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/packs/pack1.png" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/packs/mobile/pack1.png" alt="Pack Mixme 1">
                                    </picture>
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/packs/pack2.png" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/packs/mobile/pack2.png" alt="Pack Mixme 1">
                                    </picture>
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/packs/pack3.png" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/packs/mobile/pack3.png" alt="Pack Mixme 1">
                                    </picture>
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/packs/pack4.png" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/packs/mobile/pack4.png" alt="Pack Mixme 1">
                                    </picture>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php $Obj->Market(); ?>

        <section class="productos padding-section" id="productos">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-xl-10 offset-xl-1">
                        <div class="row">
                            <div class="col-12 pb-5">
                                <h2 class="main_title">
                                    <picture>
                                        <source srcset="<?php echo WEB_ROOT ?>img/icon-mezclas.svg" height="70" media="(min-width: 600px)">
                                        <img src="<?php echo WEB_ROOT ?>img/icon-mezclas.svg" alt="Nuestras mezclas" height="50" class="animate__animated animate__headShake animate__infinite">
                                    </picture>
                                    Nuestras mezclas</h2>
                            </div>
                            <?php $Obj->ProductosHome() ?>

                            <div class="col-12 pt-5 text-center">
                                <a href="<?php echo WEB_ROOT.'productos/' ?>" class="btn btn-primary btn-lg rounded-pill">Ver más productos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include_once("include/footer.php"); ?>
        <a href="https://wa.me/5491136533648" target="_blank" class="button-whatsapp">Whatsapp <img src="<?php echo WEB_ROOT ?>img/icon-whatsapp.svg" alt="Whatsapp"></a>

        
        <?php include_once("include/scripts-bottom.php") ?>

        <script src="js/owl.carousel.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#car-novedades').owlCarousel({
                    loop: false,
                    margin: 30,
                    nav: true,
                    dots: true,
                    navText: ["<svg class='feather'><use xlink:href='img/feather-sprite.svg#chevron-left'/></svg>", "<svg class='feather'><use xlink:href='img/feather-sprite.svg#chevron-right'/></svg>"],
                    responsive: {
                        0: {
                            items: 1,
                            nav: false
                        },
                        600: {
                            items: 2,
                            nav: false
                        },
                        1000: {
                            items: 3,
                            nav: true
                        }
                    }
                })
                $('#car-packs').owlCarousel({
                    loop: false,
                    margin: 30,
                    nav: false,
                    dots: true,
                    navText: ["<svg class='feather'><use xlink:href='img/feather-sprite.svg#chevron-left'/></svg>", "<svg class='feather'><use xlink:href='img/feather-sprite.svg#chevron-right'/></svg>"],
                    responsive: {
                        0: {
                            items: 2
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 4
                        }
                    }
                })
            }); // Fin document ready
        </script>

    </body>
    </html>