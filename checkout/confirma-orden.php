<?php
require_once '../class/class.php';
require_once '../class/cart.class.php';
require_once '../class/checkout.class.php';
$Obj = new mainClass;
$ObjCheckout = new Checkout;


if (isset($_SESSION['orderId'])) {

    if (isset($_SESSION['RequestKey'])) {
        setcookie('RequestKey',$_SESSION['RequestKey'],  time() + (86400 * 30), "/");
    }

    $order_reference=$_SESSION['orderId'];
    $sIDprev=session_id();
    $status=2;
    
    if ($ObjCheckout->CheckOrder($order_reference)) {

        $ObjCheckout->removeItemsCart();
        $ObjCheckout->statusOrder($order_reference,$status);
        
        session_unset();
        session_destroy();
        unset($_SESSION['orderId']);
        session_write_close();
        setcookie(session_name(),'',0,'/');
        
    }
    
}

?>
