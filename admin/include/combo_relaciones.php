<?php
require_once('../clases/class_admin.php');

$Obj=new Modelos; 

$relaciones = $Obj->ComboRelaciones();

			$numItem=count($relaciones);
            for ($i=0; $i<$numItem; $i++) {
                $datos[] = array(
                    'value'            => $relaciones[$i]['mo_id'],
                    'text'          => $relaciones[$i]['mo_nombre']
                );
            }
            echo json_encode($datos);
?>