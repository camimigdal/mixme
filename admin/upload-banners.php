<?php
require_once('clases/class_admin.php');
$Obj=new Banners;

$ds = '/'; 
 
$storeFolder = '../img/modelos';
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];           
    $targetPath = $storeFolder.$ds;
    $targetFile =  $targetPath.$_POST['nombre'].'.jpg';
 
    move_uploaded_file($tempFile,$targetFile);

    $Obj->gestionImg($_POST['nombre'].'.jpg',$_POST['id_mod']);
     
}
?> 