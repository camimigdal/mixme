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

    <body>
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header.php") ?>
        <?php require_once("cart/cart.php") ?>

        <section id="nosotros">

            <div class="container pb-5">
                <div class="row">
                    <div class="col-12 col-lg-4 text-center py-5">
                        <div class="icon mx-auto d-flex justify-content-center align-items-center"><img src="<?php echo WEB_ROOT ?>img/icono-mixme-blanco.svg" alt="Mixme Logo" width="80"></div>
                        <div class="py-4">
                            <h1>Nosotros</h1>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8 py-5">
                    <p>En <strong>MIXME</strong> buscamos que vuelvas a esos sabores que más te gustan, de la mano de una alimentación saludable y consciente.<br><br>
                    Somos una empresa argentina que brinda un servicio hecho especialmente para vos, para tu empresa o tu negocio.<br><br>
Te invitamos a que armes tu propia línea de granolas y mixes personalizados, sabiendo exactamente lo que estás comiendo y jugando con todos los sabores y lo mejor de la nutrición.<br><br>
Contamos con un equipo de trabajo con más de 10 años de experiencia en el rubro.<br><br>
Estamos en continuo crecimiento atentos a las necesidades del medioambiente, por ese motivo nuestros packaging son reutilizables y nuestras bolsas son de paper kraft biodegradables para proteger al medio ambiente y cuidar nuestra tierra.<br><br>
Entre nuestros productos vas a poder encontrar granolas, frutos secos, frutas desecadas, harinas, cereales, legumbres, condimentos, semillas y todo de excelente calidad, tenemos una amplia variedad y trabajamos pensando en vos.</p>
                    </div>
                </div>

            </div>
        </section>


        <?php include_once("include/footer.php"); ?>
        <?php include_once("include/scripts-bottom.php") ?>

    </body>

    </html>