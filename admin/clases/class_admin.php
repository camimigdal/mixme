<?php 

abstract class Conexion
{
	public function con()
	{	
		//$puntero=new mysqli('localhost', 'root', 'root', 'bd_mixme');     //DATOS DE CONEXION LOCAL
		$puntero=new mysqli('localhost', 'zuaibkjh_webmaster', 'j8)}H~7xqJ]y', 'zuaibkjh_website');      //DATOS DE CONEXION SERVIDOR
		$puntero->set_charset("utf8");
		return $puntero;
	}
}


class LoginAdmin extends Conexion
{
	private $user_admin;
	private $pass_admin;
	private $arr_administradores=array(); 
	
	public function esAdmin($ad_usuario, $ad_password){
		
		$ad_usuario = mysqli_real_escape_string(parent::con(), $ad_usuario);
		$ad_password = mysqli_real_escape_string(parent::con(), $ad_password);
		
		$this->user_admin=$ad_usuario;
		$this->pass_admin=$ad_password;
		
		$result=mysqli_query(parent::con(),"SELECT * FROM administradores WHERE ad_usuario='$this->user_admin' ");
		
			
			$this->arr_administradores = $result->fetch_array();
			
			$password_from_db = $this->arr_administradores['ad_password'];
			
			if ( $password_from_db == $this->pass_admin ) {
				return $this->arr_administradores;
			} else return false;

	}
}

class Varias
{
	protected static $texto;
	protected static $filtro;
	protected static $titulo;
	
	public static function limitar_caracteres($text)
	{
		self::$texto=$text;
		if (strlen(self::$texto)>120) {
    		self::$texto = wordwrap(self::$texto, 120, '<|*|*|>'); // separar en $max_long con ruptura sin cortar palabras. 
    		$posicion = strpos(self::$texto, '<|*|*|>'); // encontrar la primera aparición de la ruptura. 
    		self::$texto = substr(self::$texto, 0, $posicion).'...'; // tomar la porción antes de la ruptura y agregar '...' 
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
	
	public static function nombreFotos($str)
	{
		self::$texto=mb_strtolower($str, 'UTF-8');
		self::$texto=str_replace(" ","",self::$texto);
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'",'"',"(",")","?","¿","¡","!","º",",",".",":",";","/");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","","","","","","","","","","","","","-");
 		return str_replace($caracteres_raros, $caracteres_remp, self::$texto);
	}

	public static function parseToText($htmlStr) 
	{ 
		self::$texto=$htmlStr;
		self::$texto=str_replace("'",'&apos;',self::$texto); 
		return self::$texto; 
	}
	
}

class Configuracion extends Conexion
{
	public function ComboCategorias($cat)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias WHERE ct_id!=0 ORDER BY ct_titulo ASC");
			echo '<option value="">Selecione la categoría</option>';
		while ($ct=$result->fetch_assoc())
		{
            echo '<option value="'.$ct["ct_id"].'" '; if (isset($cat) and $cat==$ct["ct_id"]) { echo 'selected'; }; echo ' >'.$ct["ct_titulo"]; echo ($ct["ct_mayorista"]==1) ? ' (Mayorista)' : '';  echo '</option>';
		}
	}

	public function ComboCategoriasMix($cat){
		$result = mysqli_query(parent::con(),"SELECT * FROM tbl_categorias_mixer 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer
		WHERE ct_id!=0 ORDER BY tm_titulo ASC");
		$arr_cat = explode(",", $cat);

		while ($row=$result->fetch_assoc())
		{
			if(in_array($row['ct_id'], $arr_cat)) { 	
				echo '<div class="checkbox"><label><input type="checkbox" name="categoria[]" value="'.$row['ct_id'].'" checked/> '.$row["ct_titulo"].' (Mixer '.$row["tm_titulo"].')</label></div>';
			} else {
				echo '<div class="checkbox"><label><input type="checkbox" name="categoria[]" value="'.$row['ct_id'].'"/> '.$row["ct_titulo"].' (Mixer '.$row["tm_titulo"].')</label></div>';
		  	}
		}
	}

	// public function ComboCategoriasMix($cat)
	// {
	// 	$result=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias_mixer WHERE ct_id!=0 ORDER BY ct_titulo ASC");
	// 		echo '<option value="">Selecione la categoría</option>';
	// 	while ($ct=$result->fetch_assoc())
	// 	{
    //         echo '<option value="'.$ct["ct_id"].'" '; if (isset($cat) and $cat==$ct["ct_id"]) { echo 'selected'; }; echo ' >'.$ct["ct_titulo"].'</option>';
	// 	}
	// }

	public function tipoMixer($tipo)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_tipos_mixer ORDER BY tm_orden ASC");
			echo '<option value="">Seleccione el Mixer</option>';
		while ($tip=$result->fetch_assoc())
		{
            echo '<option value="'.$tip["tm_id"].'" '; if (isset($tipo) and $tipo==$tip["tm_id"]) { echo 'selected'; }; echo ' >'.$tip["tm_titulo"].'</option>';
		}
	}

	public function Propiedades($arr_prop){

		$array = [
			['SA', 'Sin azúcares añadidos'],
			['V', 'Vegano'],
			['SL', 'Sin lactosa'],
			['AF', 'Alto en fibra'],
			['FP', 'Fuente de proteina'],
		];

		foreach ($array as list($val, $nombre)) {
			if(strstr($arr_prop, $val)) { 	
				echo '<div class="checkbox"><label><input type="checkbox" name="prop[]" value="'.$val.'" checked/> '.$nombre.'</label></div>';
			} else {
					echo '<div class="checkbox"><label><input type="checkbox" name="prop[]" value="'.$val.'"/> '.$nombre.'</label></div>';
			}
		}

	}

