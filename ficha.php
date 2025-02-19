<?php 
require_once 'class/class.php';
require_once 'class/cart.class.php';

$Obj = new mainClass();
$ObjEnv = new Envio();
$ObjCart = new Cart();

$pagina = basename($_SERVER['PHP_SELF']);

$id_prod=filter_input(INPUT_GET,'id', FILTER_SANITIZE_SPECIAL_CHARS);

if(!isset($id_prod)) header('Location: '.WEB_ROOT.'index.php');

$prod=$Obj->FichaProducto($id_prod);
if (!$prod) header('Location: '.WEB_ROOT.'index.php');

?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <meta charset="utf-8" />
        <title>Mixme - <?php echo $prod['pd_titulo'] ?></title>
        <meta name="description" content="Descubrí <?php echo $prod['ct_titulo'] ?> en Mixme, <?php echo $prod['pd_titulo'] ?>" />
        <link rel="canonical" href="<?php echo WEB_ROOT ?><?php echo $pagina ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <?php require_once("include/css.php") ?>

        <?php require_once("include/favicon.php") ?>
        <?php require_once("include/scripts-head.php"); ?>
    </head>

    <body class="page">
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header.php"); ?>
        <?php require_once("cart/cart.php"); ?>

        <section class="breadcrumb-ficha">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="row wrap-bcrumb">
                            <nav class="breadcrumb">
                                <?php $Obj->breadcrumbFicha($prod['ct_alias'],$prod['pd_titulo']); ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="content-ficha">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="row">
                            <div class="col-12 col-lg-7">
                                <?php $Obj->FotosFicha($prod['pd_id']); ?>
                            </div>
                            <div class="col-12 col-lg-5 datos-ficha">
                            
                                <?php $Obj->DatosFicha($prod['pd_id']) ?>

                                <?php $total=$Obj->precioProdEnvio($prod['pd_id'],$prod['pd_descuento']); ?>

                                <div id="envios">
                                    <hr>
                                        <h5><i data-feather="truck" class="mr-2 text-secondary"></i> Envíos a domicilio</h5>
                                        <div class="input-group mb-3">
                                            <input onblur="loadEnvios()" type="number" class="form-control" name="envio_codpostal" id="envio_codpostal" placeholder="Tu código postal" aria-label="Tu código postal" aria-describedby="submitship" value="<?php if(isset($_SESSION['codPostal'])) echo $_SESSION['codPostal'] ?>">
                                            <input type="hidden" id="cantproductos_envio" name="cantproductos_envio" value="<?php echo 1 ?>" >
                                            <input type="hidden" id="peso_envio" name="peso_envio" value="<?php echo $prod['pd_peso'] ?>" >
                                            <input type="hidden" id="total_envio" name="total_envio" value="<?php echo $total ?>" >
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" name="submitship" id="submitship">Calcular</button>
                                            </div>
                                        </div>
                                        <span id="errorShip" class="text-danger"></span>
                                        <div id="result-envios" class="text-left"></div>
                                    <hr>
                                        <h5><i data-feather="home" class="mr-2 text-secondary"></i> Retiro personal</h5>
                                        <div class="custom-control custom-radio my-2 border">
                                            <input type="radio" name="envio" id="envio30" data-id="30" class="custom-control-input" value="S" required>
                                            <label class="custom-control-label label-shipping-method-item pl-3" for="envio30">
                                                <div class="shipping-method-item">
                                                    <span>
                                                        <h4 class="shipping-method-item-price">Gratis</h4>
                                                        <div class="shipping-method-item-name">Retiro sucursal</div>
                                                        <div class="shipping-method-item-desc"><small>Av. Argentina 5659, Villa Lugano, CABA, Argentina</small></div>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

        <?php $Obj->Relacionados($id_prod,$prod['ct_id']) ?>

        <?php include_once("include/footer.php"); ?>
        <?php include_once("include/scripts-bottom.php") ?>
        <script>
            (function($) {
                $('.spinner .btn:first-of-type').on('click', function() {
                    $('.spinner input').val(parseInt($('.spinner input').val(), 10) + 1);
                });
                $('.spinner .btn:last-of-type').on('click', function() {
                    if ($('.spinner input').val() > 1) {
                        $('.spinner input').val(parseInt($('.spinner input').val(), 10) - 1);
                    }
                });
            })(jQuery);
        </script>
        <script src="<?php echo WEB_ROOT ?>js/jquery.sliderPro.min.js"></script>
        <script src="<?php echo WEB_ROOT ?>js/jquery.fancybox.min.js"></script>
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#slider').sliderPro({
                    width: '100%',
                    height: '100%',
                    aspectRatio: 1,
                    autoHeight: true,
                    fade: true,
                    loop: false,
                    arrows: false,
                    buttons: false,
                    autoplay: false,
                    thumbnailPointer: true,
                    thumbnailWidth: 130,
                    thumbnailHeight: 140,
                    breakpoints: {
                        800: {
                            thumbnailWidth: 80,
                            thumbnailHeight: 72
                        },
                        500: {
                            thumbnailWidth: 50,
                            thumbnailHeight: 45
                        }
                    }
                });
            });
        </script>

    </body>

    </html>