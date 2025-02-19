<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';
    
    if (isset($_GET['lin'])) {
        $lin=filter_input(INPUT_GET,'lin', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $lin=1;
    }

    $ObjCarac=new Caracteristicas; 

    switch($action) {
     case 'edit':
        $ObjCarac->updateEmployee($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $ObjCarac->getEmployees($lin);
     return;
    }
?>