	public function ComboIconos($ico)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_iconos ORDER BY ic_titulo ASC");
			echo '<option value="">Selecione icono</option>';
		while ($ic=$result->fetch_assoc())
		{
            echo '<option value="'.$ic["ic_nombre"].'" '; if (isset($ico) and $ico==$ic["ic_nombre"]) { echo 'selected'; }; echo ' >'.$ic["ic_titulo"].'</option>';
		}
	}

	public function Modelos($modelo)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_modelos ORDER BY mo_nombre ASC");
			echo '<option value="">Sin modelo</option>';
		while ($mo=$result->fetch_assoc())
		{
            echo '<option value="'.$mo["mo_id"].'" '; if (isset($modelo) and $modelo==$mo["mo_id"]) { echo 'selected'; }; echo ' >'.$mo["mo_nombre"].'</option>';
		}
	}


	public function comboProvincias($prov)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM provincias ORDER BY provincia ASC");
			echo '<option value="">Selecione la provincia</option>';
		while ($pr=$result->fetch_assoc())
		{
            echo '<option value="'.$pr["id"].'" '; if (isset($prov) and $prov==$pr["id"]) { echo 'selected'; }; echo ' >'.$pr["provincia"].'</option>';
		}
	}


	public function ObtenerVariaciones($id_producto)
	{
		$this->id=$id_producto;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_productos_parent` WHERE pr_producto='$this->id' AND pr_variacion!='Color'");
		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {
			$i=1;
			while ($pr=$result->fetch_assoc())
			{
				echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_'.$i.'"><hr />
							
					<label for="varia" class="m-l-sm">Variación:</label>
					<input type="text" class="form-control" name="varia[]" value="'.$pr["pr_variacion"].'" size="5">

					<label for="valor" class="m-l-sm">Valor:</label>
					<input type="text" class="form-control" name="valor[]" value="'.$pr["pr_valor"].'" size="5">

					<label for="precio" class="m-l-sm">Precio:</label>
					<input type="text" class="form-control" name="precio[]" value="'.$pr["pr_precio"].'" size="8">
															
					<label for="stock" class="m-l-sm">Stock:</label>
					<input type="text" class="form-control" name="stock[]" value="'.$pr["pr_stock"].'" size="3">

					<label for="cod" class="m-l-sm">Código:</label>
					<input type="text" class="form-control" name="cod[]" value="'.$pr["pr_codigo"].'" size="12"> ';

					if ($row_cnt==$i) {
						echo '<input class="btn btn-primary bt_plus" id="'.$i.'" type="button" value="+ Agregar variación" />';
					} else {
						echo '<input class="btn btn-primary bt_menos" id="'.$i.'" type="button" value="- Quitar variación" />';
					}
					echo '<input type="hidden" class="form-control" name="cont[]" value="1" ></div>';
				$i++;
			}
		} else {
			echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_1"><hr />

					<label for="varia" class="m-l-sm">Variación:</label>
					<input type="text" class="form-control" name="varia[]" value="" size="5">

					<label for="valor" class="m-l-sm">Valor:</label>
					<input type="text" class="form-control" name="valor[]" value="" size="5">

					<label for="precio" class="m-l-sm">Precio:</label>
					<input type="text" class="form-control" name="precio[]" value="" size="8">

					<label for="stock" class="m-l-sm">Stock:</label>
					<input type="text" class="form-control" name="stock[]" value="" size="3">

					<label for="cod" class="m-l-sm">Código:</label>
					<input type="text" class="form-control" name="cod[]" value="" size="12">

					<input class="btn btn-primary bt_plus" id="1" type="button" value="+ Agregar variación" />
					<input type="hidden" class="form-control" name="cont[]" value="1" >
				</div>';
		}
		
	}

	public function variaciones()
	{
		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_codigo = $_POST['cod'];
		$arr_cont = $_POST['cont'];

		$arr_length_prod = count($arr_cont);

		$a=1;
		for($i=0;$i<$arr_length_prod;$i++) {

			echo '<div class="form-inline col-sm-10 col-sm-offset-2" id="div_'.$a.'"><hr />

                <label for="varia" class="m-l-sm">Variación:</label>
                <input type="text" class="form-control" name="varia[]" value="'.$arra_varia[$i].'" size="5">

				<label for="valor" class="m-l-sm">Valor:</label>
                <input type="text" class="form-control" name="valor[]" value="'.$arra_valor[$i].'" size="5">

                <label for="precio" class="m-l-sm">Precio:</label>
                <input type="text" class="form-control" name="precio[]" value="'.$arra_precio[$i].'" size="8">

                <label for="stock" class="m-l-sm">Stock:</label>
                <input type="text" class="form-control" name="stock[]" value="'.$arra_stock[$i].'" size="3">

                <label for="cod" class="m-l-sm">Código:</label>
                <input type="text" class="form-control" name="cod[]" value="'.$arra_codigo[$i].'" size="12"> ';

                if ($arr_length_prod==$a) {
                    echo '<input class="btn btn-primary bt_plus" id="'.$a.'" type="button" value="+ Agregar variación" />';
                } else {
                    echo '<input class="btn btn-primary bt_menos" id="'.$a.'" type="button" value="- Quitar variación" />';
                }
                echo '<input type="hidden" class="form-control" name="cont[]" value="1" ></div>';
            $a++;

		}
	}


}


class Productos extends Conexion
{
	public $id;
	public $linea;
	public $orden;
	public $imagen;
	private $output=array();
	public $proceso;


	public function lista()
	{
		$query="SELECT * FROM  `tbl_productos`
		LEFT JOIN tbl_categorias ON tbl_productos.pd_categoria=tbl_categorias.ct_id WHERE `status`!='mix' ";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$id=$row['pd_id'];
                $query="SELECT * FROM `tbl_img` WHERE im_producto='$id' AND im_orden=1";
                $resultImg=mysqli_query(parent::con(),"$query");
                $row_cnt = $resultImg->num_rows;
                if ($row_cnt>0) {
					$img=$resultImg->fetch_assoc();
					$row['im_nombre'] = '../img/productos/'.$img['im_nombre'];
				} else {
					$row['im_nombre'] = '../img/sin-imagen.jpg';
				}


				$categoria=$row['ct_id'];
				$resultCat=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias WHERE ct_id='$categoria' ");
				$ct=$resultCat->fetch_assoc();

					$nombreCat='';
					$catPadre1=$ct['ct_padre'];

					$nombreCat.=$ct['ct_titulo'];

					$result1=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre1' LIMIT 1");
					$cnt_res1=$result1->num_rows;

					if($cnt_res1>0) {
						$ct2=$result1->fetch_assoc();

						$catPadre2=$ct2['ct_padre'];
						$nombreCat.=' ('.$ct2['ct_titulo'];

						$result2=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_id='$catPadre2' LIMIT 1");
						$cnt_res2=$result2->num_rows;

						if($cnt_res2>0) {
							$ct3=$result2->fetch_assoc();

							$catPadre3=$ct3['ct_padre'];
							$nombreCat.='-'.$ct3['ct_titulo'];
						}
						$nombreCat.=')';
					}

					$row['ct_titulo']=$nombreCat;



				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_productos` set ".$nombre." = '".$value."' WHERE pd_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_productos` WHERE pd_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function Agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';
		if ( !empty($_POST['categoria']) )			$categoria = $_POST['categoria']; else return 'Seleccione la categoría';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripción';
		$descripcion=Varias::parseToText($descripcion);
		$descuento = $_POST['descuento'];
		if ( !empty($_POST['peso']) )			$peso = $_POST['peso']; else return 'ingrese el peso';
		if ( !empty($_POST['destacado']) ) 			$destacado = $_POST['destacado']; else $destacado = 'no';
		if ( !empty($_POST['exclusivo']) ) 			$exclusivo = $_POST['exclusivo']; else $exclusivo = 'no';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		if ( !empty($_POST['mayorista']) ) 			$mayorista = $_POST['mayorista']; else $mayorista = 0;

				
		$alias=Varias::crear_url($nombre);


		$variaVacio=false;


		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_cod = $_POST['cod'];

		if (isset($_POST['cont'])) { 
			$arra_prod = $_POST['cont'];
			$arr_length_pro = count($arra_prod);
			for($i=0;$i<$arr_length_pro;$i++)
				{
					if (empty($arra_varia[$i]) || empty($arra_valor[$i]) || empty($arra_precio[$i]) || empty($arra_stock[$i]) || empty($arra_cod[$i])) {
						$variaVacio=true;
					}
				}
		}

		if ($variaVacio) {
			return 'error en las variaciones';
		}

		$query="INSERT INTO `tbl_productos`(`pd_alias`, `pd_titulo`, `pd_descripcion`, `pd_categoria`, `pd_peso`, `pd_destacado`, `pd_exclusivo`, `pd_descuento`, `status`, `pd_mayorista`) VALUES ('$alias','$nombre','$descripcion','$categoria','$peso','$destacado','$exclusivo','$descuento','$estado','$mayorista')";

		if (mysqli_query(parent::con(),"$query")) {

			$result=mysqli_query(parent::con(),"SELECT pd_id FROM tbl_productos WHERE pd_alias='$alias' ORDER BY pd_id DESC LIMIT 1");
			$nid=$result->fetch_assoc();
				
			$id_new=$nid["pd_id"];
	
			if (!$variaVacio) {
				for($i=0;$i<$arr_length_pro;$i++)
				{
					mysqli_query(parent::con(),"INSERT INTO `tbl_productos_parent`(`pr_producto`, `pr_codigo`, `pr_precio`, `pr_variacion`, `pr_valor`, `pr_stock`) VALUES ('$id_new','$arra_cod[$i]','$arra_precio[$i]','$arra_varia[$i]','$arra_valor[$i]','$arra_stock[$i]')");
				}
			}

			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}
	
	public function EditarProducto($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';
		if ( !empty($_POST['categoria']) )			$categoria = $_POST['categoria']; else return 'Seleccione la categoría';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripción';
		$descripcion=Varias::parseToText($descripcion);
		if ( !empty($_POST['peso']) )			$peso = $_POST['peso']; else return 'ingrese el peso';
		$descuento = $_POST['descuento'];
		if ( !empty($_POST['destacado']) ) 			$destacado = $_POST['destacado']; else $destacado = 'no';
		if ( !empty($_POST['exclusivo']) ) 			$exclusivo = $_POST['exclusivo']; else $exclusivo = 'no';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		if ( !empty($_POST['mayorista']) ) 			$mayorista = $_POST['mayorista']; else $mayorista = 0;

		$alias=Varias::crear_url($nombre);

		$variaVacio=false;

		$arra_varia = $_POST['varia'];
		$arra_valor = $_POST['valor'];
		$arra_precio = $_POST['precio'];
		$arra_stock = $_POST['stock'];
		$arra_cod = $_POST['cod'];

		if (isset($_POST['cont'])) { 
			$arra_prod = $_POST['cont'];
			$arr_length_pro = count($arra_prod);
			for($i=0;$i<$arr_length_pro;$i++)
				{
					if (empty($arra_varia[$i]) || empty($arra_valor[$i]) || empty($arra_precio[$i]) || empty($arra_stock[$i]) || empty($arra_cod[$i])) {
						$variaVacio=true;
					}
				}
		}

		if ($variaVacio) {
			return 'error en las variaciones';
		}

		mysqli_query(parent::con(),"DELETE FROM `tbl_productos_parent` WHERE `pr_producto`='$this->id'");


		if (!$variaVacio) {
			for($i=0;$i<$arr_length_pro;$i++)
			{
				mysqli_query(parent::con(),"INSERT INTO `tbl_productos_parent`(`pr_producto`, `pr_codigo`, `pr_precio`, `pr_variacion`, `pr_valor`, `pr_stock`) VALUES ('$this->id','$arra_cod[$i]','$arra_precio[$i]','$arra_varia[$i]','$arra_valor[$i]','$arra_stock[$i]')");
			}
		}


		$query = "UPDATE `tbl_productos` set `pd_alias`='$alias', `pd_titulo`='$nombre',`pd_descripcion`='$descripcion',`pd_categoria`='$categoria',`pd_peso`='$peso',`pd_destacado`='$destacado',`pd_exclusivo`='$exclusivo',`pd_descuento`='$descuento',`status`='$estado',`pd_mayorista`='$mayorista' WHERE pd_id='$this->id'";
		
		if($result = mysqli_query(parent::con(), $query)) {
			return 'agregado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Borrar($id)
	{
		$this->id=$id;

		$sql="DELETE FROM `tbl_productos` WHERE pd_id='$this->id'";
		if($result = mysqli_query(parent::con(), $sql)) {
			return 'eliminado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Status($id,$acc) {
		
		$this->id=$id;
		$this->accion=$acc;
		mysqli_query(parent::con(),"UPDATE `tbl_productos` SET `status`='$this->accion' WHERE pd_id='$this->id' ");
		
		return "actualizado";
	}

	public function destacar($id,$val) {
		
		$this->id=$id;
		$this->val=$val;
		mysqli_query(parent::con(),"UPDATE `tbl_productos` SET `pd_destacado`='$this->val' WHERE pd_id='$this->id' ");
		
		return "actualizado";	
	}

	private $arr_n_fotos=array();

	public function BotonesImgProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		$_SESSION['id_producto']=$this->id;

		$result_prod=mysqli_query(parent::con(),"SELECT pd_id, ct_titulo FROM `tbl_productos` 
		INNER JOIN tbl_categorias ON tbl_categorias.ct_id=tbl_productos.pd_categoria WHERE pd_id='$this->id'");
		$prod=$result_prod->fetch_assoc();

		if($row_cnt==0){
			$nom_foto=$prod['pd_id'].'-mixme-'.Varias::crear_url($prod['ct_titulo']).'-'.date('YmdHms'); 

			echo '<p class="alert alert-danger">No hay fotos aplicadas a este producto</p><br><a href="upload_crop.php?nom_fot='.$nom_foto.'&orden=1" class="btn btn-danger btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar foto</a><hr>';
		} else {

			$ft=1;
			while($reg=$result->fetch_assoc()) {

					$info = pathinfo($reg["im_nombre"]);
					$nom_foto =  basename($reg["im_nombre"],'.'.$info['extension']);

					$n_foto=strrchr($nom_foto,"_");
					if ($n_foto) {
						$n_foto = substr($n_foto, 1);
					} else {
						$n_foto=0;
					}
					$arr_n_fotos[]=$n_foto;

            	$ft++;
			}

			$ft=max($arr_n_fotos)+1;
			$nom_foto=$prod['pd_id'].'-mixme-'.Varias::crear_url($prod['ct_titulo']).'-'.date('YmdHms').'_'.$ft; 
			$orden=$ft+1;
			echo '<a href="upload_crop.php?nom_fot='.$nom_foto.'&orden='.$orden.'" class="btn btn-success btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar otra foto</a><hr>';
		}
	}

	public function ImagenesProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				$info = pathinfo($row["im_nombre"]);
				$nom_foto =  basename($row["im_nombre"],'.'.$info['extension']);
                $row['nombreFot']=$nom_foto;

				$output[] = $row;
			}

			echo json_encode($output);
		}
	}

	public function ImagenPrincipal($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' ORDER BY im_orden ASC LIMIT 1");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				echo '<img id="thumbnil" src="../img/productos/'.$row["im_nombre"].'" class="img-thumbnail" width="100%"/>';

			}

		}
	}

	public function editarOrdenFoto($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_img` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function gestionImg($img,$id_prod,$orden) {
		
		$this->imagen=$img;
		$this->id=$id_prod;
		$this->orden=$orden;
			
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_producto='$this->id' AND im_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img`(`im_nombre`, `im_producto`, `im_orden`) VALUES ('$this->imagen','$this->id','$this->orden')");
			}

	}


	public function borrarImg($img) {
		$this->imagen=$img;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img` WHERE im_nombre='$this->imagen'")) {

			unlink("../img/productos/".$this->imagen);
			unlink("../img/productos/fichas/".$this->imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function listaOrdenDestaques()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		INNER JOIN tbl_img ON tbl_img.im_producto=tbl_productos.pd_id
		WHERE pd_destacado='si' AND im_orden=1 ORDER BY pd_orden_dest ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:100px; height: 100px;" src="../img/productos/'.$reg['im_nombre'].'"/></li>';
			$i++;
		}
	}
	public function reordenarDestaques($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden_dest = '$orden' WHERE pd_id = '$id' ");
	}

	public function listaOrdenExclusivos()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_productos 
		INNER JOIN tbl_img ON tbl_img.im_producto=tbl_productos.pd_id
		WHERE pd_exclusivo='si' AND im_orden=1 ORDER BY pd_orden_exclusivo ASC");
		
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["pd_id"].'"><img style="width:100px; height: 100px;" src="../img/productos/'.$reg['im_nombre'].'"/></li>';
			$i++;
		}
	}
	public function reordenarExclusivos($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_productos SET pd_orden_exclusivo = '$orden' WHERE pd_id = '$id' ");
	}

	public function ultimoProceso($proceso)
	{
		$this->proceso=mysqli_real_escape_string(parent::con(), $proceso);
		$result=mysqli_query(parent::con(),"SELECT MAX(pr_fecha) as fecha FROM `tbl_procesos` WHERE pr_proceso='$this->proceso' ");
		$reg=$result->fetch_assoc();

		echo $reg['fecha'];
	}

}

