<?php
$numItem=count($orderContent);
			

$CSITPRODUCTCODE='';
$CSITPRODUCTDESCRIPTION='';
$CSITPRODUCTNAME='';
$CSITPRODUCTSKU='';
$CSITTOTALAMOUNT=0;
$CSITQUANTITY='';
$CSITUNITPRICE='';


for ($i=0; $i<$numItem; $i++) {
	extract($orderContent[$i]);
	if($i==$numItem-1) {
		$CSITPRODUCTCODE.="shipping_only";
		$CSITPRODUCTDESCRIPTION.=$pd_titulo;
		$CSITPRODUCTNAME.=$pd_titulo;
		$CSITPRODUCTSKU.=$pd_id;
		$CSITTOTALAMOUNT.= $precio * $cantidad;
		$CSITQUANTITY.=$cantidad;
		$CSITUNITPRICE.=$pd_precio;
	} else {
		$CSITPRODUCTCODE.="shipping_only".'#';
		$CSITPRODUCTDESCRIPTION.=$pd_titulo.'#';
		$CSITPRODUCTNAME.=$pd_titulo.'#';
		$CSITPRODUCTSKU.=$pd_id.'#';
		$CSITTOTALAMOUNT.= $precio * $cantidad.'#';
		$CSITQUANTITY.=$cantidad.'#';
		$CSITUNITPRICE.=$pd_precio.'#';	
	}
}


if ($env_valor!=0.00) {
	$CSITPRODUCTCODE.="#"."service";
	$CSITPRODUCTDESCRIPTION.="#"."Costo de envio";
	$CSITPRODUCTNAME.="#"."envio";
	$CSITPRODUCTSKU.="#"."envio";
	$CSITTOTALAMOUNT.= "#".$env_valor;
	$CSITQUANTITY.="#"."1";
	$CSITUNITPRICE.="#".$env_valor;
}


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$ip=$_SERVER['HTTP_CLIENT_IP'];
}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip=$_SERVER['REMOTE_ADDR'];
}


use TodoPago\Sdk;

//importo archivo con SDK
include_once dirname(__FILE__)."/vendor/autoload.php";

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 454804);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', '14E966382A5273E531FFAFA21AFDC9CC');

//común a todas los métodos
$http_header = array('Authorization'=>'TODOPAGO '.SECURITY,
 'user_agent' => 'PHPSoapClient');

//id de la operacion
$operationid=strval($orderId);

