<?php 
require_once 'class/class.php';
require_once 'class/mixer.class.php';
require_once 'class/cart.class.php';

$Obj = new mainClass();
$ObjMix = new Mixer();
$ObjCart = new Cart();

$pagina = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="utf-8" />
    <title>Mixme creá tu mixer</title>
    <meta name="description" content="" />
    <link rel="canonical" href="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php require_once("include/css.php") ?>

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
                    <h5 class="modal-title">Mezclador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="tubo-mixer tubo-modal animate__animated animate__tada">
                    <div class="contenido-tubo"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <section id="mixer">
        <div class="bloquear-mixer"><p>Estamos trabajando aquí.<br>
    <span>Próximamente podrás crear tu mix favorito.<span></p></div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="row">
                        <div class="col-12 col-lg-8 px-0">

                            <div class="row">
                                <div class="col-12 col-lg-3 px-1">
                                    <div id="list-cat-mixer" class="list-group list-category-mixer">
                                        <a href="#peso" class="list-group-item list-group-item-action peso" style="color: #cd3a93">Tamaño del Mix</a>
                                        <?php $ObjMix->categoriasMixer() ?>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-9 px-1">
                                    <div data-spy="scroll" data-target="#list-cat-mixer" data-offset="0" class="content-mixer">

                                        <div id="peso">
                                            <div class="mixer-catalog">
                                                <h4 class="mixer-category-title" style="background-color:#cd3a93">ELEGÍ EL TAMAÑO DE TU MIX</h4>
                                                <div class="mixer-packs-list row">

                                                    <div class="custom-control custom-radio py-5 pr-3 pl-5 col-12 col-lg-6">
                                                        <input type="radio" name="peso" id="peso1" data-id="1" class="custom-control-input" value="350" required>

                                                        <label class="custom-control-label label-packs-item pl-3" for="peso1">
                                                            <div class="pr-3 text-center"><img alt="" src="img/tubo-muestra-mixer.png" width="100%"></div>
                                                            <div class="packs-item">
                                                                <span>
                                                                    <h4 class="packs-item-price">350g</h4>
                                                                    <div class="packs-item-name">Tubo personalizable</div>
                                                                    <div class="packs-item-desc"><p><small>En el siguiente paso podés elegir el diseño que más te guste</small></p></div>
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="custom-control custom-radio py-5 pr-3 pl-5 col-12 col-lg-6">
                                                        <input type="radio" name="peso" id="peso2" data-id="2" class="custom-control-input" value="400" required>

                                                        <label class="custom-control-label label-packs-item pl-3" for="peso2">
                                                            <div class="pr-3 text-center"><img alt="" src="img/doypack-muestra-mixer.png" width="100%"></div>
                                                            <div class="packs-item">
                                                                <span>
                                                                    <h4 class="packs-item-price">400g</h4>
                                                                    <div class="packs-item-name">Doypack</div>
                                                                    <div class="packs-item-desc"><small></small></div>
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php $ObjMix->productosMixer() ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 d-none d-sm-block tubo-mixer animate__animated animate__tada">
                            <div class="contenido-tubo"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include_once("include/footer.php"); ?>
    <?php include_once("include/scripts-bottom.php") ?>
    <script src="<?php echo WEB_ROOT ?>js/mixer.js"></script>
    <script src="<?php echo WEB_ROOT ?>js/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var offset = 300;
            var navOffset = $('.navigation').height();

            $('#list-cat-mixer a').click(function(event) {
                event.preventDefault();
                $($(this).attr('href'))[0].scrollIntoView();
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

</body>

</html>