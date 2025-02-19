<?php
require_once('../libreria/config.php');
require_once('../clases/class_admin.php');

if (!empty($_POST['data'])) {
    $data = $_POST['data'];
    $orden = 1;
    $array_elementos = explode(',', $data); // separamos por comas y guardamos en un array
    foreach ($array_elementos as $elemento) {
        // recordamos que los elementos se guardaban como "elemento-1", "elemento-2", etc
        $elemento_id = explode('-', $elemento); // en $elemento_id[1] tendríamos la id
        $id = $elemento_id[1];
		$Obj=new Productos; 
        $Obj->reordenarDestaques($id, $orden); // reordenamos
        $orden++; // aumentamos 1 al orden
    }
} ?>