class Modelos extends Conexion
{
	public $id;
	public $accion;
	public $marca;

	public function lista()
	{

		$query="SELECT * FROM `tbl_modelos` ORDER BY mo_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		$nombresRel='';

			while ($row=$result->fetch_assoc())
			{
				if ($row['mo_relaciones']!='') {
					$arr_rel = explode(",", $row['mo_relaciones']);
					$arr_lengthrel = count($arr_rel);

					for($i=0;$i<$arr_lengthrel;$i++)
					{
						$query="SELECT * FROM `tbl_modelos` WHERE mo_id='$arr_rel[$i]'";
						$resultlin=mysqli_query(parent::con(),"$query");
						$rel=$resultlin->fetch_assoc();

						$nombresRel.=$rel["mo_nombre"].'<br>';
					}
				}
				$row['nombresRel']=$nombresRel;
				$output[] = $row;
				$nombresRel='';
			}
	
		echo json_encode($output);
	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_modelos` WHERE mo_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function editar($nombre,$value,$pk)
	{

		if ($nombre=='mo_relaciones') {
			$relacionados=array();

			foreach ($value as $clave => $val) {
				$relacionados[]=$val;
			}

			$strvalue = implode(",", $relacionados);

			$sql = "UPDATE `tbl_modelos` set ".$nombre." = '".$strvalue."' WHERE mo_id='".$pk."'";
			
			if($result = mysqli_query(parent::con(), $sql)) {
				echo 'Successfully! Record updated...';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}

		} else {

			$data = array();
			$sql = "UPDATE `tbl_modelos` set ".$nombre." = '".$value."' WHERE mo_id='".$pk."'";
			
			if($result = mysqli_query(parent::con(), $sql)) {
				if ($nombre=='mo_nombre') {
					$alias=Varias::crear_url($value);
					$query = "UPDATE `tbl_modelos` set mo_alias='$alias' WHERE mo_id='".$pk."'";
					$result = mysqli_query(parent::con(), $query);
				}
				echo 'Successfully! Record updated...';
			} else {
				die("error al actualizar '".$params["name"]."' with '".$params["value"]."'");
			}
		}
		
	}



	public function agregar()
	{
		
		// definimos las variables
		if (!empty($_POST['nombre']))			$nombre = $_POST['nombre']; else return 'Ingrese el nombre';
		$descripcion = $_POST['descripcion'];

		$alias=Varias::crear_url($nombre);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_modelos`(`mo_alias`, `mo_nombre`, `mo_descripcion`) VALUES ('$alias','$nombre','$descripcion')")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_modelos` WHERE mo_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}
	}

	public function listaOrdenModelos()
	{

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_modelos ORDER BY mo_orden ASC");
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-hover" id="elemento-'.$reg["mo_id"].'">'.$reg["mo_nombre"].'</li>';
			$i++;
		}
	}
	public function reordenarModelos($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_modelos SET mo_orden = '$orden' WHERE mo_id = '$id' ");
	}

	public function ComboRelaciones()
	{
		$query="SELECT `mo_id`, `mo_nombre` FROM `tbl_modelos` ORDER BY mo_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}
	
}

class Banners extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;

	public function gestionImg($img,$id) {
		
		$this->imagen=$img;
		$this->id=$id;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_banners_mod` WHERE bm_modelo='$this->id' AND bm_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_banners_mod`(`bm_nombre`, `bm_modelo`) VALUES ('$this->imagen','$this->id')");
			}
	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_modelos` WHERE mo_id='$this->id'";
		$result=mysqli_query(parent::con(),"$query");
		return $result->fetch_assoc();
	}

	public function getEmployees($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_banners_mod` WHERE bm_modelo='$this->id'";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_banners_mod` set ".$nombre." = '".$value."' WHERE bm_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_banners_mod` WHERE bm_nombre='$imagen'")) {
			unlink("../img/modelos/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/modelos/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_banners_mod` WHERE bm_nombre='$imagen'");
	}
}

class Slides extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;

	public function gestionImg($img) {
		
		$this->imagen=$img;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_slides` WHERE sl_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_slides`(`sl_nombre`) VALUES ('$this->imagen')");
			}
	}

	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_slides` ORDER BY sl_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_slides` set ".$nombre." = '".$value."' WHERE sl_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_slides` WHERE sl_nombre='$imagen'")) {
			unlink("../img/slide/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/slide/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_slides` WHERE sl_nombre='$imagen'");
	}
}

class Ordenes extends Conexion
{
	
	public $id;
	public $accion;
	public $status;
	
