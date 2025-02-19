<?php
require_once('../clases/class_admin.php');

$ObjConfig=new Configuracion;


$id_partner = $_POST['id_partner'];
$categoria = $_POST['categoria'];
$ObjConfig->ComboSelectCategorias($id_partner,$categoria);

?>