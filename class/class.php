<?php
require_once 'envio.class.php';


class Varias
{
	protected static $texto;
	protected static $caracteres;
	protected static $filtro;
	protected static $titulo;
	
	public static function limpiar_txt($txt)
	{	
		self::$texto=$txt;
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'"," ");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","-");
		$result = str_replace($caracteres_raros, $caracteres_remp, self::$texto);
		$result=strtolower($result);
		return $result;
		
	}
	public static function LimitarCaracteres($text,$caract)
	{
		self::$texto=$text;
		self::$caracteres=$caract;
		if (strlen(self::$texto)>self::$caracteres) {
    		self::$texto = wordwrap(self::$texto, self::$caracteres, '<|*|*|>'); // separar en $max_long con ruptura sin cortar palabras. 
    		$posicion = strpos(self::$texto, '<|*|*|>'); // encontrar la primera aparición de la ruptura. 
    		self::$texto = substr(self::$texto, 0, $posicion).' ...'; // tomar la porción antes de la ruptura y agregar '...' 
		}
    	return self::$texto;
	}
	public static function TitProductos($text)
	{
		self::$texto=$text;
		if (strlen(self::$texto)>18) { 
    		self::$texto = substr(self::$texto, 0, 18).'...'; // tomar la porción antes de la ruptura y agregar '...' 
		}
    	return self::$texto;
	}
	public static function crear_url($tit)
	{

		self::$titulo=mb_strtolower($tit, 'UTF-8');
		self::$titulo = trim(self::$titulo);
        self::$titulo = str_replace( 
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), 
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), 
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), 
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), 
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), 
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), 
            self::$titulo
        ); 
        self::$titulo = str_replace( 
            array('ñ', 'Ñ', 'ç', 'Ç'), 
            array('n', 'N', 'c', 'C'), 
            self::$titulo
        ); 
        self::$titulo = str_replace(' ', '-', self::$titulo); 
        self::$titulo = str_replace('&', 'y', self::$titulo); 
        self::$titulo = str_replace( 
            array("", "¨", "º", "~", "#", "@", "|", "!",'"', "·", "$", "%", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "<", ";", ":" ,",", "."), 
            '', 
            self::$titulo
		); 
		self::$titulo=str_replace("----","-",self::$titulo);
		self::$titulo=str_replace("---","-",self::$titulo);
		self::$titulo=str_replace("--","-",self::$titulo);

        return self::$titulo; 
	}
	
}


class mainClass {
 
