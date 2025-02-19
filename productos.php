<?php 
require_once 'class/class.php';
$Obj = new mainClass();
$pagina = basename($_SERVER['PHP_SELF']);
$metaTags=$Obj->DatosSeo($pagina);

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

    <body class="page">
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header.php") ?>
        <?php require_once("cart/cart.php") ?>

        
        <section id="header-productos" class="breadcrumb-productos">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row pl-4">
                            <nav class="breadcrumb">
                                <?php $Obj->breadcrumb(); ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="productos py-3" id="productos">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-xl-11">
                        <div class="row">
                            <div class="col-12 col-md-4 col-lg-3 filtros">
                                <div class="ml-0 ml-lg-4 pt-3 border-top">
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <div class="form-orden btn-group">
                                                <button class="btn btn-orden" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Ordenar por:<span class="chevron"></span></button>
                                                <div class="dropdown-menu">
                                                    <li class="dropdown-item"><label>
                                                    <input onclick="load(1);" type="radio" name="ord" value="novedad" class="d-none" checked>
                                                    <span class="Form-label-text">Destacado</span>
                                                </label></li>
                                                    <li class="dropdown-item"><label>
                                                    <input onclick="load(1);" type="radio" name="ord" value="alpha-ascending" class="d-none">
                                                    <span class="Form-label-text">A - Z</span>
                                                </label></li>
                                                    <li class="dropdown-item"><label>
                                                    <input onclick="load(1);" type="radio" name="ord" value="alpha-descending" class="d-none">
                                                    <span class="Form-label-text">Z - A</span>
                                                </label></li>
                                                    <li class="dropdown-item"><label>
                                                    <input onclick="load(1);" type="radio" name="ord" value="price-ascending" class="d-none">
                                                    <span class="Form-label-text">Menor precio</span>
                                                </label></li>
                                                    <li class="dropdown-item"><label>
                                                    <input onclick="load(1);" type="radio" name="ord" value="price-descending" class="d-none">
                                                    <span class="Form-label-text">Mayor precio</span>
                                                </label></li>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="list-group pb-4">
                                        <?php $Obj->filtrosCategorias() ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-8 col-lg-9">
                                <input type="hidden" id="busqueda" value="<?php if (isset($_GET['buscar'])) {echo $_GET['buscar'];} ?>" />
                                <input type="hidden" id="categoria" value="<?php if (isset($_GET['cat'])) {echo $_GET['cat'];} ?>" />
                                <div class="row" id="grilla-productos"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include_once("include/footer.php"); ?>
        <?php include_once("include/scripts-bottom.php") ?>
        <script src="<?php echo WEB_ROOT ?>js/productos.js"></script>

    </body>

    </html>