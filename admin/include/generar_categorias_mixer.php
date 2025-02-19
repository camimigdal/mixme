<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';

    $Obj=new CategoríasMixer;

    switch($action) {
     case 'edit':
        $Obj->editar($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
        $Obj->lista();
     return;
    }
?>