//opciones para el método sendAuthorizeRequest (datos propios del comercio)
$optionsSAR_comercio = array (
	'Security'=> SECURITY,
	'EncodingMethod'=>ENCODINGMETHOD,
	'Merchant'=>MERCHANT,
	'URL_OK'=>"https://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].str_replace ($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME']))."/checkout/tp/exito.php?operationid=$operationid",
	'URL_ERROR'=>"https://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].str_replace ($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME']))."/checkout/tp/error.php?operationid=$operationid"
);


	if ($env_tipo=='S') {

		$optionsSAR_operacion = array (
			'MERCHANT'=> MERCHANT,
			'OPERATIONID'=> $operationid,
			'CURRENCYCODE'=> CURRENCYCODE,
			'AMOUNT'=> (float)$orderAmount,
			//Datos ejemplos CS
			'CSBTCITY'=> $or_ciudad,
			'CSSTCITY'=> $or_ciudad,
			
			'CSBTCOUNTRY'=> "AR",
			'CSSTCOUNTRY'=> "AR",
			
			'CSBTEMAIL'=> $or_email,
			'CSSTEMAIL'=> $or_email,
			
			'CSBTFIRSTNAME'=> $or_nombre,
			'CSSTFIRSTNAME'=> $or_nombre,      
			
			'CSBTLASTNAME'=> $or_apellido,
			'CSSTLASTNAME'=> $or_apellido,
			
			'CSBTPHONENUMBER'=> $or_telefono,     
			'CSSTPHONENUMBER'=> $or_telefono,     
			
			'CSBTPOSTALCODE'=> $or_codpostal,
			'CSSTPOSTALCODE'=> $or_codpostal,
			
			'CSBTSTATE'=> $or_provincia,
			'CSSTSTATE'=> $or_provincia,
			
			'CSBTSTREET1'=> $or_calle,
			'CSSTSTREET1'=> $or_calle,
			
			'CSBTCUSTOMERID'=> $operationid,
			'CSBTIPADDRESS'=> $ip,       
			'CSPTCURRENCY'=> "ARS",
			'CSPTGRANDTOTALAMOUNT'=> (float)$orderAmount,
			'CSITPRODUCTCODE'=> $CSITPRODUCTCODE,
			'CSITPRODUCTDESCRIPTION'=> $CSITPRODUCTDESCRIPTION,     
			'CSITPRODUCTNAME'=> $CSITPRODUCTNAME,  
			'CSITPRODUCTSKU'=> $CSITPRODUCTSKU,
			'CSITTOTALAMOUNT'=> $CSITTOTALAMOUNT,
			'CSITQUANTITY'=> $CSITQUANTITY,
			'CSITUNITPRICE'=> $CSITUNITPRICE
			);
	
	} else {
	
		$optionsSAR_operacion = array (
			'MERCHANT'=> MERCHANT,
			'OPERATIONID'=> $operationid,
			'CURRENCYCODE'=> CURRENCYCODE,
			'AMOUNT'=> (float)$orderAmount,
			//Datos ejemplos CS
			'CSBTCITY'=> $or_ciudad,
			'CSSTCITY'=> $env_localidad,
			
			'CSBTCOUNTRY'=> "AR",
			'CSSTCOUNTRY'=> "AR",
			
			'CSBTEMAIL'=> $or_email,
			'CSSTEMAIL'=> $or_email,
			
			'CSBTFIRSTNAME'=> $or_nombre,
			'CSSTFIRSTNAME'=> $env_nombre,      
			
			'CSBTLASTNAME'=> $or_apellido,
			'CSSTLASTNAME'=> $env_apellido,
			
			'CSBTPHONENUMBER'=> $or_telefono,     
			'CSSTPHONENUMBER'=> $env_telefono,     
			
			'CSBTPOSTALCODE'=> $or_codpostal,
			'CSSTPOSTALCODE'=> $env_codpostal,
			
			'CSBTSTATE'=> $or_provincia,
			'CSSTSTATE'=> $env_provincia,
			
			'CSBTSTREET1'=> $or_calle,
			'CSSTSTREET1'=> $env_calle,
			
			'CSBTCUSTOMERID'=> $operationid,
			'CSBTIPADDRESS'=> $ip,       
			'CSPTCURRENCY'=> "ARS",
			'CSPTGRANDTOTALAMOUNT'=> (float)$orderAmount,
			'CSITPRODUCTCODE'=> $CSITPRODUCTCODE,
			'CSITPRODUCTDESCRIPTION'=> $CSITPRODUCTDESCRIPTION,     
			'CSITPRODUCTNAME'=> $CSITPRODUCTNAME,  
			'CSITPRODUCTSKU'=> $CSITPRODUCTSKU,
			'CSITTOTALAMOUNT'=> $CSITTOTALAMOUNT,
			'CSITQUANTITY'=> $CSITQUANTITY,
			'CSITUNITPRICE'=> $CSITUNITPRICE
			);
	}

//creo instancia de la clase TodoPago
$connector = new Sdk($http_header, "prod");


$rta = $connector->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);
if($rta['StatusCode'] != -1) {
	var_dump($rta);
} else {
	$_SESSION['RequestKey'] = $rta["RequestKey"];
?>
	<hr>
	<div class="action_cart my-4">
		<a onclick="actualizar('<?php echo $rta['URL_Request']; ?>');" class="btn btn-primary btn-lg">PAGAR CON TODO PAGO</a>
	</div>

<?php }	?>