	public function listaOrdenes($status)
	{

		$this->status=mysqli_real_escape_string(parent::con(), $status);
		
		$query=	"SELECT * FROM ordenes 
		INNER JOIN status_ordenes ON status_ordenes.st_id=ordenes.or_estado
		INNER JOIN envio_orden ON envio_orden.id_orden=ordenes.id_orden";

		if(!empty($this->status)){
			if(!strstr($query,"WHERE")){
				$query .= " WHERE or_estado='$this->status'";
			}else{
				$query .= " AND or_estado='$this->status'";
			}
		} else {
			if(!strstr($query,"WHERE")){
				$query .= " WHERE or_estado>'1' AND or_estado<'7'";
			}else{
				$query .= " AND or_estado>'1' AND or_estado<'7'";
			}
		}

		if(!strstr($query,"WHERE")){
			$query.=" ORDER BY or_estado ASC, fecha_alta DESC";
		}else{
			$query.=" ORDER BY or_estado ASC, fecha_alta DESC";
		}
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{

				if ($reg["mayorista"]!=0) {
					$reg["mayorista"]= '<br><span class="label label-danger">Mayorista</span>';
				} else {
					$reg["mayorista"]= '';
				}

				if ($reg["or_medio_pago"]=='mp') {
					$reg["or_medio_pago"]= '<img src="assets/images/mp-icon.svg" width="18">';
				}elseif($reg["or_medio_pago"]=='tp'){
					$reg["or_medio_pago"]= '<img src="assets/images/tp-icon.svg" width="18">';
				}elseif($reg["or_medio_pago"]=='transferencia'){
					$reg["or_medio_pago"]= '<img src="assets/images/transf-icon.svg" width="18">';
				}elseif($reg["or_medio_pago"]=='efectivo'){
					$reg["or_medio_pago"]= '<img src="assets/images/money-icon.svg" width="18">';
				}

				switch ($reg["env_tipo"]) {
					case 'D':
						$reg["env_tipo"]='<i class="fa fa-truck fa-lg m-l-md"></i> ($'.$reg["env_valor"].')';
						break;
					
					case 'S':
						$reg["env_tipo"]='<i class="fa fa-home fa-lg m-l-md"></i> ($'.$reg["env_valor"].')';
						break;
				}

				$reg["fecha_alta"]= date("d M", strtotime($reg["fecha_alta"]));
				$reg["total_compra"]= number_format($reg["total_compra"],2,',','.');

				require_once("../../class/checkout.class.php");
				$ObjCheckout = new Checkout();
				$orderInfo = $ObjCheckout->GetOrderInfo($reg["id_orden"]);
				$orderContent = $ObjCheckout->GetOrderContent($reg["id_orden"]);
				$numItem=count($orderContent); 
				$cant_prod=0;
						
				for ($i=0; $i<$numItem; $i++) {
					extract($orderContent[$i]);
					$cant_prod += $cantidad;
				}

				$reg["productos"] = '<div class="dropdown">
				<a href="#" class="dropdown-toggle waves-effect waves-button waves-classic icon-cart-tabla" data-toggle="dropdown"><i class="glyphicon glyphicon-shopping-cart fa-lg m-r-xs"></i><span class="badge badge-danger pull-right">'.$cant_prod.'</span></a>
				<ul class="dropdown-menu title-caret dropdown-lg" role="menu">
					<li><p class="drop-title">'.$cant_prod.' Productos</p></li>
					<li class="dropdown-menu-list slimscroll messages">
						<ul class="list-unstyled">';
						
						for ($i=0; $i<$numItem; $i++) {
							extract($orderContent[$i]);
							$reg["productos"] .= '<li>
										<a href="#">
											<div class="msg-img"><img class="img-circle" src="../img/productos/'.$im_nombre.'" alt=""></div>
											<p class="msg-text">'.$pd_titulo.'</p>
											<p class="msg-time">('.$cantidad.') x '.number_format($precio,2,',','.').'</p>
										</a>
									</li>';
						}

				$reg["productos"] .= '</ul>
						</li>
					</ul>
				</div>';




				$output[] = $reg;
			}
	
		echo json_encode($output);
		
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `ordenes` set ".$nombre." = '".$value."' WHERE id_orden='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=="or_estado") {
				if ($value>3 && $value<7) {
					require_once("../../class/checkout.class.php");
					$ObjCheckout = new Checkout();
					$ObjCheckout->enviaEmailStatus($pk,$value);
				}
			}
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}
	
	
	public function statusOrden($id_orden,$status) {
		
		$this->id=$id_orden;
		$this->status=$status;
		
		$query = "UPDATE `ordenes` SET `or_estado`='$this->status' WHERE id_orden='$this->id'";
		if($result = mysqli_query(parent::con(), $query)) {

			if ($this->status>2 && $this->status<7) {
				require_once("../class/checkout.class.php");
				$ObjCheckout = new Checkout();
				$ObjCheckout->enviaEmailStatus($this->id,$this->status);
			}

			return 'agregado';
		} else {
			die();
		}
	}

	public function actualizarPago($id_orden)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id_orden);

		require_once("../class/checkout.class.php");
		$ObjCheckout = new Checkout();

		// definimos las variables
		if ( !empty($_POST['id_pago']) )			$id_pago = $_POST['id_pago']; else return 'Ingrese ID de pago';
		if ( !empty($_POST['forma_pago']) )			$forma_pago = $_POST['forma_pago']; else return 'Ingrese la forma de pago';
		if ( !empty($_POST['estado_pago']) )			$estado_pago = $_POST['estado_pago']; else return 'Seleccione el estado del pago';
		if ( !empty($_POST['total_pagado']) )			$total_pagado = $_POST['total_pagado']; else return 'Ingrese el total pagado';

			if($ObjCheckout->ActualizarOrder($this->id,$id_pago,$estado_pago,$forma_pago,$total_pagado,3)) {

				require_once("../class/checkout.class.php");
				$ObjCheckout = new Checkout();
				$ObjCheckout->enviaEmailStatus($this->id,3);

				return 'agregado';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}
	}

	public function enviarCodigo($id_orden)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id_orden);

		require_once("../class/checkout.class.php");
		$ObjCheckout = new Checkout();

		// definimos las variables
		if ( !empty($_POST['cod_seguimiento']) )			$cod_seguimiento = $_POST['cod_seguimiento']; else return 'Ingrese código de seguimiento';
		if ( !empty($_POST['link_seguimiento']) )			$link_seguimiento = $_POST['link_seguimiento']; else return 'Ingrese el link para el seguimiento';


		$query = "UPDATE `envio_orden` SET `env_seguimiento`='$cod_seguimiento', `env_link_seguimiento`='$link_seguimiento' WHERE id_orden='$this->id'";
		if($result = mysqli_query(parent::con(), $query)) {

			$ObjCheckout->enviaEmailCodigo($this->id,$cod_seguimiento,$link_seguimiento);

			return 'agregado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}
	
	public function comboEstados($id_orden,$estado) {
		
		$this->id=$id_orden;
		$this->status=$estado;
		
		$result=mysqli_query(parent::con(),"SELECT * FROM status_ordenes WHERE st_id='$this->status' ");
		$row=$result->fetch_assoc();
	
		

			switch ($row["st_id"]) {
				case '1':
					$color = 'btn-danger';
					$progresBar= '<div class="progress progress-sm">
						<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%"></div>
					</div>';
					break;
					case '2':
						$color = 'btn-danger';
						$progresBar= '<div class="progress progress-sm">
						<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
					</div>';
						break;
						case '3':
							$color = 'btn-info';
							$progresBar= '<div class="progress progress-sm">
								<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
							</div>';
							break;
							case '4':
								$color = 'btn-primary';
								$progresBar= '<div class="progress progress-sm">
									<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
								</div>';
								break;
								case '5':
									$color = 'btn-warning';
									$progresBar= '<div class="progress progress-sm">
										<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
									</div>';
									break;
									case '6':
										$color = 'btn-success';
										$progresBar= '<div class="progress progress-sm">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
										</div>';
										break;
										case '7':
											$color = 'btn-default';
											$progresBar= '<div class="progress progress-sm">
												<div class="progress-bar progress-bar-default" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
											</div>';
											break;
											case '8':
												$color = 'btn-danger';
												$progresBar= '<div class="progress progress-sm">
													<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
												</div>';
												break;
												case '9':
													$color = 'btn-danger';
													$progresBar= '<div class="progress progress-sm">
														<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
													</div>';
													break;
			}

			echo $progresBar;

			echo '<div class="btn-group"><button type="button" class="btn ';
			echo $color;
			
			echo ' btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$row["st_nombre"].' <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">';
			$result=mysqli_query(parent::con(),"SELECT * FROM status_ordenes");
			while ($reg=$result->fetch_assoc())
			{
				echo '<li';
				if ($reg["st_id"]==$this->status) {
					echo ' class="active"';
				}
				echo  '><a href="ver-orden.php?action='.$reg["st_id"].'&id_orden='.$this->id.'">'.$reg["st_nombre"].'</a></li>';
			}

        echo '</ul></div>';
                  	
		
	}

	public function countStatus($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(id_orden) as cant FROM `ordenes` WHERE or_estado='$this->status' ");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			echo $row['cant'];
		} else {
			echo 0;
		}

	}

	public function agregarNota($id)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id);

		// definimos las variables
		$notas = $_POST['notas'];

			$query = "UPDATE `ordenes` set `or_notas`='$notas' WHERE id_orden='$this->id'";
		
			if($result = mysqli_query(parent::con(), $query)) {
				return 'agregado';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}
	}



	public function traerFacturacion($orderId)
	{

		$query = "SELECT * FROM ordenes 
		INNER JOIN factura_orden ON factura_orden.id_orden=ordenes.id_orden 
		WHERE ordenes.id_orden = '$orderId'";
		$result=mysqli_query(parent::con(),$query);
		return $result->fetch_assoc();
	}

	public function traerDatosTransferencia()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_datos_transferencia` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function datosTransferencia()
	{
		
		// definimos las variables
		if ( !empty($_POST['banco']) )			$banco = $_POST['banco']; else return 'Ingrese el nombre del banco';
		if ( !empty($_POST['tipo']) )			$tipo = $_POST['tipo']; else return 'Ingrese el tipo de cuenta'; 
		if ( !empty($_POST['num_cuenta']) )			$num_cuenta = $_POST['num_cuenta']; else return 'Ingrese el número de cuenta';
		if ( !empty($_POST['cbu']) )			$cbu = $_POST['cbu']; else return 'Ingrese el CBU';
		if ( !empty($_POST['titular']) )			$titular = $_POST['titular']; else return 'Ingrese el titular o razón social';
		if ( !empty($_POST['cuit']) )			$cuit = $_POST['cuit']; else return 'Ingrese el CUIT';

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_datos_transferencia` SET `banco`='$banco',`tipo`='$tipo',`num_cuenta`='$num_cuenta',`cbu`='$cbu',`titular`='$titular',`cuit`='$cuit' WHERE id=1")) {

			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}

	public function traerCuotas()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_cuotas` WHERE id=1");
		return $result->fetch_assoc();
	}
	
	public function editarCuotas()
	{
		
		// definimos las variables
		$cuotas = $_POST['cuotas']; 

		// si no hay errores
		if (mysqli_query(parent::con(),"UPDATE `tbl_cuotas` SET `cuotas`='$cuotas' WHERE id=1")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente luego';
		}
	}
	
}

class Categorias extends Conexion
{
	public $id;
	public $accion;

	public function CategoriasNestable()
	{
		//Nestable
		if ($_POST['whichnest'] == 'nestable'){

			//Creating from_db unnested array
			$from_db = array();

			$sql = parent::con() -> prepare("SELECT ct_id, ct_padre, ct_orden FROM tbl_categorias WHERE ct_padre != ''");
			$sql -> execute();
			$sql -> bind_result($ct_id,$ct_padre,$ct_orden);

			while($sql -> fetch()) {
				$from_db[$ct_id] = ['parent'=>$ct_padre,'order'=>$ct_orden];
			}


			//Function to create id =>[ order , parent] unnested array
			function run_array_parent($array,$parent){  
				$post_db = array();     
				foreach($array as $head => $body){
					if(isset($body['children'])){
						$head++;
						$post_db[$body['id']] = ['parent'=>$parent,'order'=>$head];
						$post_db = $post_db + run_array_parent($body['children'],$body['id']);
					}else{
						$head++;
						$post_db[$body['id']] = ['parent'=>$parent,'order'=>$head]; 
					}
				}

				return $post_db;
			}

			//Creating the post_db unnested array
			$post_db = array();
			$array = json_decode($_POST['output'],true);
			$post_db = run_array_parent($array,'0');


			//Comparing the arrays and adding changed values to $to_db
			$to_db =array();

			foreach($post_db as $key => $value){
				if( !array_key_exists($key,$from_db) || ($from_db[$key]['parent'] != $value['parent']) || ($from_db[$key]['order'] != $value['order'])){
					$to_db[$key] = $value;
				}   
			}

			//Creating the DB query
			if (count($to_db) > 0){
				$query = "UPDATE tbl_categorias";
				$query_parent = " SET ct_padre = CASE ct_id";
				$query_order = " ct_orden = CASE ct_id";
				$query_ids = " WHERE ct_id IN (".implode(", ",array_keys($to_db)).")";

				foreach ($to_db as $id => $value){
					$query_parent .= " WHEN ".$id." THEN ".$value['parent'];
					$query_order .= " WHEN ".$id." THEN ".$value['order'];
				}
				$query_parent .= " END,";
				$query_order .= " END"; 

				$query = $query.$query_parent.$query_order.$query_ids;

				//Executing query
				mysqli_query(parent::con(),"$query");
			}
		}
	}

	public function loopCategorias()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_padre=0 ORDER BY ct_orden ASC");

		while($cat=$result->fetch_assoc())
			{

				echo '<li class="dd-item" data-id="'.$cat['ct_id'].'">
						<div class="dd-handle dd3-handle"></div><div class="dd3-content">
						<a href="#" data-name="ct_titulo" id="ct_titulo" data-type="text" data-pk="'.$cat['ct_id'].'" class="editable editable-click">'.$cat['ct_titulo'].'</a>
						<a href="#" data-name="ct_mayorista" id="ct_mayorista" data-type="select" data-pk="'.$cat['ct_id'].'" data-value="'.$cat['ct_mayorista'].'" class="editable editable-click m-l-lg">'; echo ($cat['ct_mayorista']==0) ? 'Minorista' : 'Mayorista'; echo '</a>
						<a href="categorias.php?action=delete&id='.$cat['ct_id'].'" data-confirm="Está seguro que desea eliminar?" class="text-danger m-l-lg"><i class="fa fa-trash"></i></a>
						</div>';
				

						$catId=$cat['ct_id'];
						$result1=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId' AND ct_id!='$catId' ORDER BY ct_orden ASC");
						$cnt_res1=$result1->num_rows;
						if($cnt_res1>0) {
							echo '<ol class="dd-list">';
							while($cat1=$result1->fetch_assoc())
								{

									echo '<li class="dd-item" data-id="'.$cat1['ct_id'].'">
											<div class="dd-handle dd3-handle"></div><div class="dd3-content">
											<a href="#" data-name="ct_titulo" id="ct_titulo" data-type="text" data-pk="'.$cat1['ct_id'].'" class="editable editable-click">'.$cat1['ct_titulo'].'</a> 
											<a href="#" data-name="ct_mayorista" id="ct_mayorista" data-type="select" data-pk="'.$cat1['ct_id'].'" data-value="'.$cat1['ct_mayorista'].'" class="editable editable-click m-l-lg">'; echo ($cat1['ct_mayorista']==0) ? 'Minorista' : 'Mayorista'; echo '</a>
											<a href="categorias.php?action=delete&id='.$cat1['ct_id'].'" data-confirm="Está seguro que desea eliminar?" class="text-danger m-l-lg"><i class="fa fa-trash"></i></a>
											</div>';

													$catId1=$cat1['ct_id'];
													$result2=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId1' ORDER BY ct_orden ASC");
													$cnt_res2=$result2->num_rows;
													if($cnt_res2>0) {
														echo '<ol class="dd-list">';
														while($cat2=$result2->fetch_assoc())
															{
																
																echo '<li class="dd-item" data-id="'.$cat2['ct_id'].'">
																		<div class="dd-handle dd3-handle"></div><div class="dd3-content">
																		<a href="#" data-name="ct_titulo" id="ct_titulo" data-type="text" data-pk="'.$cat2['ct_id'].'" class="editable editable-click">'.$cat2['ct_titulo'].'</a> 
																		<a href="#" data-name="ct_mayorista" id="ct_mayorista" data-type="select" data-pk="'.$cat2['ct_id'].'" data-value="'.$cat2['ct_mayorista'].'" class="editable editable-click m-l-lg">'; echo ($cat2['ct_mayorista']==0) ? 'Minorista' : 'Mayorista'; echo '</a>
																		<a href="categorias.php?action=delete&id='.$cat2['ct_id'].'" data-confirm="Está seguro que desea eliminar?" class="text-danger m-l-lg"><i class="fa fa-trash"></i></a>
																		</div>';

																				$catId2=$cat2['ct_id'];
																				$result3=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias` WHERE ct_padre='$catId2' ORDER BY ct_orden ASC");
																				$cnt_res3=$result3->num_rows;
																				if($cnt_res3>0) {
																					echo '<ol class="dd-list">';
																					while($cat3=$result3->fetch_assoc())
																					{
																						echo '<li class="dd-item" data-id="'.$cat3['ct_id'].'">
																								<div class="dd-handle dd3-handle"></div><div class="dd3-content">
																								<a href="#" data-name="ct_titulo" id="ct_titulo" data-type="text" data-pk="'.$cat3['ct_id'].'" class="editable editable-click">'.$cat3['ct_titulo'].'</a> 
																								<a href="#" data-name="ct_mayorista" id="ct_mayorista" data-type="select" data-pk="'.$cat3['ct_id'].'" data-value="'.$cat3['ct_mayorista'].'" class="editable editable-click m-l-lg">'; echo ($cat3['ct_mayorista']==0) ? 'Minorista' : 'Mayorista'; echo '</a>
																								<a href="categorias.php?action=delete&id='.$cat3['ct_id'].'" data-confirm="Está seguro que desea eliminar?" class="text-danger m-l-lg"><i class="fa fa-trash"></i></a>
																								</div>';
																						echo '</li>'; //cierrra 4 nivel

																					}
																					echo '</ol>'; //cierrra 4 nivel
																				}

																echo '</li>'; //cierrra 3 nivel
															}
														echo '</ol>'; //cierrra 3 nivel
													}

									echo '</li>'; //cierrra 2 nivel
								}
							echo '</ol>'; //cierrra 2 nivel
							
						}

				echo '</li>';//cierrra 1 nivel

			}

	}


	public function agregar()
	{
		
		// definimos las variables
		if (!empty($_POST['nombre']))			$nombre = $_POST['nombre']; else return 'Ingrese el nombre';
		$mayorista = $_POST['mayorista'];

		$alias=Varias::crear_url($nombre);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_categorias`(`ct_alias`, `ct_titulo`, `ct_mayorista`) VALUES ('$alias','$nombre','$mayorista')")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_categorias` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_categorias` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=='ct_titulo') {

				$alias=Varias::crear_url($value);

				$query = "UPDATE `tbl_categorias` set ct_alias='$alias' WHERE ct_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
			}

			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	
}



class Preguntas extends Conexion
{
	
	public $id;
	public $accion;
	public $status;
	
	public function lista($status)
	{

		$this->status=mysqli_real_escape_string(parent::con(), $status);
		
		$query=	"SELECT * FROM comments INNER JOIN tbl_productos ON tbl_productos.pd_id=comments.comment_post_ID";

		if(!empty($this->status)){
			if(!strstr($query,"WHERE")){
				$query .= " WHERE comment_approved='$this->status' AND comment_parent=0 ";
			}else{
				$query .= " AND comment_approved='$this->status' AND comment_parent=0 ";
			}
		} else {
			$query .= " WHERE comment_approved='pendiente' AND comment_parent=0 ";
		}

		$query.=" ORDER BY comment_date DESC";
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{

					$idComment=$reg['comment_ID'];
					$query=	"SELECT * FROM comments WHERE comment_parent='$idComment'";
					$resultParent=mysqli_query(parent::con(),"$query");
					$row_cnt=$resultParent->num_rows;
					if($row_cnt>0) {
						$reg['respuesta']='';
						while ($par=$resultParent->fetch_assoc())
						{
							$reg['respuesta'].='<strong>R:</strong> ';
							$reg['respuesta'].=$par['comment_content'];
							$reg['respuesta'].='<br>';
						}
					} else {
						$reg['respuesta']='';
					}	

				$output[] = $reg;
			}
	
		echo json_encode($output);
	}

	public function listaResumen()
	{
		$query=	"SELECT * FROM comments INNER JOIN tbl_productos ON tbl_productos.pd_id=comments.comment_post_ID 
		WHERE comment_approved='pendiente' AND comment_parent=0
		ORDER BY comment_date DESC LIMIT 5";
										
		$result=mysqli_query(parent::con(),"$query");


		while ($reg=$result->fetch_assoc())
			{
				echo '<li>
						<a href="contestar-pregunta.php?id_preg='.$reg['comment_ID'].'">
							<p class="msg-name">'.$reg['comment_author'].'</p>
							<p class="msg-text">'.$reg['comment_content'].'</p>
							<p class="msg-time">'.$reg['comment_date'].'</p>
						</a>
					</li>';
			}
	}

	public function GetRespuestas($id)
	{
		$this->id=mysqli_real_escape_string(parent::con(), $id);

		$query=	"SELECT * FROM comments WHERE comment_parent='$this->id'";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt=$result->num_rows;
		if($row_cnt>0) {
			while ($par=$result->fetch_assoc())
			{
				echo '<div class="alert alert-success m-t-lg" role="alert">
							<h4>Luz Desing contestó:</h4>
							'.$par['comment_content'].'
					</div>';
			}
		} 
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `comments` set ".$nombre." = '".$value."' WHERE comment_ID='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}
	
	public function GetPregunta($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `comments` WHERE comment_ID='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function countStatus($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(comment_ID) as cant FROM `comments` WHERE comment_approved='$this->status' AND comment_parent=0");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			echo $row['cant'];
		} else {
			echo 0;
		}
	}

	public function countPreguntas($status)
	{
		$this->status=mysqli_real_escape_string(parent::con(), $status);

		$result=mysqli_query(parent::con(),"SELECT count(comment_ID) as cant FROM `comments` WHERE comment_approved='$this->status' AND comment_parent=0");
		$row_cnt=$result->num_rows;
		if ($row_cnt>0) {
			$row=$result->fetch_assoc();
			return $row['cant'];
		} else {
			return 0;
		}
	}

	public function responder($id)
	{

		$this->id=mysqli_real_escape_string(parent::con(), $id);

		// definimos las variables
		$respuesta = $_POST['respuesta'];

		$query=	"SELECT * FROM comments WHERE comment_ID='$this->id'";
		$result=mysqli_query(parent::con(),"$query");
		$preg=$result->fetch_assoc();

		$producto=$preg['comment_post_ID'];
		$nombre="Luz Desing";
		$email="no-reply@luzdesing.com.ar";
		$comment_date = date("Y-m-d H:i:s");
		
		$emailTo=$preg['comment_author_email'];

		$query="INSERT INTO `comments` (`comment_post_ID`, `comment_author`, `comment_author_email`, `comment_date`, `comment_content`, `comment_approved`, `comment_parent`) VALUES ('$producto','$nombre','$email','$comment_date', '$respuesta', 'contestada', '$this->id')";
		mysqli_query(parent::con(), $query);

		$query = "UPDATE `comments` set `comment_approved`='contestada' WHERE comment_ID='$this->id'";

		
			require "../libreria/class.phpmailer.php";
            require "../libreria/class.smtp.php";
            $mail = new phpmailer();

            //parametros de configuración del servidor de envio
            $mail->IsSMTP();
            $mail->Host = "mail.luzdesing.com.ar";
            $mail->Username = "no-reply@luzdesing.com.ar";
            $mail->Password = "vB74vY0c5Y7uBZzu";
            //en caso de que el servidor utilice autenticación para conectarse especificamos
            $mail->SMTPAuth = true;
            //si el puerto utilizado no es el predeterminado (25) debemos especificar explicitamente el puerto de conexion utilizado por el servidor
            $mail->Port = 25;

            $mail->setFrom('no-reply@luzdesing.com.ar', 'Luz Desing');

            $mail->Timeout=30;

            //Indicamos cual es la dirección de destino del correo
            $mail->AddAddress($emailTo);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Luz Desing Iluminación - Respuesta a tu pregunta';

            $mensaje = '
            <html>
            <head>
            <title>Luz Desing Iluminación - Respuesta a tu pregunta</title>
            </head>
            <body>
            
            <table width="600" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #CCC; margin:0 auto;">
            <tr>
                <td colspan="4" height="116px" align="left" width="600px" valign="top" ><img src="'.WEB_ROOT.'img/mail/header_mail.jpg" width="600" height="116" alt="Luz Desing"></td>
            </tr>
            <tr>
                <td colspan="4" height="10px" style="padding:20px 10px 0 10px; height:auto; font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#343433""><strong>'.utf8_decode($preg["comment_author"]).':</strong><p>Esta es la respuesta a tu mensaje en <strong>Luz Desing.</strong></p></td>
            </tr>
            <tr>
                <td colspan="4">

                    <table cellspacing="0" cellpadding="0" border="0" style="padding:20px 20px 20px 20px; background-color:#fbfbfb; border: 1px solid rgb(204, 204, 204); margin: 0pt 20px; text-align: center; width:560px;" height="150px" >';
                                            
                       
							$mensaje .= '<tr>
											<td height="10px" style="padding:20px 10px 0 10px; height:auto; font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#343433""><strong>Tu pregunta:</strong><br>
                                                '.utf8_decode($preg['comment_content']).'
                                            </td>
										</tr>
										<tr>
                                            <td height="10px" style="padding:20px 10px 0 10px; height:auto; font-family:Arial, Helvetica, sans-serif; font-size:16px; text-align:left; color:#343433""><strong>Luz Desing, respondió:</strong><br>
                                                '.utf8_decode($respuesta).'
                                            </td>
                                        </tr>';


                $mensaje .= '<tr>
                            <td height="20px">&nbsp;</td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td height="10px">&nbsp;</td>
            </tr>
            <tr>
                <td width="600" style="background:#343433; font: normal 12px Arial, Helvetica, sans-serif; color:#fff; padding:10px; text-align:left;">&copy; '.date("Y").' Luz Desing - Todos los derechos reservados</td>
            </tr>
            <tr>
                <td style="font: normal 11px Arial, Helvetica, sans-serif; color: #343433; padding:10px; text-align:left;">
                            Por favor no responda este mail. Para consultas ingrese en <a href="'.WEB_ROOT.'">luzdesing.com.ar</a></td>
            </tr>

            </table>
            
            </body>
            </html>';

            $mail->Body = $mensaje;
            $mail->Send();
		
			if($result = mysqli_query(parent::con(), $query)) {
				return 'agregado';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}
	}
	
}

class Seo extends Conexion
{	
	
	public $id;
	public $accion;


	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_seo`";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_seo` set ".$nombre." = '".$value."' WHERE seo_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function agregar()
	{
		
		// definimos las variables
		if (!empty($_POST['nombre']))			$nombre = $_POST['nombre']; else return 'Ingrese el nombre de la página';

		$alias=Varias::crear_url($nombre);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_seo`(`seo_pagina`) VALUES ('$nombre')")) {
			return 'agregado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_seo` WHERE seo_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrió un error, intente nuevamente';
		}
	}

	public function traerScripts()
	{
		return $result=mysqli_query(parent::con(),"SELECT * FROM `tbl_scripts`");
	}
	
	public function scripts() {

		$scriptHead=mysqli_real_escape_string(parent::con(), $_POST["scriptHead"]);
		$scriptBody=mysqli_real_escape_string(parent::con(), $_POST["scriptBody"]);

		$query = "UPDATE `tbl_scripts` SET `scr_head`='$scriptHead', `scr_body`='$scriptBody' WHERE scr_id=1 ";	

		if(!$resultErr = mysqli_query(parent::con(), $query)) {
			return 'Ocurrió un error';
		} else {
			return 'agregado';
		}
			
	}

}

