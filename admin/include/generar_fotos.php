<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';

    $Obj=new Productos;

    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);

    switch($action) {
     case 'edit':
        $Obj->editarOrdenFoto($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->ImagenesProd($id);
     return;
    }
?>