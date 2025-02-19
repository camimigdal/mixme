<?php
require_once("class/class.php");
require_once("class/cart.class.php");
require_once("class/checkout.class.php");

$Obj = new mainClass;
$ObjCart = new Cart;
$ObjCheckout = new Checkout;

$pagina = basename($_SERVER['PHP_SELF']);
$metaTags=$Obj->DatosSeo($pagina);


if ($ObjCart->isCartEmpty()) {
    header('Location: index.php');
} else if (isset($_GET['step']) && (int)$_GET['step'] > 0 && (int)$_GET['step'] <= 2) {
    $step = (int)$_GET['step'];
    $includeFile = '';

    switch ($step) {
        case 1:
            $includeFile = 'shippingAndPaymentInfo.php';
            break;
        case 2:
            if (!empty($_POST)) {

                $orderId = $ObjCheckout->saveOrder();

                    if ($orderId) {
                        $orderAmount = $ObjCheckout->getOrderAmount($orderId);
                        $orderDiscount = $ObjCheckout->GetOrderDiscount($orderId);
                        $orderContent = $ObjCheckout->GetOrderContent($orderId);
                        $orderInfo = $ObjCheckout->GetOrderInfo($orderId);
                        extract($orderInfo);
                        
                        $_SESSION['orderId'] = $orderId;
                        
                        switch ($_POST['opcion_pago']) {
                            case 'tp':
                                $includeFilePayment = 'tp/payment.php';
                                break;
                            case 'mp':
                                $includeFilePayment = 'mp/payment.php';
                                break;
                            case 'transferencia':
                                $includeFilePayment = 'transferencia.php';
                                break;
                            case 'efectivo':
                                $includeFilePayment = 'efectivo.php';
                                break;
                            case 'otro':
                                $includeFilePayment = 'otros-pagos.php';
                                break;
                        }
                        $includeFile = 'checkoutConfirmation.php';
                    } else {
                        extract($_POST);
                        $includeFile = 'shippingAndPaymentInfo.php';
                    }

            } else {
                header('Location: '.WEB_ROOT.'checkout.php?step=1');
            }
            break;
    }

} else { 
    header('Location: index.php');
}
$cartContent = $ObjCart->getCartContent();
$cartItem=count($cartContent);
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
        <?php require_once("include/scripts-head.php") ?>

        <script src="https://unpkg.com/feather-icons"></script>

    </head>

    <body class="page">
        <?php require_once("include/scripts-body.php") ?>
        <?php require_once("include/header-checkout.php"); ?>
        <?php require_once("cart/cart.php"); ?>

        <?php require_once "checkout/$includeFile"; ?>

        <?php require_once("include/footer.php"); ?>
        <?php require_once("include/scripts-bottom.php") ?>

        <script src="<?php echo WEB_ROOT ?>js/jquery.validate.js"></script>
        <script src="<?php echo WEB_ROOT ?>js/localization/messages_es_AR.js"></script>
        <script src="<?php echo WEB_ROOT ?>js/shippingAndPaymentInfo.js"></script>
        <script src="<?php echo WEB_ROOT ?>js/checkout.js"></script>
        <script src="<?php echo WEB_ROOT ?>js/descuentos.js"></script>
    </body>
    </html>