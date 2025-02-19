<?php
require '/home/cbjuoyjt/public_html/class/facturacion.class.php';
$ObjFacturacion = new Facturacion;
$ObjFacturacion->GetOrderSinFacturar();
?>          