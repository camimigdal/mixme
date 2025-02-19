<?php
require_once("../class/mixer.class.php");

$ObjMixer = new Mixer();

$action = (isset($_GET['action']) && $_GET['action'] != '');
$action = strip_tags($_GET['action'], ENT_QUOTES);

switch ($action) {
	case 'create' :
		$ObjMixer->createMixer();
		break;
	case 'load' :
		$ObjMixer->getCartContentAjax($_REQUEST['type']);
		break;
	case 'add' :
		$ObjMixer->addToCart();						
		break;
	case 'addbase' :
		$ObjMixer->addBaseToCart();						
		break;
	case 'updateMas' :
		$ObjMixer->updateMas();
		break;
	case 'updateMenos' :
		$ObjMixer->updateMenos();
		break;
	case 'count' :
		$ObjMixer->getCountCart();
		break;
	case 'delete' :
		$ObjMixer->deleteCart();
		break;
	case 'deleteall' :
		$ObjMixer->deleteAll();
		break;
	case 'loadCheckout' :
		$ObjMixer->getMixerContentCheck();
		break;
}

?>
