<?php
require '/home/zuaibkjh/public_html/class/class.php';
require '/home/zuaibkjh/public_html/class/checkout.class.php';

$ObjCheckout = new Checkout;
$ObjEnvio = new Envio;

// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// TEST
//MercadoPago\SDK::setAccessToken('APP_USR-7708904245408069-101413-070a1a0b8f791c5a29a2d14fa8fec444-658774491');
// PRODUCCION
MercadoPago\SDK::setAccessToken('APP_USR-6146289067848574-051718-0be7c52951cbbf7b18b9b968c1fd3671-707266571');

$merchant_order = null;

if ($_GET["topic"] && $_GET["topic"]=="payment") {
    $payment_info = MercadoPago\Payment::find_by_id($_GET["id"]);
    // Get the payment and the corresponding merchant_order reported by the IPN.
    $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment_info->order->id);


	switch ($payment_info->status) {
		case 'pending':
            $statusOrden=2;
			break;
		case 'approved':
            $statusOrden=3;
			break;
		case 'in_process':
            $statusOrden=2;
			break;
		case 'in_mediation':
            $statusOrden=2;
			break;
		case 'rejected':
            $statusOrden=2;
			break;
		case 'cancelled':
            $statusOrden=8;
			break;
		case 'refunded':
            $statusOrden=2;
			break;
		case 'charged_back':
            $statusOrden=2;
			break;
	}

    $id_mp = $payment_info->id;
	$order_reference = $payment_info->external_reference;
	$email = $payment_info->payer->email;
    $payment_type = $payment_info->payment_type_id;

    $paid_amount = 0;
    foreach ($merchant_order->payments as $payment) {
        if ($payment->status == 'approved'){
            $paid_amount += $payment->transaction_amount;
        }
    }
   
    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
    if($paid_amount >= $merchant_order->total_amount){

        $orderInfo = $ObjCheckout->GetOrderInfo($order_reference);
        $ObjCheckout->ActualizarOrder($order_reference,$id_mp,$payment_info->status,$payment_type,$merchant_order->total_amount,$statusOrden);        

        if ($orderInfo['or_estado']==2) {

            $ObjCheckout->confirmarOrder($order_reference);

            $orderContent = $ObjCheckout->GetOrderContent($order_reference);
            $numItem=count($orderContent);


            //Envio de correo por Postmark
            $url ="https://api.postmarkapp.com/email/withTemplate";
            $headers = array(
                "Content-Type: application/json",
                "Accept: application/json",
                "X-Postmark-Server-Token: 9e6155b1-361b-41b9-89ad-685edc9b1ad0"
            );
            
            $detalles='';
            for ($i=0; $i<$numItem; $i++) {
                extract($orderContent[$i]);
                if ($variacion) $varia=$variacion; else $varia='';
                $detalles .= '{"description": "'.$pd_titulo.' '.$varia.'","amount": "('.$cantidad.') x $'.number_format($precio,2,',','.').'"},';
            }
            $detalles = substr($detalles, 0, -1);

            $descuentos='';
            $orderDiscount=$ObjCheckout->GetOrderDiscount($order_reference);
            $numItemDesc=count($orderDiscount);  
            for ($i=0; $i<$numItemDesc; $i++) {
                extract($orderDiscount[$i]);
                $descuentos .= '{"description": "'.$desc_descripcion.'","amount": "-$'.number_format($desc_precio,2,',','.').'"},';
            }
            $descuentos = substr($descuentos, 0, -1);


            if($orderInfo["env_tipo"]=='D') {     
                $pm_envio_domicilio = 'D';
                $pm_nombre_envio = $orderInfo['env_nombre'].' '.$orderInfo['env_apellido'];
                $pm_tel_envio = $orderInfo['env_telefono'];
                $pm_direccion_envio = $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
                if (!empty($orderInfo['env_piso'])) {
                    $pm_direccion_envio .= ' '.$orderInfo['env_piso'];
                }
                if (!empty($orderInfo['env_depto'])) {
                    $pm_direccion_envio .= ' '.$orderInfo['env_depto'];
                }
                $pm_cp_envio = $orderInfo['env_codpostal'];
                $pm_localidad_envio = $orderInfo['env_localidad'];
                $pm_provincia_envio = $orderInfo['env_provincia'];
            } else {
                $pm_envio_domicilio = '';
                $pm_nombre_envio = '';
                $pm_tel_envio = '';
                $pm_direccion_envio = '';
                $pm_cp_envio = '';
                $pm_localidad_envio = '';
                $pm_provincia_envio = '';
            }


            $pm_direccion_comprador = $orderInfo['or_calle'].' '.$orderInfo['or_calle_num'];
            if (!empty($orderInfo['or_piso'])) {
                $pm_direccion_comprador .= ' '.$orderInfo['or_piso'];
            }
            if (!empty($orderInfo['or_depto'])) {
                $pm_direccion_comprador .= ' '.$orderInfo['or_depto'];
            }

            switch ($orderInfo['or_medio_pago']) {
                case 'mp':
                    $pm_medio_pago = "MERCADO PAGO";
                    break;
                case 'tp':
                    $pm_medio_pago = "TODO PAGO";
                    break;
                case 'transferencia':
                    $pm_medio_pago = "TRANSFERENCIA BANCARIA";
                    break;
            } 
            if ($payment_type=='transferencia') {
                $pm_id_pago = 'a confirmar';
                $pm_forma_pago = 'Transferencia';
                $pm_estado_pago = 'Esperando acreditación en nuestra cuenta';
                $pm_transferencia = 'S';
                $pm_banco = $datos["banco"];
                $pm_tipo_cuenta = $datos["tipo"];
                $pm_numero_cuenta = $datos["num_cuenta"];
                $pm_cbu_cuenta = $datos["cbu"];
                $pm_titular_cuenta = $datos["titular"];
                $pm_cuit_cuenta = $datos["cuit"];
            } else {
                $pm_id_pago = $id_mp;
                $pm_forma_pago = $payment_type;
                $pm_estado_pago = $payment_info->status;
                $pm_transferencia = '';
                $pm_banco = '';
                $pm_tipo_cuenta = '';
                $pm_numero_cuenta = '';
                $pm_cbu_cuenta = '';
                $pm_titular_cuenta = '';
                $pm_cuit_cuenta = '';
            }


            $parametros_post = '{
                "From": "info@mixme.com.ar",
                "To": "'.$orderInfo["or_email"].',info@mixme.com.ar",
                "TemplateAlias": "confirmacion-compra",
                "TemplateModel": {
                    "site_url": "'.HTTP_SERVER.'",
                    "company_name": "Mixme",
                    "company_address": "Av. Argentina 5659, Villa Lugano, CABA, Argentina",
                    "name": "'.$orderInfo["or_nombre"].'",
                    "orden_id": "'.$order_reference.'",
                    "fecha_orden": "'.date("d M Y", strtotime($orderInfo["fecha_alta"])).'",
                    "invoice_details": [
                        '.$detalles.'
                    ],
                    "discount_details": [
                        '.$descuentos.'
                    ],
                    "amount_envio": "'.number_format($orderInfo["env_valor"],2,',','.').'",
                    "total": "'.number_format($merchant_order->total_amount,2,',','.').'",
                    "envio": "'.$orderInfo['env_descripcion'].'",
                    "envio_domicilio": "'.$pm_envio_domicilio.'",
                    "nombre_envio": "'.$pm_nombre_envio.'",
                    "tel_envio": "'.$pm_tel_envio.'",
                    "direccion_envio": "'.$pm_direccion_envio.'",
                    "cp_envio": "'.$pm_cp_envio.'",
                    "localidad_envio": "'.$pm_localidad_envio.'",
                    "provincia_envio": "'.$pm_provincia_envio.'",
                    "nombre_comprador": "'.$orderInfo["or_nombre"].' '.$orderInfo["or_apellido"].'",
                    "dni_comprador": "'.$orderInfo['or_dni'].'",
                    "tel_comprador": "'.$orderInfo['or_telefono'].'",
                    "direccion_comprador": "'.$pm_direccion_comprador.'",
                    "cp_comprador": "'.$orderInfo['env_codpostal'].'",
                    "localidad_comprador": "'.$orderInfo['or_ciudad'].'",
                    "provincia_comprador": "'.$orderInfo['or_provincia'].'",
                    "medio_pago": "'.$pm_medio_pago.'",
                    "id_pago": "'.$pm_id_pago.'",
                    "forma_pago": "'.$pm_forma_pago.'",
                    "estado_pago": "'.$pm_estado_pago.'",
                    "transferencia": "'.$pm_transferencia.'",
                    "banco": "'.$pm_banco.'",
                    "tipo_cuenta": "'.$pm_tipo_cuenta.'",
                    "numero_cuenta": "'.$pm_numero_cuenta.'",
                    "cbu_cuenta": "'.$pm_cbu_cuenta.'",
                    "titular_cuenta": "'.$pm_titular_cuenta.'",
                    "cuit_cuenta": "'.$pm_cuit_cuenta.'",
                    "mensaje": "'.$orderInfo['or_notas'].'"
                }
            }';

            $sesion = curl_init($url);
            curl_setopt($sesion, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($sesion, CURLOPT_CAINFO, 0);
            curl_setopt($sesion, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            curl_setopt($sesion, CURLOPT_HTTPHEADER, $headers);
            curl_setopt ($sesion, CURLOPT_POST, true);
            curl_setopt ($sesion, CURLOPT_POSTFIELDS, $parametros_post);
            curl_setopt($sesion, CURLOPT_HEADER, false);
            curl_setopt($sesion, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($sesion);
            curl_close($sesion);
            //fin Envio de correo por Postmark

        }
        
    } 

}

http_response_code(200);

?>
