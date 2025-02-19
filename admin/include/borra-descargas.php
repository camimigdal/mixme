<?php
require_once('../clases/class_admin.php');

    $nombre = isset($_POST["nombre"]) != '' ? $_POST["nombre"] : '';

    $Obj=new ArchivosDescargas; 
    $Obj->borrarDescargasUpload($nombre);

?>