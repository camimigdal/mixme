<?php
require_once('../clases/class_admin.php');

$ObjDatos=new DatosExtras;

$colores = $ObjDatos->ComboColores();

			$numItem=count($colores);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $colores[$i]['col_id'],
                    'text'          => $colores[$i]['col_nombre']
                );
            }
            echo json_encode($datos);
?>