<?php
require_once("../class/class.php");
require_once("../class/cart.class.php");
require_once("../class/checkout.class.php");
require_once("../class/envio.class.php");

$Obj = new mainClass;
$ObjCheckout = new Checkout;
$ObjEnvio = new Envio;

$collection_id = filter_input(INPUT_GET, 'collection_id', FILTER_SANITIZE_SPECIAL_CHARS);
$collection_status = filter_input(INPUT_GET, 'collection_status', FILTER_SANITIZE_SPECIAL_CHARS);
$order_reference = filter_input(INPUT_GET, 'external_reference', FILTER_SANITIZE_SPECIAL_CHARS);
$payment_type = filter_input(INPUT_GET, 'payment_type', FILTER_SANITIZE_SPECIAL_CHARS);


if (!isset($collection_id) || !isset($collection_status) || !isset($order_reference) || !isset($payment_type)) {
    header('Location: '.HTTP_SERVER );
    exit();
}

$orderContent=$ObjCheckout->GetOrderContent($order_reference);
$numItem=count($orderContent);                     
$orderInfo=$ObjCheckout->GetOrderInfo($order_reference); 
$orderAmount=$ObjCheckout->getOrderAmount($order_reference); 
$datos=$ObjCheckout->datosTransferencia();


if ($ObjCheckout->CheckOrderConfirmada($order_reference)) { 
    

    $ObjCheckout->confirmarOrder($order_reference);


    switch ($collection_status) {
        case 'pending':
            $statusOrden=2;
            break;
        case 'approved':
            $statusOrden=3;
            break;
        case 'in_process':
            $statusOrden=2;
            break;
        case 'in_mediation':
            $statusOrden=2;
            break;
        case 'rejected':
            $statusOrden=9;
            break;
        case 'cancelled':
            $statusOrden=8;
            break;
        case 'refunded':
            $statusOrden=2;
            break;
        case 'charged_back':
            $statusOrden=2;
            break;
    }



    if ($payment_type=='transferencia' or $payment_type=='efectivo' or $payment_type=='otro') {
        $total_pagado=0;
    } else {
        $total_pagado=$orderAmount;
    }

    $ObjCheckout->ActualizarOrder($order_reference,$collection_id,$collection_status,$payment_type,$total_pagado,$statusOrden);



        //Envio de correo por Postmark
        $url ="https://api.postmarkapp.com/email/withTemplate";
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Postmark-Server-Token: 9e6155b1-361b-41b9-89ad-685edc9b1ad0"
        );
        
        $detalles='';
        $productosGTM='';

        for ($i=0; $i<$numItem; $i++) {
            extract($orderContent[$i]);
            if ($variacion) $varia=$variacion; else $varia='';

            if ($status=='mix') {
                $idMix = str_replace("mix-", "", $codigo);
                $token = $ObjCheckout->GetTokenEtiquetaMix($idMix);
                $linketiqueta='mix-etiqueta-pdf.php?id='.$idMix.'&token='.$token;
                $detalles .= '{"description": "'.$pd_titulo.' '.$varia.'","amount": "('.$cantidad.') x $'.number_format($precio,2,',','.').'","linketiqueta": "'.$linketiqueta.'"},';
            } else { 
                $detalles .= '{"description": "'.$pd_titulo.' '.$varia.'","amount": "('.$cantidad.') x $'.number_format($precio,2,',','.').'"},';
            }

            $productosGTM .= "{'name': '".$pd_titulo."','id': '".$codigo."','price': ".number_format($precio,2,'.','').",'quantity': ".$cantidad."},";
        }
        $detalles = substr($detalles, 0, -1);
        $productosGTM = substr($productosGTM, 0, -1);


        $descuentos='';
        $orderDiscount=$ObjCheckout->GetOrderDiscount($order_reference);
        $numItemDesc=count($orderDiscount);  
        for ($i=0; $i<$numItemDesc; $i++) {
            extract($orderDiscount[$i]);
            $descuentos .= '{"description": "'.$desc_descripcion.'","amount": "-$'.number_format($desc_precio,2,',','.').'"},';
        }
        $descuentos = substr($descuentos, 0, -1);


        if($orderInfo["env_tipo"]=='D') {     
            $pm_envio_domicilio = 'D';
            $pm_nombre_envio = $orderInfo['env_nombre'].' '.$orderInfo['env_apellido'];
            $pm_tel_envio = $orderInfo['env_telefono'];
            $pm_direccion_envio = $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
            if (!empty($orderInfo['env_piso'])) {
                $pm_direccion_envio .= ' '.$orderInfo['env_piso'];
            }
            if (!empty($orderInfo['env_depto'])) {
                $pm_direccion_envio .= ' '.$orderInfo['env_depto'];
            }
            $pm_cp_envio = $orderInfo['env_codpostal'];
            $pm_localidad_envio = $orderInfo['env_localidad'];
            $pm_provincia_envio = $orderInfo['env_provincia'];
        } else {
            $pm_envio_domicilio = '';
            $pm_nombre_envio = '';
            $pm_tel_envio = '';
            $pm_direccion_envio = '';
            $pm_cp_envio = '';
            $pm_localidad_envio = '';
            $pm_provincia_envio = '';
        }


        $pm_direccion_comprador = $orderInfo['or_calle'].' '.$orderInfo['or_calle_num'];
        if (!empty($orderInfo['or_piso'])) {
            $pm_direccion_comprador .= ' '.$orderInfo['or_piso'];
        }
        if (!empty($orderInfo['or_depto'])) {
            $pm_direccion_comprador .= ' '.$orderInfo['or_depto'];
        }

        switch ($orderInfo['or_medio_pago']) {
            case 'mp':
                $pm_medio_pago = "MERCADO PAGO";
                break;
            case 'tp':
                $pm_medio_pago = "TODO PAGO";
                break;
            case 'transferencia':
                $pm_medio_pago = "TRANSFERENCIA BANCARIA";
                break;
            case 'efectivo':
                $pm_medio_pago = "EFECTIVO CONTRA ENTREGA";
                break;
            case 'otro':
                $pm_medio_pago = "OTRO MEDIO DE PAGO";
                break;
        } 
        if ($payment_type=='transferencia') {
            $pm_id_pago = 'a confirmar';
            $pm_forma_pago = 'Transferencia';
            $pm_estado_pago = 'Esperando acreditación en nuestra cuenta';
            $pm_transferencia = 'S';
            $pm_banco = $datos["banco"];
            $pm_tipo_cuenta = $datos["tipo"];
            $pm_numero_cuenta = $datos["num_cuenta"];
            $pm_cbu_cuenta = $datos["cbu"];
            $pm_titular_cuenta = $datos["titular"];
            $pm_cuit_cuenta = $datos["cuit"];
        } elseif ($payment_type=='efectivo') {
            $pm_id_pago = 'a confirmar';
            $pm_forma_pago = 'Efectivo contra entrega';
            $pm_estado_pago = 'Paga al recibir la compra';
            $pm_transferencia = '';
            $pm_banco = '';
            $pm_tipo_cuenta = '';
            $pm_numero_cuenta = '';
            $pm_cbu_cuenta = '';
            $pm_titular_cuenta = '';
            $pm_cuit_cuenta = '';
        } elseif ($payment_type=='otro') {
            $pm_id_pago = 'a confirmar';
            $pm_forma_pago = 'Otro medio de pago';
            $pm_estado_pago = 'Contactar para arreglar medio de pago';
            $pm_transferencia = '';
            $pm_banco = '';
            $pm_tipo_cuenta = '';
            $pm_numero_cuenta = '';
            $pm_cbu_cuenta = '';
            $pm_titular_cuenta = '';
            $pm_cuit_cuenta = '';
        } else {
            $pm_id_pago = $collection_id;
            $pm_forma_pago = $payment_type;
            $pm_estado_pago = $collection_status;
            $pm_transferencia = '';
            $pm_banco = '';
            $pm_tipo_cuenta = '';
            $pm_numero_cuenta = '';
            $pm_cbu_cuenta = '';
            $pm_titular_cuenta = '';
            $pm_cuit_cuenta = '';
        }


        $parametros_post = '{
            "From": "info@mixme.com.ar",
            "To": "'.$orderInfo["or_email"].',info@mixme.com.ar",
            "TemplateAlias": "confirmacion-compra",
            "TemplateModel": {
                "site_url": "'.HTTP_SERVER.'",
                "company_name": "Mixme",
                "company_address": "Av. Argentina 5659, Villa Lugano, CABA, Argentina",
                "name": "'.$orderInfo["or_nombre"].'",
                "orden_id": "'.$order_reference.'",
                "fecha_orden": "'.date("d M Y", strtotime($orderInfo["fecha_alta"])).'",
                "invoice_details": [
                    '.$detalles.'
                ],
                "discount_details": [
                    '.$descuentos.'
                ],
                "amount_envio": "'.number_format($orderInfo["env_valor"],2,',','.').'",
                "total": "'.number_format($orderAmount,2,',','.').'",
                "envio": "'.$orderInfo['env_descripcion'].'",
                "envio_domicilio": "'.$pm_envio_domicilio.'",
                "nombre_envio": "'.$pm_nombre_envio.'",
                "tel_envio": "'.$pm_tel_envio.'",
                "direccion_envio": "'.$pm_direccion_envio.'",
                "cp_envio": "'.$pm_cp_envio.'",
                "localidad_envio": "'.$pm_localidad_envio.'",
                "provincia_envio": "'.$pm_provincia_envio.'",
                "nombre_comprador": "'.$orderInfo["or_nombre"].' '.$orderInfo["or_apellido"].'",
                "dni_comprador": "'.$orderInfo['or_dni'].'",
                "tel_comprador": "'.$orderInfo['or_telefono'].'",
                "direccion_comprador": "'.$pm_direccion_comprador.'",
                "cp_comprador": "'.$orderInfo['env_codpostal'].'",
                "localidad_comprador": "'.$orderInfo['or_ciudad'].'",
                "provincia_comprador": "'.$orderInfo['or_provincia'].'",
                "medio_pago": "'.$pm_medio_pago.'",
                "id_pago": "'.$pm_id_pago.'",
                "forma_pago": "'.$pm_forma_pago.'",
                "estado_pago": "'.$pm_estado_pago.'",
                "transferencia": "'.$pm_transferencia.'",
                "banco": "'.$pm_banco.'",
                "tipo_cuenta": "'.$pm_tipo_cuenta.'",
                "numero_cuenta": "'.$pm_numero_cuenta.'",
                "cbu_cuenta": "'.$pm_cbu_cuenta.'",
                "titular_cuenta": "'.$pm_titular_cuenta.'",
                "cuit_cuenta": "'.$pm_cuit_cuenta.'",
                "mensaje": "'.$orderInfo['or_notas'].'"
            }
        }';

        $sesion = curl_init($url);
        curl_setopt($sesion, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($sesion, CURLOPT_CAINFO, 0);
        curl_setopt($sesion, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($sesion, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($sesion, CURLOPT_POST, true);
        curl_setopt ($sesion, CURLOPT_POSTFIELDS, $parametros_post);
        curl_setopt($sesion, CURLOPT_HEADER, false);
        curl_setopt($sesion, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($sesion);
        curl_close($sesion);
        //fin Envio de correo por Postmark

    }
?>
    <!DOCTYPE html>
    <html lang="es-ES">

    <head>
        <meta charset="utf-8" />
        <title>Mixme - Compra confirmada</title>
        <meta name="description" content="Su compra fue aprobada" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <?php require_once("../include/css.php") ?>    
        <?php require_once("../include/favicon.php") ?>
        
        <script>
            var dataLayer  = window.dataLayer || [];
            dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
            dataLayer.push({
                'event': 'transaction',
                'ecommerce': {
                    'purchase': {
                        'actionField': {
                            'id': '<?php echo $order_reference ?>', 
                            'revenue': '<?php echo number_format($orderAmount,2,'.','') ?>'
                        },
                        'products': [<?php echo $productosGTM ?>]
                    }
                }
            });
        </script>
        <?php require_once("../include/scripts-head.php"); ?>
    </head>

    <body>
        <?php require_once("../include/scripts-body.php") ?>
        <?php require_once("../include/header.php"); ?>
        <?php require_once("../cart/cart.php"); ?>

        <section id="checkout">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 pb-5">
                        <h2 class="main_title">Gracias por tu compra!</h2>
                    </div>

                    <div class="col-12 col-md-7">
                        <h4><?php echo $orderInfo["or_nombre"] ?>:</h4>
                        <hr>
                        
                        <?php 
                        if ($payment_type=='transferencia') {

                        echo '<p class="my-4 text-primary">Ahora tenés que hacer la transferencia de <span class="font-weight-bold">$'.number_format($orderAmount,2,',','.').'</span> a la siguiente cuenta:</p>';

                        echo '<p><strong>Banco:</strong> '.$datos["banco"].'<br>
                        <strong>Tipo de cuenta:</strong> '.$datos["tipo"].'<br>
                        <strong>Número de cuenta:</strong> '.$datos["num_cuenta"].'<br>
                        <strong>CBU:</strong> '.$datos["cbu"].'<br>
                        <strong>Titular:</strong> '.$datos["titular"].'<br>
                        <strong>CUIT:</strong> '.$datos["cuit"].'</p>';

                        echo '<p class="my-4">Una vez hecha la transferencia debes informar tu pago a <a href="mailto:info@mixme.com.ar">info@mixme.com.ar</a>. Cuando tu dinero esté acreditado te informaremos sobre cada estado que se encuentra el pedido.</p>
                        <hr>';
                        
                        } ?>

                        <?php 
                        if ($payment_type=='efectivo') {

                        echo '<p class="my-4 text-primary">Al recibir tu pedido tenés que pagar <span class="font-weight-bold">$'.number_format($orderAmount,2,',','.').'</span> en efectivo.<br>
                        Si tenés alguna duda podemos ayudarte, escribinos!</p><hr>';
                        
                        } ?>

                        
                        <p>Te enviamos un email con el detalle de la compra.<br>
                        Asimismo te informaremos sobre cada estado que se encuentra el pedido.<br>
                        Si de todos modos tenés alguna consulta, podés comunicarte a los teléfonos o mails informados en este sítio web.<br><br>
                        El equipo de <strong>MIXME</strong></p>

                    </div>
                    <div class="col-12 col-md-5 p-5 bg-light">

                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>ORDEN</h4>
                                            <p>#<?php echo $order_reference; ?></p>
                                        </div>
                                    </div>


                        <?php if($orderInfo["env_tipo"]=='D') { ?>
                                    
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>ENTREGA</h4>
                                            <p><?php echo $orderInfo['env_nom_correo']; ?></p>
                                            <p><?php echo $orderInfo['env_descripcion']; ?></p>
                                        </div>
                                    </div>

                                    <div class="review-block">

                                            <div class="mt-3">
                                                <h6>DATOS DE ENTREGA</h4>
                                                <p>DNI/CUIT <?php echo $orderInfo['env_dni'] ?></p>
                                                <p><?php echo $orderInfo['env_nombre'].' '.$orderInfo['env_apellido']; ?></p>
                                                <p>Tel <?php echo $orderInfo['env_telefono']; ?></p>
                                                <p><?php echo $orderInfo['env_calle'].' '.$orderInfo['env_numero'];

                                                    if (!empty($orderInfo['env_piso'])) {
                                                        echo ' '.$orderInfo['env_piso'];
                                                    }
                                                    if (!empty($orderInfo['env_depto'])) {
                                                        echo ' '.$orderInfo['env_depto'];
                                                    }
                                                
                                                echo ', CP '.$orderInfo['env_codpostal']; ?></p>
                                                <p><?php echo $orderInfo['env_localidad'].', '.$orderInfo['env_provincia']; ?></p>
                                            </div>

                                            <div class="mt-3">
                                                <h6>DATOS DE FACTURACIÓN</h4>
                                                <p>DNI/CUIT <?php echo $orderInfo['or_dni'] ?></p>
                                                <p><?php echo $orderInfo['or_nombre'].' '.$orderInfo['or_apellido']; ?></p>
                                                <p>Tel <?php echo $orderInfo['or_telefono']; ?></p>
                                                <p><?php echo $orderInfo['or_calle'].' '.$orderInfo['or_calle_num'];

                                                    if (!empty($orderInfo['or_piso'])) {
                                                        echo ' '.$orderInfo['or_piso'];
                                                    }
                                                    if (!empty($orderInfo['or_depto'])) {
                                                        echo ' '.$orderInfo['or_depto'];
                                                    }
                                                    
                                                echo ', CP '.$orderInfo['or_codpostal']; ?></p>
                                                <p><?php echo $orderInfo['or_ciudad'].', '.$orderInfo['or_provincia']; ?></p>
                                            </div>

                                    </div>

                            <?php } elseif($orderInfo["env_tipo"]=='S') { ?>

                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>ENTREGA</h4>
                                            <p>Retiro personal en Mixme</p>
                                            <p>Av. Argentina 5659, Villa Lugano, CABA, Argentina</p>
                                        </div>
                                    </div>
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>DATOS DE FACTURACIÓN</h4>
                                            <p>DNI/CUIT <?php echo $orderInfo['or_dni'] ?></p>
                                            <p><?php echo $orderInfo['or_nombre'].' '.$orderInfo['or_apellido']; ?></p>
                                            <p>Tel <?php echo $orderInfo['or_telefono']; ?></p>
                                            <p><?php echo $orderInfo['or_calle'].' '.$orderInfo['or_calle_num'];

                                                if (!empty($orderInfo['or_piso'])) {
                                                    echo ' '.$orderInfo['or_piso'];
                                                }
                                                if (!empty($orderInfo['or_depto'])) {
                                                    echo ' '.$orderInfo['or_depto'];
                                                }
                                                
                                            echo ', CP '.$orderInfo['or_codpostal']; ?></p>
                                            <p><?php echo $orderInfo['or_ciudad'].', '.$orderInfo['or_provincia']; ?></p>
                                        </div>
                                    </div>

                            <?php } ?>


                                <div class="review-block">
                                    <div class="mt-3">
                                        <h6>PAGO</h4>
                                        <?php 
                                            switch ($orderInfo['or_medio_pago']) {
                                                case 'mp':
                                                    echo "<p><strong>MERCADO PAGO</strong></p>";
                                                    break;
                                                case 'tp':
                                                    echo "<p><strong>TODO PAGO</strong></p>";
                                                    break;
                                                case 'transferencia':
                                                    echo "<p>TRANSFERENCIA BANCARIA</p>";
                                                    break;
                                                case 'efectivo':
                                                    echo "<p>EFECTIVO</p>";
                                                    break;
                                                case 'otro':
                                                    echo "<p>El Vendedor se contacta</p>";
                                                    break;
                                            } 

                                            if ($payment_type=='transferencia') {
                                                echo '<p>ID PAGO: a confirmar</p>';
                                                echo '<p>FORMA DE PAGO: Transferencia</p>';
                                                echo '<p>ESTADO: Esperando acreditación en nuestra cuenta.</p>';
                                                echo '<p class="text-primary font-weight-bold">TOTAL A PAGAR: $'.number_format($orderAmount,2,',','.').'</p>';
                                            } elseif ($payment_type=='efectivo') {
                                                echo '<p>ID PAGO: a confirmar</p>';
                                                echo '<p>FORMA DE PAGO: Efectivo contra entrega</p>';
                                                echo '<p>ESTADO: Paga al recibir la compra</p>';
                                                echo '<p class="text-primary font-weight-bold">TOTAL A PAGAR: $'.number_format($orderAmount,2,',','.').'</p>';
                                            } elseif ($payment_type=='otro') {
                                                echo '<p class="text-primary font-weight-bold">TOTAL A PAGAR: $'.number_format($orderAmount,2,',','.').'</p>';
                                            } else {
                                                echo '<p>ID PAGO: '.$collection_id.'</p>';
                                                echo '<p>FORMA DE PAGO: '.$payment_type.'</p>';
                                                echo '<p>ESTADO: '.$collection_status.'</p>';
                                                echo '<p class="font-weight-bold">TOTAL PAGADO: $'.number_format($orderAmount,2,',','.').'</p>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($orderInfo['or_notas'])) { ?>
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>NOTAS DE PEDIDO</h4>
                                            <p><?php echo $orderInfo['or_notas']; ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                    
                    </div>
                </div>
            </div>
        </section>

        <?php require_once("../include/footer.php"); ?>
        <?php require_once("../include/scripts-bottom.php") ?>
    </body>
    </html>