class Envios extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT * FROM `tbl_envios` 
		INNER JOIN provincias ON provincias.id=tbl_envios.env_provincia 
		ORDER BY provincias.provincia ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_envios` set ".$nombre." = '".$value."' WHERE env_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['provincia']) )			$provincia = $_POST['provincia']; else return 'Ingrese la provincia/región';
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese el nombre';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese la descripción';
		if ( $_POST['price_normal']!='' )			$price_normal = $_POST['price_normal']; else return 'Ingrese el precio';
		$horas_entrega = $_POST['horas_entrega'];
		$monto = $_POST['monto'];


		if (mysqli_query(parent::con(),"INSERT INTO `tbl_envios`(`env_provincia`, `env_nombre`, `env_descripcion`, `env_horas_entrega`, `price_normal`, `monto_mayor_a`) VALUES ('$provincia','$nombre','$descripcion','$horas_entrega','$price_normal','$monto')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_envios` WHERE env_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function ComboProvincias()
	{

		$query="SELECT * FROM `provincias` ORDER BY provincia ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}

}

class Estadisticas extends Conexion
{
	public $id;
	private $valDesdeHasta = array();

	public function periodoFechas($periodo)
	{
		$fechaPersonalizada=false;

		switch ($periodo) {
			case 'hoy':
				$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
				$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
				break;
				case 'semana':
					$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d') - 7, date('Y')));
					$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
					break;
					case 'mes':
						$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m') - 1, date('d'), date('Y')));
						$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
						break;
						case 'trimestre':
							$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m') - 3, date('d'), date('Y')));
							$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
							break;
							case 'anio':
								$valDesd = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y') - 1 ));
								$valHast = date('Y-m-d H:i:s', mktime(00,00,00, date('m'), date('d'), date('Y')));
								break;

								default:
								$fechaPersonalizada=true;
								$valDesd = substr($periodo, 6, 10);
								$valHast = substr($periodo, -10, 10);

								$date = DateTime::createFromFormat('d/m/Y', $valDesd);
								$valDesd = $date->format('Y-m-d H:i:s');

								$date = DateTime::createFromFormat('d/m/Y', $valHast);
								$valHast = $date->format('Y-m-d H:i:s');
								break;
		}
		$this->valDesdeHasta[] = $valDesd;
		$this->valDesdeHasta[] = $valHast;
		$this->valDesdeHasta[] = $fechaPersonalizada;
		return implode(",", $this->valDesdeHasta);
	}

