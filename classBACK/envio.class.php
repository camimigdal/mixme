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

			$this->enviosPresonalizado($this->Provincia,$this->cantProd,$this->totalEnvio);

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