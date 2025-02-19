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

        <section id="faqs">
            <div class="container pb-5">
                <div class="row">
                    <div class="col-12 col-lg-4 text-center pt-5">
                        <div class="icon mx-auto d-flex justify-content-center align-items-center"><img src="<?php echo WEB_ROOT ?>img/icon-faqs-blanco.svg" alt="Mixme faqs" width="50"></div>
                        <div class="pt-4">
                            <h1>FAQs</h1>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8 py-5">
                        <h2 class="title-faqs">Relacionado con mi pedido</h2>
                        <div class="accordion mb-5" id="accordionFaqs">
                            <div class="card">
                                <div class="card-header" id="heading1">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        ¿Es necesario crear una cuenta para hacer un pedido?
                                          </button>
                                    </h2>
                                </div>

                                <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionFaqs">
                                    <div class="card-body p-4">
                                    No es necesario, pero te podés anotar en nuestro Newsletter para recibir promociones y descuentos. Si ingresas con tus datos, simplificas tus futuros pedidos ya que quedará agendada tu información personal. Te recordamos que nuestro sitio es completamente seguro.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading3">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        ¿Cómo puedo saber si mi pedido fue efectuado correctamente?
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionFaqs">
                                    <div class="card-body p-4">
                                    Luego de haber hecho tu pedido, te va a llegar por mail la confirmación con los detalles de tu pedido. Si tu mail no llegó posiblemente se encuentra en la casilla de SPAM, CORREO NO DESEADO. En ese caso envianos un mail a <a href="mailto:info@mixme.com.ar">info@mixme.com.ar</a> con el número de pedido y redacta tu inquietud. Si no es el caso, y tu correo está escrito correctamente, escribinos! 
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading4">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        ¿Cuánto tarda el pedido en llegar a mi casa? 
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionFaqs">
                                    <div class="card-body p-4">
                                    Desde el momento que realizas el pedido, se programa para llegar a tu domicilio en máximo 2 días hábiles. Nuestros envíos se realizan con motos particulares, por lo cual si tenés preferencias de horarios, envianos un mail a <a href="mailto:info@mixme.com.ar">info@mixme.com.ar</a> o un whatsapp al +5491136533648 con el número de pedido y te responderemos a la brevedad. Horario de Atención de Lunes a Viernes de 9 a 18hs. 
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading5">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                        ¿Qué pasa si quiero cancelar o cambiar mi pedido? 
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionFaqs">
                                    <div class="card-body p-4">
                                    Para cancelar o modificar tu pedido, contactanos por correo electrónico a <a href="mailto:info@mixme.com.ar">info@mixme.com.ar</a> con el asunto “MODIFICACIÓN DE PEDIDO Nºxxxxx”, si el pedido no está en marcha, podremos modificarlo, en caso de que ya haya sido armado por el staff, no se podrá modificar.
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card">
                                <div class="card-header" id="heading12">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
                                            Tengo una pregunta que no está en la lista
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse12" class="collapse" aria-labelledby="heading12" data-parent="#accordionFaqs">
                                    <div class="card-body p-4">
                                        Contactate con nosotros<br>
                                        <strong>Teléfono:</strong> 11-5619-6815<br>
                                        <strong>WhatsApp:</strong> 11-3653-3648<br>
                                        <strong>info@mixme.com.ar</strong>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h2 class="title-faqs">¿Cómo hago para hacer mi mezcla?</h2>
                        <div class="accordion" id="accordionFaqsmixer">
                            <div class="card">
                                <div class="card-header" id="heading11">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                        ¿Cómo hago para hacer mi mezcla? 
                                          </button>
                                    </h2>
                                </div>

                                <div id="collapse11" class="collapse" aria-labelledby="heading11" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    En este video te dejamos un explicativo de como funciona el mixer: Cualquier duda o consulta, te podés contactar con nosotros.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading13">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13">
                                        ¿Cuántos ingredientes puedo colocar en mi mezcla? 
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse13" class="collapse" aria-labelledby="heading3" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    Una vez elegido el packaging con el total de producto, podés elegir entre más de 50 ingredientes los que prefieras para tu mezcla. Todos los ingredientes tienen una porción recomendada que se relaciona con el tamaño que elegiste. Te recomendamos elegir máximo 6 porciones que pueden ser frutos secos, semillas y cereales, chocolates en caso de que lo desees y frutas. Las porciones varían según la categoría y podés verlo debajo del nombre de cada ingrediente. ¡A jugar! 
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading14">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14">
                                        Si quiero más de un ingrediente en particular, ¿puedo aumentar la cantidad?  
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse14" class="collapse" aria-labelledby="heading14" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    Si, se puede!,en el mixer vas a encontrar un  + y un - , desde ahí, podés multiplicar las porciones, o eliminar el ingrediente de manera muy simple y rápida.  
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading15">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse15" aria-expanded="false" aria-controls="collapse15">
                                        ¿Cuánto tiempo dura mi producto? 
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse15" class="collapse" aria-labelledby="heading15" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    Todos nuestros productos deben almacenarse en un lugar fresco y seco, en general duran 6 meses desde la fecha de elaboración, pero es tan rico que te lo vas a comer mucho más rápido!
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card">
                                <div class="card-header" id="heading16">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse16" aria-expanded="false" aria-controls="collapse16">
                                        ¿Cuáles son los valores nutricionales de mi mezcla?
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse16" class="collapse" aria-labelledby="heading16" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    En el mezclador vas a poder visualizar mientras armas tu mezcla, todos los valores nutricionales. Tambien tenes iconos que te muestran que ingredientes son más altos en fibra, fuente de proteínas, sin lactosa, sin azúcar agregada, veganos, por lo cual, podes armar tu mezcla con las especificaciones que estés buscando para tu dieta!
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading17">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse17" aria-expanded="false" aria-controls="collapse17">
                                        ¿Puedo hacer una mezcla vegana? 
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse17" class="collapse" aria-labelledby="heading17" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    Si podés, hay muchos ingredientes, como copos y frutas que son veganas por naturaleza, en cada ingrediente igualmente, vas a visualizar el icono de vegano para que puedas elegir más libremente, fácil y rápido!.
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading18">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-lg collapsed" type="button" data-toggle="collapse" data-target="#collapse18" aria-expanded="false" aria-controls="collapse18">
                                        ¿Cuáles son los medios de pago?
                                          </button>
                                    </h2>
                                </div>
                                <div id="collapse18" class="collapse" aria-labelledby="heading18" data-parent="#accordionFaqsmixer">
                                    <div class="card-body p-4">
                                    Podés pagar por Mercado pago, transferencia o en efectivo cuando recibís la compra! ¡No te olvides de enviarnos el comprobante!
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </section>


        <?php include_once("include/footer.php"); ?>
        <?php include_once("include/scripts-bottom.php") ?>

    </body>

    </html>