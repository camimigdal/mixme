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
	private $pack;
	private $mixId;
	private $token;

	public function validarTypeMixer($type)
	{
		$this->type = mysqli_real_escape_string($this->conn, $type);
		$sid = session_id();

        $query = $this->conn->prepare("SELECT * FROM `tbl_tipos_mixer` 
		WHERE tm_alias=? ");
        $query->bind_param("s", $this->type);
        if ($query->execute()) {
            if ($result=$query->get_result()) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
            $query->close();
		} else {
			return false;
		}
	}

	public function validarIdMixer($mix)
	{
		$this->mixId = mysqli_real_escape_string($this->conn, $mix);
		$sid = session_id();

        $query = $this->conn->prepare("SELECT * FROM `tbl_cart_mixer`
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id = tbl_cart_mixer.tipo_mixer
		WHERE id_mix=? AND session_id=? ");
        $query->bind_param("is", $this->mixId, $sid);
        if ($query->execute()) {
            if ($result=$query->get_result()) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
            $query->close();
		} else {
			return false;
		}
	}

	public function createMixer()
	{ 	
		$this->peso = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['peso'], ENT_QUOTES)));
		$this->pack = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['pack'], ENT_QUOTES)));
		$this->type = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}
		$sid = session_id();

		$query = "SELECT id_mix FROM tbl_cart_mixer WHERE session_id = '$sid' AND tipo_mixer=$this->type ";

		if(!empty($this->mixId)){	
			$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
		} else {
			$query .= " AND id_prod_creado=0 ";
		}
		


		$result=mysqli_query($this->conn,$query);
		$mixer = $result->num_rows;
		
		if ($mixer == 0) {
			
			$query = "INSERT INTO tbl_cart_mixer (peso, tipo_mixer, pack, session_id, date)
			VALUES ('$this->peso', '$this->type', '$this->pack', '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
			} else {
				echo '<p>Error 1</p>';
			}

		} else {
			
			$query = "UPDATE tbl_cart_mixer 
			SET peso = '$this->peso', tipo_mixer = '$this->type', pack = '$this->pack'
			WHERE session_id = '$sid' AND tipo_mixer=$this->type";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
			} else {
				echo '<p>Error 2</p>';
			}			
		}

		
	}

	public function addBaseToCart()
	{ 

		$this->productId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$this->type = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		$sid = session_id();

		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}

		//verificamos que esté creado el mixer y que haya una base cargada
		$query = "SELECT id_mix, base FROM tbl_cart_mixer WHERE session_id = '$sid' AND tipo_mixer=$this->type ";
		if(!empty($this->mixId)){	
			$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
		} else {
			$query .= " AND id_prod_creado=0 ";
		}
		$result=mysqli_query($this->conn,$query);
		$ctnMix = $result->num_rows;
		
		if ($ctnMix == 0) {
			echo 'mixer-no-creado';
			exit;
		} else {
			$mixer=$result->fetch_assoc();
			$id_mix=$mixer['id_mix'];
		}
		
		$query = "SELECT producto_id FROM tbl_cart_item_mixer WHERE es_base = 'si' AND session_id = '$sid' AND id_mix = '$id_mix'";
		$result=mysqli_query($this->conn,$query);
		$base = $result->num_rows;
		
		if ($base == 0) {
			// put the product in cart table
			$query = "INSERT INTO tbl_cart_item_mixer (id_mix, producto_id, cantidad, es_base, session_id, date)
			VALUES ('$id_mix', '$this->productId', 1, 'si', '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$query = "UPDATE tbl_cart_mixer 
				SET base = '$this->productId'
				WHERE session_id = '$sid' AND id_mix = '$id_mix'";

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax($this->type);
				} else {
					echo '<p>Error 2</p>';
				}
			} else {
				echo '<p>Error 1</p>';
			}
		} else {
			
			$query = "UPDATE tbl_cart_item_mixer 
			SET producto_id = '$this->productId'
			WHERE session_id = '$sid' AND es_base = 'si' AND id_mix = '$id_mix'";

			if (mysqli_query($this->conn,$query)) {
				$query = "UPDATE tbl_cart_mixer 
				SET base = '$this->productId'
				WHERE session_id = '$sid' AND id_mix = '$id_mix'";	

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax($this->type);
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
		$this->type = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		$sid = session_id();
		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}

		//verificamos que esté creado el mixer y que haya una base cargada
		$query = "SELECT id_mix, peso, tipo_mixer, base FROM tbl_cart_mixer WHERE session_id = '$sid' AND tipo_mixer=$this->type ";
		if(!empty($this->mixId)){	
			$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
		} else {
			$query .= " AND id_prod_creado=0 ";
		}
		$result=mysqli_query($this->conn,$query);
		$ctnMix = $result->num_rows;
		
		if ($ctnMix == 0) {
			echo 'mixer-no-creado';
			exit;
		} else {
			$mixer=$result->fetch_assoc();
			$id_mix=$mixer['id_mix'];
			if ($mixer['base']==0 && $mixer['tipo_mixer']==100) {
				echo 'base-vacia';
				exit;
			}
		}
		
		if (!$this->controlPeso($id_mix,$this->type,$this->productId,$mixer['peso'])) {
			echo 'mixer-completo';
			exit;
		}

		$query = "SELECT producto_id, cantidad FROM tbl_cart_item_mixer WHERE producto_id = '$this->productId' AND session_id = '$sid' AND id_mix = '$id_mix'";
		$result=mysqli_query($this->conn,$query);
		$total_prod = $result->num_rows;
		
		if ($total_prod == 0) {
			// put the product in cart table
			$query = "INSERT INTO tbl_cart_item_mixer (id_mix, producto_id, cantidad, session_id, date)
			VALUES ('$id_mix', '$this->productId', 1, '$sid', NOW())";

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
			} else {
				echo '<p>Error 1</p>';
			}
		} else {
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']+1;

			$query = "UPDATE tbl_cart_item_mixer 
			SET cantidad = $this->cantidad
			WHERE session_id = '$sid' AND producto_id = '$this->productId' AND id_mix = '$id_mix' ";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
			} else {
				echo '<p>Error 2</p>';
			}			
		}
	}

	public function controlPeso($idMix,$tipoMix,$prod,$pesoTotal)
	{
		$this->idCart = mysqli_real_escape_string($this->conn,$idMix);
		$this->productId = mysqli_real_escape_string($this->conn,$prod);
		$this->peso = mysqli_real_escape_string($this->conn,$pesoTotal);
		$this->type = mysqli_real_escape_string($this->conn,$tipoMix);
		$sid = session_id();

		$query = "SELECT SUM(pd_peso*cantidad) as total_consumido FROM tbl_cart_item_mixer 
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
		LEFT JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
		WHERE tbl_cart_item_mixer.session_id = '$sid' AND id_mix='$this->idCart' AND vm_tipo_mixer='$this->type' AND es_base='no'";
		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
			$row=$result->fetch_assoc();
			$consumido=intval($row['total_consumido']);
		} else {
			$consumido=0;
		}


		$query = "SELECT pd_peso FROM tbl_variaciones_mixer WHERE vm_producto = '$this->productId' AND vm_tipo_mixer='$this->type'";
		$result=mysqli_query($this->conn,$query);
		$row=$result->fetch_assoc();
		$peso_nuevo=intval($row['pd_peso']);

		$requerido=$consumido+$peso_nuevo;
		
		if ($this->type==100) {
			$limite=(40*$this->peso)/100;
		} else {
			$limite=$this->peso;
		}
		
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

		$query = "SELECT peso, tbl_cart_mixer.id_mix, tipo_mixer, producto_id, cantidad FROM tbl_cart_mixer 
		INNER JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		WHERE id = '$this->idCart' AND tbl_cart_mixer.session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']+1;
			$this->productId=$prod['producto_id'];
			$this->peso=$prod['peso'];
			$this->type=$prod['tipo_mixer'];
			$this->mixId=$prod['id_mix'];
		
			if (!$this->controlPeso($this->mixId,$this->type,$this->productId,$this->peso)) {
				echo 'mixer-completo';
				exit;
			}

			$query = "UPDATE tbl_cart_item_mixer 
			SET cantidad = $this->cantidad
			WHERE session_id = '$sid' AND producto_id = '$this->productId' AND id_mix = $this->mixId";		

			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
			} else {
				echo '<p>Error 2</p>';
			}
	}

	public function updateMenos()
	{ 		
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$sid = session_id();
		
		$query = "SELECT peso, tbl_cart_mixer.id_mix, tipo_mixer, producto_id, cantidad FROM tbl_cart_mixer 
		INNER JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		WHERE id = '$this->idCart' AND tbl_cart_mixer.session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		
			$prod=$result->fetch_assoc();
			$this->cantidad=$prod['cantidad']-1;
			$this->productId=$prod['producto_id'];
			$this->peso=$prod['peso'];
			$this->type=$prod['tipo_mixer'];
			$this->mixId=$prod['id_mix'];

			if ($this->cantidad>0) {
				$query = "UPDATE tbl_cart_item_mixer 
				SET cantidad = $this->cantidad
				WHERE session_id = '$sid' AND producto_id = '$this->productId' AND id_mix = $this->mixId";		

				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax($this->type);
				} else {
					echo '<p>Error 2</p>';
				}
			} else {
				$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id = '$this->idCart' ";	
				if (mysqli_query($this->conn,$query)) {
					$this->getCartContentAjax($this->type);
				} else {
					echo '<p>Error</p>';
				}
			}
	}

	public function deleteAll()
	{ 	
		$sid = session_id();
		$this->idCart = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
		$this->type = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		$query = "UPDATE tbl_cart_mixer SET base=0 WHERE session_id = '$sid' AND id_mix = '$this->idCart' ";	

		if (mysqli_query($this->conn,$query)) {
			$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id_mix = '$this->idCart' ";		
			if (mysqli_query($this->conn,$query)) {
				$this->getCartContentAjax($this->type);
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

		$query = "SELECT tipo_mixer FROM tbl_cart_mixer 
		INNER JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		WHERE id = '$this->idCart' AND tbl_cart_mixer.session_id = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$prod=$result->fetch_assoc();
		$this->type=$prod['tipo_mixer'];


		$query = "DELETE FROM tbl_cart_item_mixer WHERE session_id = '$sid' AND id = '$this->idCart' ";	

		if (mysqli_query($this->conn,$query)) {
			$this->getCartContentAjax($this->type);
		} else {
			echo '<p>Error</p>';
		}
	}


	public function getCartContentAjax($tipoMix)
	{
		$sid = session_id();
		$this->type=mysqli_real_escape_string($this->conn, $tipoMix);

		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}

		//nombre del mix para el titulo del tubo
		$query = "SELECT tm_titulo FROM tbl_tipos_mixer WHERE tm_id='$this->type'";
		$result=mysqli_query($this->conn,$query);
		$tm = $result->fetch_assoc();


		$query = "SELECT * FROM tbl_cart_mixer 
		WHERE tbl_cart_mixer.session_id = '$sid' AND tbl_cart_mixer.tipo_mixer='$this->type'";
		if(!empty($this->mixId)){	
			if(!strstr($query,"WHERE")){
				$query .= " WHERE tbl_cart_mixer.id_mix = '$this->mixId' ";
			}else{
				$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
			}
		} else {
			$query .= " AND id_prod_creado=0 ";
		}
		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
							
					$rowMix=$result->fetch_assoc();
					$id_mix = $rowMix['id_mix'];
					$peso = $rowMix['peso'];
					$tipo_mixer = $rowMix['tipo_mixer'];

					$query = "SELECT * FROM tbl_cart_item_mixer 
					INNER JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
					INNER JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
					WHERE id_mix='$id_mix' AND tbl_variaciones_mixer.vm_tipo_mixer='$this->type'";
					$result=mysqli_query($this->conn,$query);

					$ingredientes = '';
					$peso_total = 0;
					$precio_total = 0;
					$haybase=false;

					$cntIng=$result->num_rows;
					//si hay ingredientes
					if($cntIng>0) {
						while ($row=$result->fetch_assoc()) {
								extract($row);

									$ingredientes .= '<div class="col-4 p-2 my-1';
									if ($es_base=='si') {
										$haybase=true;
										$ingredientes .= ' base';
										$precio_base = $pd_precio;
									} else {
										$peso_total += $cantidad * $pd_peso;
										$precio_total += $cantidad * $pd_precio;
									}
									$ingredientes .= '">
										<div class="recipe-list-item">
											<div class="recipe-list-item-background">';
											if (!empty($pd_icono)) {
												$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/'.$pd_icono.'">';
											} else {
												$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/sin-icono.svg">';
											}
											$ingredientes .= '</div>
											<span class="amount">';
											if ($cantidad>1) {$ingredientes .= $cantidad;}
											$ingredientes .= '</span>
											<p class="name">'.$pd_titulo.'</p>';
											if ($es_base=='no') {
												$ingredientes .= '<div class="d-flex justify-content-around align-items-center">
													<div><button type="button" class="btntrash" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button></div>
													<div><button type="button" class="btnmas" data-id="'.$id.'"><i class="fas fa-plus-circle"></i></button></div>
													<div><button type="button" class="btnmenos" data-id="'.$id.'"><i class="fas fa-minus-circle"></i></button></div>
												</div>';
											} 
										$ingredientes .= '</div>
									</div>';

						}


						//reduce el precio de la base según los ingredientes agregados
						if (isset($precio_base)) {
							$peso_base = $peso - $peso_total;
							$precio_base_reduc = ($peso_base * $precio_base) / 350;
							$precio_total += $precio_base_reduc;
						}

					//si no hay ingredientes y es el mix de granola
					} elseif($tipo_mixer==100) {
						$ingredientes .= '<div class="col-12 p-2 mb-2 text-primary">
								<div class="recipe-list-item">
									<p class="mensaje">Primero tenes que elegir una base y luego podes agregar otros ingredientes.</p>
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
					//si no hay ingredientes y es distinto al mix de granola
					} else {
						$ingredientes .= '<div class="col-12 p-2 mb-2 text-primary">
								<div class="recipe-list-item">
									<p class="mensaje">Ahora agrega los ingredientes que más te gusten</p>
								</div>
							</div>';
					}

				
				if ($haybase || (!empty($producto_id) && $tipo_mixer!=100) ) {
					echo '<div class="text-center btn-rotate pb-3"><a href="#val-nut" class="text-primary"><img src="'.WEB_ROOT.'img/rotate.svg" height="30" class="icon-rotate"></a></div>';
				}


				if ($rowMix['nombre']=='') {
					echo '<h2 class="title-tubo">Tu Mix '.$tm['tm_titulo'].'</h2>';
				} else {
					echo '<h2 class="title-tubo">Mix '.$rowMix['nombre'].'</h2>';
				}

				echo '<div class="ingredientes-tubo py-3 container-fluid">
							<div class="row">
								'.$ingredientes.'
							</div>
						</div>
						<div class="footer-tubo px-2">';
							if ($haybase || (!empty($producto_id) && $tipo_mixer!=100) ) {
								echo '<a href="#val-nut" class="btn btn-outline-dark w-100 rounded-0 my-1">Valores nutricionales</a>';

								if (!$haybase) {

									$libre = $peso - $peso_total;
									$completado = $peso_total;
									$percentComp = round(($peso_total*100)/$peso);

									echo '<p class="price-tubo mt-3">'.$completado.'g por $'.number_format($precio_total,2,',','.').'</p>';
									echo '<div class="progress mb-3">
									<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: '.$percentComp.'%;" aria-valuenow="'.$percentComp.'" aria-valuemin="0" aria-valuemax="100">'.$percentComp.'%</div>
								  </div>';
								  if ($completado==$peso) {
									echo '<a href="'.WEB_ROOT.'mixer-checkout.php?order='.$id_mix.'" class="btn btn-primary w-100 rounded-pill">Terminar</a>';
								  }
								} else {
									echo '<p class="price-tubo mt-3">'.$peso.'g por $'.number_format($precio_total,2,',','.').'</p>';
									echo '<a href="'.WEB_ROOT.'mixer-checkout.php?order='.$id_mix.'" class="btn btn-primary w-100 rounded-pill">Terminar</a>';
								}
								echo '<a href="#" data-id="'.$id_mix.'" class="btn btn-outline-dark btn-sm rounded-pill mt-3" id="vaciarmix"><i class="far fa-trash-alt"></i> Vaciar el Mix</a>';
							} else {
								echo '<p class="price-tubo">'.$peso.'g</p>';
							}
						
					echo '</div>';

		} else {
			echo '<h2 class="title-tubo">Tu Mix '.$tm['tm_titulo'].'</h2>';
		}
	}

	public function getMixerContentCheck()
	{
		$sid = session_id();
		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}

		$query = "SELECT * FROM tbl_cart_mixer 
		LEFT JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		LEFT JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_cart_mixer.tipo_mixer
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
		LEFT JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
		WHERE tbl_cart_mixer.session_id = '$sid' AND tbl_cart_mixer.tipo_mixer=tbl_variaciones_mixer.vm_tipo_mixer";

		if(!empty($this->mixId)){	
			if(!strstr($query,"WHERE")){
				$query .= " WHERE tbl_cart_mixer.id_mix = '$this->mixId' ";
			}else{
				$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
			}
		}

		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
							
					$ingredientes = '';
					$peso_total = 0;
					$precio_total = 0;
					$haybase=false;

					while ($row=$result->fetch_assoc()) {
							extract($row);

							$ingredientes .= '<div class="col-3 p-2 my-1">
									<div class="recipe-list-item">
										<div class="recipe-list-item-background">';
										if (!empty($pd_icono)) {
											$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/'.$pd_icono.'">';
										} else {
											$ingredientes .= '<img alt="'.$pd_titulo.'" title="'.$pd_titulo.'" src="'.WEB_ROOT.'img/iconos/sin-icono.svg">';
										}
										$ingredientes .= '</div>
										<span class="amount">';
										if ($cantidad>1) {$ingredientes .= $cantidad;}
										$ingredientes .= '</span>
										<p class="name">'.$pd_titulo.'</p>';
										
									$ingredientes .= '</div>
								</div>';

							if ($es_base=='si') {
								$precio_base = $pd_precio;
							} else {
								$peso_total += $cantidad * $pd_peso;
								$precio_total += $cantidad * $pd_precio;
							}

					}

					//reduce el precio de la base según los ingredientes agregados
					if (isset($precio_base)) {
						$peso_base = $peso - $peso_total;
						$precio_base_reduc = ($peso_base * $precio_base) / 350;
						$precio_total += $precio_base_reduc;
					}
					
				echo '<div class="ingredientes-mix py-3 container-fluid">
							<div class="row">
								'.$ingredientes.'
								<div class="col-12 pt-4"><a href="'.WEB_ROOT.'mixer/'.$tm_alias.'/order'.$this->mixId.'" class="btn btn-outline-primary btn-sm rounded-pill">Modificar ingredientes</a></div>
							</div>
						</div>
						<hr>
						<div class="footer-resumen-mix text-center">';

						echo '<p class="price-mix">'.$peso.'g. por $'.number_format($precio_total,2,',','.').'</p>
						<a href="#" onclick="addMixToCart('.$id_mix.');" class="btn btn-primary btn-lg rounded-pill"><img src="'.WEB_ROOT.'img/icon-cart-btn.svg" alt="Agregar al carrito" height="30"> Agregar al carrito</a>';
						
					echo '</div>';

		}
	}


	public function getValNutri()
	{
		$sid = session_id();
		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}
		if (isset($_REQUEST['type'])) {
			$this->type=mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		}

		$query = "SELECT * FROM tbl_cart_mixer 
		LEFT JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
        LEFT JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
		WHERE tbl_cart_mixer.session_id = '$sid' AND tbl_cart_mixer.tipo_mixer=tbl_variaciones_mixer.vm_tipo_mixer";

		if(!empty($this->mixId)){	
			$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
		} else {
			$query .= " AND id_prod_creado=0 AND tbl_cart_mixer.tipo_mixer='$this->type'";
		}
		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
			
			$porcion=40;
			$peso_ingredientes=0;
			$tieneBase = false;
			$SA = true;
			$V = true;
			$SL = true;

			$tot_kcal = 0;
			$tot_hidratos_carbono = 0;
			$tot_proteinas = 0;
			$tot_grasas_totales = 0;
			$tot_grasas_saturadas = 0;
			$tot_grasas_trans = 0;
			$tot_grasas_monoinsaturadas = 0;
			$tot_grasas_poliinsaturadas = 0;
			$tot_colesterol = 0;
			$tot_fibra_alimentaria = 0;
			$tot_sodio = 0;
			$ingredientes = array();

			while ($row=$result->fetch_assoc()) {
				extract($row);

				if ($producto_id) {

					if ($es_base=='si') {
						$tieneBase = true;
						$base_kcal = $kcal;
						$base_hidratos_carbono = $hidratos_carbono;
						$base_proteinas = $proteinas;
						$base_grasas_totales = $grasas_totales;
						$base_grasas_saturadas = $grasas_saturadas;
						$base_grasas_trans = $grasas_trans;
						$base_grasas_monoinsaturadas = $grasas_monoinsaturadas;
						$base_grasas_poliinsaturadas = $grasas_poliinsaturadas;
						$base_colesterol = $colesterol;
						$base_fibra_alimentaria = $fibra_alimentaria;
						$base_sodio = $sodio;
					} else {
						$peso_ingredientes += $cantidad * $pd_peso;

						$tot_kcal += ($pd_peso*$cantidad*$kcal)/100;
						$tot_hidratos_carbono += ($pd_peso*$cantidad*$hidratos_carbono)/100;
						$tot_proteinas += ($pd_peso*$cantidad*$proteinas)/100;
						$tot_grasas_totales += ($pd_peso*$cantidad*$grasas_totales)/100;
						$tot_grasas_saturadas += ($pd_peso*$cantidad*$grasas_saturadas)/100;
						$tot_grasas_trans += ($pd_peso*$cantidad*$grasas_trans)/100;
						$tot_grasas_monoinsaturadas += ($pd_peso*$cantidad*$grasas_monoinsaturadas)/100;
						$tot_grasas_poliinsaturadas += ($pd_peso*$cantidad*$grasas_poliinsaturadas)/100;
						$tot_colesterol += ($pd_peso*$cantidad*$colesterol)/100;
						$tot_fibra_alimentaria += ($pd_peso*$cantidad*$fibra_alimentaria)/100;
						$tot_sodio += ($pd_peso*$cantidad*$sodio)/100;
					}


					$arr_prop = explode("-", $pd_componentes);
					if (!in_array("SA", $arr_prop)) $SA = false;
					if (!in_array("V", $arr_prop)) $V = false;
					if (!in_array("SL", $arr_prop)) $SL = false;

					//ingredientes
					if (!empty($pd_icono)) {
						$ingredientes[] = array(
							"icono" => $pd_icono,
							"nombre" => $pd_titulo,
							"cantidad" => $cantidad
						);
					} else {
						$ingredientes[] = array(
							"icono" => 'sin-icono.svg',
							"nombre" => $pd_titulo,
							"cantidad" => $cantidad
						);
					}
					
				} 

			}

			if ($tieneBase) {
				//calculo el peso de base
				$peso_base = $peso - $peso_ingredientes;

				//Sumo a los totales los valores de la base
				$tot_kcal += ($peso_base*$base_kcal)/100;
				$tot_hidratos_carbono += ($peso_base*$base_hidratos_carbono)/100;
				$tot_proteinas += ($peso_base*$base_proteinas)/100;
				$tot_grasas_totales += ($peso_base*$base_grasas_totales)/100;
				$tot_grasas_saturadas += ($peso_base*$base_grasas_saturadas)/100;
				$tot_grasas_trans += ($peso_base*$base_grasas_trans)/100;
				$tot_grasas_monoinsaturadas += ($peso_base*$base_grasas_monoinsaturadas)/100;
				$tot_grasas_poliinsaturadas += ($peso_base*$base_grasas_poliinsaturadas)/100;
				$tot_colesterol += ($peso_base*$base_colesterol)/100;
				$tot_fibra_alimentaria += ($peso_base*$base_fibra_alimentaria)/100;
				$tot_sodio += ($peso_base*$base_sodio)/100;
			}
			

			//valores por porcion (Ej: $porcion=40g en este caso)
			$val_kcal = ($porcion * $tot_kcal) / $peso;
			$val_hidratos_carbono = ($porcion * $tot_hidratos_carbono) / $peso;
			$val_proteinas = ($porcion * $tot_proteinas) / $peso;
			$val_grasas_totales = ($porcion * $tot_grasas_totales) / $peso;
			$val_grasas_saturadas = ($porcion * $tot_grasas_saturadas) / $peso;
			$val_grasas_trans = ($porcion * $tot_grasas_trans) / $peso;
			$val_grasas_monoinsaturadas = ($porcion * $tot_grasas_monoinsaturadas) / $peso;
			$val_grasas_poliinsaturadas = ($porcion * $tot_grasas_poliinsaturadas) / $peso;
			$val_colesterol = ($porcion * $tot_colesterol) / $peso;
			$val_fibra_alimentaria = ($porcion * $tot_fibra_alimentaria) / $peso;
			$val_sodio = ($porcion * $tot_sodio) / $peso;


			//porcentaje de valores diarios
			$VD_Kcal = $val_kcal * 100 / 2000;
			$VD_Hidratos_carbono = $val_hidratos_carbono * 100 / 300;
			$VD_Proteinas = $val_proteinas * 100 / 75;
			$VD_Grasas_totales = $val_grasas_totales * 100 / 55;
			$VD_Grasas_saturadas = $val_grasas_saturadas * 100 / 22;
			$VD_Grasas_trans = '-';
			$VD_Grasas_monoinsaturadas = '-';
			$VD_Grasas_poliinsaturadas = '_';
			$VD_Colesterol = '_';
			$VD_Fibra_alimentaria = $val_fibra_alimentaria * 100 / 25;
			$VD_Sodio = $val_sodio * 100 / 2400;

			if ($VD_Proteinas>6) $FP = true; else $FP = false;
			if ($VD_Fibra_alimentaria>6) $AF = true; else $AF = false;


			$valoresNutricionales = array(
				"tabla" => array(
					"kcal" => array(
						"nombre" => 'Valor calórico',
						"unidad" => 'Kcal',
						"cantidad" => round($val_kcal, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Kcal, 1, PHP_ROUND_HALF_UP)
					),
					"hidratos_carbono" => array(
						"nombre" => 'Carbohidratos',
						"unidad" => 'g',
						"cantidad" => round($val_hidratos_carbono, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Hidratos_carbono, 1, PHP_ROUND_HALF_UP)
					),
					"proteinas" => array(
						"nombre" => 'Proteínas',
						"unidad" => 'g',
						"cantidad" => round($val_proteinas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Proteinas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_totales" => array(
						"nombre" => 'Grasas totales',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_totales, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_totales, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_saturadas" => array(
						"nombre" => 'Grasas Saturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_saturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_saturadas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_trans" => array(
						"nombre" => 'Grasas Trans',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_trans, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_trans, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_monoinsaturadas" => array(
						"nombre" => 'Grasas Monoinsaturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_monoinsaturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_monoinsaturadas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_poliinsaturadas" => array(
						"nombre" => 'Grasas Poliinsaturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_poliinsaturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_poliinsaturadas, 1, PHP_ROUND_HALF_UP)
					),
					"colesterol" => array(
						"nombre" => 'Colesterol',
						"unidad" => 'g',
						"cantidad" => round($val_colesterol, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Colesterol, 1, PHP_ROUND_HALF_UP)
					),
					"fibra_alimentaria" => array(
						"nombre" => 'Fibra alimentaria',
						"unidad" => 'g',
						"cantidad" => round($val_fibra_alimentaria, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Fibra_alimentaria, 1, PHP_ROUND_HALF_UP)
					),
					"sodio" => array(
						"nombre" => 'Sodio',
						"unidad" => 'g',
						"cantidad" => round($val_sodio, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Sodio, 1, PHP_ROUND_HALF_UP)
					)
				),
				"iconos" => array(
					"SA" => $SA,
					"V" => $V,
					"SL" => $SL,
					"FP" => $FP,
					"AF" => $AF
				),
				"ingredientes" => $ingredientes,
				"contenido-neto" => $peso
			);

			return $valoresNutricionales;

		} else {
			return false;
		}
	}
	

	public function estilosCategoriasMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->type);
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
			$this->type=mysqli_real_escape_string($this->conn, $this->type);
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

	public function navMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->type);
		} else {
			$this->type='granola';
		}

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_tipos_mixer` ORDER BY tm_orden ASC");

		while($cat=$result->fetch_assoc())
			{
				echo '<li class="nav-item">
						<a class="nav-link';

							if ($cat['tm_alias']==$this->type) {
								echo ' active';
							}

						echo '" href="'.WEB_ROOT.'mixer/'.$cat['tm_alias'].'/"><span>Mixer</span> '.$cat['tm_titulo'].'</a>
					</li>';
			}
	}

	public function getPacksList()
	{
		$sid = session_id();
		if (isset($_REQUEST['idmix'])) {
			$this->mixId = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['idmix'], ENT_QUOTES)));
		}
		if (isset($_REQUEST['type'])) {
			$this->type=mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['type'], ENT_QUOTES)));
		}

		$query = "SELECT peso FROM tbl_cart_mixer WHERE tbl_cart_mixer.session_id = '$sid'";

		if(!empty($this->mixId)){	
			$query .= " AND tbl_cart_mixer.id_mix = '$this->mixId' ";
		} else {
			$query .= " AND id_prod_creado=0 AND tipo_mixer='$this->type'";
		}
		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
			$row=$result->fetch_assoc();
			return $row['peso'];
		} else {
			return 0;
		}
	}

	public function productosMixer() {

		if(isset($_GET['type'])){
			$this->type=filter_input(INPUT_GET,'type', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->type=mysqli_real_escape_string($this->conn, $this->type);
		} else {
			$this->type='granola';
		}

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias_mixer` 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
		WHERE tm_alias='$this->type'");

		while($cat=$result->fetch_assoc())
			{
				$categoria=$cat['ct_id'];
				$tipo_mixer=$cat['tm_id'];

				echo '<div id="'.$cat['ct_alias'].'" style="min-height: 700px; margin-top:1px;">
						<div class="mixer-catalog">
							<h4 class="mixer-category-title" style="background-color:'.$cat['ct_color'].'">'.$cat['ct_titulo'].'</h4>
							<div class="mixer-ingredients-list">';

							$resultProd=mysqli_query($this->conn,"SELECT * FROM `tbl_productos_mixer` 
							INNER JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
							WHERE pd_categoria LIKE '%$categoria%' AND vm_tipo_mixer='$tipo_mixer'");
					
							while($prod=$resultProd->fetch_assoc())
								{
									echo '<div class="mixer-ingredient" style="border-left:12px solid '.$cat['ct_color'].'">
											<div class="d-flex flex-column flex-md-row ingredient-teaser">
												<div class="image-container">';
													if (!empty($prod['pd_img'])) {
														echo '<img class="img-mixer rounded-circle" alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/productos-mixer/'.$prod['pd_img'].'" width="100%">';
													} else {
														echo '<img class="img-mixer rounded-circle" alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/productos-mixer/sin-imagen.jpg" width="100%">';
													}
													
													if (!empty($prod['pd_icono'])) {
														echo '<div class="image-circle"><img alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/iconos/'.$prod['pd_icono'].'" width="36" height="36"></div>';
													} else {
														echo '<div class="image-circle"><img alt="'.$prod['pd_titulo'].'" title="'.$prod['pd_titulo'].'" src="'.WEB_ROOT.'img/iconos/sin-icono.svg"  width="36" height="36"></div>';
													}
												echo '</div>
												<div>
													<h3 class="title">'.$prod['pd_titulo'].'</h3>
													<div class="extra-information">$'.$prod['pd_precio'];
													if ($cat['ct_alias']!=='bases') {
														if ($prod['pd_peso']!=0) {
															echo '<span class="item-weight">︱'.$prod['pd_peso'].'g</span>';
														} else {
															echo '<span class="item-weight">︱<small>Cantidad necesaria</small></span>';
														}
														
													}
													echo '</div>';

													//boton agregar al mixer
													echo '<a class="btn btn-outline-primary btn-sm rounded-pill';
													if ($cat['ct_alias']=='bases') {
														echo ' addbases';
													} else {
														echo ' addingredientes';
													}
													echo '" data-id="'.$prod['pd_id'].'">Agregar al mix</a>';


													echo '<ul class="list-inline propiedades">';
														$arr_prop = explode("-", $prod['pd_componentes']);
														$arr_length = count($arr_prop);
														for($i=0;$i<$arr_length;$i++)
														{
															switch ($arr_prop[$i]) {
																case 'SA':
																	$titProp='Sin azúcares añadidos';
																	$imgProp=WEB_ROOT.'img/iconos-propiedades/sin-azucares-anadidos.svg';
																	echo '<li class="list-inline-item"><span data-toggle="tooltip" title="'.$titProp.'"><img alt="'.$titProp.'" width="30" height="30" src="'.$imgProp.'" class="img-responsive"></span></li>';
																	break;
																case 'V':
																	$titProp='Vegano';
																	$imgProp=WEB_ROOT.'img/iconos-propiedades/vegano.svg';
																	echo '<li class="list-inline-item"><span data-toggle="tooltip" title="'.$titProp.'"><img alt="'.$titProp.'" width="30" height="30" src="'.$imgProp.'" class="img-responsive"></span></li>';
																	break;
																case 'SL':
																	$titProp='Sin lactosa';
																	$imgProp=WEB_ROOT.'img/iconos-propiedades/sin-lactosa.svg';
																	echo '<li class="list-inline-item"><span data-toggle="tooltip" title="'.$titProp.'"><img alt="'.$titProp.'" width="30" height="30" src="'.$imgProp.'" class="img-responsive"></span></li>';
																	break;
																case 'AF':
																	$titProp='Alto en fibra';
																	$imgProp=WEB_ROOT.'img/iconos-propiedades/alto-en-fibra.svg';
																	echo '<li class="list-inline-item"><span data-toggle="tooltip" title="'.$titProp.'"><img alt="'.$titProp.'" width="30" height="30" src="'.$imgProp.'" class="img-responsive"></span></li>';
																	break;
																case 'FP':
																	$titProp='Fuente de proteina';
																	$imgProp=WEB_ROOT.'img/iconos-propiedades/fuente-de-proteina.svg';
																	echo '<li class="list-inline-item"><span data-toggle="tooltip" title="'.$titProp.'"><img alt="'.$titProp.'" width="30" height="30" src="'.$imgProp.'" class="img-responsive"></span></li>';
																	break;
															}
														}
													echo '</ul>
													<div class="item-information"><a href="#" data-toggle="modal" data-target="#masinfo" data-id="'.$prod['pd_id'].'" id="getMasinfo"><small>Más info ...</small></a></div>
												</div>
											</div>
										</div>';
								}

				echo '</div></div></div>';
			}
	}

	public function getMasInfo()
	{
		$this->productId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
		$this->productId = mysqli_real_escape_string($this->conn, $this->productId);
		
		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_productos_mixer` WHERE pd_id='$this->productId'");
		return $result->fetch_assoc();

	}

	public function traerMixPdf($mix,$token)
	{
		$this->mixId = mysqli_real_escape_string($this->conn, $mix);
		$this->token = mysqli_real_escape_string($this->conn, $token);

        $query = $this->conn->prepare("SELECT * FROM `tbl_cart_mixer`
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id = tbl_cart_mixer.tipo_mixer
		WHERE id_mix=? AND session_id=? ");
        $query->bind_param("is", $this->mixId, $this->token);
        if ($query->execute()) {
            if ($result=$query->get_result()) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
            $query->close();
		} else {
			return false;
		}
	}

	public function etiquetaMixPDF($mix,$token)
	{
		$this->mixId = mysqli_real_escape_string($this->conn, $mix);
		$this->token = mysqli_real_escape_string($this->conn, $token);

		$query = "SELECT * FROM tbl_cart_mixer 
		LEFT JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
        LEFT JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
		WHERE tbl_cart_mixer.session_id = '$this->token' 
		AND tbl_cart_mixer.tipo_mixer=tbl_variaciones_mixer.vm_tipo_mixer
		AND tbl_cart_mixer.id_mix = '$this->mixId'";

		$result=mysqli_query($this->conn,$query);
		$cnt_res=$result->num_rows;
		if($cnt_res>0) {
			
			$porcion=40;
			$peso_ingredientes=0;
			$tieneBase = false;
			$SA = true;
			$V = true;
			$SL = true;

			$tot_kcal = 0;
			$tot_hidratos_carbono = 0;
			$tot_proteinas = 0;
			$tot_grasas_totales = 0;
			$tot_grasas_saturadas = 0;
			$tot_grasas_trans = 0;
			$tot_grasas_monoinsaturadas = 0;
			$tot_grasas_poliinsaturadas = 0;
			$tot_colesterol = 0;
			$tot_fibra_alimentaria = 0;
			$tot_sodio = 0;
			$ingredientes = array();

			while ($row=$result->fetch_assoc()) {
				extract($row);

				if ($producto_id) {

					if ($es_base=='si') {
						$tieneBase = true;
						$base_kcal = $kcal;
						$base_hidratos_carbono = $hidratos_carbono;
						$base_proteinas = $proteinas;
						$base_grasas_totales = $grasas_totales;
						$base_grasas_saturadas = $grasas_saturadas;
						$base_grasas_trans = $grasas_trans;
						$base_grasas_monoinsaturadas = $grasas_monoinsaturadas;
						$base_grasas_poliinsaturadas = $grasas_poliinsaturadas;
						$base_colesterol = $colesterol;
						$base_fibra_alimentaria = $fibra_alimentaria;
						$base_sodio = $sodio;
					} else {
						$peso_ingredientes += $cantidad * $pd_peso;

						$tot_kcal += ($pd_peso*$cantidad*$kcal)/100;
						$tot_hidratos_carbono += ($pd_peso*$cantidad*$hidratos_carbono)/100;
						$tot_proteinas += ($pd_peso*$cantidad*$proteinas)/100;
						$tot_grasas_totales += ($pd_peso*$cantidad*$grasas_totales)/100;
						$tot_grasas_saturadas += ($pd_peso*$cantidad*$grasas_saturadas)/100;
						$tot_grasas_trans += ($pd_peso*$cantidad*$grasas_trans)/100;
						$tot_grasas_monoinsaturadas += ($pd_peso*$cantidad*$grasas_monoinsaturadas)/100;
						$tot_grasas_poliinsaturadas += ($pd_peso*$cantidad*$grasas_poliinsaturadas)/100;
						$tot_colesterol += ($pd_peso*$cantidad*$colesterol)/100;
						$tot_fibra_alimentaria += ($pd_peso*$cantidad*$fibra_alimentaria)/100;
						$tot_sodio += ($pd_peso*$cantidad*$sodio)/100;
					}


					$arr_prop = explode("-", $pd_componentes);
					if (!in_array("SA", $arr_prop)) $SA = false;
					if (!in_array("V", $arr_prop)) $V = false;
					if (!in_array("SL", $arr_prop)) $SL = false;

					//ingredientes
					if (!empty($pd_icono)) {
						$ingredientes[] = array(
							"icono" => $pd_icono,
							"nombre" => $pd_titulo,
							"cantidad" => $cantidad
						);
					} else {
						$ingredientes[] = array(
							"icono" => 'sin-icono.svg',
							"nombre" => $pd_titulo,
							"cantidad" => $cantidad
						);
					}
					
				} 

			}

			if ($tieneBase) {
				//calculo el peso de base
				$peso_base = $peso - $peso_ingredientes;

				//Sumo a los totales los valores de la base
				$tot_kcal += ($peso_base*$base_kcal)/100;
				$tot_hidratos_carbono += ($peso_base*$base_hidratos_carbono)/100;
				$tot_proteinas += ($peso_base*$base_proteinas)/100;
				$tot_grasas_totales += ($peso_base*$base_grasas_totales)/100;
				$tot_grasas_saturadas += ($peso_base*$base_grasas_saturadas)/100;
				$tot_grasas_trans += ($peso_base*$base_grasas_trans)/100;
				$tot_grasas_monoinsaturadas += ($peso_base*$base_grasas_monoinsaturadas)/100;
				$tot_grasas_poliinsaturadas += ($peso_base*$base_grasas_poliinsaturadas)/100;
				$tot_colesterol += ($peso_base*$base_colesterol)/100;
				$tot_fibra_alimentaria += ($peso_base*$base_fibra_alimentaria)/100;
				$tot_sodio += ($peso_base*$base_sodio)/100;
			}
			

			//valores por porcion (Ej: $porcion=40g en este caso)
			$val_kcal = ($porcion * $tot_kcal) / $peso;
			$val_hidratos_carbono = ($porcion * $tot_hidratos_carbono) / $peso;
			$val_proteinas = ($porcion * $tot_proteinas) / $peso;
			$val_grasas_totales = ($porcion * $tot_grasas_totales) / $peso;
			$val_grasas_saturadas = ($porcion * $tot_grasas_saturadas) / $peso;
			$val_grasas_trans = ($porcion * $tot_grasas_trans) / $peso;
			$val_grasas_monoinsaturadas = ($porcion * $tot_grasas_monoinsaturadas) / $peso;
			$val_grasas_poliinsaturadas = ($porcion * $tot_grasas_poliinsaturadas) / $peso;
			$val_colesterol = ($porcion * $tot_colesterol) / $peso;
			$val_fibra_alimentaria = ($porcion * $tot_fibra_alimentaria) / $peso;
			$val_sodio = ($porcion * $tot_sodio) / $peso;


			//porcentaje de valores diarios
			$VD_Kcal = $val_kcal * 100 / 2000;
			$VD_Hidratos_carbono = $val_hidratos_carbono * 100 / 300;
			$VD_Proteinas = $val_proteinas * 100 / 75;
			$VD_Grasas_totales = $val_grasas_totales * 100 / 55;
			$VD_Grasas_saturadas = $val_grasas_saturadas * 100 / 22;
			$VD_Grasas_trans = '-';
			$VD_Grasas_monoinsaturadas = '-';
			$VD_Grasas_poliinsaturadas = '_';
			$VD_Colesterol = '_';
			$VD_Fibra_alimentaria = $val_fibra_alimentaria * 100 / 25;
			$VD_Sodio = $val_sodio * 100 / 2400;

			if ($VD_Proteinas>6) $FP = true; else $FP = false;
			if ($VD_Fibra_alimentaria>6) $AF = true; else $AF = false;


			$valoresNutricionales = array(
				"tabla" => array(
					"kcal" => array(
						"nombre" => 'Valor calórico',
						"unidad" => 'Kcal',
						"cantidad" => round($val_kcal, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Kcal, 1, PHP_ROUND_HALF_UP)
					),
					"hidratos_carbono" => array(
						"nombre" => 'Carbohidratos',
						"unidad" => 'g',
						"cantidad" => round($val_hidratos_carbono, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Hidratos_carbono, 1, PHP_ROUND_HALF_UP)
					),
					"proteinas" => array(
						"nombre" => 'Proteínas',
						"unidad" => 'g',
						"cantidad" => round($val_proteinas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Proteinas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_totales" => array(
						"nombre" => 'Grasas totales',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_totales, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_totales, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_saturadas" => array(
						"nombre" => 'Grasas Saturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_saturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_saturadas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_trans" => array(
						"nombre" => 'Grasas Trans',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_trans, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_trans, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_monoinsaturadas" => array(
						"nombre" => 'Grasas Monoinsaturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_monoinsaturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_monoinsaturadas, 1, PHP_ROUND_HALF_UP)
					),
					"grasas_poliinsaturadas" => array(
						"nombre" => 'Grasas Poliinsaturadas',
						"unidad" => 'g',
						"cantidad" => round($val_grasas_poliinsaturadas, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Grasas_poliinsaturadas, 1, PHP_ROUND_HALF_UP)
					),
					"colesterol" => array(
						"nombre" => 'Colesterol',
						"unidad" => 'g',
						"cantidad" => round($val_colesterol, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Colesterol, 1, PHP_ROUND_HALF_UP)
					),
					"fibra_alimentaria" => array(
						"nombre" => 'Fibra alimentaria',
						"unidad" => 'g',
						"cantidad" => round($val_fibra_alimentaria, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Fibra_alimentaria, 1, PHP_ROUND_HALF_UP)
					),
					"sodio" => array(
						"nombre" => 'Sodio',
						"unidad" => 'g',
						"cantidad" => round($val_sodio, 1, PHP_ROUND_HALF_UP),
						"diario" => round($VD_Sodio, 1, PHP_ROUND_HALF_UP)
					)
				),
				"iconos" => array(
					"SA" => $SA,
					"V" => $V,
					"SL" => $SL,
					"FP" => $FP,
					"AF" => $AF
				),
				"ingredientes" => $ingredientes,
				"contenido-neto" => $peso
			);

			return $valoresNutricionales;

		} else {
			return false;
		}
	}
}

?>