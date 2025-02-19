<?php 
require_once 'class/class.php';
require_once 'class/mixer.class.php';
require_once 'class/cart.class.php';

$Obj = new mainClass();
$ObjMix = new Mixer();
$ObjCart = new Cart();

$pagina = basename($_SERVER['PHP_SELF']);

$order=filter_input(INPUT_GET,'order', FILTER_SANITIZE_NUMBER_INT);
if(isset($order)) {
    $mix=$ObjMix->validarIdMixer($order);
    if (!$mix) header('Location: '.WEB_ROOT.'mixer.php');
} else {
    header('Location: '.WEB_ROOT.'mixer.php');
}

?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="utf-8" />
    <title>Mixme - Mix checkout</title>
    <meta name="description" content="" />
    <link rel="canonical" href="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php require_once("include/css.php") ?>

    <?php require_once("include/favicon.php") ?>
    <?php require_once("include/scripts-head.php"); ?>

</head>

<body class="page">
    <?php require_once("include/scripts-body.php") ?>
    <?php require_once("include/header.php"); ?>
    <?php require_once("cart/cart.php"); ?>

    <section id="mixer">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="row">
                        <div class="col-12 col-lg-8" id="mixer-checkout">
                            <input type="hidden" name="idmix" id="idmix" class="custom-control-input" value="<?php if (isset($mix['id_mix'])) {echo $mix['id_mix'];} ?>">

                            <div class="panel-white">
                                <h3>Identificá tu Mix</h3>
                                <div class="nombre-mix">
                                    <div class="form-group">
                                        <label for="nombreMix">Ponele nombre</label>
                                        <input type="text" class="form-control" id="nombreMix" name="nombreMix" aria-describedby="nombremixHelp" maxlength="20" value="<?php if (isset($mix['nombre'])) echo $mix['nombre']; else echo 'Mix favorito'; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcionMix">Descripción</label>
                                        <textarea name="descripcionMix" id="descripcionMix" type="textarea" maxlength="200" class="form-control"><?php if (isset($mix['descripcion'])) echo $mix['descripcion']; else echo 'El Mix que más me gusta'; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <?php if ($mix['pack']=='tubo') { ?>
                                
                                <div class="panel-white">
                                    <h3>Tu pack: ¿Qué diseño te gusta?</h3>

                                    <div id="car-packs" class="owl-carousel owl-theme">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="modelo" id="modelo1" class="custom-control-input" value="1" required <?php if ($mix['modelo']=='1') echo 'checked' ?>>
                                            <label class="custom-control-label label-packs-item" for="modelo1">
                                            <img src="<?php echo WEB_ROOT ?>img/packs/modelos/1.png" alt="Pack Mixme 1">
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="modelo" id="modelo2" class="custom-control-input" value="2" required <?php if ($mix['modelo']=='2') echo 'checked' ?>>
                                            <label class="custom-control-label label-packs-item" for="modelo2">
                                            <img src="<?php echo WEB_ROOT ?>img/packs/modelos/2.png" alt="Pack Mixme 2">
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="modelo" id="modelo3" class="custom-control-input" value="3" required <?php if ($mix['modelo']=='3') echo 'checked' ?>>
                                            <label class="custom-control-label label-packs-item" for="modelo3">
                                            <img src="<?php echo WEB_ROOT ?>img/packs/modelos/3.png" alt="Pack Mixme 3">
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="modelo" id="modelo4" class="custom-control-input" value="4" required <?php if ($mix['modelo']=='4' || $mix['modelo']=='0') echo 'checked' ?>>
                                            <label class="custom-control-label label-packs-item" for="modelo4">
                                            <img src="<?php echo WEB_ROOT ?>img/packs/modelos/4.png" alt="Pack Mixme 4">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="cont-neto pt-3"><?php echo $mix['peso'] ?>g<br><span>Contenido Neto</span></p>
                                        <a href="<?php echo WEB_ROOT.'mixer/'.$mix['tm_alias'].'/order'.$mix['id_mix']; ?>" class="btn btn-outline-primary btn-sm rounded-pill">Modificar pack</a>
                                    </div>
                                </div>

                            <?php } elseif($mix['pack']=='doypack') { ?>

                                <div class="panel-white">
                                    <h3>Tu pack</h3>
                                    <div id="car-packs" class="owl-carousel owl-theme">
                                        <div><img src="<?php echo WEB_ROOT ?>img/packs/modelos/doypack.png" alt="Doypack"></div>
                                    </div>
                                    <div class="col-12">
                                        <p class="cont-neto pt-3"><?php echo $mix['peso'] ?>g<br><span>Contenido Neto</span></p>
                                        <a href="<?php echo WEB_ROOT.'mixer/'.$mix['tm_alias'].'/order'.$mix['id_mix']; ?>" class="btn btn-outline-primary btn-sm rounded-pill">Modificar pack</a>
                                    </div>
                                </div>

                            <?php } elseif($mix['pack']=='bolsa') { ?>

                                <div class="panel-white">
                                    <h3>Tu pack</h3>
                                    <div id="car-packs" class="owl-carousel owl-theme">
                                        <div><img src="<?php echo WEB_ROOT ?>img/packs/modelos/bolsa.png" alt="Bolsa"></div>
                                    </div>
                                    <div class="col-12">
                                        <p class="cont-neto pt-3"><?php echo $mix['peso']*5 ?>g<br><span>Contenido Neto</span></p>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="panel-white">
                                <h3>Ingredientes</h3>
                                <div class="resumen-mix"></div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-4 text-center tubo-mixer-checkout">
                            <?php if ($mix['pack']=='bolsa') { ?>
                                <div class="content-val-nut"></div>
                            <?php } else { ?>
                                <div class="owl-carousel">
                                    <div class="dorso-tubo" data-hash="val-nut" style="background-image: url(img/packs/modelos/fondo<?php echo $mix['modelo']; ?>-dorso.png)">
                                        <div class="btn-rotate"><a href="#mix-content" class="text-primary"><img src="img/rotate.svg" width="50" height="40" class="icon-rotate"></a></div>
                                        <div class="content-val-nut"></div>
                                    </div>
                                    <div class="frente-tubo" data-hash="mix-content" style="background-image: url(img/packs/modelos/fondo<?php echo $mix['modelo']; ?>.png)">
                                        <div class="btn-rotate"><a href="#val-nut" class="text-primary"><img src="img/rotate.svg" width="50" height="40" class="icon-rotate"></a></div>
                                    </div>
                                </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>


    <?php include_once("include/footer.php"); ?>
    <?php include_once("include/scripts-bottom.php") ?>
    <script src="<?php echo WEB_ROOT ?>js/mixer-checkout.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/jquery.fancybox.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/owl.carousel.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-fancybox]').fancybox({
                buttons: [
                    "close"
                ],
                thumbs: {
                    autoStart: true
                },
                animationDuration: 800,
                animationEffect: "fade",
                infobar: false,
            });
        });
    </script>
    <script>
        $(window).on('load', function() {
            $('.owl-carousel').owlCarousel({
            items:1,
            loop:true,
            nav:false,
            dots:false,
            autoplay:false,
            URLhashListener:true,
            startPosition: 'URLHash'
            });
        });
    </script>
        <script type="text/javascript">
            $(document).ready(function() {
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