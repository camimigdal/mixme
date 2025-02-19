<?php
require_once('../clases/class_admin.php');

    $action = isset($_GET["action"]) != '' ? $_GET['action'] : '';
    
    $Obj=new Seo; 

    switch($action) {
     case 'edit':
        $Obj->updateEmployee($_POST["name"],$_POST["value"],$_POST["pk"]);
     break;
     default:
     $Obj->getEmployees();
     return;
    }
?>