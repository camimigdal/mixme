<?php 
require_once 'class/class.php';
require_once 'class/mixer.class.php';
require_once 'class/cart.class.php';

$Obj = new mainClass();
$ObjMix = new Mixer();
$ObjCart = new Cart();

$pagina = basename($_SERVER['PHP_SELF']);


$type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
if(isset($type)) {
    $typ=$ObjMix->validarTypeMixer($type);
    if (!$typ) header('Location: '.WEB_ROOT.'mixer/granola/');
} else {
    header('Location: '.WEB_ROOT.'mixer/granola/');
}

$order=filter_input(INPUT_GET,'order', FILTER_SANITIZE_NUMBER_INT);
if(isset($order)) {
    $mix=$ObjMix->validarIdMixer($order);
    if (!$mix) header('Location: '.WEB_ROOT.'mixer.php');
}

# Iniciando la variable de control que permitirá mostrar o no el modal
$exibirModal = false;
# Verificando si existe o no la cookie
if(!isset($_COOKIE["mostrarModal"]))
{
  $expirar = 43200; //muestra cada 12 horas
  setcookie('mostrarModal', 'SI', (time() + $expirar)); // mostrará cada 12 horas.
  $exibirModal = true;
}


?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="utf-8" />
    <title>Mixme creá tu Mix</title>
    <meta name="description" content="" />
    <link rel="canonical" href="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php require_once("include/css.php") ?>
    <link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/shepherd.css" type="text/css">

    <?php require_once("include/favicon.php") ?>
    <?php require_once("include/scripts-head.php"); ?>
    <?php $ObjMix->estilosCategoriasMixer() ?>
</head>

<body class="page">
    <?php require_once("include/scripts-body.php") ?>
    <?php require_once("include/header.php"); ?>
    <?php require_once("cart/cart.php"); ?>


    <div class="modal modal-mixer right fade" id="pop_mixer" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mixer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="tubo-mixer tubo-modal animate__animated animate__shakeX">
                    <div class="owl-carousel">
                        <div class="contenido-tubo" data-hash="mix-content"></div>
                        <div class="content-val-nut" data-hash="val-nut"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-info fade" id="masinfo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="content-mas-info">
            </div>
        </div>
    </div>


    <section id="mixer">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="row">
                        <div class="col-12 col-lg-8 px-lg-0">

                        <ul class="nav nav-mixer mb-4" id="nav-mixer">
                            <?php $ObjMix->navMixer() ?>
                        </ul>

                            <div class="row">
                                <div class="col-12 col-lg-3 px-1">
                                
                                    <div class="nav navbar-expand-xl justify-content-center">
                                        <button class="navbar-toggler btn-filtros py-3" type="button" data-toggle="collapse" data-target="#navbarFilt" aria-controls="navbarFilt" aria-expanded="true" aria-label="Toggle navigation"><i class="fas fa-caret-down"></i> Filtros</button>
                                        <div class="collapse navbar-collapse" id="navbarFilt">
                                            <div id="list-cat-mixer" class="list-group list-category-mixer">
                                                <a href="#peso" class="list-group-item list-group-item-action peso" style="color: #cd3a93">Peso / Pack</a>
                                                <?php $ObjMix->categoriasMixer() ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="col-12 col-lg-9 px-1">
                                    <div data-spy="scroll" data-target="#list-cat-mixer" data-offset="0" class="content-mixer">

                                        <div id="peso">
                                            <div class="mixer-catalog">
                                                <h4 class="mixer-category-title" style="background-color:#cd3a93">ELEGÍ EL TAMAÑO DE TU MIX</h4>
                                                <div class="mixer-packs-list row"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="idmix" id="idmix" value="<?php if (isset($mix['id_mix'])) {echo $mix['id_mix'];} ?>">
                                        <input type="hidden" name="tipomix" id="tipomix" value="<?php echo $typ['tm_id']; ?>">

                                        <?php $ObjMix->productosMixer() ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 d-none d-sm-block tubo-mixer animate__animated animate__shakeX">
                            <div class="owl-carousel">
                            
                                <div class="contenido-tubo" data-hash="mix-content"></div>
                                <div class="content-val-nut" data-hash="val-nut"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include_once("include/footer.php"); ?>
    <?php include_once("include/scripts-bottom.php") ?>
    <script src="<?php echo WEB_ROOT ?>js/mixer.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/jquery.touchwipe.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/jquery.fancybox.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/owl.carousel.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var getOffset = function() {
                if (window.innerWidth < 768) {
                    // Extra Small Device
                    return 400
                } else if (window.innerWidth < 991) {
                    // Small Device
                    return 300
                } else if (window.innerWidth < 1199) {
                    // Medium Device
                    return 300
                } else {
                    // Large Device
                    return 300
                }
            };
            var offset = getOffset();

            $('#list-cat-mixer a').click(function(event) {
                event.preventDefault();
                $($(this).attr('href'))[0].scrollIntoView();
                scrollBy(0, -offset);
                $('#navbarFilt').collapse("hide")
            });

            $('.addbases').click(function(event) {
                event.preventDefault();
                $("#frutos-secos")[0].scrollIntoView();
                scrollBy(0, -offset);
            });


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
            touchDrag:false,
            URLhashListener:true,
            autoplay:false,
            startPosition: 'URLHash'
            });
        });
    </script>
    <script src="<?php echo WEB_ROOT ?>js/shepherd.min.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/shepherd.esm.min.js"></script>

    <?php if($exibirModal === true) : // Si nuestra variable de control "$exibirModal" es igual a TRUE activa nuestro modal y será visible a nuestro usuario. ?>
            <script>
                
                var tour = new Shepherd.Tour({

                // Default options for Steps, created through 'addStep'
                defaultStepOptions: {
                    cancelIcon: {
                    enabled: true
                    },
                    scrollTo: { behavior: 'smooth', block: 'center' }
                },

                // Whether or not steps should be placed above a darkened modal overlay. 
                // If true, the overlay will create an opening around the target element so that it can remain interactive
                useModalOverlay: true,

                // Exiting the tour with the escape key will be enabled unless this is explicitly set to false.
                exitOnEsc: true,

                // Navigating the tour via left and right arrow keys will be enabled unless this is explicitly set to false.
                keyboardNavigation: true,

                // If true, will issue a window.confirm before cancelling
                confirmCancel: false,

                })


                tour.addStep({
                    text: 'Aca podes elegir el mix que queres armar',
                    attachTo: { 
                        element: '#nav-mixer', 
                        on: 'bottom'
                    },
                    buttons: [
                        {
                        text: 'Entendido!',
                        action: tour.next
                        }
                    ]
                });

                

                tour.start();
            </script>
    <?php endif; ?>

</body>

</html>