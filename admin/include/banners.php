<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';
    
    if (isset($_GET['id_mod'])) {
        $id_mod=filter_input(INPUT_GET,'id_mod', FILTER_SANITIZE_NUMBER_INT);
    }

    $Obj=new Banners; 

    switch($action) {
     case 'edit':
        $Obj->updateEmployee($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->getEmployees($id_mod);
     return;
    }
?>