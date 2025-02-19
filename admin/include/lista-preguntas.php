<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';
    
    if (isset($_GET['status'])) {
        $status=filter_input(INPUT_GET,'status', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $status='';
    }

    $Obj=new Preguntas; 

    switch($action) {
     case 'edit':
        $Obj->updateEmployee($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->lista($status);
     return;
    }
?>