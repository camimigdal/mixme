<?php
require_once("../class/mixer.class.php");

$ObjMixer = new Mixer();

$action = (isset($_GET['action']) && $_GET['action'] != '');
$action = strip_tags($_GET['action'], ENT_QUOTES);

switch ($action) {
	case 'loadCheckout' :
		$ObjMixer->getMixerContentCheck();
		break;
}

?>