	public function countOrdenesPagas($periodo)
	{
		
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT COUNT(id_orden) as cant FROM `ordenes` ";
		
		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 ";
		}

		$result=mysqli_query(parent::con(),$query);
		$data=0;
		while ($row=$result->fetch_assoc())
			{
				$data+= $row['cant'];
			}

		return $data;
	}

	public function OrdenesPorDia($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		if ($periodo=='hoy') {
			return 1;
		} else {		
			$datetime1 = new DateTime($valDesd);
			$datetime2 = new DateTime($valHast);
			return $interval = $datetime1->diff($datetime2)->days;
		}
	}

	public function countFacturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT SUM(total_compra) as total, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 ";
		}

		$result=mysqli_query(parent::con(),$query);
		$row_cnt=$result->num_rows;

		$data=0;

		if ($row_cnt>0) {
			while ($row=$result->fetch_assoc())
			{
				$data+= $row['total'];
			}
		}

		return $data;
	}

	public function ordenesPagas($periodo)
	{

		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];
		
		$datetime1 = new DateTime($valDesd);
		$datetime2 = new DateTime($valHast);
		$interval = $datetime1->diff($datetime2)->days;

		$query = "SELECT COUNT(id_orden) as cant, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 ";
		}


		if ($interval>92) {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta) ";
		} elseif ($interval>28 && $interval<93) {
			$query .= "GROUP BY YEAR(fecha_alta), WEEK(fecha_alta) ";
		} else {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta), DAY(fecha_alta) ";
		}
		$query .= "ORDER BY fecha_alta ASC";
		

		$result=mysqli_query(parent::con(),$query);
		$data='';
		while ($row=$result->fetch_assoc())
			{
				if ($interval>92) {
					$fecha=date("Y-m", strtotime($row['fecha_alta']));
				} elseif ($interval>28 && $interval<93) {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				} else {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				}

				$data.= "{ day: '".$fecha."', ordenes: ".$row['cant']." },";
			}
			$data = substr($data, 0, -1);
			echo $data;
	}

	public function facturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];
		
		$datetime1 = new DateTime($valDesd);
		$datetime2 = new DateTime($valHast);
		$interval = $datetime1->diff($datetime2)->days;

		$query = "SELECT SUM(total_compra) as total, fecha_alta FROM `ordenes` ";

		if ($fechaPersonalizada) {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 ";
		} else {
			$query .= "WHERE fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 ";
		}

		if ($interval>92) {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta) ";
		} elseif ($interval>28 && $interval<93) {
			$query .= "GROUP BY YEAR(fecha_alta), WEEK(fecha_alta) ";
		} else {
			$query .= "GROUP BY YEAR(fecha_alta), MONTH(fecha_alta), DAY(fecha_alta) ";
		}


		$result=mysqli_query(parent::con(),$query);
		$data='';
		while ($row=$result->fetch_assoc())
			{
				if ($interval>92) {
					$fecha=date("Y-m", strtotime($row['fecha_alta']));
				} elseif ($interval>28 && $interval<93) {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				} else {
					$fecha=date("Y-m-d", strtotime($row['fecha_alta']));
				}

				$data.= "{ day: '".$fecha."', total: ".$row['total']." },";
			}
			$data = substr($data, 0, -1);
			echo $data;
	}

	public function productosMasVendidos($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT COUNT(pd_id) as cant, pd_id, pd_titulo FROM ordenes
		INNER JOIN items_orden ON items_orden.id_orden=ordenes.id_orden 
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id ";

		if ($fechaPersonalizada) {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 GROUP BY pd_id ORDER BY cant DESC";
		} else {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 GROUP BY pd_id ORDER BY cant DESC";
		}

		$result=mysqli_query(parent::con(),$query);

		while ($row=$result->fetch_assoc())
			{
				$id_prod=$row["pd_id"];
			
				$query = "SELECT im_nombre FROM tbl_img WHERE im_producto='$id_prod' ORDER BY im_orden ASC LIMIT 1";
				$result_img=mysqli_query(parent::con(),$query);
				$rowImg=$result_img->fetch_assoc();

				echo '<div class="inbox-item">
						<div class="inbox-item-img"><img src="../img/productos/'.$rowImg["im_nombre"].'" class="img-circle" alt=""></div>
						<p class="inbox-item-author">'.$row['pd_titulo'].'</p>
						<p class="inbox-item-date">'.$row['cant'].'</p>
					</div>';

			}
	}

	public function productosMasFacturacion($periodo)
	{
		$fechas = explode(",", $this->periodoFechas($periodo));
		$valDesd=$fechas[0];
		$valHast=$fechas[1];
		$fechaPersonalizada=$fechas[2];

		$query = "SELECT SUM(precio) as total, pd_id, pd_titulo FROM ordenes
		INNER JOIN items_orden ON items_orden.id_orden=ordenes.id_orden 
		INNER JOIN tbl_productos ON tbl_productos.pd_id=items_orden.producto_id 
		WHERE items_orden.producto_id = tbl_productos.pd_id ";

		if ($fechaPersonalizada) {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= '$valHast' AND or_estado>2 AND or_estado<8 GROUP BY pd_id ORDER BY total DESC";
		} else {
			$query .= "AND fecha_alta >= '$valDesd' AND fecha_alta <= NOW() AND or_estado>2 AND or_estado<8 GROUP BY pd_id ORDER BY total DESC";
		}

		$result=mysqli_query(parent::con(),$query);

		while ($row=$result->fetch_assoc())
			{
				$id_prod=$row["pd_id"];
			
				$query = "SELECT im_nombre FROM tbl_img WHERE im_producto='$id_prod' ORDER BY im_orden ASC LIMIT 1";
				$result_img=mysqli_query(parent::con(),$query);
				$rowImg=$result_img->fetch_assoc();


				echo '<div class="inbox-item">
						<div class="inbox-item-img"><img src="../img/productos/'.$rowImg["im_nombre"].'" class="img-circle" alt=""></div>
						<p class="inbox-item-author">'.$row['pd_titulo'].'</p>
						<p class="inbox-item-date">$'.number_format($row['total'],2,',','.').'</p>
					</div>';

			}
	}
	
}

class Cupones extends Conexion
{
	public $id;
	public $orden;
	private $output=array();

	public function ListaCupones()
	{
		$query="SELECT * FROM `tbl_codigos_descuento` ORDER BY id DESC";
		$result=mysqli_query(parent::con(),"$query");


			while ($row=$result->fetch_assoc())
				{
					echo '<tr>
													<td><strong>'.$row['codigo'].'</strong></td>
                                                   <td>'.$row['descripcion'].'</td>
                                                   <td>'.$row['porcentaje_descuento'].'% descuento</td>
                                                   <td>'.$row['validez'].'</td>';
                                                   echo '<td><div class="btn-group">
															<button type="button" class="btn ';

															if($row["status"]=='papelera') {
																	echo 'btn-danger';
															} else {
																	echo 'btn-success';
															}	
															 		echo ' btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$row["status"].' <span class="caret"></span>
															</button>
															<ul class="dropdown-menu">';
																			  
																if($row["status"]=='papelera') {
																	echo '<li><a href="cupones.php?action=publicado&id='.$row["id"].'">Publicar</a></li>';
																} else {
																	echo '<li><a href="cupones.php?action=papelera&id='.$row["id"].'">Enviar a papelera</a></li>';
																}		
												   echo '</ul></div></td>
												   <td><a href="editar-cupon.php?id='.$row['id'].'" class="btn btn-warning"><i class="fa fa-edit"></i> Editar</a></td>
                                                   <td><a href="cupones.php?action=delete&id='.$row["id"].'" class="btn btn-danger"><i class="fa fa-trash-o"></i> Eliminar</a></td>
                                                   
                                               </tr>';
			}

	}


	public function Agregar()
	{
		

		// definimos las variables
		if ( !empty($_POST['codigo']) )			$codigo = $_POST['codigo']; else return 'Ingrese el código';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripcion';
		$descripcion=Varias::parseToText($descripcion);
		if ( !empty($_POST['descuento']) )			$descuento = $_POST['descuento']; else return 'Ingrese Descuento';
		if ( !empty($_POST['dates']) )			$validez = $_POST['dates']; else return 'Ingrese fechas de validez';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';


		$valDesd = substr($validez, 6, 10);
		$valHast = substr($validez, -10, 10);

		$date = DateTime::createFromFormat('d/m/Y', $valDesd);
		$valDesd = $date->format('Y-m-d');

		$date = DateTime::createFromFormat('d/m/Y', $valHast);
		$valHast = $date->format('Y-m-d');


		$query="INSERT INTO `tbl_codigos_descuento`(`codigo`, `descripcion`, `validez`, `val-desde`, `val-hasta`, `porcentaje_descuento`, `status`) VALUES ('$codigo','$descripcion','$validez','$valDesd','$valHast','$descuento','$estado')";

			if (mysqli_query(parent::con(),"$query")) {
				return 'agregado';
			} else {
				return 'Ocurrio un error, intente nuevamente';
			}

	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_codigos_descuento` WHERE id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function Editar($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['codigo']) )			$codigo = $_POST['codigo']; else return 'Ingrese el código';
		if ( !empty($_POST['descripcion']) )			$descripcion = $_POST['descripcion']; else return 'Ingrese descripcion';
		$descripcion=Varias::parseToText($descripcion);
		if ( !empty($_POST['descuento']) )			$descuento = $_POST['descuento']; else return 'Ingrese Descuento';
		if ( !empty($_POST['dates']) )			$validez = $_POST['dates']; else return 'Ingrese fechas de validez';
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';


		$valDesd = substr($validez, 6, 10);
		$valHast = substr($validez, -10, 10);


		$date = DateTime::createFromFormat('d/m/Y', $valDesd);
		$valDesd = $date->format('Y-m-d');

		$date = DateTime::createFromFormat('d/m/Y', $valHast);
		$valHast = $date->format('Y-m-d');


			$query = "UPDATE `tbl_codigos_descuento` SET `codigo`='$codigo', `descripcion`='$descripcion',`validez`='$validez',`val-desde`='$valDesd',`val-hasta`='$valHast',`porcentaje_descuento`='$descuento',`status`='$estado' WHERE id='$this->id'";
		
			if($result = mysqli_query(parent::con(), $query)) {
				return 'agregado';
			} else {
				return 'Ocurrio un error, intente nuevamente';
			}
	}

	public function Borrar($id)
	{
		$this->id=$id;

		$sql="DELETE FROM `tbl_codigos_descuento` WHERE id='$this->id'";
		if($result = mysqli_query(parent::con(), $sql)) {
			return 'eliminado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Status($id,$acc) {
		
		$this->id=$id;
		$this->accion=$acc;
		mysqli_query(parent::con(),"UPDATE `tbl_codigos_descuento` SET `status`='$this->accion' WHERE id='$this->id' ");
		
		return "actualizado";
			
	}

}


class Iconos extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;

	public function gestionImg($img) {
		
		$this->imagen=$img;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_iconos` WHERE ic_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_iconos`(`ic_nombre`) VALUES ('$this->imagen')");
			}
	}

	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_iconos` ORDER BY ic_titulo ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_iconos` set ".$nombre." = '".$value."' WHERE ic_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_iconos` WHERE ic_nombre='$imagen'")) {
			unlink("../img/iconos/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/iconos/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_iconos` WHERE ic_nombre='$imagen'");
	}
}




