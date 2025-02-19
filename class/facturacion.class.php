<?php 

class Facturacion 
{
	private $conn;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
	}
	
	
	public function saveFactura($orden,$UrlFacturaPdf,$idfactura,$fechaFactura,$nroFactura)
	{		
			$query ="INSERT INTO `factura_orden`(`id_orden`, `fc_factura_pdf`, `fc_id_factura`, `fc_fecha_factura`, `fc_nro_factura`) VALUES ('$orden','$UrlFacturaPdf','$idfactura','$fechaFactura','$nroFactura')";
			if (mysqli_query($this->conn,$query)) {
				return true;
			} else {
				return false;
			}
	}

	public function GetOrderSinFacturar()
	{
		require_once("checkout.class.php");
		$ObjCheckout = new Checkout;

		$query = "SELECT ordenes.id_orden FROM ordenes
		LEFT JOIN factura_orden ON ordenes.id_orden=factura_orden.id_orden 
		WHERE factura_orden.id_orden is null AND or_estado > 2 AND or_estado < 6";

		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;

		if($cnt_res>0) {
		
			while ($row=$result->fetch_assoc()) {

				$this->orderId=$row["id_orden"];

				$orderContent=$ObjCheckout->GetOrderContent($this->orderId);
				$orderInfo=$ObjCheckout->GetOrderInfo($this->orderId);
				$this->altaFacturaVenta($orderContent,$orderInfo);

			}
		}

	}

	public function altaFacturaVenta($orderContent,$orderInfo)
	{

		$parametros_post = '{
			"auth": {
				"usuario": "martinpandelo@gmail.com",
				"password": "a175446792d82504f20ec46788caa7a6"
			},
			"service": {
				"provision": "Usuario",
				"operacion": "iniciar_sesion"
			},
			"parameters": {
				"usuario": "psanmiguel@bulltrade.com.ar",
				"password": "443aa902e9b039232c97cbf2f70cfb19"
			}
		}';
		  
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://login.colppy.com/lib/frontera2/service.php",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $parametros_post,
				CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json"
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$result = json_decode($response, true);
			

			if ($result['response']['success']) {

				$claveSesion=$result['response']['data']['claveSesion'];


				if (strlen($orderInfo['or_dni'])==11) {
					$ini = substr($orderInfo['or_dni'], 0, 2);
					$medio = substr($orderInfo['or_dni'], 2, 8);
					$fin = substr($orderInfo['or_dni'], -1);
					$cuit = $ini.'-'.$medio.'-'.$fin;
					$dni='';

					$filterCliente= '"filter": [{
						"field": "CUIT",
						"op": "=",
						"value": "'.$cuit.'"
					}],
					"order": [{
						"field": "CUIT",
						"dir": "ASC"
					}]';
				} else {
					$dni=$orderInfo['or_dni'];
					$cuit='';

					$filterCliente= '"filter": [{
						"field": "dni",
						"op": "=",
						"value": "'.$orderInfo['or_dni'].'"
					}],
					"order": [{
						"field": "dni",
						"dir": "ASC"
					}]';
				}

				$parametros_post = '{
					"auth": {
						"usuario": "martinpandelo@gmail.com",
						"password": "a175446792d82504f20ec46788caa7a6"
					},
					"service": {
						"provision": "Cliente",
						"operacion": "listar_cliente"
					},
					"parameters": {
						"sesion": {
							"usuario": "psanmiguel@bulltrade.com.ar",
							"claveSesion": "'.$claveSesion.'"
						},
						"idEmpresa": "30910",
						"start": 0,
						"limit": 1,
						'.$filterCliente.'
					}
				}';
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://login.colppy.com/lib/frontera2/service.php",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $parametros_post,
					CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json"
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($response, true);

				if ($result['response']['success']) {

					if ($result['response']['total']==0) {

						if ($orderInfo['or_provincia']=='Buenos Aires (GBA)') {
							$provincia='Buenos Aires';
						} else {
							$provincia=$orderInfo['or_provincia'];
						}
						
						$parametros_post = '{
							"auth": {
								"usuario": "martinpandelo@gmail.com",
								"password": "a175446792d82504f20ec46788caa7a6"
							},
							"service": {
								"provision": "Cliente",
								"operacion": "alta_cliente"
							},
							"parameters": {
								"sesion": {
									"usuario": "psanmiguel@bulltrade.com.ar",
									"claveSesion": "'.$claveSesion.'"
								},
								"info_general": {
									"idUsuario": "",
									"idCliente": "",
									"idEmpresa": "30910",
									"NombreFantasia": "'.$orderInfo['or_nombre'].' '.$orderInfo['or_apellido'].'",
									"RazonSocial": "'.$orderInfo['or_nombre'].' '.$orderInfo['or_apellido'].'",
									"CUIT": "'.$cuit.'",
									"dni": "'.$dni.'",
									"DirPostal": "'.$orderInfo['or_calle'].' '.$orderInfo['or_calle_num'].'",
									"DirPostalCiudad": "'.$orderInfo['or_ciudad'].'",
									"DirPostalCodigoPostal": "'.$orderInfo['or_codpostal'].'",
									"DirPostalProvincia": "'.$provincia.'",
									"DirPostalPais": "Argentina",
									"Telefono": "'.$orderInfo['or_telefono'].'",
									"Email": "'.$orderInfo['or_email'].'"
								},
								"info_otra": {
									"Activo": "1",
									"FechaAlta": "",
									"DirFiscal": "",
									"DirFiscalCiudad": "",
									"DirFiscalCodigoPostal": "",
									"DirFiscalProvincia": "",
									"DirFiscalPais": "",
									"idCondicionPago": "",
									"idCondicionIva": "",
									"porcentajeIVA": "",
									"idPlanCuenta": "",
									"CuentaCredito": "",
									"DirEnvio": "",
									"DirEnvioCiudad": "",
									"DirEnvioCodigoPostal": "",
									"DirEnvioProvincia": "",
									"DirEnvioPais": ""
								}
							}
						}';
						
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => "https://login.colppy.com/lib/frontera2/service.php",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => $parametros_post,
							CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json"
							),
						));
						$response = curl_exec($curl);
						curl_close($curl);
						$result = json_decode($response, true);

						if ($result['response']['success']) {
							$idCliente=$result['response']['data'][0]['idCliente'];
						}

					} else {
						$idCliente=$result['response']['data'][0]['idCliente'];
					}

				}


				if (!empty($cuit)) {
					$idTipoFactura='A';
				} else {
					$idTipoFactura='B';
				}

				$netoGravado = $orderInfo['total_pagado'] / 1.21;
				$iva = $orderInfo['total_pagado'] - $netoGravado;

				$numItem=count($orderContent);
				$itemsFactura='';
				//itemsFactura
				for ($i=0; $i<$numItem; $i++) {
					extract($orderContent[$i]);
					$itemsFactura .= '{
						"Descripcion": "'.$pd_titulo.'",
						"unidadMedida": "",
						"ccosto1": "",
						"ccosto2": "",
						"Cantidad": '.$cantidad.',
						"ImporteUnitario": '.$precio.',
						"porcDesc": "0.00",
						"IVA": "21",
						"idPlanCuenta": "Ventas",
						"Comentario": ""
					},';
				}
				if ($orderInfo['env_tipo']=='D' && $orderInfo['env_valor']!=0.00) {
					$itemsFactura .= '{
						"Descripcion": "'.$orderInfo['env_descripcion'].'",
						"unidadMedida": "",
						"ccosto1": "",
						"ccosto2": "",
						"Cantidad": 1,
						"ImporteUnitario": '.$orderInfo['env_valor'].',
						"porcDesc": "0.00",
						"IVA": "21",
						"idPlanCuenta": "Fletes",
						"Comentario": ""
					},';
				}
				$itemsFactura = substr($itemsFactura, 0, -1);

				if ($orderInfo['or_medio_pago']=='mp') {
					$idMedioCobro='Tarjeta de Credito';
					$idPlanCuenta='Mercado Pago';
					$Banco='Mercado Pago';
				} else {
					$idMedioCobro='Transferencia';
					$idPlanCuenta='CAJA en $';
					$Banco='';
				}

				


				$parametros_post = '{
					"auth": {
						"usuario": "martinpandelo@gmail.com",
						"password": "a175446792d82504f20ec46788caa7a6"
					},
					"service": {
						"provision": "FacturaVenta",
						"operacion": "alta_facturaventa"
					},
					"parameters": {
						"sesion": {
							"usuario": "psanmiguel@bulltrade.com.ar",
							"claveSesion": "'.$claveSesion.'"
						},
						"descripcion": "ORD '.$orderInfo['id_orden'].'",
						"fechaFactura": "'.$fecha=date("d-m-Y", strtotime($orderInfo['fecha_alta'])).'",
						"idCondicionPago": "Contado",
						"fechaPago": "'.$fecha=date("d-m-Y", strtotime($orderInfo['fecha_alta'])).'",
						"idCliente": "'.$idCliente.'",
						"idEmpresa": "30910",
						"idEstadoAnterior": "",
						"idEstadoFactura": "Cobrada",
						"idFactura": "",
						"idMedioCobro": "Efectivo",
						"idMoneda": "1",
						"idTipoComprobante": "8",
						"idTipoFactura": "'.$idTipoFactura.'",
						"netoGravado": "'.$netoGravado.'",
						"netoNoGravado": "0.00",
						"nroFactura1": "0004",
						"nroFactura2": "",
						"labelfe": "Factura Electrónica",
						"percepcionIVA": "0.00",
						"percepcionIIBB": "0.00",
						"itemsFactura": ['.$itemsFactura.'],
						"ItemsCobro": [{
							"idMedioCobro": "'.$idMedioCobro.'",
							"idPlanCuenta": "'.$idPlanCuenta.'",
							"Banco": "'.$Banco.'",
							"nroCheque": "",
							"fechaValidez": "'.$fecha=date("d-m-Y", strtotime($orderInfo['fecha_alta'])).'",
							"importe": "'.$orderInfo['total_pagado'].'",
							"VAD": "S",
							"idTabla": "0",
							"idElemento": "0",
							"idItem": "0"
						}],
						"tipoFactura": "Contado",
						"totalFactura": "'.$orderInfo['total_compra'].'",
						"totalIVA": "'.$iva.'",
						"totalpagadofactura": "'.$orderInfo['total_pagado'].'",
						"valorCambio": "1",
						"totalesiva": [{
								"alicuotaIva": "0",
								"baseImpIva": "0.00",
								"importeIva": "0.00"
							},
							{
								"alicuotaIva": "21",
								"baseImpIva": "'.$netoGravado.'",
								"importeIva": "'.$iva.'"
							},
							{
								"alicuotaIva": "27",
								"baseImpIva": "0.00",
								"importeIva": "0.00"
							}
						]
					}
				}';
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://login.colppy.com/lib/frontera2/service.php",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $parametros_post,
					CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json"
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($response, true);


				if ($result['response']['success']) {
					
					$UrlFacturaPdf=$result['response']['UrlFacturaPdf'];
					$idfactura=$result['response']['idfactura'];
					$fechaFactura=$result['response']['fechaFactura'];
					$nroFactura=$result['response']['nroFactura'];

					$this->saveFactura($orderInfo['id_orden'],$UrlFacturaPdf,$idfactura,$fechaFactura,$nroFactura);

				}

		  	}
	}

	
}

?>