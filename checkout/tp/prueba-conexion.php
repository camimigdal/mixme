<?php


use TodoPago\Sdk;

//importo archivo con SDK
include_once 'vendor/autoload.php';

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 454804);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', '14E966382A5273E531FFAFA21AFDC9CC');

//común a todas los métodos
$http_header = array('Authorization'=>'TODOPAGO '.SECURITY,
 'user_agent' => 'PHPSoapClient');


//creo instancia de la clase TodoPago
$connector = new Sdk($http_header, "prod");

var_dump($connector);
?>
