<?php
include_once("../class/class.php");

$Obj = new mainClass();

$prod=filter_input(INPUT_GET,'idprec', FILTER_SANITIZE_NUMBER_INT);
		
		$datosPrec = $Obj->actualizarPrecioFicha($prod);
		$numItem=count($datosPrec);
		if ($numItem>0) {
			for ($i=0; $i<$numItem; $i++) {
				$datos[] = array(
					'precio'          	=> $datosPrec[$i]['pr_precio'],
					'id'     	    => $datosPrec[$i]['pr_id'],
					'codigo'     	    => $datosPrec[$i]['pr_codigo'],
					'stock'     	    => $datosPrec[$i]['pr_stock'],
					'preciofinal'     	    => $datosPrec[$i]['preciofinal'],
					'precioorig'     	    => $datosPrec[$i]['precioorig']
				);
			}
			echo json_encode($datos);
		}

?>
