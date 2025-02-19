<?php 

class Mixer 
{
	private $conn;
	private $cartContent = array();

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
	}
	
	
	private $productId;
	private $cantidad;
	private $type;
	private $peso;
	private $mixId;


	public function createMixer()
	{ 		
		$this->peso = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['peso'], ENT_QUOTES)));
		$sid = session_id();

		$query = "SELECT id_mix FROM tbl_cart_mixer WHERE session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$mixer = $result->num_rows;
		
		if ($mixer == 0) {
			
			$query = "INSERT INTO tbl_cart_mixer (peso, session_id, date)
			VALUES ('$this->peso', '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error</p>';
			}

		} else {
			
			$query = "UPDATE tbl_cart_mixer 
			SET peso = $this->peso
			WHERE session_id = '$sid'";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error</p>';
			}			
		}

		
	}

	public function addBaseToCart()
	{ 

		$this->productId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$sid = session_id();

		//verificamos que esté creado el mixer y que haya una base cargada
		$query = "SELECT id_mix, base FROM tbl_cart_mixer WHERE session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$ctnMix = $result->num_rows;
		
		if ($ctnMix == 0) {
			echo 'mixer-no-creado';
			exit;
		} else {
			$mixer=$result->fetch_assoc();
			$id_mix=$mixer['id_mix'];
		}
		
		$query = "SELECT producto_id FROM tbl_cart_item_mixer WHERE es_base = 'si' AND session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$base = $result->num_rows;
		
		if ($base == 0) {
			// put the product in cart table
			$query = "INSERT INTO tbl_cart_item_mixer (id_mix, producto_id, cantidad, es_base, session_id, date)
			VALUES ('$id_mix', '$this->productId', 1, 'si', '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$query = "UPDATE tbl_cart_mixer 
				SET base = '$this->productId'
				WHERE session_id = '$sid' ";		

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax();
				} else {
					echo '<p>Error 2</p>';
				}
			} else {
				echo '<p>Error 1</p>';
			}
		} else {
			
			$query = "UPDATE tbl_cart_item_mixer 
			SET producto_id = '$this->productId'
			WHERE session_id = '$sid' AND es_base = 'si' ";

			if (mysqli_query($this->conn,$query)) {
				$query = "UPDATE tbl_cart_mixer 
				SET base = '$this->productId'
				WHERE session_id = '$sid' ";		

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax();
				} else {
					echo '<p>Error 2</p>';
				}
			} else {
				echo '<p>Error 1</p>';
			}
		}
				
	}

	public function addToCart()
	{ 
		$this->productId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$sid = session_id();

		//verificamos que esté creado el mixer y que haya una base cargada
		$query = "SELECT id_mix, peso, base FROM tbl_cart_mixer WHERE session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$ctnMix = $result->num_rows;
		
		if ($ctnMix == 0) {
			echo 'mixer-no-creado';
			exit;
		} else {
			$mixer=$result->fetch_assoc();
			$id_mix=$mixer['id_mix'];
			if ($mixer['base']==0) {
				echo 'base-vacia';
				exit;
			}
		}
		
		if (!$this->controlPeso($id_mix,$this->productId,$mixer['peso'])) {
			echo 'mixer-completo';
			exit;
		}

		$query = "SELECT producto_id, cantidad FROM tbl_cart_item_mixer WHERE producto_id = '$this->productId' AND session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$total_prod = $result->num_rows;
		
		if ($total_prod == 0) {
			// put the product in cart table
			$query = "INSERT INTO tbl_cart_item_mixer (id_mix, producto_id, cantidad, session_id, date)
			VALUES ('$id_mix', '$this->productId', 1, '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error 1</p>';
			}
		} else {
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']+1;

			$query = "UPDATE tbl_cart_item_mixer 
			SET cantidad = $this->cantidad
			WHERE session_id = '$sid' AND producto_id = '$this->productId' ";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error 2</p>';
			}			
		}
	}

	public function controlPeso($idMix,$prod,$pesoTotal)
	{
		$this->idCart = mysqli_real_escape_string($this->conn,$idMix);
		$this->productId = mysqli_real_escape_string($this->conn,$prod);
		$this->peso = mysqli_real_escape_string($this->conn,$pesoTotal);
		$sid = session_id();

		$query = "SELECT SUM(pd_peso*cantidad) as total_consumido FROM tbl_cart_item_mixer 
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
		WHERE tbl_cart_item_mixer.session_id = '$sid' AND id_mix='$this->idCart' AND es_base='no'";
		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();
		$consumido=intval($row['total_consumido']);

		$query = "SELECT pd_peso FROM tbl_productos_mixer WHERE pd_id = '$this->productId'";
		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();
		$peso_nuevo=intval($row['pd_peso']);

		$requerido=$consumido+$peso_nuevo;
		
		$limite=(60*$this->peso)/100;

		if ($requerido>$limite) {
			return false;
		} else {
			return true;
		}
	}


	private $idCart;

	public function updateMas()
	{ 		
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$sid = session_id();

		$query = "SELECT peso, tbl_cart_mixer.id_mix, producto_id, cantidad FROM tbl_cart_mixer 
		INNER JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		WHERE id = '$this->idCart' AND tbl_cart_mixer.session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']+1;
			$this->productId=$prod['producto_id'];
			$this->peso=$prod['peso'];
			$this->mixId=$prod['id_mix'];
		
			if (!$this->controlPeso($this->mixId,$this->productId,$this->peso)) {
				echo 'mixer-completo';
				exit;
			}

			$query = "UPDATE tbl_cart_item_mixer 
			SET cantidad = $this->cantidad
			WHERE session_id = '$sid' AND producto_id = '$this->productId' ";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error 2</p>';
			}
	}

	public function updateMenos()
	{ 		
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$sid = session_id();
		
		$query = "SELECT peso, tbl_cart_mixer.id_mix, producto_id, cantidad FROM tbl_cart_mixer 
		INNER JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		WHERE id = '$this->idCart' AND tbl_cart_mixer.session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']-1;
			$this->productId=$prod['producto_id'];
			$this->peso=$prod['peso'];
			$this->mixId=$prod['id_mix'];

			if ($this->cantidad>0) {
				$query = "UPDATE tbl_cart_item_mixer 
				SET cantidad = $this->cantidad
				WHERE session_id = '$sid' AND producto_id = '$this->productId' ";		

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax();
				} else {
					echo '<p>Error 2</p>';
				}
			} else {
				$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id = '$this->idCart' ";	
				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax();
				} else {
					echo '<p>Error</p>';
				}
			}

			
	}

	public function deleteAll()
	{ 	
		$sid = session_id();
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$query = "UPDATE tbl_cart_mixer SET base=0 WHERE session_id = '$sid' AND id_mix = '$this->idCart' ";	

		if (mysqli_query($this->conn,$query)) {
			$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id_mix = '$this->idCart' ";		
			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax();
			} else {
				echo '<p>Error</p>';
			}
		} else {
			echo '<p>Error</p>';
		}
	}

	public function deleteCart()
	{ 	
		$sid = session_id();
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id = '$this->idCart' ";	

		if (mysqli_query($this->conn,$query)) {
			$this->getCartContentAjax();
		} else {
			echo '<p>Error</p>';
		}
	}


	public function getCartContentAjax()
	{
		$sid = session_id();

		$query = "SELECT * FROM tbl_cart_mixer 
		LEFT JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
		WHERE tbl_cart_mixer.session_id = '$sid'";

		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
							
					$ingredientes = '';
					$peso_total = 0;
					$precio_total = 0;
					$haybase=false;

					while ($row=$result->fetch_assoc()) {
							extract($row);

							if ($producto_id) {

								$ingredientes .= '<div class="col-4 p-2 my-1';
								if ($es_base=='si') {
									$haybase=true;
									$ingredientes .= ' base';
								} else {
									$peso_total += $pd_peso;
								}
								$ingredientes .= '">
									<div class="recipe-list-item">
										<div class="recipe-list-item-background">';
										if (!empty($pd_img)) {
											$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/'.$pd_img.'">';
										} else {
											$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/sin-icono.svg">';
										}
										$ingredientes .= '</div>
										<span class="amount">';
										if ($cantidad>1) {$ingredientes .= $cantidad;}
										$ingredientes .= '</span>
										<p class="name">'.$pd_titulo.'</p>';
										if ($es_base=='no') {
											$ingredientes .= '<div class="d-flex justify-content-center align-items-center">
												<div><button type="button" class="btntrash" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button></div>
												<div><button type="button" class="btnmas" data-id="'.$id.'">+</button> <button type="button" class="btnmenos" data-id="'.$id.'">-</button></div>
											</div>';
										} 
									$ingredientes .= '</div>
								</div>';
								$precio_total += $cantidad * $pd_precio;

							} else {

								$ingredientes .= '<div class="col-12 p-2 mb-2 border border-primary">
										<div class="recipe-list-item">
											<p class="mensaje">Aún no se ha elegido una base. Simplemente elija una base a la izquierda.</p>
										</div>
									</div>
									<div class="col-4 p-2 base">
										<div class="recipe-list-item">
											<div class="recipe-list-item-background">
												<img alt="Elige la base" draggable="true" title="Elige la base" src="'.WEB_ROOT.'img/iconos/blank.png">
											</div>
											<p class="name">Elige la Base</p>
										</div>
									</div>';
							}

					}

				$precio_gramo=(100*$precio_total)/$peso;
				
				echo '<h2 class="title-tubo">Tu Mixer Favorito</h2>
					<div class="ingredientes-tubo py-3">
						<div class="row">
							'.$ingredientes.'
						</div>
					</div>
					<div class="footer-tubo">';
						if ($haybase) {
							echo '<a href="#" data-id="'.$id_mix.'" class="btn btn-outline-dark w-100 rounded-0 my-1" id="vaciarmix">Vaciar el mixer</a>
							<a href="#" class="btn btn-outline-dark w-100 rounded-0 my-1">Valores nutricionales</a>
							<p class="price-tubo mt-3">'.$peso.'g por $'.number_format($precio_total,2,',','.').'<br>
							<small>$'.number_format($precio_gramo,2,',','.').' / 100g IVA incluido, <br><span class="text-primary">no incluye gastos de envío</span></small></p>
							<a href="#" class="btn btn-primary w-100">Seguir</a>';
						} else {
							echo '<p class="price-tubo">'.$peso.'g</p>';
						}
						
					echo '</div>';

		} else {
			echo '<h2 class="title-tubo">Tu Mixer Favorito</h2>';
		}

	}
	

	public function estilosCategoriasMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->cat);
		} else {
			$this->type='granola';
		}

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias_mixer` 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
		WHERE tm_alias='$this->type'");

		echo '<style>';
		while($cat=$result->fetch_assoc())
			{
				echo '.'.$cat['ct_alias'].'.active,.'.$cat['ct_alias'].':hover {background-color: '.$cat['ct_color'].';border-color: '.$cat['ct_color'].';}';
			}
			echo '.peso.active,.peso:hover {background-color: #cd3a93;border-color: #cd3a93;}';
		echo '</style>';
	}


	public function categoriasMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->cat);
		} else {
			$this->type='granola';
		}

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias_mixer` 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
		WHERE tm_alias='$this->type'");

		while($cat=$result->fetch_assoc())
			{
				echo '<a href="#'.$cat['ct_alias'].'" class="list-group-item list-group-item-action '.$cat['ct_alias'].'" style="color: '.$cat['ct_color'].'">'.$cat['ct_titulo'].'</a>';
			}
	}

	public function productosMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->cat);
		} else {
			$this->type='granola';
		}

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias_mixer` 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
		WHERE tm_alias='$this->type'");

		while($cat=$result->fetch_assoc())
			{
				$categoria=$cat['ct_id'];

				echo '<div id="'.$cat['ct_alias'].'" style="min-height: 800px;">
						<div class="mixer-catalog">
							<h4 class="mixer-category-title" style="background-color:'.$cat['ct_color'].'">'.$cat['ct_titulo'].'</h4>
							<div class="mixer-ingredients-list">';
				
							$resultProd=mysqli_query($this->conn,"SELECT * FROM `tbl_productos_mixer` 
							INNER JOIN tbl_categorias_mixer ON tbl_categorias_mixer.ct_id=tbl_productos_mixer.pd_categoria
							INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
							WHERE pd_categoria='$categoria'");
					
							while($prod=$resultProd->fetch_assoc())
								{
									echo '<div class="mixer-ingredient '; 
									
									if ($cat['ct_alias']=='bases') {
										echo 'addbases';
									} else {
										echo 'addingredientes';
									}

									echo '" data-id="'.$prod['pd_id'].'" style="background-image: url(https://www.mymuesli.com/contents/cms/a/100x100/3f539047-643a-4772-93f4-9bade1d401f6/sonnenblumenkerne_thumb.png?v=38982); ">
											<div class="d-flex ingredient-teaser">
												<div>
													<h3 class="title">'.$prod['pd_titulo'].'</h3>
													<div>'.$prod['pd_descripcion'].'</div>
													<div class="extra-information">$'.$prod['pd_precio'];
													if ($cat['ct_alias']!=='bases') {
														echo '<span class="item-weight">︱'.$prod['pd_peso'].'g</span>';
													}
													echo '</div>
												</div>
												<div class="image-container ml-auto">';
												if (!empty($prod['pd_img'])) {
													echo '<div class="image-circle"><img alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/iconos/'.$prod['pd_img'].'" width="46" height="46"></div>';
												} else {
													echo '<div class="image-circle"><img alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/iconos/sin-icono.svg"></div>';
												}
												echo '</div>
											</div>
											<div class="component">
												<a href="#" class="hints-item"><img alt="CO2 Super Saver" width="30" height="30" src="https://www.mymuesli.com/contents/cms/a/default/b0b31f61-f4a5-49a2-a931-c51d5df8ac01/besonderswenigco2enochbesserfuerunserklima_de_DE.svg?v=38982"
														class="img-responsive"> CO2 Super Saver</a>
												<a href="#" class="hints-item"><img alt="vegan" width="30" height="30" src="https://www.mymuesli.com/contents/cms/i/1ff45d95417fd525407b4578040184fe/bWQ1LTg2Ky9aOEZDN1hZbUMyMDBFc3hhbEE9PQ!!/default/vegan_de_DE.svg?v=38982"
														class="img-responsive"> Vegano</a>
												<a href="#" class="hints-item"><img alt="hoher Proteingehalt" width="30" height="30" src="https://www.mymuesli.com/contents/cms/i/1ff45d95417fd525407b4578040184fe/bWQ1LVAvd1JiN1FoaTFtbFlPNEN2aTk3NVE9PQ!!/default/hoherproteingehalt_de_DE.svg?v=38982"
														class="img-responsive"> Alto en proteinas</a>
											</div>
											<div class="item-information"><a href="#"><small>Más info ...</small></a></div>
										</div>';
								}

				echo '</div></div></div>';
			}
	}

}

?>