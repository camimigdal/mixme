<?php 

class Envio 
{
	private $conn;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
	}
	
	
	private $provincia;
	private $nombreProvincia;
	private $codProvincia;
	private $codPostal;
	private $pesoEnvio;
	private $totalEnvio;


	public function seleccionarProvincia($codPostal) {
		$query = "SELECT * FROM codigos_postales 
		INNER JOIN provincias ON provincias.id = codigos_postales.provincia_id
		WHERE codigopostal='$codPostal' LIMIT 1";
		$result=mysqli_query($this->conn,$query);
		$rowCnt = $result->num_rows;
		$arrProvincia = array();

		if ($rowCnt>0) {
			while ($row = $result->fetch_assoc()) {
                $arrProvincia[] = $row;
			}
		}
		return $arrProvincia;
	}

	public function codigoProvincia($prov) {
		$query = "SELECT codigo FROM provincias WHERE provincia='$prov'";
		$result=mysqli_query($this->conn,$query);
		$rowCnt = $result->num_rows;
		if ($rowCnt>0) {
			$row = $result->fetch_assoc();
			return $row['codigo'];
		} else {
			return false;
		}
	}

	public function enviosPresonalizadoMayorista($prov,$cod) {

		$this->codProvincia = $cod;

		if ($this->codProvincia=='C') {

			$query = "SELECT * FROM tbl_envios 
			INNER JOIN provincias ON provincias.id = tbl_envios.env_provincia
			WHERE env_provincia='$prov' ";
			$result=mysqli_query($this->conn,$query);
			$rowCnt = $result->num_rows;
			
			if ($rowCnt>0) {
				$i=20;
				while ($row = $result->fetch_assoc()) {

					$price=0;
					$valor='Gratis';
					echo '<div class="custom-control custom-radio my-2 border">
									<input type="radio" name="envio" id="envio'.$i.'" data-id="'.$i.'" class="custom-control-input" value="D" required>
									
									<input type="hidden" name="provincia'.$i.'" id="provincia'.$i.'" value="'.$row['provincia'].'">
									<input type="hidden" name="id_correo'.$i.'" id="id_correo'.$i.'" value="envio-presonalizado">
									<input type="hidden" name="nombre_correo'.$i.'" id="nombre_correo'.$i.'" value="'.$row['env_nombre'].'">
									<input type="hidden" name="descripcion_correo'.$i.'" id="descripcion_correo'.$i.'" value="'.$row['env_descripcion'].'">
									<input type="hidden" name="despacho'.$i.'" id="despacho'.$i.'" value="-">
									<input type="hidden" name="modalidad'.$i.'" id="modalidad'.$i.'" value="-">
									<input type="hidden" name="servicio'.$i.'" id="servicio'.$i.'" value="-">
									<input type="hidden" name="horas_entrega'.$i.'" id="horas_entrega'.$i.'" value="'.$row['env_horas_entrega'].'">
									<input type="hidden" name="costo_envio'.$i.'" id="costo_envio'.$i.'" value="'.$price.'">
									
									<label class="custom-control-label label-shipping-method-item pl-3" for="envio'.$i.'">
										<div class="shipping-method-item">
											<span>
												<h4 class="shipping-method-item-price">'.$valor.'</h4>
												<div class="shipping-method-item-name">'.$row['env_nombre'].'</div>
												<div class="shipping-method-item-desc"><small>'.$row['env_descripcion'].' - Tiempo de entrega: '.$row['env_horas_entrega'].'</small></div>
											</span>
										</div>
									</label>
								</div>';
					$i++;
				}
			}

		} else {

			if ($prov==25) {
				
					echo '<div class="custom-control custom-radio my-2 border">
								<input type="radio" name="envio" id="envio21" data-id="21" class="custom-control-input" value="D" required>
								<input type="hidden" name="provincia21" id="provincia21" value="-">
								<input type="hidden" name="id_correo21" id="id_correo21" value="-">
								<input type="hidden" name="nombre_correo21" id="nombre_correo21" value="A convenir">
								<input type="hidden" name="descripcion_correo21" id="descripcion_correo21" value="El vendedor se contacta.">
								<input type="hidden" name="despacho21" id="despacho21" value="-">
								<input type="hidden" name="modalidad21" id="modalidad21" value="-">
								<input type="hidden" name="servicio21" id="servicio21" value="-">
								<input type="hidden" name="horas_entrega21" id="horas_entrega21" value="-">
								<input type="hidden" name="costo_envio21" id="costo_envio21" value="0">
								
								<label class="custom-control-label label-shipping-method-item pl-3" for="envio21">
									<div class="shipping-method-item">
										<span>
											<div class="shipping-method-item-name">A convenir</div>
											<div class="shipping-method-item-desc"><small>Luego de la compra te contactamos para establecer el envío</small></div>
										</span>
									</div>
								</label>
							</div>';

			} else {

			
						echo '<div class="custom-control custom-radio my-2 border">
									<input type="radio" name="envio" id="envio20" data-id="20" class="custom-control-input" value="D" required>
									
									<input type="hidden" name="provincia20" id="provincia20" value="-">
									<input type="hidden" name="id_correo20" id="id_correo20" value="-">
									<input type="hidden" name="nombre_correo20" id="nombre_correo20" value="Despacho en transporte">
									<input type="hidden" name="descripcion_correo20" id="descripcion_correo20" value="Despacho en transporte dentro de CABA">
									<input type="hidden" name="despacho20" id="despacho20" value="-">
									<input type="hidden" name="modalidad20" id="modalidad20" value="-">
									<input type="hidden" name="servicio20" id="servicio20" value="-">
									<input type="hidden" name="horas_entrega20" id="horas_entrega20" value="-">
									<input type="hidden" name="costo_envio20" id="costo_envio20" value="0">
									
									<label class="custom-control-label label-shipping-method-item pl-3" for="envio20">
										<div class="shipping-method-item">
											<span>
												<h4 class="shipping-method-item-price">Gratis</h4>
												<div class="shipping-method-item-name">Despacho en tu transporte</div>
												<div class="shipping-method-item-desc"><small>Enviamos hasta el transporte que utilices dentro de CABA sin cargo</small></div>
											</span>
										</div>
									</label>
									<p class="alert alert-danger mt-2">En el campo "Mensaje/Aclaraciones" indicanos nombre y dirección del transporte que utilizan para enviar al interior del país. <strong>Solo si se encuentra dentro de CABA</strong></p>
								</div>';
			

								
						echo '<div class="custom-control custom-radio my-2 border">
								<input type="radio" name="envio" id="envio21" data-id="21" class="custom-control-input" value="D" required>
								<input type="hidden" name="provincia21" id="provincia21" value="-">
								<input type="hidden" name="id_correo21" id="id_correo21" value="-">
								<input type="hidden" name="nombre_correo21" id="nombre_correo21" value="A convenir">
								<input type="hidden" name="descripcion_correo21" id="descripcion_correo21" value="El vendedor se contacta.">
								<input type="hidden" name="despacho21" id="despacho21" value="-">
								<input type="hidden" name="modalidad21" id="modalidad21" value="-">
								<input type="hidden" name="servicio21" id="servicio21" value="-">
								<input type="hidden" name="horas_entrega21" id="horas_entrega21" value="-">
								<input type="hidden" name="costo_envio21" id="costo_envio21" value="0">
								
								<label class="custom-control-label label-shipping-method-item pl-3" for="envio21">
									<div class="shipping-method-item">
										<span>
											<div class="shipping-method-item-name">A convenir</div>
											<div class="shipping-method-item-desc"><small>Luego de la compra te contactamos para establecer el envío</small></div>
										</span>
									</div>
								</label>
							</div>';
			}

		}

	}

	public function enviosPresonalizado($prov,$cant,$total) {

		$query = "SELECT * FROM tbl_envios 
		INNER JOIN provincias ON provincias.id = tbl_envios.env_provincia
		WHERE env_provincia='$prov' ";
		$result=mysqli_query($this->conn,$query);
		$rowCnt = $result->num_rows;
		
		if ($rowCnt>0) {
			$i=20;
			while ($row = $result->fetch_assoc()) {

				if ($total>$row['monto_mayor_a']) {
					$price=$row['price_descuento'];
				} else {
					$price=$row['price_normal'];
				}

				if ($price==0) {
					$valor='Gratis';
				} else {
					$valor='$'.number_format($price,2,',','.');
				}

                echo '<div class="custom-control custom-radio my-2 border">
								<input type="radio" name="envio" id="envio'.$i.'" data-id="'.$i.'" class="custom-control-input" value="D" required>
								
								<input type="hidden" name="provincia'.$i.'" id="provincia'.$i.'" value="'.$row['provincia'].'">
								<input type="hidden" name="id_correo'.$i.'" id="id_correo'.$i.'" value="envio-presonalizado">
								<input type="hidden" name="nombre_correo'.$i.'" id="nombre_correo'.$i.'" value="'.$row['env_nombre'].'">
								<input type="hidden" name="descripcion_correo'.$i.'" id="descripcion_correo'.$i.'" value="'.$row['env_descripcion'].'">
								<input type="hidden" name="despacho'.$i.'" id="despacho'.$i.'" value="-">
								<input type="hidden" name="modalidad'.$i.'" id="modalidad'.$i.'" value="-">
								<input type="hidden" name="servicio'.$i.'" id="servicio'.$i.'" value="-">
								<input type="hidden" name="horas_entrega'.$i.'" id="horas_entrega'.$i.'" value="'.$row['env_horas_entrega'].'">
								<input type="hidden" name="costo_envio'.$i.'" id="costo_envio'.$i.'" value="'.$price.'">
								
								<label class="custom-control-label label-shipping-method-item pl-3" for="envio'.$i.'">
									<div class="shipping-method-item">
										<span>
											<h4 class="shipping-method-item-price">'.$valor.'</h4>
											<div class="shipping-method-item-name">'.$row['env_nombre'].'</div>
											<div class="shipping-method-item-desc"><small>'.$row['env_descripcion'].' - Tiempo de entrega: '.$row['env_horas_entrega'].'</small></div>
										</span>
									</div>
								</label>
							</div>';
				$i++;
			}
		}

	}

	public function calcularEnvio()
	{
		$result = array();
		$arrProvincia = array();

		$this->codPostal = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['c_postal'], ENT_QUOTES)));
		$this->cantProd = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['cantproductos'], ENT_QUOTES)));
		$this->pesoEnvio = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['peso'], ENT_QUOTES)));
		$this->totalEnvio = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['total'], ENT_QUOTES)));
		
		$_SESSION['codPostal'] = $this->codPostal;
		$arrProvincia=$this->seleccionarProvincia($this->codPostal);

		if ($arrProvincia) {

			$this->codProvincia=$arrProvincia[0]['codigo'];
			$this->Provincia=$arrProvincia[0]['provincia_id'];
			$this->nombreProvincia=$arrProvincia[0]['provincia'];

			if(isset($_SESSION['mayoristas'])){	
				$this->enviosPresonalizadoMayorista($this->Provincia,$this->codProvincia);
			} else {
				$this->enviosPresonalizado($this->Provincia,$this->cantProd,$this->totalEnvio);
			}

		} else {
			echo 'El código postal no existe';
		}
		
	}

	public function habilitaPagoEfectivo()
	{
		$result = array();
		$arrProvincia = array();

		$this->codPostal = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['c_postal'], ENT_QUOTES)));
		
		$_SESSION['codPostal'] = $this->codPostal;
		$arrProvincia=$this->seleccionarProvincia($this->codPostal);

		if ($arrProvincia) {

			if ($arrProvincia[0]['codigo']=='C') {
				$this->mostrarOpcionPagoEfectivo();
			}

		} 
		
	}

	public function mostrarOpcionPagoEfectivo() {
		echo '<h6 class="my-3 font-weight-bold">Efectivo:</h6>
					<div class="custom-control custom-radio">
						<input type="radio" name="opcion_pago" id="opcion_pago3" value="efectivo" class="custom-control-input" required>
						<label class="custom-control-label" for="opcion_pago3">Efectivo contra entrega
						<small class="form-text text-muted mt-0 mb-2">Pagas al recibir el paquete.</small></label>
					</div>';
	}
	
}

?>