class CategoríasMixer extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{

		$query="SELECT `ct_id`, `ct_titulo`, `ct_tipo_mixer`, `ct_color`, `ct_orden`, `tm_titulo` FROM `tbl_categorias_mixer` 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_categorias_mixer.ct_tipo_mixer ORDER BY ct_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboTipoMixer()
	{

		$query="SELECT `tm_id`, `tm_titulo` FROM `tbl_tipos_mixer` ORDER BY tm_titulo ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}

	public function editar($nombre,$value,$pk)
	{

		if ($nombre=="ct_tipo_mixer") {
			$value = implode(",", $value);
		}

		$sql = "UPDATE `tbl_categorias_mixer` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			if ($nombre=='ct_titulo') {
				$alias=Varias::crear_url($value);
				$query = "UPDATE `tbl_categorias_mixer` set ct_alias='$alias' WHERE ct_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
			}
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';
		if ( !empty($_POST['tipo']) )			$tipo = $_POST['tipo']; else return 'Complete tipo de mixer';
		if ( !empty($_POST['color']) )			$color = $_POST['color']; else return 'Complete color';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_categorias_mixer`(`ct_tipo_mixer`,`ct_alias`,`ct_titulo`,`ct_color`) VALUES ('$tipo','$alias','$campo1','$color')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_categorias_mixer` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
	
}


class Mixer extends Conexion
{
	public $id;
	public $linea;
	public $orden;
	public $imagen;
	private $output=array();
	public $proceso;


	public function lista()
	{
		$query="SELECT * FROM `tbl_productos_mixer`
		LEFT JOIN tbl_categorias_mixer ON tbl_productos_mixer.pd_categoria=tbl_categorias_mixer.ct_id";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				if (!empty($row['pd_img'])) {
					if (file_exists('../../img/productos-mixer/'.$row['pd_img'])) {
						$row['pd_img'] = '../img/productos-mixer/'.$row['pd_img'];
					} else {
						$row['pd_img'] = '../img/productos-mixer/sin-imagen.jpg';
					}
				} else {
					$row['pd_img'] = '../img/productos-mixer/sin-imagen.jpg';
				}


				$categoria=$row['ct_id'];
				$resultCat=mysqli_query(parent::con(),"SELECT * FROM tbl_categorias_mixer WHERE ct_id='$categoria' ");
				$ct=$resultCat->fetch_assoc();

					$nombreCat='';
					$catPadre1=$ct['ct_padre'];

					$nombreCat.=$ct['ct_titulo'];

					$result1=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias_mixer` WHERE ct_id='$catPadre1' LIMIT 1");
					$cnt_res1=$result1->num_rows;

					if($cnt_res1>0) {
						$ct2=$result1->fetch_assoc();

						$catPadre2=$ct2['ct_padre'];
						$nombreCat.=' ('.$ct2['ct_titulo'];

						$result2=mysqli_query(parent::con(),"SELECT * FROM `tbl_categorias_mixer` WHERE ct_id='$catPadre2' LIMIT 1");
						$cnt_res2=$result2->num_rows;

						if($cnt_res2>0) {
							$ct3=$result2->fetch_assoc();

							$catPadre3=$ct3['ct_padre'];
							$nombreCat.='-'.$ct3['ct_titulo'];
						}
						$nombreCat.=')';
					}

					$row['ct_titulo']=$nombreCat;



				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_productos_mixer` set ".$nombre." = '".$value."' WHERE pd_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_productos_mixer` WHERE pd_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function variacionesProdMixer($id)
	{
		$this->id=$id;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_tipos_mixer`");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				echo '<div class="form-group">
						<div class="col-sm-2">
							<h3><strong>'.$row["tm_titulo"].'</strong></h3>
						</div>';

				$tipoMixer = $row["tm_id"];
				$nombreMixer = $row["tm_titulo"];

				$query="SELECT * FROM `tbl_variaciones_mixer` WHERE vm_producto='$this->id'AND vm_tipo_mixer='$tipoMixer' ";
				$resultVar=mysqli_query(parent::con(),"$query");
				$cntVar=$resultVar->num_rows;

				if($cntVar>0){
					$var=$resultVar->fetch_assoc();

					echo '<div class="col-sm-10">
							<div class="row">
								<div class="col-xs-12 col-md-3">
									<label for="pd_peso'.$tipoMixer.'">Peso porción</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso'.$tipoMixer.'" value="'.$var['pd_peso'].'">
									</div>
								</div>

								<div class="col-xs-12 col-md-3">
									<label for="pd_peso_mayor'.$tipoMixer.'">Peso porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso_mayor'.$tipoMixer.'" value="'.$var['pd_peso_mayo'].'">
									</div>
								</div>

								<div class="col-xs-12 col-md-3">
									<label for="pd_precio'.$tipoMixer.'">Precio porción</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio'.$tipoMixer.'" step="0.01" value="'.$var['pd_precio'].'" >
									</div>
								</div>

								<div class="col-xs-12 col-md-3">
									<label for="pd_precio_mayor'.$tipoMixer.'">Precio porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio_mayor'.$tipoMixer.'" step="0.01" value="'.$var['pd_precio_mayo'].'" >
									</div>
								</div>
							</div>
						</div>';
				} else {
					echo '<div class="col-sm-10">
							<div class="row">
								<div class="col-xs-12 col-md-3">
									<label for="pd_peso'.$tipoMixer.'">Peso porción</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso'.$tipoMixer.'" >
									</div>
								</div>
								<div class="col-xs-12 col-md-3">
									<label for="pd_peso_mayor'.$tipoMixer.'">Peso porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso_mayor'.$tipoMixer.'" >
									</div>
								</div>

								<div class="col-xs-12 col-md-3">
									<label for="pd_precio'.$tipoMixer.'">Precio porción</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio'.$tipoMixer.'" step="0.01" >
									</div>
								</div>
								<div class="col-xs-12 col-md-3">
									<label for="pd_precio_mayor'.$tipoMixer.'">Precio porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio_mayor'.$tipoMixer.'" step="0.01" >
									</div>
								</div>
							</div>
						</div>';
				}

