<?php
require '/home/cbjuoyjt/public_html/class/class.php';
require '/home/cbjuoyjt/public_html/class/checkout.class.php';

$ObjCheckout = new Checkout;
$orderInfo = $ObjCheckout->GetOrderInfo($order_reference);

$paymentId = $orderInfo[''];


// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// TEST
MercadoPago\SDK::setAccessToken('APP_USR-7708904245408069-101413-070a1a0b8f791c5a29a2d14fa8fec444-658774491');
// PRODUCCION
//MercadoPago\SDK::setAccessToken('APP_USR-808642073626698-041119-8fdbedd04abce152ad13aa27591ff029-321966989');




			
$payment = MercadoPago\Payment::find_by_id($paymentId);
$payment->refund(10.5);

?>

<hr>
<div class="action_cart my-4">
    <a onclick="actualizar('<?php echo $preference->init_point; ?>');" class="btn btn-primary btn-lg">PAGAR CON MERCADO PAGO</a>
</div>