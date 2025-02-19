<?php 

class Cart 
{
	private $conn;
	private $cartContent = array();

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
	}
	
	
	public function getCartContent()
	{
		$sid = session_id();
		$query = "SELECT id, producto_id, variacion, cantidad, pd_alias, pd_titulo, pd_peso, pd_descuento, pr_id, pr_codigo, pr_precio FROM tbl_cart
				INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_cart.producto_id
				INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_id=tbl_cart.precio 
				WHERE session_id = '$sid'";

		$result=mysqli_query($this->conn,$query);

		while ($row=$result->fetch_assoc()) {

			$id_prod=$row["producto_id"];
			
			$query = "SELECT im_nombre FROM tbl_img WHERE im_producto='$id_prod' ORDER BY im_orden ASC LIMIT 1";
			$result_img=mysqli_query($this->conn,$query);
			$rowImg=$result_img->fetch_assoc();
			$row["im_nombre"]=$rowImg["im_nombre"];

			if ($row['pd_descuento']!='') {
				$descuento=($row['pd_descuento']*$row['pr_precio'])/100;
				$precioFinal=$row['pr_precio']-$descuento;
				$row['pr_precio']=$precioFinal;
			} 

			$this->cartContent[] = $row;
		}	
		
		return $this->cartContent;
	}
	
	private $productId;
	private $productVar;
	private $cantidad;
	private $productPrec;
	
	public function addToCart($prod,$variacion,$cant,$prec)
	{ 

		$this->productId = mysqli_real_escape_string($this->conn, $prod);
		$this->productVar = mysqli_real_escape_string($this->conn, $variacion);
		$this->cantidad = mysqli_real_escape_string($this->conn, $cant);
		$this->productPrec = mysqli_real_escape_string($this->conn, $prec);

		$sid = session_id();
		
		$query = "SELECT producto_id FROM tbl_cart WHERE producto_id = '$this->productId' AND variacion='$this->productVar' AND session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$total_compra = $result->num_rows;
		
		if ($total_compra == 0) {
			// put the product in cart table
			$query = "INSERT INTO tbl_cart (producto_id, variacion, cantidad, precio, session_id, date)
			VALUES ('$this->productId','$this->productVar', '$this->cantidad', '$this->productPrec', '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error</p>';
			}
		} else {
			// update product quantity in cart table
			$query = "UPDATE tbl_cart 
			SET cantidad = $this->cantidad
			WHERE session_id = '$sid' AND producto_id = '$this->productId' AND variacion='$this->productVar'";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error</p>';
			}			
		}
				
	}

	public function verificarStockCart($cant,$id)
	{
		$this->id=mysqli_real_escape_string($this->conn, $id);
		$this->cant=mysqli_real_escape_string($this->conn, $cant);
		// current session id
		$sid = session_id();

		$query = "SELECT pr_stock FROM `tbl_productos_parent` 
		WHERE pr_id='$this->id'";					
		$result=mysqli_query($this->conn,"$query");

		$stock=$result->fetch_assoc();

		if ($stock['pr_stock']<$this->cant) {
			return $stock['pr_stock'];
		} else {
			return 'ok';
		}
	}

	private $idCart;

	public function updateCart()
	{ 		
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idCart'], ENT_QUOTES)));
		$this->cantidad = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['cant'], ENT_QUOTES)));

		// update product quantity
		$query = "UPDATE tbl_cart
		SET cantidad = '$this->cantidad'
		WHERE id = '$this->idCart'";

		if (mysqli_query($this->conn,$query)) {

			$this->getCartContentAjax();

		} else {
			echo '<p>Error</p>';
		}
	}

	public function deleteCart()
	{ 	
		$sid = session_id();
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idCart'], ENT_QUOTES)));
		$query = "DELETE FROM tbl_cart WHERE session_id = '$sid' AND id = '$this->idCart' ";
		if (mysqli_query($this->conn,$query)) {
			$this->getCartContentAjax();
		} else {
			echo '<p>Error</p>';
		}
	}

	public function getCountCart()
	{
		$sid = session_id();

		$query = "SELECT SUM(cantidad) AS TotalItems FROM tbl_cart
		INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_cart.producto_id
		INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_id=tbl_cart.precio
		WHERE session_id = '$sid'";

		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();
		if (!empty($row['TotalItems'])) {
			echo $row['TotalItems'];
		} else {
			echo 0;
		}
		
		
	}


	public function getCartContentAjax()
	{
		$sid = session_id();
		
		$query = "SELECT id, producto_id, variacion, cantidad, pd_alias, pd_titulo, pd_peso, pd_descuento, pr_id, pr_codigo, pr_precio FROM tbl_cart
		INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_cart.producto_id
		INNER JOIN tbl_productos_parent ON tbl_productos_parent.pr_id=tbl_cart.precio 
		WHERE session_id = '$sid'";

		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {

			echo '<div class="modal-body carrito">';

				echo '<div class="table-responsive">
						<table class="table table-striped">
							<tr>
								<th>PRODUCTOS</th>
								<th>SUBTOTAL</th>
								<th></th>
							</tr>';
							
					$cant_prod=0;
					$total=0;

					while ($row=$result->fetch_assoc()) {

							extract($row);

							if ($pd_descuento!='') {
								$descuento=($pd_descuento*$pr_precio)/100;
								$precioFinal=$pr_precio-$descuento;
								$pr_precio=$precioFinal;
							} 

							$total += $pr_precio * $cantidad;
							$cant_prod += $cantidad;

							$query = "SELECT im_nombre FROM tbl_img WHERE im_producto='$producto_id' ORDER BY im_orden ASC LIMIT 1";
							$result_img=mysqli_query($this->conn,$query);
							$rowImg=$result_img->fetch_assoc();
							$pd_thumbnail=$rowImg["im_nombre"];


							echo '<tr>
									<td style="min-width:30%"><img class="mb-1" src="'.WEB_ROOT.'img/productos/'.$pd_thumbnail.'" alt="..." style="width: 80px;">
										<p>'.$pr_codigo.'<br>'.$pd_titulo;
										if ($variacion) {
											echo ' <br>'.$variacion;
										}
										echo '<br><strong class="unit-price">$'.number_format($pr_precio,2,',','.').'</strong></p>
										
										<div class="form-group row">
											<label for="txtQty" class="col-sm-2 col-form-label col-form-label-sm">Cantidad</label>
											<div class="col-sm-10">
											<input type="hidden" name="prec'.$id.'" id="prec'.$id.'" value="'.$pr_id.'">
											<input name="txtQty" type="number" data-id="'.$id.'" id="txtQty'.$id.'" class="form-control form-control-sm box_cant_cart" value="'.$cantidad.'" min="0" max="999" maxlength="4">
											</div>
										</div>

									</td>';
									echo '<td class="price">$'.number_format($pr_precio * $cantidad,2,',','.').'</td>';
									echo '<td><a href="#" onclick="deleteCart('.$id.');" class="btn_quitar text-secondary"><svg class="feather"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#trash-2"/></svg></a></td>';
							echo '</tr>';			

					}
				echo '</table>          
					</div>';

				echo '<div class="total-cart">
						<hr>
						<p>TOTAL <span>$'.number_format($total,2,',','.').'</span></p>
					</div>';

				echo '
						<div class="action-cart py-4">
							<a href="'.WEB_ROOT.'checkout.php?step=1" class="btn btn-primary btn-lg rounded-pill">Comprar ahora!</a>
							<button type="button" class="btn" data-dismiss="modal">¡Seguir mirando más!</button>
						</div>
					</div>';
		} else {

			echo '<div class="modal-body pop_content">
					<p><strong class="text-primary">No agregaste ningún producto al carro!</strong></p>
				</div>';
		}

		$this->deleteAbandonedCart();
	}

	
	public function deleteAbandonedCart()
	{
		$yesterday = date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
		$query = "DELETE FROM tbl_cart WHERE date < '$yesterday'";
		mysqli_query($this->conn,$query);	
	}
	
	
	public function isCartSave()
	{
		$save = false;

		$cliente_id=$_SESSION['user_id'];
		$sid = session_id();

		$query = "SELECT id FROM tbl_cart
		WHERE cliente = '$cliente_id' AND session_id!='$sid'";
		
		$result=mysqli_query($this->conn,$query);
		$items = $result->num_rows;
		if ($items>0) {
			$save = true;
		}		
		
		return $save;
	}

	public function isCartEmpty()
	{
		$isEmpty = false;
		
		$sid = session_id();
		$query = "SELECT id
				FROM tbl_cart
				WHERE session_id = '$sid'";
		
		$result=mysqli_query($this->conn,$query);
		$items = $result->num_rows;
		if ($items==0) {
			$isEmpty = true;
		}		
		
		return $isEmpty;
	}
	
	public function comb_provincias($prov)
	{
		$result=mysqli_query($this->conn,"SELECT * FROM provincias ORDER BY provincia ASC");
			echo '<option value=""></option>';
		while ($pro=$result->fetch_assoc())
		{
            echo '<option value="'.$pro["provincia"].'" '; if (isset($prov) and $prov==$pro["provincia"]) { echo 'selected'; }; echo ' >'.$pro["provincia"].'</option>';
		}
	}

	public function codProvincia($prov)
	{
		$result=mysqli_query($this->conn,"SELECT * FROM provincias WHERE provincia='$prov'");
		$pro=$result->fetch_assoc();
			return $pro["codigo"];
	}

}

?>