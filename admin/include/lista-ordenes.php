<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';
    
    if (isset($_GET['status'])) {
        $status=filter_input(INPUT_GET,'status', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $status='';
    }

    $Obj=new Ordenes; 

    switch($action) {
     case 'edit':
        $Obj->updateEmployee($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->listaOrdenes($status);
     return;
    }
?>