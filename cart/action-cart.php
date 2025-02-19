<?php
require_once("../class/cart.class.php");

$ObjCart = new Cart();

$action = (isset($_GET['action']) && $_GET['action'] != '');
$action = strip_tags($_GET['action'], ENT_QUOTES);

switch ($action) {
	case 'load' :
		$ObjCart->getCartContentAjax();
		break;
	case 'add' :
		$prod=filter_input(INPUT_GET,'prod', FILTER_SANITIZE_NUMBER_INT);
		$cant=filter_input(INPUT_GET,'cant', FILTER_SANITIZE_NUMBER_INT);
		if (isset($_GET['variacion'])) {
			$variacion=filter_input(INPUT_GET,'variacion', FILTER_SANITIZE_SPECIAL_CHARS);
		} else {
			$variacion=0;
		}
		$prec=filter_input(INPUT_GET,'precio', FILTER_SANITIZE_NUMBER_INT);

		$ObjCart->addToCart($prod,$variacion,$cant,$prec);						
		break;
	case 'update' :
		$ObjCart->updateCart();
		break;
	case 'count' :
		$ObjCart->getCountCart();
		break;
	case 'delete' :
		$ObjCart->deleteCart();
		break;
	case 'addMix' :
		$ObjCart->addMixToCart();
		break;
}

?>
