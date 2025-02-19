<?php
include_once("../class/class.php");

$Obj = new mainClass();

$prod=filter_input(INPUT_GET,'prod', FILTER_SANITIZE_NUMBER_INT);
		
		$datosProd = $Obj->actualizarFicha($prod);
		$numItem=count($datosProd);
		if ($numItem>0) {
			for ($i=0; $i<$numItem; $i++) {
				$datos[] = array(
					'precio'          	=> $datosProd[$i]['pd_precio'],
					'id'     	    => $datosProd[$i]['pd_id']
				);
			}
			echo json_encode($datos);
		}

?>
