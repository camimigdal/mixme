<?php
require_once("../class/class.php");
require_once("../class/cart.class.php");
require_once("../class/checkout.class.php");
require_once("../class/envio.class.php");

$Obj = new mainClass;
$ObjCheckout = new Checkout;
$ObjEnvio = new Envio;



$orderContent=$ObjCheckout->GetOrderContent(63);                   
$orderInfo=$ObjCheckout->GetOrderInfo(63); 
$orderAmount=$ObjCheckout->getOrderAmount(63); 

$resultEnvio=$ObjEnvio->crearPedidoEnvioPack($orderContent,$orderInfo);

var_dump($resultEnvio);
?>
