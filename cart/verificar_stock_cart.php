<?php
require_once("../class/cart.class.php");

$ObjCart = new Cart();

$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_SANITIZE_NUMBER_INT);
$pr_id = filter_input(INPUT_POST, 'idpr', FILTER_SANITIZE_NUMBER_INT);

$stock=$ObjCart->verificarStockCart($cantidad,$pr_id);

if($stock=='ok') {
	echo "0";
} else { 
	echo $stock;
}
?>