    private $conn;
	private $id_producto;
	private $alias_producto;
	private $arr_productos=array();
	private $cat;
	private $col;
	private $pag;
	private $articulo;
	private $proceso;
	private $id;
	private $cant;
	private $variacion;
	private $modelo;
 
    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->conn = $db->connect();
    }
	
	public function datosSeo($pagina)
	{
		$this->pag = mysqli_real_escape_string($this->conn, $pagina);
				
		$query = "SELECT * FROM `tbl_seo` WHERE seo_pagina='$this->pag'";
		$result=mysqli_query($this->conn,"$query");
		$cnt_res=$result->num_rows;

		if($cnt_res>0) {
			return $result->fetch_assoc();
		} else {
			$row = array();
			return $row;
		}
	}

	public function scriptsHead()
	{
		$result=mysqli_query($this->conn,"SELECT scr_head FROM `tbl_scripts`");
		$row=$result->fetch_assoc();
		$cnt_res=$result->num_rows;

		if($cnt_res>0) {
			echo $row['scr_head'];
		}
		
	}

	public function scriptsBody()
	{
		$result=mysqli_query($this->conn,"SELECT scr_body FROM `tbl_scripts`");
		$row=$result->fetch_assoc();
		$cnt_res=$result->num_rows;

		if($cnt_res>0) {
			echo $row['scr_body'];
		}
		
	}

	public function listaBuscador()
	{
				$result=mysqli_query($this->conn,"SELECT pd_titulo FROM `tbl_productos` 
				WHERE status='publicado'");
				
				while($reg=$result->fetch_assoc()) {
					$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðòóôõöøùúûýýþÿŔŕ"';
				    $modificadas = 'AAAAAAACEEEEIIIIDOOOOOOUUUUYbsaaaaaaaceeeeiiiidoooooouuuyybyRr ';
				    $titulo = utf8_decode($reg['pd_titulo']);
				    $titulo = strtr($titulo, utf8_decode($originales), $modificadas);
				    $titulo = utf8_encode($titulo);

					$list_productos[]= '"'.$titulo.'"';
				}
				$arr_buscador= implode(",", $list_productos);
				return $arr_buscador;
	}

	public function Market()
	{

		$query="SELECT DISTINCT(pd_id), pd_alias, pd_titulo, pd_etiqueta, pd_descuento FROM `tbl_productos`
		LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto 
		WHERE pd_destacado='si' AND status='publicado'";

		if(isset($_SESSION['mayoristas'])){	
			$query .= " AND pd_mayorista=1";
		} else {
			$query .= " AND pd_mayorista=0";
		}

		$query .= " ORDER BY pd_orden_dest ASC";

		$result=mysqli_query($this->conn,"$query");
		$cnt_res=$result->num_rows;

		if($cnt_res>0) {

			echo '<section class="market padding-section" id="market">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<h2 class="main_title">
									<picture>
                                        <source srcset="'.WEB_ROOT.'img/icon-market.svg" height="60" media="(min-width: 600px)">
                                        <img src="'.WEB_ROOT.'img/icon-market.svg" alt="Market" height="40" class="animate__animated animate__bounce animate__infinite"> 
                                    </picture>
								Market</h2>
							</div>
							<div id="car-novedades" class="owl-carousel owl-theme pt-5">';
		
			while($prod=$result->fetch_assoc())
			{
				echo '<div class="wrap-card">
						<div class="card">
							<figure class="effect-steve">';
							
							if ($prod['pd_descuento']!='0.00') {
								echo '<div class="etiq-descuento ';

								if ($prod['pd_descuento']<=10) {
									echo 'etiq-yellow';
								} elseif($prod['pd_descuento']>=10 and $prod['pd_descuento']<=20) {
									echo 'etiq-orange';
								} else {
									echo 'etiq-red';
								}
								echo '">'.round($prod['pd_descuento']).'% off</div>';
							}

							$query="SELECT im_nombre FROM `tbl_img` WHERE im_producto='{$prod['pd_id']}' ORDER BY im_orden,im_id ASC LIMIT 1";
							$result_img=mysqli_query($this->conn,"$query");
							$cnt_res=$result_img->num_rows;

							if ($cnt_res>0) {
								$img=$result_img->fetch_assoc();
								$imagen=WEB_ROOT.'img/productos/'.$img['im_nombre'];
							} else {
								$imagen=WEB_ROOT.'img/productos/sin-imagen.jpg';
							}


							echo '<img src="'.$imagen.'" alt="'.$prod['pd_titulo'].'" class="rounded-pill"/>
								<figcaption>
									<a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="main-link">ver '.$prod['pd_titulo'].'</a>
								</figcaption>
								<svg class="feather fa-2x"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#plus"/></svg>
							</figure>
							<div class="card-body info-producto">
								<h4 class="card-title">'.ucfirst(mb_strtolower($prod['pd_titulo'], 'UTF-8')).'</h4>';

									$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='{$prod['pd_id']}'";
									$result_pre=mysqli_query($this->conn,"$query");
									$precio=$result_pre->fetch_assoc();

									if ($prod['pd_descuento']!='0.00') {
										$descuento=($prod['pd_descuento']*$precio['minimo'])/100;
										$precioFinal=$precio['minimo']-$descuento;
										echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
												<div><p>$'.number_format($precioFinal,2,',','.').'</p>
												<small><del>$'.number_format($precio['minimo'],2,',','.').'</del></small></div>
												<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
											</div>';
									} else {
										$precioFinal=$precio['minimo'];
										echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
												<div><p>$'.number_format($precioFinal,2,',','.').'</p></div>
												<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
											</div>';
									}

								echo '</div>
							 </div>
						 </div>';

			}

			echo '</div></div></div></section>';
		}
		
	}



	public function ProductosHome()
	{
		$query="SELECT DISTINCT(pd_id), pd_alias, pd_titulo, pd_descuento FROM `tbl_productos`
		LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
		WHERE status='publicado' AND pd_exclusivo='si' ";

		if(isset($_SESSION['mayoristas'])){	
			$query .= " AND pd_mayorista=1";
		} else {
			$query .= " AND pd_mayorista=0";
		}

		$query .= " ORDER BY pd_orden_exclusivo ASC";

		$result=mysqli_query($this->conn,"$query");
        
		
		while($prod=$result->fetch_assoc())
		{
							echo '<div class="col-6 col-lg-3 pb-3 wrap-card">
									<div class="card">
										<figure class="effect-steve">';
										
										if ($prod['pd_descuento']!='0.00') {
											echo '<div class="etiq-descuento ';

											if ($prod['pd_descuento']<=10) {
												echo 'etiq-yellow';
											} elseif($prod['pd_descuento']>=10 and $prod['pd_descuento']<=20) {
												echo 'etiq-orange';
											} else {
												echo 'etiq-red';
											}
											echo '">'.round($prod['pd_descuento']).'% off</div>';
										}
						
										$query="SELECT im_nombre FROM `tbl_img` WHERE im_producto='{$prod['pd_id']}' ORDER BY im_orden,im_id ASC LIMIT 1";
										$result_img=mysqli_query($this->conn,"$query");
										$cnt_res=$result_img->num_rows;

										if ($cnt_res>0) {
											$img=$result_img->fetch_assoc();
											$imagen=WEB_ROOT.'img/productos/'.$img['im_nombre'];
										} else {
											$imagen=WEB_ROOT.'img/productos/sin-imagen.jpg';
										}

										echo '<img src="'.$imagen.'" alt="'.$prod['pd_titulo'].'"/>
											<figcaption>
												<a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="main-link">ver '.$prod['pd_titulo'].'</a>
											</figcaption>
											<svg class="feather fa-2x"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#plus"/></svg>
										</figure>
										<div class="card-body info-producto">
											<h4 class="card-title">'.ucfirst(mb_strtolower($prod['pd_titulo'], 'UTF-8')).'</h4>';

												$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='{$prod['pd_id']}'";
												$result_pre=mysqli_query($this->conn,"$query");
												$precio=$result_pre->fetch_assoc();

												if ($prod['pd_descuento']!='0.00') {
													$descuento=($prod['pd_descuento']*$precio['minimo'])/100;
													$precioFinal=$precio['minimo']-$descuento;
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p>
															<small><del>$'.number_format($precio['minimo'],2,',','.').'</del></small></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												} else {
													$precioFinal=$precio['minimo'];
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												}

										echo '</div>
									</div>
								</div>';

		}
		
	}


	public function breadcrumb() {

		if(isset($_GET['cat'])){
			$this->cat=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->cat=mysqli_real_escape_string($this->conn, $this->cat);
								
			$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_alias='$this->cat'");
			$cat=$result->fetch_assoc();

			$catId=$cat['ct_id'];
			$catAlias=$cat['ct_alias'];
			$catTitulo=$cat['ct_titulo'];
			$catPadre=$cat['ct_padre'];

            if ($catPadre==0) {
                echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/">Productos</a>
                <span class="breadcrumb-item active">'.$catTitulo.'</span>';
            } else {
				echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/">Productos</a>';

                $result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre' AND ct_id!='$catId'");
				$cat=$result->fetch_assoc();

				$catId1=$cat['ct_id'];
				$catAlias1=$cat['ct_alias'];
				$catTitulo1=$cat['ct_titulo'];
				$catPadre1=$cat['ct_padre'];

				if ($catPadre1==0) {
					echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias1.'/">'.$catTitulo1.'</a>
					<span class="breadcrumb-item active">'.$catTitulo.'</span>';
				} else {

					
					$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre1' AND ct_id!='$catId1'");
					$cat=$result->fetch_assoc();
	
					$catId2=$cat['ct_id'];
					$catAlias2=$cat['ct_alias'];
					$catTitulo2=$cat['ct_titulo'];
					$catPadre2=$cat['ct_padre'];

					if ($catPadre2==0) {
						echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias2.'/">'.$catTitulo2.'</a>
						<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias2.'/'.$catAlias1.'/">'.$catTitulo1.'</a>
						<span class="breadcrumb-item active">'.$catTitulo.'</span>';
					}
				}
			}
			
		} elseif(isset($_GET['buscar'])){
			echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'">Inicio</a>
			<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/">Productos</a>
			<span class="breadcrumb-item active">Resultados para "'.$_GET['buscar'].'"</span>';
        } else {
            echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'">Inicio</a>
			<span class="breadcrumb-item active">Productos</span>';
        }
	}
	
	public function breadcrumbFicha($cat,$tit) {

			$this->cat=mysqli_real_escape_string($this->conn, $cat);
			$this->articulo=mysqli_real_escape_string($this->conn, $tit);
								
			$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_alias='$this->cat'");
			$cat=$result->fetch_assoc();

			$catId=$cat['ct_id'];
			$catAlias=$cat['ct_alias'];
			$catTitulo=$cat['ct_titulo'];
			$catPadre=$cat['ct_padre'];

            if ($catPadre==0) {
				echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/">Productos</a>
				<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias.'/">'.$catTitulo.'</a>
                <span class="breadcrumb-item active">'.$this->articulo.'</span>';
            } else {
				echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/">Productos</a>';

                $result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre' AND ct_id!='$catId'");
				$cat=$result->fetch_assoc();

				$catId1=$cat['ct_id'];
				$catAlias1=$cat['ct_alias'];
				$catTitulo1=$cat['ct_titulo'];
				$catPadre1=$cat['ct_padre'];

				if ($catPadre1==0) {
					echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias1.'/">'.$catTitulo1.'</a>
					<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias1.'/'.$catAlias.'/">'.$catTitulo.'</a>
					<span class="breadcrumb-item active">'.$this->articulo.'</span>';
				} else {

					$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre1' AND ct_id!='$catId1'");
					$cat=$result->fetch_assoc();
	
					$catId2=$cat['ct_id'];
					$catAlias2=$cat['ct_alias'];
					$catTitulo2=$cat['ct_titulo'];
					$catPadre2=$cat['ct_padre'];

					if ($catPadre2==0) {
						echo '<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias2.'/">'.$catTitulo2.'</a>
						<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias2.'/'.$catAlias1.'/">'.$catTitulo1.'</a>
						<a class="breadcrumb-item" href="'.WEB_ROOT.'productos/'.$catAlias2.'/'.$catAlias1.'/'.$catAlias.'/">'.$catTitulo.'</a>
						<span class="breadcrumb-item active">'.$this->articulo.'</span>';
					}
				}
			}

    }


	public function filtrosCategorias() {

		if(isset($_GET['cat'])){
			$this->cat=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->cat=mysqli_real_escape_string($this->conn, $this->cat);

			$query = "SELECT * FROM `tbl_categorias` WHERE ct_alias='$this->cat'";
			if(isset($_SESSION['mayoristas'])){	
				$query .= " AND ct_mayorista=1";
			} else {
				$query .= " AND ct_mayorista=0";
			}
			$result=mysqli_query($this->conn,$query);
			$cat=$result->fetch_assoc();

			$catId=$cat['ct_id'];
			$catAlias=$cat['ct_alias'];
			$catTitulo=$cat['ct_titulo'];
			$catPadre=$cat['ct_padre'];

			$query = "SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId' AND ct_id!='$catId'";
			if(isset($_SESSION['mayoristas'])){	
				$query .= " AND ct_mayorista=1";
			} else {
				$query .= " AND ct_mayorista=0";
			}
			$result=mysqli_query($this->conn,$query);

			$cnt_res=$result->num_rows;

			if($cnt_res>0) {
				if($pos = strrpos($_SERVER["REQUEST_URI"],$catAlias.'/')) {
					$url=substr($_SERVER["REQUEST_URI"], 0,$pos);
				} 

				$resultTit=mysqli_query($this->conn,"SELECT ct_titulo FROM `tbl_categorias` WHERE ct_id='$catPadre' ");
				$cntTitPad=$resultTit->num_rows;
				if($cntTitPad>0) {
					$titPadre=$resultTit->fetch_assoc();
					echo '<h4 class="list-group-item"><a href="'.$url.'"><svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-left"/></svg> '.$titPadre['ct_titulo'].'</a></h4>';
				} else {
					echo '<h4 class="list-group-item pb-4"><a href="'.$url.'"><svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-left"/></svg> Categorías</a></h4>';
				}

				
				echo '<h4 class="list-group-item">'.$catTitulo.'</h4>';

				while($cat=$result->fetch_assoc())
				{
					$url=$_SERVER["REQUEST_URI"];

					echo '<a href="'.$url.$cat['ct_alias'].'/" class="list-group-item list-group-item-action">'.$cat['ct_titulo'].'</a>';
				}

			} else {
				if ($catPadre==0) {
					if($pos = strrpos($_SERVER["REQUEST_URI"],$catAlias.'/')) {
						$url=substr($_SERVER["REQUEST_URI"], 0,$pos);
					} 

					echo '<h4 class="list-group-item"><a href="'.$url.'"><svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-left"/></svg> Categorías</a></h4>';
				} else {
					$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre' AND ct_id!='$catId'");
					$cnt_res=$result->num_rows;

					if($cnt_res>0) {
						$cat=$result->fetch_assoc();

						if($pos = strrpos($_SERVER["REQUEST_URI"],$catAlias.'/')) {
							$url=substr($_SERVER["REQUEST_URI"], 0,$pos);
						} 

						echo '<h4 class="list-group-item"><a href="'.$url.'"><svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-left"/></svg> '.$cat['ct_titulo'].'</a></h4>';
					}
				}
				
			}
			

		} else {
			$query = "SELECT * FROM `tbl_categorias` WHERE ct_padre=0 ";
			if(isset($_SESSION['mayoristas'])){	
				$query .= " AND ct_mayorista=1";
			} else {
				$query .= " AND ct_mayorista=0";
			}
			$query .= " ORDER BY ct_orden ASC";
			$result=mysqli_query($this->conn,$query);
			
			echo '<h4 class="list-group-item">Categorías</h4>';
			while($cat=$result->fetch_assoc())
				{
					echo '<a href="'.WEB_ROOT.'productos/'.$cat['ct_alias'].'/" class="list-group-item list-group-item-action">'.$cat['ct_titulo'].'</a>';
				}
		}
	}

	public function filtrosVariacion($variacion) {

		$this->variacion=mysqli_real_escape_string($this->conn, $variacion);

		$query="SELECT DISTINCT(pr_valor) FROM `tbl_productos`
		LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
		LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id ";


		if(isset($_GET['cat'])){
			$this->cat=filter_input(INPUT_GET,'cat', FILTER_SANITIZE_SPECIAL_CHARS);
			$this->cat=mysqli_real_escape_string($this->conn, $this->cat);
			$arrayCategorias=$this->loopCategorias($this->cat);

			foreach ($arrayCategorias as $valor) {
				if(!strstr($query,"WHERE")){
					$query .= " WHERE ct_alias='$valor'";
				}else{
					$query .= " OR ct_alias='$valor'";
				}
			}
		}

		if(!strstr($query,"WHERE")){
			$query .= " WHERE pr_variacion='$this->variacion'";
		}else{
			$query .= " AND pr_variacion='$this->variacion'";
		}

		$result = mysqli_query($this->conn,"$query");
		$cnt_res=$result->num_rows;

		if ($cnt_res>0) {		
			echo '<div class="list-group pb-4">
					<h4 class="list-group-item">Colores</h4>
					<div class="list-group-item">
						<ul class="colores">';
						while($row=$result->fetch_assoc())
							{
								$valor=$row['pr_valor'];
								$resultVar=mysqli_query($this->conn,"SELECT * FROM `tbl_colores` WHERE col_nombre='$valor'");
								$var=$resultVar->fetch_assoc();
								echo '<li style="background-color: '.$var['col_hexa'].';"><a href="'.$_SERVER["REQUEST_URI"].'Color='.$var['col_alias'].'" data-toggle="tooltip" title="'.$valor.'">'.$valor.'</a></li>';
							}
			echo '</ul></div></div>';
		}
	}

	public $arrayAliasCat=array();

	public function loopCategorias($categoria)
	{
		$this->cat=$categoria;
		
		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_alias='$this->cat'");
		$cat=$result->fetch_assoc();

		$arrayAliasCat[]=$this->cat;
		$catId=$cat['ct_id'];

		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId' AND ct_id!='$catId'");

		while($cat1=$result->fetch_assoc())
			{
				$arrayAliasCat[]=$cat1['ct_alias'];
				$catId1=$cat1['ct_id'];

				$result2=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId1'");
				while($cat2=$result2->fetch_assoc())
					{
						$arrayAliasCat[]=$cat2['ct_alias'];
						$catId2=$cat2['ct_id'];

						$result3=mysqli_query($this->conn,"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId2'");
						while($cat3=$result3->fetch_assoc())
						{
							$arrayAliasCat[]=$cat3['ct_alias'];

						}
					}

			}

		return $arrayAliasCat;
	}


	public function GrillaProductos()
	{
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

		if($action == 'ajax'){

			$this->busqueda = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
			$this->cat = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['cat'], ENT_QUOTES)));
			$this->orden = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['orden'], ENT_QUOTES)));

			$query="SELECT DISTINCT(pd_id), pd_alias, pd_titulo, pd_etiqueta, pd_descuento, IF(pd_destacado='si', pd_orden_dest, 1000) AS orden FROM `tbl_productos`
			LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
			LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id ";


			if(!empty($this->cat)){
				$arrayCategorias=$this->loopCategorias($this->cat);
				foreach ($arrayCategorias as $valor) {
					if(!strstr($query,"WHERE")){
						$query .= " WHERE (ct_alias='$valor'";
					}else{
						$query .= " OR ct_alias='$valor'";
					}
				}
				$query .= ") ";
			}


			if(!empty($this->busqueda)){	
				if(!strstr($query,"WHERE")){
					$query .= " WHERE (pd_titulo LIKE '%$this->busqueda%' OR pd_descripcion LIKE '%$this->busqueda%')";
				}else{
					$query .= " AND (pd_titulo LIKE '%$this->busqueda%' OR pd_descripcion LIKE '%$this->busqueda%')";
				}
			}

			if(!strstr($query,"WHERE")){
				$query.=" WHERE status='publicado'";
			}else{
				$query.=" AND status='publicado'";
			}

			if(isset($_SESSION['mayoristas'])){	
				if(!strstr($query,"WHERE")){
					$query .= " WHERE pd_mayorista=1";
				}else{
					$query .= " AND pd_mayorista=1";
				}
			} else {
				if(!strstr($query,"WHERE")){
					$query .= " WHERE pd_mayorista=0";
				}else{
					$query .= " AND pd_mayorista=0";
				}
			}

			if(!empty($this->orden)){
				switch ($this->orden) {
					case 'novedad':
						$query.=" ORDER BY orden ASC";
						break;
					case 'alpha-ascending':
						$query.=" ORDER BY pd_titulo ASC";
						break;
					case 'alpha-descending':
						$query.=" ORDER BY pd_titulo DESC";
						break;
					case 'price-ascending':
						$query.=" ORDER BY pr_precio ASC";
						break;
					case 'price-descending':
						$query.=" ORDER BY pr_precio DESC";
						break;
				}
			}

			$query_paginado=$query;

			$count_query=mysqli_query($this->conn,"$query_paginado");
			$cnt_res=$count_query->num_rows;

			
			//pagination variables
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
			$per_page = intval($_REQUEST['per_page']); 
			$adjacents  = 4;
			$offset = ($page - 1) * $per_page;

			$numrows = $cnt_res;
			$total_pages = ceil($numrows/$per_page);
			
			$query.=" LIMIT $offset,$per_page";

			//main query to fetch the data
			$result = mysqli_query($this->conn,"$query");

			
			if ($numrows>0){
				
				$finales=0;
				
						while($prod=$result->fetch_assoc())
							{
								echo '<div class="col-6 col-md-4 col-xl-3 pb-3 wrap-card">
									<div class="card">
										<figure class="effect-steve">';
										
										if ($prod['pd_descuento']!='0.00') {
											echo '<div class="etiq-descuento ';

											if ($prod['pd_descuento']<=10) {
												echo 'etiq-yellow';
											} elseif($prod['pd_descuento']>=10 and $prod['pd_descuento']<=20) {
												echo 'etiq-orange';
											} else {
												echo 'etiq-red';
											}
											echo '">'.round($prod['pd_descuento']).'% off</div>';
										}
						
										$query="SELECT im_nombre FROM `tbl_img` WHERE im_producto='{$prod['pd_id']}' ORDER BY im_orden,im_id ASC LIMIT 1";
										$result_img=mysqli_query($this->conn,"$query");
										$cnt_res=$result_img->num_rows;

										if ($cnt_res>0) {
											$img=$result_img->fetch_assoc();
											$imagen=WEB_ROOT.'img/productos/'.$img['im_nombre'];
										} else {
											$imagen=WEB_ROOT.'img/productos/sin-imagen.jpg';
										}
		
										echo '<img src="'.$imagen.'" alt="'.$prod['pd_titulo'].'"/>
											<figcaption>
												<a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="main-link">ver '.$prod['pd_titulo'].'</a>
											</figcaption>
											<svg class="feather fa-2x"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#plus"/></svg>
										</figure>
										<div class="card-body info-producto">
											<h4 class="card-title">'.ucfirst(mb_strtolower($prod['pd_titulo'], 'UTF-8')).'</h4>';

												$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='{$prod['pd_id']}'";
												$result_pre=mysqli_query($this->conn,"$query");
												$precio=$result_pre->fetch_assoc();

												if ($prod['pd_descuento']!='0.00') {
													$descuento=($prod['pd_descuento']*$precio['minimo'])/100;
													$precioFinal=$precio['minimo']-$descuento;
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p>
															<small><del>$'.number_format($precio['minimo'],2,',','.').'</del></small></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												} else {
													$precioFinal=$precio['minimo'];
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												}

										echo '</div>
										 </div>
									 </div>';

								$finales++;
							}
				
							if ($total_pages>1) {
								echo $this->paginate( $page, $total_pages, $adjacents);
							}

			}	
		}

	}

	public function paginate($page, $tpages, $adjacents)
	{
		$prevlabel = '<svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-left"/></svg>';
		$nextlabel = '<svg class="feather fa-sm"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-right"/></svg>';
		$out = '<div class="col-12">
			<nav aria-label="Page navigation example" class="paginado-prod mt-5">
			<ul class="pagination justify-content-center">';
		
		// previous label

		if($page==1) {
			$out.= '<li class="page-item disabled"><a class="deco-none page-link">'.$prevlabel.'</a></li>';
		} else if($page==2) {
			$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load(1)">'.$prevlabel.'</a></li>';
		}else {
			$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load('.($page-1).')">'.$prevlabel.'</a></li>';
		}
		
		// first label
		if($page>($adjacents+1)) {
			$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load(1)">1</a></li>';
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= '<li class="page-item"><a class="deco-none page-link">...</a></li>';
		}

		// pages

		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= '<li class="page-item active"><a class="deco-none page-link">'.$i.'</a></li>';
			}else if($i==1) {
				$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load(1)">'.$i.'</a></li>';
			}else {
				$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load('.$i.')">'.$i.'</a></li>';
			}
		}

		// interval

		if($page<($tpages-$adjacents-1)) {
			$out.= '<li class="page-item"><a class="deco-none page-link">...</a></li>';
		}

		// last

		if($page<($tpages-$adjacents)) {
			$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load('.$tpages.')">'.$tpages.'</a></li>';
		}

		// next

		if($page<$tpages) {
			$out.= '<li class="page-item"><a class="deco-none page-link" href="#productos" onclick="load('.($page+1).')">'.$nextlabel.'</a></li>';
		}else {
			$out.= '<li class="page-item disabled"><a class="deco-none page-link">'.$nextlabel.'</a></li>';
		}
		
		$out.= '</ul></nav></div>';
		return $out;
	}
	
	
	public function FichaProducto($prod)
	{
		$string = mysqli_real_escape_string($this->conn, $prod);
		
		$pos = strpos($string, '-');
		$this->id_producto = substr($string, 0, $pos);
		$this->alias_producto = substr($string, $pos+1);

        $query = $this->conn->prepare("SELECT * FROM `tbl_productos` 
        INNER JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id
        WHERE pd_id=? AND pd_alias=? ");
        $query->bind_param("is", $this->id_producto, $this->alias_producto);
        if ($query->execute()) {
            if ($result=$query->get_result()) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
            $query->close();
		} 
	}
	

	public function FotosFicha($prod)
	{

			$this->id_producto = mysqli_real_escape_string($this->conn, $prod);
									
			$query = "SELECT im_nombre FROM `tbl_img` WHERE im_producto='$this->id_producto' ORDER BY im_orden,im_id ASC";
			$result=mysqli_query($this->conn,"$query");
			$cnt_res=$result->num_rows;


			echo '<div id="slider" class="slider-pro">
			<div class="sp-slides">';


			if ($cnt_res>0) {

				while($arr_fotos=$result->fetch_assoc())
					{
						echo '<div class="sp-slide">
							<div class="item-gallery">
								<div class="figcaption">
									<figure class="gallery-image">
										<div class="gallery-img">';
											$img=WEB_ROOT.'img/productos/fichas/'.$arr_fotos['im_nombre'];
											echo '<a href="'.$img.'" data-fancybox="gallery"><i data-feather="zoom-in"></i>
											<img alt="" src="css/images/blank.gif" data-src="'.$img.'"/>
											</a>
										</div>
									</figure>
								</div>
							</div></div>';
					}

			} else {
				echo '<div class="sp-slide">
							<div class="item-gallery">
								<div class="figcaption">
									<figure class="gallery-image">
										<div class="gallery-img">';
											$img=WEB_ROOT.'img/productos/fichas/sin-imagen.jpg';
											echo '<a href="'.$img.'" data-fancybox="gallery"><i data-feather="zoom-in"></i>
											<img alt="" src="css/images/blank.gif" data-src="'.$img.'"/>
											</a>
										</div>
									</figure>
								</div>
							</div></div>';
			}
			
			echo '</div>';

			if(($cnt_res)>1) {

				echo '<div class="sp-thumbnails">';
						mysqli_data_seek($result, 0);
						while($arr_fotos=$result->fetch_assoc())
							{
								$img=WEB_ROOT.'img/productos/'.$arr_fotos['im_nombre'];
								echo '<img src="'.WEB_ROOT.'css/images/blank.gif" data-src="'.$img.'" class="sp-thumbnail" alt=""/>';
							}
				echo '</div>';

			}
			echo '</div>';
	}

	private $ref;

	public function precioProd($idProd,$descuento)
	{
		$this->id_producto = mysqli_real_escape_string($this->conn, $idProd);
				
		$query="SELECT COUNT(pr_precio) as count FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
        $result_count=mysqli_query($this->conn,"$query");
		$count=$result_count->fetch_assoc();

		if (!$count['count']) {
			echo '<div class="price"><p>SIN STOCK</p></div>';
		} else {

			$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
	        $result_pre=mysqli_query($this->conn,"$query");
			$precio=$result_pre->fetch_assoc();


			if ($descuento!='0.00') {
				$descuento=($descuento*$precio['minimo'])/100;
				$precioFinal=$precio['minimo']-$descuento;

				echo '<div class="price">
						<p>$'.number_format($precioFinal,2,',','.').'</p>
						<small><del>'.number_format($precio['minimo'],2,',','.').'</del></small>
					</div>';

			} else {
				$precioFinal=$precio['minimo'];

				echo '<div class="price">
						<p>$'.number_format($precioFinal,2,',','.').'</p>
					</div>';
			}

		}
	}

	public function precioProdEnvio($idProd,$descuento)
	{
		$this->id_producto = mysqli_real_escape_string($this->conn, $idProd);
				
		$query="SELECT COUNT(pr_precio) as count FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
        $result_count=mysqli_query($this->conn,"$query");
		$count=$result_count->fetch_assoc();

		if (!$count['count']) {
			return 0;
		} else {

			$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
	        $result_pre=mysqli_query($this->conn,"$query");
			$precio=$result_pre->fetch_assoc();


			if ($descuento!='0.00') {
				$descuento=($descuento*$precio['minimo'])/100;
				$precioFinal=$precio['minimo']-$descuento;
			} else {
				$precioFinal=$precio['minimo'];
			}

			return $precioFinal;
		}
	}

	public function cuotasSinInteres($idProd,$descuento,$cuotas)
	{
		$this->id_producto = mysqli_real_escape_string($this->conn, $idProd);
				
		$query="SELECT COUNT(pr_precio) as count FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
        $result_count=mysqli_query($this->conn,"$query");
		$count=$result_count->fetch_assoc();

		if ($count['count']) {
			$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='$this->id_producto' AND pr_stock!=0";
	        $result_pre=mysqli_query($this->conn,"$query");
			$precio=$result_pre->fetch_assoc();

			if ($descuento) {
				$descuento=($descuento*$precio['minimo'])/100;
				$precioFinal=$precio['minimo']-$descuento;
				if ($cuotas>0) {
					$valorCuota=$precioFinal/$cuotas;
					echo '<p class="m-0"><small class="d-inline-block border border-secondary text-secondary py-1 px-3 m-0 mt-3"><span class="font-weight-bold">'.$cuotas.'</span> cuotas sin interés de <span class="font-weight-bold">$'.number_format($valorCuota,2,',','.').'</span></small></p>';
				}

			} else {
				$precioFinal=$precio['minimo'];
				if ($cuotas>0) {
					$valorCuota=$precioFinal/$cuotas;
					echo '<p class="m-0"><small class="d-inline-block border border-secondary text-secondary py-1 px-3 m-0 mt-3"><span class="font-weight-bold">'.$cuotas.'</span> cuotas sin interés de <span class="font-weight-bold">$'.number_format($valorCuota,2,',','.').'</span></small></p>';
				}
			}
		} 
	}

	public function DatosFicha($ref)
	{
				$this->ref = mysqli_real_escape_string($this->conn, $ref);
				

				$query = "SELECT * FROM  `tbl_productos` 
				LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
				WHERE pd_id='$this->ref' AND status='publicado'";
				$result=mysqli_query($this->conn,"$query");
				$prod=$result->fetch_assoc();

							echo '<form name="datcart" id="datcart">';
																	 
									echo '<h1>'.$prod['pd_titulo'].'</h1>';
									
									echo '<input type="hidden" name="prod" value="'.$prod['pd_id'].'">
											<div>
												<div class="act_prec"><input type="hidden" name="precio" id="precio" value="'.$prod['pr_id'].'"></div>';
												$this->precioProd($prod['pd_id'],$prod['pd_descuento']);
									echo '</div>';

									echo '<ul class="list-unstyled variaciones">';

												$htmlvariacion='';

			                                	$query="SELECT DISTINCT(pr_variacion) as pr_variacion FROM `tbl_productos_parent`
			                                	WHERE pr_producto='$this->ref' AND pr_stock!=0 ORDER BY pr_variacion ASC";
												$result_var=mysqli_query($this->conn,"$query");
												$row_col = $result_var->num_rows;
												$a=1;
												while($variacion=$result_var->fetch_assoc())
												{

													$var_act=$variacion["pr_variacion"];
													$query="SELECT DISTINCT(pr_valor) as pr_valor FROM `tbl_productos_parent`
						                            WHERE pr_producto='$this->ref' AND pr_variacion='$var_act' AND pr_stock!=0 ";
													$result_id=mysqli_query($this->conn,"$query");
													$cantValor = $result_id->num_rows;
													$i=1;
													
													while($valorVaria=$result_id->fetch_assoc())
													{

														if ($cantValor==1 and $valorVaria["pr_valor"]!='-') {
															$htmlvariacion.='<li class="pt-2"><strong>'.ucfirst(strtolower($variacion["pr_variacion"])).': </strong>
															<input type="hidden" id="variacion" name="variacion" class="custom-control-input" value="'.$valorVaria["pr_valor"].'" checked>
															'.$valorVaria["pr_valor"].'</li>';

														} elseif ($cantValor>1 and $valorVaria["pr_valor"]!='-') {
															$valAct=$valorVaria["pr_valor"];
															$query="SELECT pr_id FROM `tbl_productos_parent`
															WHERE pr_producto='$this->ref' AND pr_valor='$valAct' AND pr_stock!=0 ";
															$result_val=mysqli_query($this->conn,"$query");
															
															while($idvar=$result_val->fetch_assoc())
															{
																if ($i==1) {
																	$htmlvariacion.='<li class="pt-2"><strong>'.ucfirst(strtolower($variacion["pr_variacion"])).': </strong></li>';
																}

																$htmlvariacion.='<li class="cardCheckOptions">
																<div class="custom-control custom-radio custom-control-inline">
																<input onclick="actualizarPrec('.$idvar["pr_id"].');" type="radio" id="variacion'.$a.'" name="variacion" class="custom-control-input" value="'.$valorVaria["pr_valor"].'" '; 
																if ($idvar["pr_id"]==$prod['pr_id']) {
																	$htmlvariacion.='checked';
																}
																$htmlvariacion.='>
																<label class="custom-control-label" for="variacion'.$a.'">'.$valorVaria["pr_valor"].'</label>
																</div></li>';
																$a++;
															}
														}
														$i++;

													}
													
												}

												echo $htmlvariacion;

									echo '</ul>';

								if ($prod['pr_stock']>0) {
								
									echo '<div class="form-inline">
											<div class="input-group spinner">
												<label for="cant">Cantidad: </label>
												<input type="text" name="cant" id="cant" class="form-control box_cant" value="1" min="1">
												<div class="input-group-btn-vertical">
													<button class="btn btn-default" type="button"><svg class="feather"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-up"/></svg></button>
													<button class="btn btn-default" type="button"><svg class="feather"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#chevron-down"/></svg></button>
												</div>
											</div>
										</div>';

									if ($prod['pr_stock']==1) {
										echo '<small class="stock">Único disponible</small>';
									} else {
										echo '<small class="stock">'.$prod['pr_stock'].' disponibles</small>';
									}

									echo '<div class="sect-comp my-4">
											<a onclick="addToCart();" class="btn btn-primary btn-lg rounded-pill"><img src="'.WEB_ROOT.'img/icon-cart-btn.svg" alt="Agregar al carrito" height="30"> Agregar al carrito</a><br>
											<div class="error_addcart"></div>
										</div>';
								}

								if (!empty($prod['pd_descripcion'])) {
										
									echo '<div id="textmore" class="text-ficha">
											<p>'.$prod['pd_descripcion'].'</p>
										</div>';
								}

		                echo '</form>';
	}
	
	private $datosProd = array();

	public function actualizarFicha($id)
	{
				$this->id = $id;
				
				$query = "SELECT pd_id, pd_precio FROM  `tbl_productos` WHERE pd_id='$this->id'";					
				$result=mysqli_query($this->conn,"$query");

				$this->datosProd[]=$result->fetch_assoc();			
				return $this->datosProd;
	}

	public function actualizarPrecioFicha($id)
	{
				$this->id=mysqli_real_escape_string($this->conn, $id);
				
				$query = "SELECT pr_id, pr_precio, pr_codigo, pr_stock, pd_descuento FROM `tbl_productos_parent` 
				INNER JOIN tbl_productos ON tbl_productos.pd_id=tbl_productos_parent.pr_producto
				WHERE pr_id='$this->id' AND pr_stock!=0";					
				$result=mysqli_query($this->conn,"$query");

			
				$this->datosProd[] = $result->fetch_assoc();

				if ($this->datosProd[0]['pd_descuento']) {
					$descuento=($this->datosProd[0]['pd_descuento']*$this->datosProd[0]['pr_precio'])/100;
					$this->datosProd[0]['preciofinal'] = number_format($this->datosProd[0]['pr_precio']-$descuento,2,',','.');
					$this->datosProd[0]['precioorig'] = number_format($this->datosProd[0]['pr_precio'],2,',','.');
				} else {
					$this->datosProd[0]['preciofinal'] = number_format($this->datosProd[0]['pr_precio'],2,',','.');
					$this->datosProd[0]['precioorig'] = number_format($this->datosProd[0]['pr_precio'],2,',','.');
				}

				return $this->datosProd;
	}


	public function Relacionados($id,$cat)
	{
				$this->id_producto = mysqli_real_escape_string($this->conn, $id);
				$this->cat = mysqli_real_escape_string($this->conn, $cat);


				$query = "SELECT DISTINCT(pd_id), pd_alias, pd_titulo, pd_etiqueta, pd_descuento, IF(pd_destacado='si', pd_orden_dest, 1000) AS orden FROM `tbl_productos`
				LEFT JOIN tbl_productos_parent ON tbl_productos.pd_id = tbl_productos_parent.pr_producto
				LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id
				INNER JOIN tbl_img ON tbl_productos.pd_id = tbl_img.im_producto 
				WHERE pd_id!='$this->id_producto' AND pd_categoria='$this->cat' AND im_orden=1 AND status='publicado'";

				if(isset($_SESSION['mayoristas'])){	
					$query .= " AND pd_mayorista=1";
				} else {
					$query .= " AND pd_mayorista=0";
				}

				$query .= " ORDER BY orden ASC";

				$result=mysqli_query($this->conn,"$query");

				$row_cnt = $result->num_rows;

				if ($row_cnt) {
					
					echo '<section class="productos padding-section" id="productos">
							<div class="container-fluid">
								<div class="row justify-content-center">
									<div class="col-12 col-xl-10">
										<div class="row">
											<div class="col-12 pb-4 text-center">
												<h2 class="secondary_title"><img src="'.WEB_ROOT.'img/icon-relacionados.svg" alt="Relacionados" height="70"> Te puede interesar</h2>
											</div>';


					while($prod=$result->fetch_assoc())
					{
						echo '<div class="col-6 col-md-6 col-lg-3 pb-3 wrap-card">
									<div class="card">
										<figure class="effect-steve">';
										
										if ($prod['pd_descuento']!='0.00') {
											echo '<div class="etiq-descuento ';

											if ($prod['pd_descuento']<=10) {
												echo 'etiq-yellow';
											} elseif($prod['pd_descuento']>=10 and $prod['pd_descuento']<=20) {
												echo 'etiq-orange';
											} else {
												echo 'etiq-red';
											}
											echo '">'.round($prod['pd_descuento']).'% off</div>';
										}
										
										$query="SELECT im_nombre FROM `tbl_img` WHERE im_producto='{$prod['pd_id']}' ORDER BY im_orden,im_id ASC LIMIT 1";
										$result_img=mysqli_query($this->conn,"$query");
										$cnt_res=$result_img->num_rows;

										if ($cnt_res>0) {
											$img=$result_img->fetch_assoc();
											$imagen=WEB_ROOT.'img/productos/'.$img['im_nombre'];
										} else {
											$imagen=WEB_ROOT.'img/productos/sin-imagen.jpg';
										}
		
										echo '<img src="'.$imagen.'" alt="'.$prod['pd_titulo'].'"/>
											<figcaption>
												<a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="main-link">ver '.$prod['pd_titulo'].'</a>
											</figcaption>
											<svg class="feather fa-2x"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#plus"/></svg>
										</figure>
										<div class="card-body info-producto">
											<h4 class="card-title">'.ucfirst(mb_strtolower($prod['pd_titulo'], 'UTF-8')).'</h4>';

												$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='{$prod['pd_id']}'";
												$result_pre=mysqli_query($this->conn,"$query");
												$precio=$result_pre->fetch_assoc();

												if ($prod['pd_descuento']!='0.00') {
													$descuento=($prod['pd_descuento']*$precio['minimo'])/100;
													$precioFinal=$precio['minimo']-$descuento;
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p>
															<small><del>$'.number_format($precio['minimo'],2,',','.').'</del></small></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												} else {
													$precioFinal=$precio['minimo'];
													echo '<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center info-price">
															<div><p>$'.number_format($precioFinal,2,',','.').'</p></div>
															<div><a href="'.WEB_ROOT.'producto/'.$prod['pd_id'].'-'.$prod['pd_alias'].'" class="btn btn-outline-primary btn-sm mt-2 mt-lg-0"><svg class="feather fa-sm mr-1"><use xlink:href="'.WEB_ROOT.'img/feather-sprite.svg#shopping-cart"/></svg> Comprar</a></div>
														</div>';
												}

											echo '</div>
										 </div>
									 </div>';
					}
					echo '</div></div></div></div></section>';
				}
	}

	public function SlideHome()
	{
		$result=mysqli_query($this->conn,"SELECT * FROM `tbl_slides` ORDER BY sl_orden ASC");

		$row_cnt = $result->num_rows;
		echo '<ol class="carousel-indicators">';
		for($a=0; $a<$row_cnt; $a++){
			echo '<li data-target=".slide" data-slide-to="'.$a.'" '; if($a==0) { echo ' class="active"'; } echo '></li>';
		}
		echo '</ol>';

		$i=0;
		echo'<div class="carousel-inner" role="listbox">';

		while($row=$result->fetch_assoc())
		{
			echo '<div class="carousel-item '; if ($i==0) { echo 'active'; } echo '">
					<a href="'.$row["sl_link"].'">
					<picture>
                        <source srcset="img/slide/'.$row["sl_nombre"].'" media="(min-width: 600px)">
                        <img src="img/slide/'.$row["sl_nombre"].'" class="d-block w-100" alt="Mixme">
                    </picture>
					</a>
				</div>';

			$i++;
		}
		echo '<a class="carousel-control-prev" href=".carouselHome" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"><svg class="feather fa-3x"><use xlink:href="img/feather-sprite.svg#chevron-left"/></svg></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href=".carouselHome" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"><svg class="feather fa-3x"><use xlink:href="img/feather-sprite.svg#chevron-right"/></svg></span>
					<span class="sr-only">Next</span>
				</a>';
		echo'</div>';
	}

	public function insertarProceso($proceso)
	{
		$this->proceso=mysqli_real_escape_string($this->conn, $proceso);
		mysqli_query($this->conn,"INSERT INTO `tbl_procesos` (`pr_proceso`) VALUES ('$this->proceso')");
	}
	

	public function parseToXML($htmlStr) 
	{ 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&apos;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr); 
		return $xmlStr; 
	}

	private $arrProductos = array();

	public function FeedFacebook()
	{
		$query="SELECT pd_id, pd_titulo, pd_descripcion, pd_descuento, im_nombre, ct_titulo, ct_orden, IF(pd_destacado='si', pd_orden_dest, 1000) AS orden FROM `tbl_productos`
		INNER JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id
		INNER JOIN tbl_img ON tbl_productos.pd_id = tbl_img.im_producto 
		WHERE status='publicado' AND im_orden=1 ORDER BY orden, ct_orden ASC, pd_id DESC ";

		$result_prod=mysqli_query($this->conn,"$query");

		while($prod=$result_prod->fetch_assoc())
			{
				$descripcion=str_replace('<br>',', ',$prod['pd_descripcion']);
				$descripcion=str_replace('<br />',', ',$descripcion);
				$descripcion=strip_tags($descripcion);
				$prod['pd_descripcion']= $descripcion;

				$prod['url'] = HTTP_SERVER.'producto/'.$prod['pd_id'].'-'.Varias::crear_url($prod['pd_titulo']);
				$prod['imagen'] = HTTP_SERVER.'img/productos/fichas/'.$prod['im_nombre'];

				$query="SELECT MIN(pr_precio) as minimo FROM `tbl_productos_parent` WHERE pr_producto='{$prod['pd_id']}'";
				$result_pre=mysqli_query($this->conn,"$query");
				$precio=$result_pre->fetch_assoc();

				if ($prod['pd_descuento']) {
					$descuento=($prod['pd_descuento']*$precio['minimo'])/100;
					$precioFinal=$precio['minimo']-$descuento;
					$prod['precio']=number_format($precioFinal,2,'.','').' ARS';
				} else {
					$precioFinal=$precio['minimo'];
					$prod['precio']=number_format($precioFinal,2,'.','').' ARS';
				}
				$this->arrProductos[] = $prod;
			}

		return $this->arrProductos;

	}

	public function preguntas($id){

		$this->id_producto = mysqli_real_escape_string($this->conn, $id);

		$query = "SELECT * FROM comments WHERE comment_post_ID='$this->id_producto' AND comment_parent='0' AND comment_approved='contestada' ORDER BY comment_date DESC";
		$result=mysqli_query($this->conn,$query);
		$row_com = $result->num_rows;

		if ($row_com==0) {
			echo '<p>No hay preguntas. Sé el primero en preguntar.</p>';
        } else {

        	echo '<ul class="list-unstyled">';

			while($comentario=$result->fetch_assoc())
			{

				$idComentario=$comentario['comment_ID'];

				echo '<li class="media">
	                        <div class="mt-2">
								<i class="fas fa-comment fa-2x mr-3"></i>
	                        </div>
	                        <div class="media-body">
	                            <small class="comment-date">';
	                            setlocale(LC_ALL,"es_AR");
	                            echo utf8_encode(strftime("%d de %B de %Y", strtotime($comentario['comment_date'])));
	                            echo '</small>
	                            <p>'.$comentario['comment_content'].'</p>';

	                            $query = "SELECT * FROM comments WHERE comment_post_ID='$this->id_producto' AND comment_parent='$idComentario' AND comment_approved='contestada' ORDER BY comment_date DESC";
								$resultsub=mysqli_query($this->conn,$query);

								$row_sub = $resultsub->num_rows;

								if ($row_sub>0) {
									while($subComentario=$resultsub->fetch_assoc())
									{
										echo '<div class="media">
		                                        <div class="mt-2">
													<i class="fas fa-comments fa-2x mr-3"></i>
		                                        </div>
		                                        <div class="media-body">
		                                          <small class="comment-date">';
						                            setlocale(LC_ALL,"es_AR");
						                            echo utf8_encode(strftime("%d de %B de %Y", strtotime($subComentario['comment_date'])));
						                            echo '</small>
		                                          <p>'.$subComentario['comment_content'].'</p>
		                                        </div>
		                                      </div>';
									}
								}

	                        echo '</div>
	                </li>';									
			}
			echo '</ul>';

        }

	}
	
	private $nombre;
	private $email;
	private $pregunta;

	public function agregarPregunta()
	{
		
		$this->nombre = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['nombre'], ENT_QUOTES)));
		$this->email = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['email'], ENT_QUOTES)));
		$this->pregunta = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['pregunta'], ENT_QUOTES)));
		$this->id_producto = mysqli_real_escape_string($this->conn,(strip_tags($_REQUEST['producto'], ENT_QUOTES)));

		if (!empty($this->nombre) || !empty($this->email) || !empty($this->pregunta) || !empty($this->id_producto)) {
			$comment_date = date("Y-m-d H:i:s");

			if (mysqli_query($this->conn,"INSERT INTO `comments` (`comment_post_ID`, `comment_author`, `comment_author_email`, `comment_date`, `comment_content`, `comment_approved`) VALUES ('$this->id_producto','$this->nombre','$this->email','$comment_date', '$this->pregunta', 'pendiente')")) {
				echo '<div class="alert alert-success" role="alert">
				Su pregunta será respondida a la brevedad a su email.
			</div>';
			} else {
				echo '<div class="alert alert-danger" role="alert">
				La pregunta no pudo enviarse, intente nuevamente. 
			</div>';
			}
		}
		

	}


	public function aprobar_comentario($comment_date, $ap){
		$comment_date = mysqli_real_escape_string($this->conn, $comment_date);
		$ap = mysqli_real_escape_string($this->conn, $ap);

		mysqli_query($this->conn,"UPDATE `comments` SET `comment_approved`='$ap' WHERE `comment_date`='$comment_date' ");
	}


	private $arrDesc=array(); 

	public function getDescargas() {
        $result=mysqli_query($this->conn,"SELECT * FROM `tbl_descargas` ORDER BY des_orden ASC LIMIT 1");
		$row_cnt = $result->num_rows;
        if ($row_cnt > 0) {
            while($row = $result->fetch_assoc()) {
                $this->arrDesc[] = $row;
            }
        }
        return $this->arrDesc;
    }
}

 
?>