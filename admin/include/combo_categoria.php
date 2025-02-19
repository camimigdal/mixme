<?php
require_once('../clases/class_admin.php');

	if (isset($_GET['marc'])) {
        $marc=filter_input(INPUT_GET,'marc', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $marc=1;
    }


$Obj=new Configuracion;

$categoria = $Obj->ComboCategorias($marc);

			$numItem=count($categoria);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $categoria[$i]['ct_id'],
                    'text'          => $categoria[$i]['ct_titulo']
                );
            }
            echo json_encode($datos);
?>