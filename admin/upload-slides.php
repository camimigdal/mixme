<?php
require_once('clases/class_admin.php');
$Obj=new Slides;

$ds = '/'; 
 
$storeFolder = '../img/slide';

if (!empty($_FILES)) {
    

    $tempFile = $_FILES['file']['tmp_name'];           
    $targetPath = $storeFolder.$ds;

    $info = pathinfo($_FILES['file']['name']);
	$nom_foto =  basename($_FILES['file']['name'],'.'.$info['extension']);
    $nom_foto=Varias::crear_url($nom_foto);


    $targetFile =  $targetPath.$nom_foto.'.jpg';
 
    move_uploaded_file($tempFile,$targetFile);

    $Obj->gestionImg($nom_foto.'.jpg');
     
}
?> 