				echo '</div><hr>';
			}
		}
	}

	public function agregaVariacionesProdMixer()
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_tipos_mixer`");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {
				
				$tipoMixer = $row["tm_id"];

				echo '<div class="form-group">
						<div class="col-sm-2">
							<h3><strong>'.$row["tm_titulo"].'</strong></h3>
						</div>';

				echo '<div class="col-sm-10">
							<div class="row">
								<div class="col-xs-12 col-md-3">
									<label for="pd_peso'.$tipoMixer.'">Peso porción</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso'.$tipoMixer.'" >
									</div>
								</div>
								<div class="col-xs-12 col-md-3">
									<label for="pd_peso_mayor'.$tipoMixer.'">Peso porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">gr.</div>
										<input type="number" class="form-control" name="pd_peso_mayor'.$tipoMixer.'" >
									</div>
								</div>

								<div class="col-xs-12 col-md-3">
									<label for="pd_precio'.$tipoMixer.'">Precio porción</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio'.$tipoMixer.'" step="0.01" >
									</div>
								</div>
								<div class="col-xs-12 col-md-3">
									<label for="pd_precio_mayor'.$tipoMixer.'">Precio porción (Mayor)</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="number" class="form-control" name="pd_precio_mayor'.$tipoMixer.'" step="0.01" >
									</div>
								</div>
							</div>
						</div>';

				echo '</div><hr>';
			}
		}
	}

	public function Agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';

		$categoria='';
		if (!empty($_POST['categoria'])) 	{ 
			$arra_cat = $_POST['categoria'];
			$categoria = implode(",", $arra_cat);
		} else {
			return 'Seleccione al menos una categoría';
		}

		if ( !empty($_POST['icono']) )			$icono = $_POST['icono']; else return 'Seleccione un icono representativo';
		$descripcion = $_POST['descripcion'];
		$descripcion=Varias::parseToText($descripcion);
		if (!empty($_POST['prop'])) 	{ 
			$arra_prop = $_POST['prop'];
			$propiedades = implode("-", $arra_prop);
		} 

		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado';
		
		$kcal = $_POST['kcal'];
		$hidratos_carbono = $_POST['hidratos_carbono'];
		$proteinas = $_POST['proteinas'];
		$grasas_totales = $_POST['grasas_totales'];
		$grasas_saturadas = $_POST['grasas_saturadas'];
		$grasas_trans = $_POST['grasas_trans'];
		$grasas_monoinsaturadas = $_POST['grasas_monoinsaturadas'];
		$grasas_poliinsaturadas = $_POST['grasas_poliinsaturadas'];
		$colesterol = $_POST['colesterol'];
		$fibra_alimentaria = $_POST['fibra_alimentaria'];
		$sodio = $_POST['sodio'];

		$alias=Varias::crear_url($nombre);

		$query="INSERT INTO `tbl_productos_mixer`(`pd_alias`, `pd_titulo`, `pd_descripcion`, `pd_categoria`, `pd_icono`, `pd_componentes`, `kcal`, `hidratos_carbono`, `proteinas`, `grasas_totales`, `grasas_saturadas`, `grasas_trans`, `grasas_monoinsaturadas`, `grasas_poliinsaturadas`, `colesterol`, `fibra_alimentaria`, `sodio`, `status`) VALUES ('$alias','$nombre','$descripcion','$categoria','$icono','$propiedades','$kcal','$hidratos_carbono','$proteinas','$grasas_totales','$grasas_saturadas','$grasas_trans','$grasas_monoinsaturadas','$grasas_poliinsaturadas','$colesterol','$fibra_alimentaria','$sodio','$estado')";

		if (mysqli_query(parent::con(),"$query")) {

			$result=mysqli_query(parent::con(),"SELECT pd_id FROM tbl_productos_mixer WHERE pd_alias='$alias' ORDER BY pd_id DESC LIMIT 1");
			$nid=$result->fetch_assoc();
				
			$id_new=$nid["pd_id"];

			//variaciones de los distintos mixer
			$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_tipos_mixer`");
			while($row=$result->fetch_assoc()) {
				$tipoMixer = $row["tm_id"];
				$varpeso = 'pd_peso'.$tipoMixer;
				$varprecio = 'pd_precio'.$tipoMixer;
				$varpesoMayor = 'pd_peso_mayor'.$tipoMixer;
				$varprecioMayor = 'pd_precio_mayor'.$tipoMixer;

				if ($_POST[$varpeso]!= '' && $_POST[$varprecio]!= '' && $_POST[$varpesoMayor]!= '' && $_POST[$varprecioMayor]!= '') {
					$peso=$_POST[$varpeso];
					$precio=$_POST[$varprecio];
					$pesoMayor=$_POST[$varpesoMayor];
					$precioMayor=$_POST[$varprecioMayor];
					$query="INSERT INTO `tbl_variaciones_mixer`(`vm_producto`, `vm_tipo_mixer`, `pd_peso`, `pd_precio`, `pd_peso_mayo`, `pd_precio_mayo`) VALUES ('$id_new','$tipoMixer','$peso','$precio','$pesoMayor','$precioMayor')";
					mysqli_query(parent::con(),"$query");
				}
			}

			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}
	
	public function EditarProducto($id)
	{
		$this->id=$id;

		// definimos las variables
		if ( !empty($_POST['nombre']) )			$nombre = $_POST['nombre']; else return 'Ingrese título';

		$categoria='';
		if (!empty($_POST['categoria'])) 	{ 
			$arra_cat = $_POST['categoria'];
			$categoria = implode(",", $arra_cat);
		} else {
			return 'Seleccione al menos una categoría';
		}

		if ( !empty($_POST['icono']) )			$icono = $_POST['icono']; else return 'Seleccione un icono representativo';
		$descripcion = $_POST['descripcion'];
		$descripcion=Varias::parseToText($descripcion);
		if (!empty($_POST['prop'])) 	{ 
			$arra_prop = $_POST['prop'];
			$propiedades = implode("-", $arra_prop);
		} 

		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado';

		
		$kcal = $_POST['kcal'];
		$hidratos_carbono = $_POST['hidratos_carbono'];
		$proteinas = $_POST['proteinas'];
		$grasas_totales = $_POST['grasas_totales'];
		$grasas_saturadas = $_POST['grasas_saturadas'];
		$grasas_trans = $_POST['grasas_trans'];
		$grasas_monoinsaturadas = $_POST['grasas_monoinsaturadas'];
		$grasas_poliinsaturadas = $_POST['grasas_poliinsaturadas'];
		$colesterol = $_POST['colesterol'];
		$fibra_alimentaria = $_POST['fibra_alimentaria'];
		$sodio = $_POST['sodio'];



		//variaciones de los distintos mixer
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_tipos_mixer`");
		while($row=$result->fetch_assoc()) {
			$tipoMixer = $row["tm_id"];
			$varpeso = 'pd_peso'.$tipoMixer;
			$varprecio = 'pd_precio'.$tipoMixer;
			$varpesoMayor = 'pd_peso_mayor'.$tipoMixer;
			$varprecioMayor = 'pd_precio_mayor'.$tipoMixer;

			var_dump($_POST[$varpeso]);
			if ($_POST[$varpeso]!= '' && $_POST[$varprecio]!= '' && $_POST[$varpesoMayor]!= '' && $_POST[$varprecioMayor]!= '') {
				$peso=$_POST[$varpeso];
				$precio=$_POST[$varprecio];
				$pesoMayor=$_POST[$varpesoMayor];
				$precioMayor=$_POST[$varprecioMayor];
				$resultVar=mysqli_query(parent::con(),"SELECT * FROM `tbl_variaciones_mixer` WHERE vm_tipo_mixer=$tipoMixer AND vm_producto='$this->id'");
				$cnt=$resultVar->num_rows;
				if($cnt>0){
					$var=$resultVar->fetch_assoc();
					$idVaria = $var["vm_id"];
					$query = "UPDATE `tbl_variaciones_mixer` SET `pd_peso`='$peso', `pd_precio`='$precio', `pd_peso_mayo`='$pesoMayor', `pd_precio_mayo`='$precioMayor' WHERE vm_id='$idVaria'";
					mysqli_query(parent::con(),"$query");
				} else {
					$query="INSERT INTO `tbl_variaciones_mixer`(`vm_producto`, `vm_tipo_mixer`, `pd_peso`, `pd_precio`, `pd_peso_mayo`, `pd_precio_mayo`) VALUES ('$this->id','$tipoMixer','$peso','$precio','$pesoMayor','$precioMayor')";
					mysqli_query(parent::con(),"$query");
				}
			} else {
				$resultVar=mysqli_query(parent::con(),"SELECT * FROM `tbl_variaciones_mixer` WHERE vm_tipo_mixer=$tipoMixer AND vm_producto='$this->id'");
				$cnt=$resultVar->num_rows;
				if($cnt>0){
					$var=$resultVar->fetch_assoc();
					$idVaria = $var["vm_id"];
					$query = "DELETE FROM `tbl_variaciones_mixer` WHERE vm_id='$idVaria'";
					mysqli_query(parent::con(),"$query");
				} 
			}
				
		}


		$alias=Varias::crear_url($nombre);

		$query = "UPDATE `tbl_productos_mixer` set `pd_alias`='$alias', `pd_titulo`='$nombre',`pd_descripcion`='$descripcion',`pd_categoria`='$categoria',`pd_icono`='$icono',`pd_componentes`='$propiedades',`kcal`='$kcal',`hidratos_carbono`='$hidratos_carbono',`proteinas`='$proteinas',`grasas_totales`='$grasas_totales',`grasas_saturadas`='$grasas_saturadas',`grasas_trans`='$grasas_trans',`grasas_monoinsaturadas`='$grasas_monoinsaturadas',`grasas_poliinsaturadas`='$grasas_poliinsaturadas',`colesterol`='$colesterol',`fibra_alimentaria`='$fibra_alimentaria',`sodio`='$sodio',`status`='$estado' WHERE pd_id='$this->id'";
		
		if($result = mysqli_query(parent::con(), $query)) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function Borrar($id)
	{
		$this->id=$id;

		$sql="DELETE FROM `tbl_productos_mixer` WHERE pd_id='$this->id'";
		if($result = mysqli_query(parent::con(), $sql)) {
			return 'eliminado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function Status($id,$acc) {
		
		$this->id=$id;
		$this->accion=$acc;
		mysqli_query(parent::con(),"UPDATE `tbl_productos_mixer` SET `status`='$this->accion' WHERE pd_id='$this->id' ");
		
		return "actualizado";
	}


	private $arr_n_fotos=array();

	public function BotonesImgProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_productos_mixer` WHERE pd_id='$this->id'");
		$reg=$result->fetch_assoc();

		$_SESSION['id_producto']=$this->id;

		if(!$reg['pd_img']){
			$nom_foto=$reg['pd_id'].'-'.Varias::crear_url($reg['pd_titulo']).'-'.date('YmdHms'); 
			echo '<p class="alert alert-danger">No hay fotos aplicadas a este ingrediente</p><br><a href="upload_crop_mixer.php?nom_fot='.$nom_foto.'&orden=1" class="btn btn-danger btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar foto</a><hr>';
		} 
	}

	public function ImagenesProd($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_productos_mixer` WHERE pd_id='$this->id'");
		$row_cnt=$result->num_rows;

		if($row_cnt>0){

			while($row=$result->fetch_assoc()) {

				$info = pathinfo($row["pd_img"]);
				$nom_foto =  basename($row["pd_img"],'.'.$info['extension']);
                $row['nombreFot']=$nom_foto;

				$output[] = $row;
			}

			echo json_encode($output);
		}
	}

	public function ImagenPrincipal($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_productos_mixer` WHERE pd_id='$this->id'");
		$row=$result->fetch_assoc();

		if(!empty($row["pd_img"])){
			echo '<img id="thumbnil" src="../img/productos-mixer/'.$row["pd_img"].'" class="img-thumbnail" width="100%"/>';
		} else {
			echo '<img id="thumbnil" src="../img/productos-mixer/sin-imagen.jpg" class="img-thumbnail" width="100%"/>';
		}
	}

	public function gestionImg($img,$id_prod,$orden) {
		
		$this->imagen=$img;
		$this->id=$id_prod;
		$this->orden=$orden;
			
		mysqli_query(parent::con(),"UPDATE `tbl_productos_mixer` SET `pd_img`='$this->imagen' WHERE pd_id='$this->id'");

	}


	public function borrarImg($img) {
		$this->imagen=$img;

		if (mysqli_query(parent::con(),"UPDATE `tbl_productos_mixer` SET `pd_img`='' WHERE pd_img='$this->imagen'")) {

			unlink("../img/productos-mixer/".$this->imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	private $mixContent = array();

	public function GetMixContent($id)
	{
		$this->id=$id;

		$query = "SELECT * FROM tbl_cart_mixer 
		LEFT JOIN tbl_cart_item_mixer ON tbl_cart_item_mixer.id_mix=tbl_cart_mixer.id_mix
		LEFT JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id=tbl_cart_mixer.tipo_mixer
		LEFT JOIN tbl_productos_mixer ON tbl_productos_mixer.pd_id=tbl_cart_item_mixer.producto_id
		LEFT JOIN tbl_variaciones_mixer ON tbl_variaciones_mixer.vm_producto=tbl_productos_mixer.pd_id
		WHERE tbl_cart_mixer.tipo_mixer=tbl_variaciones_mixer.vm_tipo_mixer
		AND tbl_cart_mixer.id_mix = '$this->id'";

		$result=mysqli_query(parent::con(),$query);
		
		while ($row=$result->fetch_assoc()) {

			$this->mixContent[] = $row;
		}	
		
		return $this->mixContent;	
	}

	public function GetMixInfo($id)
	{
		$this->id=$id;

		$query = "SELECT * FROM tbl_cart_mixer 
		INNER JOIN tbl_tipos_mixer ON tbl_tipos_mixer.tm_id = tbl_cart_mixer.tipo_mixer
		WHERE id_mix = '$this->id'";
		$result=mysqli_query(parent::con(),$query);
		return $result->fetch_assoc();
	}
}


class ArchivosDescargas extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	public $producto;
	
	public function gestionDescargas($arch) {
		
		$this->archivo=$arch;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_descargas` WHERE des_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_descargas`(`des_nombre`) VALUES ('$this->archivo')");
			}
	}


	public function getEmployees()
	{

		$query="SELECT * FROM `tbl_descargas` ORDER BY des_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$sql = "UPDATE `tbl_descargas` set ".$nombre." = '".$value."' WHERE des_id='".$pk."'";
		
		if(mysqli_query(parent::con(), $sql)) {
			echo 'exito';
		} else {
			echo 'error';
		}
	}


	public function borrarDescargas($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_descargas` WHERE des_nombre='$this->archivo'")) {
			unlink("../descargas/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarDescargasUpload($arch) {
		$this->archivo=$arch;

		unlink("../../descargas/".$this->archivo);
		mysqli_query(parent::con(),"DELETE FROM `tbl_descargas` WHERE des_nombre='$this->archivo'");
	}
	
}
?>