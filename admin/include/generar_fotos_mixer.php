<?php
require_once('../clases/class_admin.php');

    $Obj=new Mixer;
    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
    $Obj->ImagenesProd($id);
    
?>