<?php 
use TodoPago\Sdk;

require_once("../../class/class.php");
require_once("../../class/cart.class.php");
require_once("../../class/checkout.class.php");
require_once("../../class/envio.class.php");

$Obj = new puntoed;
$ObjCheckout = new Checkout;
$ObjEnvio = new Envio;

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


	$status='RECHAZADA';
	$id_tp=$rta['Payload']['Answer']['AUTHORIZATIONCODE'];
	$payment_type=$rta['Payload']['Answer']['PAYMENTMETHODNAME'];
	$transaction_amount_order=$rta['Payload']['Request']['AMOUNT'];

    $ObjCheckout->ActualizarOrder($order_reference,$id_tp,$status,$payment_type,$transaction_amount_order,9);

    $orderInfo=$ObjCheckout->GetOrderInfo($order_reference); 

?>
<head>
        <meta charset="utf-8" />
        <title>Punto de Encuentro - Pedido cancelado</title>
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
                        <h2 class="main_title">Pedido cancelado</h2>
                    </div>
                    <div class="col-12">
                        <h4>Tu pedido está cancelado</h4>
                        <hr>
                        <p>Te invitamos a realizar un nuevo pedido en cualquier momento.<br /><br />
                        <strong>Muchas Gracias!!!</strong></p> 
                    </div>	
                </div>
            </div>
        </section>

        <?php require_once("../../include/footer.php"); ?>
        <?php require_once("../../include/scripts-bottom.php") ?>
    </body>
    </html>