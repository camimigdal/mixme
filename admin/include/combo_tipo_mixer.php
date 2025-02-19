<?php
require_once('../clases/class_admin.php');

$Obj=new CategoríasMixer;

$tiposMixer = $Obj->ComboTipoMixer();

			$numItem=count($tiposMixer);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $tiposMixer[$i]['tm_id'],
                    'text'          => $tiposMixer[$i]['tm_titulo']
                );
            }
            echo json_encode($datos);
?>