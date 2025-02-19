<?php 
use TodoPago\Sdk;

require_once("../../class/class.php");
require_once("../../class/cart.class.php");
require_once("../../class/checkout.class.php");

$Obj = new puntoed;
$ObjCheckout = new Checkout;

if (!isset($_GET['operationid'])) {	
	session_destroy();
	header('Location: '.HTTP_SERVER );
}

//importo archivo con SDK
include_once dirname(__FILE__)."/vendor/autoload.php";

define('MERCHANT', 454804);
define('SECURITY', '14E966382A5273E531FFAFA21AFDC9CC');
$rk = $_COOKIE['RequestKey'];
$ak = $_GET['Answer'];
$order_reference = $_GET['operationid'];

$optionsGAA = array (     
        'Security'   => SECURITY,      
        'Merchant'   => MERCHANT,     
        'RequestKey' => $rk,       
        'AnswerKey'  => $ak // *Importante     
);  

//común a todas los métodos
$http_header = array('Authorization'=>'TODOPAGO '.SECURITY);


//creo instancia de la clase TodoPago
$connector = new Sdk($http_header, "prod");

$rta = $connector->getAuthorizeAnswer($optionsGAA);


if ($rta['StatusCode']== -1){

    $orderContent=$ObjCheckout->GetOrderContent($order_reference);
    $numItem=count($orderContent);                     
    $orderInfo=$ObjCheckout->GetOrderInfo($order_reference); 
    $orderAmount=$ObjCheckout->getOrderAmount($order_reference);


	$status=$rta['StatusMessage'];
	$id_tp=$rta['Payload']['Answer']['AUTHORIZATIONCODE'];
	$payment_type=$rta['Payload']['Answer']['PAYMENTMETHODNAME'];
	$transaction_amount_order=$rta['Payload']['Request']['AMOUNT'];

	$ObjCheckout->ActualizarOrder($order_reference,$id_tp,$status,$payment_type,$transaction_amount_order,3);


    if ($orderInfo['or_estado']==2) {

        $ObjCheckout->confirmarOrder($order_reference);


        //actualizar stock
        for ($i=0; $i<$numItem; $i++) {
            extract($orderContent[$i]);
        }

        //Envio de correo por Postmark
        $url ="https://api.postmarkapp.com/email/withTemplate";
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Postmark-Server-Token: b7f51bff-c796-46f5-8997-b99aab64868a"
        );
        
        $detalles='';
        for ($i=0; $i<$numItem; $i++) {
            extract($orderContent[$i]);
            $detalles .= '{"description": "'.$pd_titulo.'","amount": "('.$cantidad.') x $'.number_format($precio,2,',','.').'"},';
        }
        $detalles = substr($detalles, 0, -1);

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
        } else {
            $pm_id_pago = $id_tp;
            $pm_forma_pago = $payment_type;
            $pm_estado_pago = $status;
            $pm_transferencia = '';
            $pm_banco = '';
            $pm_tipo_cuenta = '';
            $pm_numero_cuenta = '';
            $pm_cbu_cuenta = '';
            $pm_titular_cuenta = '';
            $pm_cuit_cuenta = '';
        }


        $parametros_post = '{
            "From": "ventas@puntoed.com.ar",
            "To": "'.$orderInfo["or_email"].',ventas@puntoed.com.ar",
            "TemplateAlias": "confirmacion-compra",
            "TemplateModel": {
                "site_url": "'.HTTP_SERVER.'",
                "company_name": "Punto de Encuentro",
                "company_address": "Av. de Mayo 1110 - CABA, Argentina",
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
                "total": "'.number_format($transaction_amount_order,2,',','.').'",
                "envio": "'.$orderInfo['env_nom_correo'].' - '.$orderInfo['env_descripcion'].' - '.$orderInfo['env_horas_entrega'].'",
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
        <title>Punto de Encuentro - Compra confirmada</title>
        <meta name="description" content="Su compra fue aprobada" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <?php require_once("../../include/css.php") ?>    
        <?php require_once("../../include/favicon.php") ?>
    </head>

    <body class="page">

        <?php require_once("../../include/header.php"); ?>
        <?php require_once("../../cart/cart.php"); ?>

        <section id="checkout">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 pb-5">
                        <h2 class="main_title">Gracias por tu compra!</h2>
                    </div>

                    <div class="col-12 col-md-7">
                        <h4><?php echo $orderInfo["or_nombre"] ?>, el pedido ya es tuyo:</h4>
                        <hr>
                        

                        <p>Te enviamos un email con el detalle de la compra.<br>
                        Asimismo te informaremos sobre cada estado que se encuentra el pedido.<br>
                        Si de todos modos tenés alguna consulta, podés comunicarte a los teléfonos o mails informados en este sítio web.<br><br>
                        El equipo de <strong>Punto de Encuentro</strong></p>

                    </div>
                    <div class="col-12 col-md-5 p-5 bg-white">

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
                                            <p>A domicilio por:</p>
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
                                            <p>Retiro personal</p>
                                            <p>Av. de Mayo 1110, CABA, Argentina</p>
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
                                            echo "<p><strong>TODO PAGO</strong></p>";
                                            echo '<p>ID PAGO: '.$id_tp.'</p>';
                                            echo '<p>FORMA DE PAGO: '.$payment_type.'</p>';
                                            echo '<p>ESTADO: '.$status.'</p>';
                                            echo '<p class="font-weight-bold">TOTAL PAGADO: $'.number_format($transaction_amount_order,2,',','.').'</p>';
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

        <?php require_once("../../include/footer.php"); ?>
        <?php require_once("../../include/scripts-bottom.php") ?>
    </body>
    </html>

<?php 
}else{
	header("location: error.php?operationid=$order_reference");
}
?>