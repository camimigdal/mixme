<?php
require_once('../clases/class_admin.php');

$Obj=new Envios; 

$provincias = $Obj->ComboProvincias();

			$numItem=count($provincias);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $provincias[$i]['id'],
                    'text'          => $provincias[$i]['provincia']
                );
            }
            echo json_encode($datos);
?>