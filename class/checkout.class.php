<?php 

class Checkout
{
	private $conn;
	private $orderId;
	public $id;
	public $stock;
	public $cantidad;
	public $variacion;
	public $status;
	public $sid;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
	}


	public function saveOrder()
	{		
			$filter = array(
				'per_email' => FILTER_VALIDATE_EMAIL,
				'descuento' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_nombre' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_apellido' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_telefono'    => array('filter'  => FILTER_VALIDATE_REGEXP,
							   'options' => array('regexp' => '/^[0-9]{5,13}$/')),
				'per_dni'    => array('filter'  => FILTER_VALIDATE_REGEXP,
							   'options' => array('regexp' => '/^[0-9]{7,11}$/')),
				'per_direccion' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_calle_num' => FILTER_SANITIZE_NUMBER_INT,
				'per_piso' => FILTER_SANITIZE_NUMBER_INT,
				'per_dpto' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_ciudad' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_provincia' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'per_codpostal' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				
				
				'envio_nombre' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'envio_apellido' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'envio_telefono'    => array('filter'  => FILTER_VALIDATE_REGEXP,
							   'options' => array('regexp' => '/^[0-9]{5,13}$/')),
				'envio_dni'    => array('filter'  => FILTER_VALIDATE_REGEXP,
						   		'options' => array('regexp' => '/^[0-9]{7,11}$/')),
				'envio_direccion' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'envio_calle_num' => FILTER_SANITIZE_NUMBER_INT,
				'envio_piso' => FILTER_SANITIZE_NUMBER_INT,
				'envio_dpto' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'envio_ciudad' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'envio_provincia' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'env_codpostal' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				

				'envio' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'id_correo' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'nombre_correo' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'descripcion_correo' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'despacho' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'modalidad' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'servicio' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
				'horas_entrega' => array( 'filter' => FILTER_SANITIZE_STRING,
								'flags'  => FILTER_FLAG_ENCODE_LOW),
				'costo_envio' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),
							   

				'opcion_pago' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),

				'totalSinEnvio' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW),

				'mensaje' => array( 'filter' => FILTER_SANITIZE_STRING,
							   'flags'  => FILTER_FLAG_ENCODE_LOW)
			);
	
			$inputs = filter_input_array(INPUT_POST, $filter);
			foreach ($inputs as $k=>$v ) {
			    $saniainputs[mysqli_real_escape_string( $this->conn, $k )] = mysqli_real_escape_string($this->conn, $v );
			}
			extract($saniainputs);

			if ($envio=='D') {
				if (isset($_POST['chkDatos'])) {
					$per_nombre=$envio_nombre;
					$per_apellido=$envio_apellido;
					$per_dni=$envio_dni;
					$per_telefono=$envio_telefono;
					$per_direccion=$envio_direccion;
					$per_calle_num=$envio_calle_num;
					$per_piso=$envio_piso;
					$per_dpto=$envio_dpto;
					$per_ciudad=$envio_ciudad;
					$per_provincia=$envio_provincia;
					$per_codpostal=$env_codpostal;
				}
			} elseif ($envio=='S') {
				$envio_nombre=$per_nombre;
				$envio_apellido=$per_apellido;
				$envio_dni=$per_dni;
				$envio_telefono=$per_telefono;
				$envio_direccion=$per_direccion;
				$envio_calle_num=$per_calle_num;
				$envio_piso=$per_piso;
				$envio_dpto=$per_dpto;
				$envio_ciudad=$per_ciudad;
				$envio_provincia=$per_provincia;
				$env_codpostal=$per_codpostal;
			}

			$sid = session_id();
			$per_nombre = ucwords($per_nombre);
			$per_apellido = ucwords($per_apellido);
			$orderAmount=$totalSinEnvio+$costo_envio;

			// otros descuentos
			if ($descuento!=='') {
				$arrDesc=$this->codDescuentos($descuento);
				if ($arrDesc) {
					$porcentaje_descuento=$arrDesc[0]['porcentaje_descuento'];
					$descuentoAmount = ($porcentaje_descuento*$totalSinEnvio)/100;
					$orderAmount=$orderAmount-$descuentoAmount;
				} 
			}

			if(isset($_SESSION['mayoristas'])){	
				$mayorista = $_SESSION['mayoristas'];
			} else {
				$mayorista=0;
			}
			
			$query ="INSERT INTO `ordenes`(`session_id`, `fecha_alta`, `or_nombre`, `or_apellido`, `or_dni`, `or_telefono`, `or_email`, `or_calle`, `or_calle_num`, `or_piso`, `or_depto`, `or_ciudad`, `or_provincia`, `or_codpostal`, `or_medio_pago`, `total_compra`, `or_estado`, `mayorista`, `or_notas`) 
			VALUES ('$sid',NOW(),'$per_nombre','$per_apellido','$per_dni','$per_telefono','$per_email','$per_direccion','$per_calle_num','$per_piso','$per_dpto','$per_ciudad','$per_provincia','$per_codpostal','$opcion_pago','$orderAmount',1,'$mayorista','$mensaje')";
			
			if (mysqli_query($this->conn,$query)) {
				// get the order id
				$orderId = mysqli_insert_id($this->conn);
				
				if ($orderId) {
					$ObjCart = new Cart();
					$cartContent = $ObjCart->getCartContent();
					$numItem = count($cartContent);
					
					// save order items
					for ($i = 0; $i < $numItem; $i++) {
						extract($cartContent[$i]);
						$query = "INSERT INTO items_orden (id_orden, producto_id, codigo, variacion, cantidad, precio)
						VALUES ($orderId,'$producto_id','$pr_codigo','$variacion','$cantidad','$pr_precio')";
						mysqli_query($this->conn,$query);
					}
					
					// save shipping info
					$query = "INSERT INTO `envio_orden`(`id_orden`, `env_tipo`, `env_id_correo`, `env_nom_correo`, `env_descripcion`, `env_despacho`, `env_modalidad`, `env_servicio`, `env_horas_entrega`, `env_valor`, `env_nombre`, `env_apellido`, `env_telefono`, `env_dni`, `env_calle`, `env_numero`, `env_piso`, `env_depto`, `env_codpostal`, `env_localidad`, `env_provincia`, `env_estado`) 
					VALUES ($orderId,'$envio','$id_correo','$nombre_correo','$descripcion_correo','$despacho','$modalidad','$servicio','$horas_entrega','$costo_envio','$envio_nombre','$envio_apellido','$envio_telefono','$envio_dni','$envio_direccion','$envio_calle_num','$envio_piso','$envio_dpto','$env_codpostal','$envio_ciudad','$envio_provincia','B')";
					mysqli_query($this->conn,$query);


					// save item descuento
					if ($descuento!=='') {
						if ($arrDesc) {
							$desc_codigo=$arrDesc[0]['codigo'];
							$desc_descripcion=$arrDesc[0]['descripcion'];
							$query = "INSERT INTO descuentos_orden (id_orden, desc_codigo, desc_descripcion, desc_precio) VALUES ($orderId,'$desc_codigo','$desc_descripcion','$descuentoAmount')";
							!mysqli_query($this->conn,$query);
						} 
					}
						
				} else {
					return false;
				}
			} else {
				return false;
			}
			return $orderId;
	}

	public function removeItemsCart()
	{
		$ObjCart = new Cart();
		$cartContent = $ObjCart->getCartContent();
		$numItem = count($cartContent);
					
		for ($i = 0; $i < $numItem; $i++) {
			$query = "DELETE FROM tbl_cart WHERE id = {$cartContent[$i]['id']}";
			mysqli_query($this->conn,$query);				
		}
	}

	public function getOrderAmount($orderId)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);
		$orderAmount = 0;
		
		$query = "SELECT SUM(items_orden.precio * items_orden.cantidad) FROM items_orden WHERE items_orden.id_orden = $this->orderId
				
				UNION
				
				SELECT env_valor FROM envio_orden WHERE id_orden = $this->orderId";
				
		$result=mysqli_query($this->conn,$query);	
		
		if (mysqli_num_rows($result) == 2) {
			$row = mysqli_fetch_row($result);
			$totalPurchase = $row[0];
			
			$row = mysqli_fetch_row($result);
			$shippingCost = $row[0];
			
			$descuento=0;

			$query = "SELECT desc_precio FROM descuentos_orden WHERE id_orden = $this->orderId ";
			$result=mysqli_query($this->conn,$query);
			$rowCnt = $result->num_rows;
			if ($rowCnt>0) {
				while ($row = $result->fetch_assoc()) {
					$descuento += $row['desc_precio'];
				}
			}

			$orderAmount = $totalPurchase + $shippingCost - $descuento;

		}	
		
		return $orderAmount;	
	}
	
	private $orderContent = array();
	
	public function GetOrderContent($orderId)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);

		$query = "SELECT * FROM items_orden
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id AND items_orden.id_orden='$this->orderId' ";

		$result=mysqli_query($this->conn,$query);
		
		while ($row=$result->fetch_assoc()) {

			$id_prod=$row["producto_id"];
			
			$query = "SELECT im_nombre FROM tbl_img WHERE im_producto='$id_prod' ORDER BY im_orden ASC LIMIT 1";
			$result_img=mysqli_query($this->conn,$query);
			$rowImg=$result_img->fetch_assoc();
			$row["im_nombre"]=$rowImg["im_nombre"];

			$this->orderContent[] = $row;
		}	
		
		return $this->orderContent;	
	}
	
	public function GetOrderInfo($orderId)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);

		$query = "SELECT * FROM ordenes 
		INNER JOIN envio_orden ON envio_orden.id_orden=ordenes.id_orden 
		WHERE ordenes.id_orden = '$this->orderId'";
		$result=mysqli_query($this->conn,$query);
		return $result->fetch_assoc();
	}

	private $orderDiscount = array();

	public function GetOrderDiscount($orderId)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);

		$query = "SELECT * FROM descuentos_orden WHERE id_orden = $this->orderId ";
		$result=mysqli_query($this->conn,$query);
		
		while ($row=$result->fetch_assoc()) {
			$this->orderDiscount[] = $row;
		}	
		
		return $this->orderDiscount;	
	}

	public function GetMayoristaInfo($idCliente)
	{
		$this->id=mysqli_real_escape_string($this->conn,$idCliente);

		$query = "SELECT * FROM tbl_clientes_mayoristas WHERE id_cliente = '$this->id'";
		$result=mysqli_query($this->conn,$query);
		return $result->fetch_assoc();
	}

	public function GetTokenEtiquetaMix($orderId)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);

		$query = "SELECT session_id FROM tbl_cart_mixer WHERE id_mix = '$this->orderId'";
		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();
		return $row['session_id'];
	}
	
	public function ActualizarOrder($orderId,$pago_id,$pago_status,$pago_forma,$total,$estado)
	{
		$this->orderId=mysqli_real_escape_string($this->conn,$orderId);
		$pago_id=mysqli_real_escape_string($this->conn,$pago_id);
		$pago_status=mysqli_real_escape_string($this->conn,$pago_status);
		$pago_forma=mysqli_real_escape_string($this->conn,$pago_forma);
		$total=mysqli_real_escape_string($this->conn,$total);
		$estado=mysqli_real_escape_string($this->conn,$estado);

		$query = "UPDATE `ordenes` SET `pago_id`='$pago_id',`pago_status`='$pago_status',`pago_forma`='$pago_forma',`total_pagado`='$total',`or_estado`='$estado' WHERE id_orden = '$this->orderId'";

		if(mysqli_query($this->conn,$query)) {
			return true;
		} else {
			return false;
		}
	}

	public function enviaEmailStatus($id_orden,$status) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		$this->status=mysqli_real_escape_string($this->conn,$status);

		$orderInfo=$this->GetOrderInfo($this->orderId);

		$query = "SELECT * FROM status_ordenes WHERE st_id='$this->status'";
		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();

		$estado_orden=$row["st_nombre"];
		$text_email=$row["st_text_email"];

		if ($this->status==5 && $orderInfo["env_tipo"]=='S') {
			$estado_orden="Lista para retirar";
			$text_email="Tu compra ya está en nuestra sucursal para que vengas a retirarla.";
		}
		
		//Envio de correo por Postmark
        $url ="https://api.postmarkapp.com/email/withTemplate";
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
            "X-Postmark-Server-Token: 9e6155b1-361b-41b9-89ad-685edc9b1ad0"
		);
		$parametros_post = '{
            "From": "info@mixme.com.ar",
            "To": "'.$orderInfo["or_email"].'",
            "TemplateAlias": "estados-orden",
			"TemplateModel": {
				"site_url": "'.HTTP_SERVER.'",
				"estado_orden": "'.$estado_orden.'",
				"orden_id": "'.$this->orderId.'",
				"fecha_orden": "'.date("d M Y", strtotime($orderInfo["fecha_alta"])).'",
				"name": "'.$orderInfo["or_nombre"].'",
				"text": "'.$text_email.'",
				"company_name": "Mixme",
                "company_address": "Av. Argentina 5659, Villa Lugano, CABA, Argentina"
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

	public function enviaEmailCodigo($id_orden,$codigo,$link) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		$this->codigo=mysqli_real_escape_string($this->conn,$codigo);
		$this->link=mysqli_real_escape_string($this->conn,$link);

		$orderInfo=$this->GetOrderInfo($this->orderId);

		$text_email="A continuación te damos el código de seguimiento para que puedas saber donde está tu paquete";
		
		//Envio de correo por Postmark
		$url ="https://api.postmarkapp.com/email/withTemplate";
		$headers = array(
			"Content-Type: application/json",
			"Accept: application/json",
			"X-Postmark-Server-Token: 9e6155b1-361b-41b9-89ad-685edc9b1ad0"
		);
		$parametros_post = '{
            "From": "info@mixme.com.ar",
            "To": "'.$orderInfo["or_email"].'",
            "TemplateAlias": "codigo-seguimiento",
			"TemplateModel": {
				"site_url": "'.HTTP_SERVER.'",
				"cod_seguimiento": "'.$this->codigo.'",
				"link_seguimiento": "'.$this->link.'",
				"orden_id": "'.$this->orderId.'",
				"fecha_orden": "'.date("d M Y", strtotime($orderInfo["fecha_alta"])).'",
				"name": "'.$orderInfo["or_nombre"].'",
				"text": "'.$text_email.'",
				"company_name": "Mixme",
                "company_address": "Av. Argentina 5659, Villa Lugano, CABA, Argentina",
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


	public function statusOrder($id_orden,$status) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		$this->status=mysqli_real_escape_string($this->conn,$status);
		
		$query = "UPDATE `ordenes` SET `or_estado`='$this->status' WHERE id_orden='$this->orderId'";
		if($result = mysqli_query($this->conn, $query)) {
			if ($this->status>2 && $this->status<7) {
				$this->enviaEmailStatus($this->orderId,$this->status);
			}
			return true;
		} else {
			return false;
		}
	}

	public function CheckOrder($id_orden) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		$sid = session_id();
		
		$query = "SELECT id_orden FROM `ordenes` WHERE id_orden='$this->orderId' AND session_id='$sid' AND or_estado=1 ";
		if($result = mysqli_query($this->conn, $query)) {
			$cnt_res=$result->num_rows;
			if($cnt_res>0) {
				return true;
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}

	public function CheckOrderConfirmada($id_orden) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		
		$query = "SELECT id_orden FROM `ordenes` WHERE id_orden='$this->orderId' AND session_id!='010' AND or_estado=2 ";
		if($result = mysqli_query($this->conn, $query)) {
			$cnt_res=$result->num_rows;
			if($cnt_res>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function confirmarOrder($id_orden) {
		
		$this->orderId=mysqli_real_escape_string($this->conn,$id_orden);
		
		$query = "UPDATE `ordenes` SET `session_id`='010' WHERE id_orden='$this->orderId'";
		if($result = mysqli_query($this->conn, $query)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function datosTransferencia()
	{
		$query = "SELECT * FROM tbl_datos_transferencia ";
		$result=mysqli_query($this->conn,$query);
		
		return $datos=$result->fetch_assoc();
	}

	public function codDescuentos($cod) {
		$this->codigoDesc = $cod;
		$query = "SELECT * FROM tbl_codigos_descuento WHERE codigo = '$this->codigoDesc' AND `val-hasta` >= NOW() ";
		$result=mysqli_query($this->conn,$query);
		$rowCnt = $result->num_rows;
		$arrDesc = array();

		if ($rowCnt>0) {
			while ($row = $result->fetch_assoc()) {
                $arrDesc[] = $row;
			}
		}
		return $arrDesc;
	}

	public function validarDescuento()
	{ 
		$this->codigoDesc = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['codigo'], ENT_QUOTES)));

		//
		$query = "SELECT * FROM tbl_codigos_descuento WHERE codigo = '$this->codigoDesc' AND `val-hasta` >= NOW()";
		$result=mysqli_query($this->conn,$query);
		$ctn = $result->num_rows;
		
		if ($ctn == 0) {
			echo 'descuento-no-existe';
			exit;
		} else {
			$desc=$result->fetch_assoc();
			echo '<p class="text-success"><i class="fas fa-check"></i> '.$desc['porcentaje_descuento'].'% descuento - '.$desc['descripcion'].'</p>';
			
		}

	}

	public function actualizarStockItem($producto_id,$cantidad,$variacion)
	{
		$this->id=mysqli_real_escape_string($this->conn,$producto_id);
		$this->cantidad=mysqli_real_escape_string($this->conn,$cantidad);
		$this->variacion=mysqli_real_escape_string($this->conn,$variacion);

		$query = "SELECT pr_stock FROM tbl_productos_parent WHERE pr_producto = '$this->id' AND pr_valor='$this->variacion' AND pr_stock!=0";
		
		if ($result=mysqli_query($this->conn,$query)) {
			$row = $result->fetch_assoc();
			$this->stock = $row['pr_stock'];
			
			$this->stock=$this->stock - $this->cantidad;

			$query = "UPDATE `tbl_productos_parent` SET `pr_stock`='$this->stock' WHERE pr_producto = '$this->id' AND pr_stock!=0  AND pr_valor='$this->variacion' ";
			mysqli_query($this->conn,$query);
		}
		
	}
	
}

?>