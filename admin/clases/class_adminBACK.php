<?php 

abstract class Conexion
{
	public function con()
	{	
		//$puntero=new mysqli('localhost', 'root', 'root', 'bd-lucciola');     //DATOS DE CONEXION LOCAL
		$puntero=new mysqli('localhost', 'lucciola_lucc_ad', 'LucAdmin2018', 'lucciola_admin'); //DATOS DE CONEXION SERVIDOR
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
		self::$titulo=str_replace("-","",self::$titulo);
		self::$titulo=str_replace(" ","-",self::$titulo);
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'",'"',"(",")","?","¿","¡","!","º",",",".",":",";","/");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","","","","","","","","","","","","","-");
 		return str_replace($caracteres_raros, $caracteres_remp, self::$titulo);
	}
	
	public static function nombreFotos($str)
	{
		self::$texto=mb_strtolower($str, 'UTF-8');
		self::$texto=str_replace(" ","",self::$texto);
		$caracteres_raros = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü","'",'"',"(",")","?","¿","¡","!","º",",",".",":",";","/");
		$caracteres_remp = array("a","e","i","o","u","A","E","I","O","U","n","N","u","U","","","","","","","","","","","","","","-");
 		return str_replace($caracteres_raros, $caracteres_remp, self::$texto);
	}
	
}

class Configuracion extends Conexion
{
	public function linea($linea)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_lineas ORDER BY ln_nombre ASC");

		while ($ln=$result->fetch_assoc())
		{
            echo '<option value="'.$ln["ln_id"].'" '; if (isset($linea) and $linea==$ln["ln_id"]) { echo 'selected'; }; echo ' >'.$ln["ln_nombre"].'</option>';
		}
	}
	public function lineaTabla($linea)
	{
		$result=mysqli_query(parent::con(),"SELECT DISTINCT ln_id,ln_nombre FROM tbl_lineas 
			INNER JOIN tbl_estructura_tablas ON tbl_estructura_tablas.et_linea=tbl_lineas.ln_id
			ORDER BY ln_nombre ASC");

		while ($ln=$result->fetch_assoc())
		{
            echo '<option value="'.$ln["ln_id"].'" '; if (isset($linea) and $linea==$ln["ln_id"]) { echo 'selected'; }; echo ' >'.$ln["ln_nombre"].'</option>';
		}
	}
	public function tipoLuminaria($tipo)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_tipos_luminarias ORDER BY tl_nombre ASC");
			echo '<option value="">Seleccione el tipo de luminaria</option>';
		while ($ap=$result->fetch_assoc())
		{
            echo '<option value="'.$ap["tl_id"].'" '; if (isset($tipo) and $tipo==$ap["tl_id"]) { echo 'selected'; }; echo ' >'.$ap["tl_nombre"].'</option>';
		}
	}
	public function Iconos($icono)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_iconos_resumen ORDER BY ir_nombre ASC");
			echo '<option value="">Seleccione el ícono</option>';
		while ($ic=$result->fetch_assoc())
		{
            echo '<option value="'.$ic["ir_id"].'" '; if (isset($icono) and $icono==$ic["ir_id"]) { echo 'selected'; }; echo ' >'.$ic["ir_nombre"].'</option>';
		}
	}
	public function Titulos($titulo)
	{
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_tit_caracteristicas ORDER BY ct_titulo ASC");
			echo '<option value="">Seleccione el Título</option>';
		while ($tit=$result->fetch_assoc())
		{
            echo '<option value="'.$tit["ct_id"].'" '; if (isset($titulo) and $titulo==$tit["ct_id"]) { echo 'selected'; }; echo ' >'.$tit["ct_titulo"].'</option>';
		}
	}

}



class Tablas extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function estructuraTabla($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_estructura_tablas` WHERE et_linea='$this->linea' ORDER BY et_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

		$cont=1;
			while ($row=$result->fetch_assoc())
			{
				echo '<th>'.$row["et_titulo"].'</th>';

				$cont++;
			}
			for ($i=$cont; $i < 11; $i++) { 
				echo '<th>Campo '.$i.'</th>';
			}
	}

	public function CamposFormAddTabla($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_estructura_tablas` WHERE et_linea='$this->linea' ORDER BY et_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

		$cont=1;
			while ($row=$result->fetch_assoc())
			{
				echo '<div class="form-group"><input type="text" name="campo'.$cont.'" class="form-control" placeholder="'.$row["et_titulo"].'"></div>';
				$cont++;
			}
			for ($i=$cont; $i < 11; $i++) { 
				echo '<div class="form-group"><input type="text" name="campo'.$i.'" class="form-control" placeholder="Campo '.$i.'"></div>';
			}
	}

	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT `ct_id`, `ct_col1`, `ct_col2`, `ct_col3`, `ct_col4`, `ct_col5`, `ct_col6`, `ct_col7`, `ct_col8`, `ct_col9`, `ct_col10`, `ct_orden` FROM `tbl_contenido_tablas` WHERE ct_linea='$this->linea' ORDER BY ct_orden ASC";

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
		$sql = "UPDATE `tbl_contenido_tablas` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function AgregarFila($lin)
	{
		$this->linea=$lin;

		// definimos las variables
		$campo1 = $_POST['campo1'];
		$campo2 = $_POST['campo2'];
		$campo3 = $_POST['campo3'];
		$campo4 = $_POST['campo4'];
		$campo5 = $_POST['campo5'];
		$campo6 = $_POST['campo6'];
		$campo7 = $_POST['campo7'];
		$campo8 = $_POST['campo8'];
		$campo9 = $_POST['campo9'];
		$campo10 = $_POST['campo10'];

		// si no hay errores
		if (mysqli_query(parent::con(),"INSERT INTO `tbl_contenido_tablas`(`ct_linea`, `ct_col1`, `ct_col2`, `ct_col3`, `ct_col4`, `ct_col5`, `ct_col6`, `ct_col7`, `ct_col8`, `ct_col9`, `ct_col10`) VALUES ('$this->linea','$campo1','$campo2','$campo3','$campo4','$campo5','$campo6','$campo7','$campo8','$campo9','$campo10')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarFila($id_fila)
	{
		$this->id=$id_fila;	

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_contenido_tablas` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function getTitulos($lin)
	{
		$this->linea=$lin;

		$query="SELECT `et_id`, `et_titulo`, `et_orden` FROM `tbl_estructura_tablas` WHERE et_linea='$this->linea' ORDER BY et_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}


	public function updateTitulos($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_estructura_tablas` set ".$nombre." = '".$value."' WHERE et_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function AgregarTitulo($lin)
	{
		$this->linea=$lin;

		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el título';

		// si no hay errores
		if (mysqli_query(parent::con(),"INSERT INTO `tbl_estructura_tablas`(`et_linea`, `et_titulo`) VALUES ('$this->linea','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarTitulo($id_fila)
	{
		$this->id=$id_fila;	

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_estructura_tablas` WHERE et_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

}


class Caracteristicas extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT `car_id`, `car_titulo`, `car_contenido`, `car_orden`, `ct_id`, `ct_titulo` FROM `tbl_caracteristicas` 
		INNER JOIN tbl_tit_caracteristicas ON tbl_tit_caracteristicas.ct_id=tbl_caracteristicas.car_titulo
		WHERE car_linea='$this->linea' ORDER BY car_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboTitulos()
	{

		$query="SELECT `ct_id`, `ct_titulo` FROM `tbl_tit_caracteristicas` ORDER BY ct_titulo ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_caracteristicas` set ".$nombre." = '".$value."' WHERE car_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function AgregarFila($lin)
	{
		$this->linea=$lin;

		// definimos las variables
		if ( !empty($_POST['titulo']) )			$titulo = $_POST['titulo']; else return 'Ingrese el título';
		if ( !empty($_POST['campo2']) )			$campo2 = $_POST['campo2']; else return 'Ingrese el texto';


		// si no hay errores
		if (mysqli_query(parent::con(),"INSERT INTO `tbl_caracteristicas`(`car_linea`, `car_titulo`, `car_contenido`) VALUES ('$this->linea','$titulo','$campo2')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarFila($id_fila)
	{
		$this->id=$id_fila;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_caracteristicas` WHERE car_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

}

class TitulosCaracteristicas extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT `ct_id`, `ct_titulo` FROM `tbl_tit_caracteristicas` ORDER BY ct_titulo ASC";

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
		$sql = "UPDATE `tbl_tit_caracteristicas` set ".$nombre." = '".$value."' WHERE ct_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';


		if (mysqli_query(parent::con(),"INSERT INTO `tbl_tit_caracteristicas`(`ct_titulo`) VALUES ('$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_tit_caracteristicas` WHERE ct_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}

class CaracteristicasIconos extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT `ci_id`, `ci_texto`, `ci_icono`, `ci_orden`, `ir_alias`, `ir_nombre` FROM `tbl_caracteristicas_iconos` 
		INNER JOIN tbl_iconos_resumen ON tbl_iconos_resumen.ir_id=tbl_caracteristicas_iconos.ci_icono 
		WHERE ci_linea='$this->linea' ORDER BY ci_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboIconos()
	{

		$query="SELECT `ir_id`, `ir_nombre` FROM `tbl_iconos_resumen` ORDER BY ir_nombre ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}


	public function updateEmployee($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_caracteristicas_iconos` set ".$nombre." = '".$value."' WHERE ci_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function AgregarFila($lin)
	{
		$this->linea=$lin;

		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el título';
		if ( !empty($_POST['icono']) )			$icono = $_POST['icono']; else return 'Seleccione el icono';


		// si no hay errores
		if (mysqli_query(parent::con(),"INSERT INTO `tbl_caracteristicas_iconos`(`ci_linea`, `ci_texto`, `ci_icono`) VALUES ('$this->linea','$campo1','$icono')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarFila($id_fila)
	{
		$this->id=$id_fila;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_caracteristicas_iconos` WHERE ci_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

}

class DatosExtras extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT `de_id`, `de_colores`, `de_simbolos`, `de_temperatura`, `de_lamparas` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";

		$result=mysqli_query(parent::con(),"$query");

		$nombresCol='';
		$nombresSim='';
		$nombresTemp='';
		$nombresLam='';

			while ($row=$result->fetch_assoc())
			{
				if ($row['de_colores']!='') {
					$arr_col = explode(",", $row['de_colores']);
					$arr_lengthcol = count($arr_col);

					for($i=0;$i<$arr_lengthcol;$i++)
					{
						$query="SELECT * FROM `tbl_colores` WHERE col_id='$arr_col[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$col=$result->fetch_assoc();

						$nombresCol.=$col["col_nombre"].'<br>';
					}
				}


				if ($row['de_simbolos']!='') {
					$arr_sim = explode(",", $row['de_simbolos']);
					$arr_lengthsim = count($arr_sim);

					for($i=0;$i<$arr_lengthsim;$i++)
					{
						$query="SELECT * FROM `tbl_simbolos` WHERE sim_id='$arr_sim[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$sim=$result->fetch_assoc();

						$nombresSim.='<img src="../simbolos/'.$sim["sim_alias"].'.svg" width="15px"> '.$sim["sim_nombre"].'<br>';
					}
				}


				if ($row['de_temperatura']!='') {
					$arr_temp = explode(",", $row['de_temperatura']);
					$arr_lengthtemp = count($arr_temp);

					for($i=0;$i<$arr_lengthtemp;$i++)
					{
						$query="SELECT * FROM `tbl_temperaturas` WHERE temp_id='$arr_temp[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$temp=$result->fetch_assoc();

						$nombresTemp.=$temp["temp_nombre"].'<br>';
					}
				}


				if ($row['de_lamparas']!='') {
					$arr_lam = explode(",", $row['de_lamparas']);
					$arr_lengthlam = count($arr_lam);

					for($i=0;$i<$arr_lengthlam;$i++)
					{
						$query="SELECT * FROM `tbl_lamparas` WHERE lam_id='$arr_lam[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$lam=$result->fetch_assoc();

						$nombresLam.=$lam["lam_nombre"].'<br>';
					}
				}


				$row['nombresCol']=$nombresCol;
				$row['nombresSim']=$nombresSim;
				$row['nombresTemp']=$nombresTemp;
				$row['nombresLam']=$nombresLam;

				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboColores()
	{
		$query="SELECT `col_id`, `col_nombre`, `col_hexa` FROM `tbl_colores` ORDER BY col_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}
	public function ComboSimbolos()
	{
		$query="SELECT `sim_id`, `sim_nombre` FROM `tbl_simbolos` ORDER BY sim_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}
	public function ComboTemperatura()
	{
		$query="SELECT `temp_id`, `temp_nombre` FROM `tbl_temperaturas` ORDER BY temp_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}
	public function ComboLamparas()
	{
		$query="SELECT `lam_id`, `lam_nombre` FROM `tbl_lamparas` ORDER BY lam_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$strvalue = implode(",", $value);

		$sql = "UPDATE `tbl_datos_extras` set ".$nombre." = '".$strvalue."' WHERE de_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

}


class Relacionados extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT `ln_id`, `ln_relacionados` FROM `tbl_lineas` WHERE ln_id='$this->linea'";

		$result=mysqli_query(parent::con(),"$query");

		$nombresRel='';

			while ($row=$result->fetch_assoc())
			{


				if ($row['ln_relacionados']!='') {
					$arr_rel = explode(",", $row['ln_relacionados']);
					$arr_lengthrel = count($arr_rel);

					for($i=0;$i<$arr_lengthrel;$i++)
					{
						$query="SELECT * FROM `tbl_lineas` WHERE ln_id='$arr_rel[$i]'";
						$resultlin=mysqli_query(parent::con(),"$query");
						$rel=$resultlin->fetch_assoc();

						$nombresRel.=$rel["ln_nombre"].'<br>';
					}
				}


				$row['nombresRel']=$nombresRel;

				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboRelacionados()
	{
		$query="SELECT `ln_id`, `ln_nombre` FROM `tbl_lineas` ORDER BY ln_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
		return $output;
	}

	public function updateEmployee($nombre,$value,$pk)
	{
		$query="SELECT `ln_tipo` FROM `tbl_lineas` 
		INNER JOIN tbl_tipos_luminarias ON tbl_tipos_luminarias.tl_id=tbl_lineas.ln_tipo
		WHERE ln_id='$pk' ";
		$result=mysqli_query(parent::con(),"$query");
		$row=$result->fetch_assoc();

		$tipoOrig=$row['ln_tipo'];

		$relacionados=array();

		foreach ($value as $clave => $val) {
			
			$query="SELECT `ln_tipo` FROM `tbl_lineas` 
			INNER JOIN tbl_tipos_luminarias ON tbl_tipos_luminarias.tl_id=tbl_lineas.ln_tipo
			WHERE ln_id='$val' ";
			$result=mysqli_query(parent::con(),"$query");
			$row=$result->fetch_assoc();
			$tipo=$row['ln_tipo'];

			if ($tipoOrig==$tipo) {
				array_unshift($relacionados, $val);
			} else {
				$relacionados[]=$val;
			}

		}

		$strvalue = implode(",", $relacionados);

		$sql = "UPDATE `tbl_lineas` set ".$nombre." = '".$strvalue."' WHERE ln_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

}

class InformacionAdicional extends Conexion
{
	public $id;
	public $accion;
	public $linea;

	public function traer($id)
	{
		$this->id=$id;

		$query="SELECT * FROM `tbl_info_adicional` WHERE ia_id='$this->id' ";
		$result=mysqli_query(parent::con(),"$query");

		return $result->fetch_assoc();
	}

	public function Agregar($lin)
	{
		$this->linea=$lin;

			// definimos las variables
			if (!empty($_POST['titulo']))			$titulo = $_POST['titulo']; else return 'Ingrese el titulo';
			if (!empty($_POST['campo1']))			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';


			if (mysqli_query(parent::con(),"INSERT INTO `tbl_info_adicional`(`ia_linea`,`ia_titulo`,`ia_contenido`) VALUES ('$this->linea','$titulo','$campo1')")) {
				return 'agregado';
			} else {
				return 'Ocurrio un error, intente nuevamente';
			}
	}

	public function Editar($id)
	{
		$this->id=$id;

			$titulo = $_POST['titulo'];
			$campo1 = $_POST['campo1'];

			$sql = "UPDATE `tbl_info_adicional` set ia_titulo='".$titulo."', ia_contenido='".$campo1."' WHERE ia_id='".$this->id."'";
		
			if($result = mysqli_query(parent::con(), $sql)) {
				return 'agregado';
			} else {
				die("error to update '".$params["name"]."' with '".$params["value"]."'");
			}
	}

	public function borrarInfo($id)
	{
		$this->id=$id;

		$sql="DELETE FROM `tbl_info_adicional` WHERE ia_id='$this->id'";
		if($result = mysqli_query(parent::con(), $sql)) {
			return 'eliminado';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function ListaInformacion()
	{
		$query="SELECT * FROM `tbl_info_adicional` 
		INNER JOIN tbl_lineas ON tbl_lineas.ln_id=tbl_info_adicional.ia_linea 
		ORDER BY ia_linea ASC";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt = mysqli_num_rows($result);

			while ($row=$result->fetch_assoc())
				{
					echo '<tr>
                                                   <th scope="row">'.$row['ia_id'].'</th>
                                                   <td><strong>'.$row['ln_nombre'].'</strong></td>
                                                   <td>'.$row['ia_titulo'].'</td>
                                                   <td><a href="editar-informacion-adicional.php?id='.$row['ia_id'].'" class="btn btn-success">Editar</a></td>
                                                   <td><a href="informacion-adicional.php?action=delete&id='.$row["ia_id"].'" class="btn btn-danger">Eliminar</a></td>
                                               </tr>';
			}
	}

}

class Lineas extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{

		$query="SELECT `ln_id`, `ln_nombre`, `ln_tipo`, `ln_ubicacion`, `tl_nombre` FROM `tbl_lineas` 
		INNER JOIN tbl_tipos_luminarias ON tbl_tipos_luminarias.tl_id=tbl_lineas.ln_tipo ORDER BY ln_nombre ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function ComboTipoLuminarias()
	{

		$query="SELECT `tl_id`, `tl_nombre` FROM `tbl_tipos_luminarias` ORDER BY tl_nombre ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$output[] = $row;
			}
	
		return $output;
	}

	public function editar($nombre,$value,$pk)
	{

		if ($nombre=="ln_tipo") {
			$value = implode(",", $value);
		}

		$sql = "UPDATE `tbl_lineas` set ".$nombre." = '".$value."' WHERE ln_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			if ($nombre=='ln_nombre') {
				$alias=Varias::crear_url($value);
				$query = "UPDATE `tbl_lineas` set ln_alias='$alias' WHERE ln_id='".$pk."'";
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
		if ( !empty($_POST['tipo']) )			$tipo = $_POST['tipo']; else return 'Complete el tipo de luminaria';
		if ( !empty($_POST['ubicacion']) )			$ubicacion = $_POST['ubicacion']; else return 'Complete donde se muestra la línea';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_lineas`(`ln_alias`,`ln_nombre`,`ln_tipo`,`ln_ubicacion`) VALUES ('$alias','$campo1','$tipo','$ubicacion')")) {

			$result=mysqli_query(parent::con(),"SELECT MAX(ln_id) FROM tbl_lineas");
			$nid=$result->fetch_assoc();
			
			$id_new=$nid["MAX(ln_id)"];

			mysqli_query(parent::con(),"INSERT INTO `tbl_datos_extras`(`de_linea`) VALUES ('$id_new')");

			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_lineas` WHERE ln_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function listaOrdenLinea($tipo)
	{
		$this->id=$tipo;

		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_lineas WHERE ln_tipo='$this->id' ORDER BY ln_orden ASC");
		$i=1;
		while ($reg=$result->fetch_assoc())
		{
			echo '<li class="ui-state-default" id="elemento-'.$reg["ln_id"].'">'.$reg["ln_nombre"].'</li>';
			$i++;
		}
	}

	public function reordenarLinea($id, $orden)
	{
		$result=mysqli_query(parent::con(),"UPDATE tbl_lineas SET ln_orden = '$orden' WHERE ln_id = '$id' ");
	}

	
}

class TiposLuminarias extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{

		$query="SELECT `tl_id`, `tl_alias`, `tl_nombre`, `tl_orden` FROM `tbl_tipos_luminarias` ORDER BY tl_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$rutaFoto = '../../img/categorias/'.$row['tl_alias'].'.jpg'; 
				if (file_exists($rutaFoto)) {
					$row['foto']=$row['tl_alias'].'.jpg';
				} else {
					$row['foto']="sin-imagen.jpg";
				}

				$rutaPortada = '../../img/caratulas/'.$row['tl_alias'].'.jpg'; 
				if (file_exists($rutaPortada)) {
					$row['caratula']=$row['tl_alias'].'.jpg';
				} else {
					$row['caratula']="sin-imagen.jpg";
				}

				$output[] = $row;
			}
	
		echo json_encode($output);
	}

	public function editar($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_tipos_luminarias` set ".$nombre." = '".$value."' WHERE tl_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {

			if ($nombre=='tl_nombre') {
				$resultAlias=mysqli_query(parent::con(),"SELECT `tl_alias` FROM `tbl_tipos_luminarias` WHERE tl_id='".$pk."'");
				$row=$resultAlias->fetch_assoc();
				$aliasviejo=$row['tl_alias'];

				$alias=Varias::crear_url($value);
				$query = "UPDATE `tbl_tipos_luminarias` set tl_alias='$alias' WHERE tl_id='".$pk."'";
				$result = mysqli_query(parent::con(), $query);
				rename ("../../img/categorias/".$aliasviejo.".jpg", "../../img/categorias/".$alias.".jpg");
				rename ("../../img/caratulas/".$aliasviejo.".jpg", "../../img/caratulas/".$alias.".jpg");
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

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_tipos_luminarias`(`tl_alias`,`tl_nombre`) VALUES ('$alias','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_tipos_luminarias` WHERE tl_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}

class Colores extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT * FROM `tbl_colores` ORDER BY col_nombre ASC";

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
		$sql = "UPDATE `tbl_colores` set ".$nombre." = '".$value."' WHERE col_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';
		$campo2 = $_POST['campo2'];
		if ( !empty($_POST['color']) )			$hexa = $_POST['color']; else return 'Ingrese el color hexadecimal';
		$hexatext = $_POST['colortext'];

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_colores`(`col_alias`,`col_nombre`,`col_iniciales`,`col_hexa`,`col_hexa_text`) VALUES ('$alias','$campo1','$campo2','$hexa','$hexatext')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_colores` WHERE col_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}



class Simbolos extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT `sim_id`, `sim_alias`, `sim_nombre` FROM `tbl_simbolos` ORDER BY sim_nombre ASC";

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
		$sql = "UPDATE `tbl_simbolos` set ".$nombre." = '".$value."' WHERE sim_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_simbolos`(`sim_alias`,`sim_nombre`) VALUES ('$alias','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_simbolos` WHERE sim_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}

class Iconos extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT `ir_id`, `ir_alias`, `ir_nombre` FROM `tbl_iconos_resumen` ORDER BY ir_nombre ASC";

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
		$sql = "UPDATE `tbl_iconos_resumen` set ".$nombre." = '".$value."' WHERE ir_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_iconos_resumen`(`ir_alias`,`ir_nombre`) VALUES ('$alias','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_iconos_resumen` WHERE ir_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}

class Temperaturas extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT `temp_id`,`temp_alias`,`temp_nombre` FROM `tbl_temperaturas` ORDER BY temp_nombre ASC";

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
		$sql = "UPDATE `tbl_temperaturas` set ".$nombre." = '".$value."' WHERE temp_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_temperaturas`(`temp_alias`,`temp_nombre`) VALUES ('$alias','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_temperaturas` WHERE temp_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}


class Lamparas extends Conexion
{
	public $id;
	public $accion;
	public $imagen;

	public function lista()
	{
		$query="SELECT `lam_id`,`lam_alias`,`lam_nombre` FROM `tbl_lamparas` ORDER BY lam_nombre ASC";

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
		$sql = "UPDATE `tbl_lamparas` set ".$nombre." = '".$value."' WHERE lam_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function agregar()
	{
		
		// definimos las variables
		if ( !empty($_POST['campo1']) )			$campo1 = $_POST['campo1']; else return 'Ingrese el nombre';

		$alias=Varias::crear_url($campo1);

		if (mysqli_query(parent::con(),"INSERT INTO `tbl_lamparas`(`lam_alias`,`lam_nombre`) VALUES ('$alias','$campo1')")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}

	}

	public function borrar($id)
	{
		$this->id=$id;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_lamparas` WHERE lam_id='$this->id'")) {
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
}

class FichaPDF extends Conexion
{
	public $linea;
	
	public function FotosFichaPDF($lin)
	{
		$this->linea = mysqli_real_escape_string(parent::con(), $lin);

		$query="SELECT * FROM `tbl_img` WHERE im_linea='$this->linea' ORDER BY im_orden ASC LIMIT 1";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<img src="../img/productos/grandes/'.$row['im_nombre'].'" width="100%" class="img-princ">';
			}
	}

	public function FotosFichaPDFChica($lin)
	{
		$this->linea = mysqli_real_escape_string(parent::con(), $lin);

		$query="SELECT * FROM `tbl_img` WHERE im_linea='$this->linea' ORDER BY im_orden ASC LIMIT 1";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<img src="../img/productos/grandes/'.$row['im_nombre'].'" width="70%" class="img-princ">';
			}
	}

	public function CaracteristicasIconos($lin)
	{
		$this->linea=$lin;

		$query="SELECT `ci_id`, `ci_texto`, `ci_icono`, `ci_orden`, `ir_alias`, `ir_nombre` FROM `tbl_caracteristicas_iconos` 
		INNER JOIN tbl_iconos_resumen ON tbl_iconos_resumen.ir_id=tbl_caracteristicas_iconos.ci_icono 
		WHERE ci_linea='$this->linea' ORDER BY ci_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo '<li><p>'.$row['ci_texto'].'</p><img src="../iconos/png/'.$row['ir_alias'].'.png" width="20" /></li>';
			}
	}

	public function CaracteristicasTexto($lin)
	{
		$this->linea=$lin;

		$query="SELECT `car_id`, `car_titulo`, `car_contenido`, `car_orden`, `ct_id`, `ct_titulo` FROM `tbl_caracteristicas` 
		INNER JOIN tbl_tit_caracteristicas ON tbl_tit_caracteristicas.ct_id=tbl_caracteristicas.car_titulo
		WHERE car_linea='$this->linea' ORDER BY car_orden ASC";


		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><span>'.$row['ct_titulo'].': </span>'.$row['car_contenido'].'</li>';
			}
	}
	public function Tablas($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_estructura_tablas` WHERE et_linea='$this->linea' ORDER BY et_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		echo '<table class="table table-striped table-condensed text-center">
                    <thead><tr>';
			$cont=0;
			while ($row=$result->fetch_assoc())
			{
				echo '<th>'.$row["et_titulo"].'</th>';
				$cont++;
			}

		echo '</tr></thead>';



		$query="SELECT `ct_col1`, `ct_col2`, `ct_col3`, `ct_col4`, `ct_col5`, `ct_col6`, `ct_col7`, `ct_col8`, `ct_col9`, `ct_col10` FROM `tbl_contenido_tablas` WHERE ct_linea='$this->linea' ORDER BY ct_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		echo '<tbody>';

			while ($row=$result->fetch_assoc())
			{
				$row=array_values($row);
				echo '<tr>';
				for ($i=0; $i < $cont; $i++) {
					echo '<td>'.$row[$i].'</td>';
				}
				echo '</tr>';
			}

		echo '</tbody></table>';
	}

	public function Simbolos($lin)
	{
		$this->linea=$lin;

		$query="SELECT `de_simbolos` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$nombresSim='';

		$row=$result->fetch_assoc();

				if ($row['de_simbolos']!='') {
					$arr_sim = explode(",", $row['de_simbolos']);
					$arr_lengthsim = count($arr_sim);

					for($i=0;$i<$arr_lengthsim;$i++)
					{
						$query="SELECT * FROM `tbl_simbolos` WHERE sim_id='$arr_sim[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$sim=$result->fetch_assoc();

						$nombresSim.='<li><img src="../simbolos/png/'.$sim["sim_alias"].'.png" height="15px"></li>';
					}

					echo '<ul class="list-inline list-simb">'.$nombresSim.'</ul>';
				}

	}

	public function GrafTemp($lin)
	{
		$this->linea=$lin;

		$query="SELECT `de_temperatura` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$nombresTemp='';

		$row=$result->fetch_assoc();

				if ($row['de_temperatura']!='') {
					$arr_temp = explode(",", $row['de_temperatura']);
					$arr_lengthtemp = count($arr_temp);

					for($i=0;$i<$arr_lengthtemp;$i++)
					{
						$query="SELECT * FROM `tbl_temperaturas` WHERE temp_id='$arr_temp[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$temp=$result->fetch_assoc();

						$nombresTemp.='<li><img src="../temperaturas/'.$temp["temp_alias"].'.png" width="125"></li>';
					}

					echo '<ul class="list-unstyled list-temp">'.$nombresTemp.'</ul>';
				}

	}

	public function Colores($lin)
	{
		$this->linea=$lin;

		$query="SELECT `de_colores` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$nombresCol='';

		$row=$result->fetch_assoc();

				if ($row['de_colores']!='') {
					$arr_col = explode(",", $row['de_colores']);
					$arr_lengthcol = count($arr_col);

					for($i=0;$i<$arr_lengthcol;$i++)
					{
						$query="SELECT * FROM `tbl_colores` WHERE col_id='$arr_col[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$col=$result->fetch_assoc();

						$nombresCol.='<li class="text-center" style="background-color:'.$col["col_hexa"].'"><span style="color: '.$col["col_hexa_text"].';">'.$col["col_iniciales"].'</span></li>';

					}

					echo '<p class="title-graf">COLORES</p>
                        <ul class="list-inline list-col">'.$nombresCol.'</ul>';
				}

	}

	public function Lamparas($lin)
	{
		$this->linea = mysqli_real_escape_string(parent::con(), $lin);

		$query="SELECT `de_lamparas` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$nombresLam='';

		$row=$result->fetch_assoc();


				if ($row['de_lamparas']!='') {
					$arr_lam = explode(",", $row['de_lamparas']);
					$arr_lengthlam = count($arr_lam);

					for($i=0;$i<$arr_lengthlam;$i++)
					{
						$query="SELECT * FROM `tbl_lamparas` WHERE lam_id='$arr_lam[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$lam=$result->fetch_assoc();

						$file='../lamparas/'.$lam["lam_alias"].'.png';
						$imagen = getimagesize($file);
						$ancho = $imagen[0]/2.5;
						$alto = $imagen[1];

						$nombresLam.='<li><img src="../lamparas/'.$lam["lam_alias"].'.png" width="'.$ancho.'"><p class="title-graf">'.$lam["lam_nombre"].'</p></li>';
					}

					echo '<ul class="list-unstyled list-lamp">'.$nombresLam.'</ul>';
				}

	}

	public function DibujosTecnicos($lin,$anc)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_dibujos_tecnicos` WHERE dt_linea='$this->linea' ORDER BY dt_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {

			echo '<h2 class="subtitle m-0">DIMENSIONES</h2><br>
			<ul class="list-inline list-dib">';
			$anchoMax=0;
			while ($row=$result->fetch_assoc())
			{
				$file='../img/dibujos-tecnicos/'.$row['dt_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/3.5;
				$alto = $imagen[1];

				$anchoMax=$anchoMax+$ancho;

				if ($anchoMax>$anc) {
					echo '<br>';
					$anchoMax=$ancho;
				}

				echo'<li><p class="title-graf">'.$row['dt_texto'].'</p><img src="../img/dibujos-tecnicos/'.$row['dt_nombre'].'" width="'.$ancho.'"></li>';

			}
			echo '</ul>';
		}
	}

	public function Fotometrias($lin,$anc)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_fotometrias` WHERE fot_linea='$this->linea' ORDER BY fot_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {
		

			echo '<h2 class="subtitle m-0">FOTOMETRÍA</h2><br>
			<ul class="list-inline list-dib">';
			$anchoMax=0;
			while ($row=$result->fetch_assoc())
			{
				$file='../img/fotometrias/'.$row['fot_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/3.5;
				$alto = $imagen[1];

				$anchoMax=$anchoMax+$ancho;

				if ($anchoMax>$anc) {
					echo '<br>';
					$anchoMax=$ancho;
				}

				echo'<li><p class="title-graf">'.$row['fot_texto'].'</p><img src="../img/fotometrias/'.$row['fot_nombre'].'" width="'.$ancho.'"></li>';
			}
			echo '</ul>';

		}
	}

	public function Luminancias($lin,$anc)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_luminancias` WHERE lum_linea='$this->linea' ORDER BY lum_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {
		

			echo '<h2 class="subtitle m-0">ILUMINANCIA</h2><br>
			<ul class="list-inline list-dib">';
			$anchoMax=0;

			while ($row=$result->fetch_assoc())
			{
				$file='../img/iluminancias/'.$row['lum_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/3.5;
				$alto = $imagen[1];

				$anchoMax=$anchoMax+$ancho;

				if ($anchoMax>$anc) {
					echo '<br>';
					$anchoMax=$ancho;
				}

				echo'<li><p class="title-graf">'.$row['lum_texto'].'</p><img src="../img/iluminancias/'.$row['lum_nombre'].'" width="'.$ancho.'"></li>';
			}

			echo '</ul>';
		}
	}


	public function InfoAdicional($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_renders` WHERE ren_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$row_cnt = $result->num_rows;

		if ($row_cnt>0) {
		
			echo '<div style="page-break-before: always;"></div>';

			echo'<div class="row pdf_content-ficha">
                <div class="col-xs-12">';

			while ($row=$result->fetch_assoc())
			{
				echo'<img src="../img/renders/'.$row['ren_nombre'].'" width="100%">';
			}

			echo'</div></div>';

			$query="SELECT * FROM `tbl_info_adicional` WHERE ia_linea='$this->linea'";
			$result=mysqli_query(parent::con(),"$query");

			$row_cnt = $result->num_rows;

			if ($row_cnt>0) {

				if ($row_cnt==2) {
					echo'<div class="row pdf_content-ficha">';
					while ($row=$result->fetch_assoc())
					{
						echo '<div class="col-xs-5">';
						echo '<h2 class="subtitle m-0">'.$row['ia_titulo'].'</h2><br>';
						echo '<div class="txt-gral">'.$row['ia_contenido'].'</div>';
						echo '</div>';
					}
					echo'</div>';

					$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";
					$result=mysqli_query(parent::con(),"$query");

					$row_cnt = $result->num_rows;

					if ($row_cnt>0) {

						echo '<div class="row pdf_content-ficha">
							<div class="col-xs-12">
							<h2 class="subtitle m-0">MÓDULOS</h2><br>
                    		<div class="content-graf">';

						echo '<ul class="list-inline list-mod">';
						$anchoMax=0;
						while ($row=$result->fetch_assoc())
						{
							$file='../img/modulos/'.$row['mod_nombre'];
							$imagen = getimagesize($file);
							$ancho = $imagen[0]/3.5;
							$alto = $imagen[1];

							$anchoMax=$anchoMax+$ancho;

							if ($anchoMax>700) {
								echo '<br>';
								$anchoMax=$ancho;
							}

							echo'<li><p class="title-graf">'.$row['mod_texto'].'</p><img src="../img/modulos/'.$row['mod_nombre'].'" width="'.$ancho.'"></li>';

						}
						echo '</ul></div></div></div>';
					} 

				} else {
					echo'<div class="row pdf_content-ficha">';
					while ($row=$result->fetch_assoc())
					{
						echo '<div class="col-xs-5">';
						echo '<h2 class="subtitle m-0">'.$row['ia_titulo'].'</h2><br>';
						echo '<div class="txt-gral">'.$row['ia_contenido'].'</div>';
						echo '</div>';
					}

					$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";
					$result=mysqli_query(parent::con(),"$query");

					$row_cnt = $result->num_rows;

					if ($row_cnt>0) {

						echo '<div class="col-xs-5">
							<h2 class="subtitle m-0">MÓDULOS</h2><br>
                    		<div class="content-graf">';

						echo '<ul class="list-inline list-mod">';
						$anchoMax=0;
						while ($row=$result->fetch_assoc())
						{
							$file='../img/modulos/'.$row['mod_nombre'];
							$imagen = getimagesize($file);
							$ancho = $imagen[0]/3.5;
							$alto = $imagen[1];

							$anchoMax=$anchoMax+$ancho;

							if ($anchoMax>320) {
								echo '<br>';
								$anchoMax=$ancho;
							}

							echo'<li><p class="title-graf">'.$row['mod_texto'].'</p><img src="../img/modulos/'.$row['mod_nombre'].'" width="'.$ancho.'"></li>';

						}
						echo '</ul></div></div></div>';
					} else {
						echo '</div>';
					}
				}
				
			} else {

				$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";
				$result=mysqli_query(parent::con(),"$query");

				$row_cnt = $result->num_rows;

				if ($row_cnt>0) {

						echo '<div class="row pdf_content-ficha">
							<div class="col-xs-12">
							<h2 class="subtitle m-0">MÓDULOS</h2><br>
                    		<div class="content-graf">';

						echo '<ul class="list-inline list-mod">';
						$anchoMax=0;
						while ($row=$result->fetch_assoc())
						{
							$file='../img/modulos/'.$row['mod_nombre'];
							$imagen = getimagesize($file);
							$ancho = $imagen[0]/3.5;
							$alto = $imagen[1];

							$anchoMax=$anchoMax+$ancho;

							if ($anchoMax>700) {
								echo '<br>';
								$anchoMax=$ancho;
							}

							echo'<li><p class="title-graf">'.$row['mod_texto'].'</p><img src="../img/modulos/'.$row['mod_nombre'].'" width="'.$ancho.'"></li>';

						}
						echo '</ul></div></div></div>';
				}

			}
			
			$query="SELECT * FROM `tbl_detalles` WHERE det_linea='$this->linea' ORDER BY det_orden ASC";
			$result=mysqli_query(parent::con(),"$query");

			$row_cnt = $result->num_rows;

			if ($row_cnt>0) {
				echo '<div class="row pdf_content-ficha">
						<div class="col-xs-12">
							<h2 class="subtitle">DETALLES</h2><br>
								<ul class="list-inline list-det">';
					$countDet=0;
					while ($row=$result->fetch_assoc())
						{
							if ($countDet==3) {
								echo '<br>';
							}
							echo'<li><p class="title-graf">'.$row['det_texto'].'</p><img src="../img/detalles/'.$row['det_nombre'].'" width="32%"></li>';
							$countDet++;
						}
				echo '</ul></div></div>';
			}

		} else {

			$query="SELECT * FROM `tbl_detalles` WHERE det_linea='$this->linea' ORDER BY det_orden ASC";
			$result=mysqli_query(parent::con(),"$query");

			$row_cnt = $result->num_rows;

			if ($row_cnt>0) {
				echo '<div style="page-break-before: always;"></div>';
				echo '<div class="row pdf_content-ficha">
						<div class="col-xs-12">
							<h2 class="subtitle">DETALLES</h2><br>
								<ul class="list-inline list-det">';
					$countDet=0;
					while ($row=$result->fetch_assoc())
						{
							if ($countDet==3) {
								echo '<br>';
							}
							echo'<li><p class="title-graf">'.$row['det_texto'].'</p><img src="../img/detalles/'.$row['det_nombre'].'" width="32%"></li>';
							$countDet++;
						}
				echo '</ul></div></div>';
			}


			$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";
				$result=mysqli_query(parent::con(),"$query");

				$row_cnt = $result->num_rows;

				if ($row_cnt>0) {

						echo '<div class="row pdf_content-ficha">
							<div class="col-xs-12">
							<h2 class="subtitle m-0">MÓDULOS</h2><br>
                    		<div class="content-graf">';

						echo '<ul class="list-inline list-mod">';
						$anchoMax=0;
						while ($row=$result->fetch_assoc())
						{
							$file='../img/modulos/'.$row['mod_nombre'];
							$imagen = getimagesize($file);
							$ancho = $imagen[0]/3.5;
							$alto = $imagen[1];

							$anchoMax=$anchoMax+$ancho;

							if ($anchoMax>700) {
								echo '<br>';
								$anchoMax=$ancho;
							}

							echo'<li><p class="title-graf">'.$row['mod_texto'].'</p><img src="../img/modulos/'.$row['mod_nombre'].'" width="'.$ancho.'"></li>';

						}
						echo '</ul></div></div></div>';
				}
		}
	}

	public function AmbienteSimple($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ambientes` WHERE amb_linea='$this->linea' AND amb_disp='simple' ORDER BY amb_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<div class="row pdf_content-ficha">
					<div class="col-xs-12">';
				echo'<img src="../img/ambientes/'.$row['amb_nombre'].'" width="100%">';
				echo '</div></div>';
			}
	}

	public function AmbienteDoble($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ambientes` WHERE amb_linea='$this->linea' AND amb_disp='doble' ORDER BY amb_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		$count=0;
			while ($row=$result->fetch_assoc())
			{
				echo'<div class="row pdf_content-ficha">
					<div class="col-xs-12 '; 

					if ($count==0) {
						echo 'amb-izq';
					} else {
						echo 'amb-der';
					}

					echo '">';
				echo'<img src="../img/ambientes/'.$row['amb_nombre'].'" width="100%">';
				echo '</div></div>';

				if ($count==0) {
					echo '<div style="page-break-before: always;"></div>';
				}

				$count++;
			}
	}
}

class Previsualizacion extends Conexion
{
	public $id;
	public $linea;
	private $output=array();

	public function ListaLineas()
	{
		$query="SELECT * FROM `tbl_lineas` 
		INNER JOIN tbl_tipos_luminarias ON tbl_tipos_luminarias.tl_id=tbl_lineas.ln_tipo WHERE ln_ubicacion!='borrador'";

		if(isset($_GET['search'])){
			$this->busqueda = mysqli_real_escape_string(parent::con(), $_GET['search']);
			if(!strstr($query,"WHERE")){
				$query .= " WHERE ln_nombre LIKE '%$this->busqueda%'";
			}else{
				$query .= " AND ln_nombre LIKE '%$this->busqueda%'";
			}
		}

		$query .= " ORDER BY ln_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");

		$row_cnt = mysqli_num_rows($result);

		if($row_cnt>0) {

			while ($row=$result->fetch_assoc())
				{
					echo '<tr>
                                                   <th scope="row">'.$row['ln_id'].'</th>
                                                   <td><strong>'.$row['ln_nombre'].'</strong></td>
                                                   <td>'.$row['tl_nombre'].'</td>
                                                   <td><a href="caracteristicas.php?lin='.$row['ln_id'].'" class="btn btn-success">Descripción</a></td>
                                                   <td><a href="caracteristicas-iconos.php?lin='.$row['ln_id'].'" class="btn btn-success">Iconos</a></td>
                                                   <td><a href="datos-extra.php?lin='.$row['ln_id'].'" class="btn btn-success">Datos tecnicos</a></td>
                                                   <td><a href="table-data.php?lin='.$row['ln_id'].'" class="btn btn-success">Tablas</a></td>
                                                   <td><a href="ficha.php?lin='.$row['ln_id'].'" class="btn btn-info btn-rounded"><i class="icon-eye"></i> Vista Previa</a></td>
                                               </tr>';
			}
		} else {
			echo '<tr>
                    <td coldspan=8 ><p class="text-danger">No hay resultados para la busqueda</p></td>
                </tr>';
		}
	}

	public function DatosLinea($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_lineas` 
		INNER JOIN tbl_tipos_luminarias ON tbl_tipos_luminarias.tl_id=tbl_lineas.ln_tipo 
		WHERE ln_id='$this->linea'";

		$result=mysqli_query(parent::con(),"$query");

		return $row=$result->fetch_assoc();
	}

	public function CaracteristicasIconos($lin)
	{
		$this->linea=$lin;

		$query="SELECT `ci_id`, `ci_texto`, `ci_icono`, `ci_orden`, `ir_alias`, `ir_nombre` FROM `tbl_caracteristicas_iconos` 
		INNER JOIN tbl_iconos_resumen ON tbl_iconos_resumen.ir_id=tbl_caracteristicas_iconos.ci_icono 
		WHERE ci_linea='$this->linea' ORDER BY ci_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo '<li><img src="../iconos/'.$row['ir_alias'].'.svg" width="40px"> '.$row['ci_texto'].'</li>';
			}
	}
	public function CaracteristicasTexto($lin)
	{
		$this->linea=$lin;

		$query="SELECT `car_id`, `car_titulo`, `car_contenido`, `car_orden`, `ct_id`, `ct_titulo` FROM `tbl_caracteristicas` 
		INNER JOIN tbl_tit_caracteristicas ON tbl_tit_caracteristicas.ct_id=tbl_caracteristicas.car_titulo
		WHERE car_linea='$this->linea' ORDER BY car_orden ASC";


		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><strong>'.$row['ct_titulo'].': </strong>'.$row['car_contenido'].'</li>';
			}
	}

	public function Datos($lin)
	{
		$this->linea=$lin;

		$query="SELECT `de_id`, `de_colores`, `de_simbolos`, `de_temperatura`, `de_lamparas` FROM `tbl_datos_extras` WHERE de_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		$nombresCol='';
		$nombresSim='';
		$nombresTemp='';
		$nombresLam='';

		$row=$result->fetch_assoc();

				if ($row['de_simbolos']!='') {
					$arr_sim = explode(",", $row['de_simbolos']);
					$arr_lengthsim = count($arr_sim);

					for($i=0;$i<$arr_lengthsim;$i++)
					{
						$query="SELECT * FROM `tbl_simbolos` WHERE sim_id='$arr_sim[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$sim=$result->fetch_assoc();

						$nombresSim.='<li><img src="../simbolos/'.$sim["sim_alias"].'.svg" width="30px"></li>';
					}

					echo '<div class="panel-body">
                                        <ul class="list-inline">'.$nombresSim.'</ul>
                                    </div>';
				}


				if ($row['de_temperatura']!='') {
					$arr_temp = explode(",", $row['de_temperatura']);
					$arr_lengthtemp = count($arr_temp);

					for($i=0;$i<$arr_lengthtemp;$i++)
					{
						$query="SELECT * FROM `tbl_temperaturas` WHERE temp_id='$arr_temp[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$temp=$result->fetch_assoc();

						$nombresTemp.='<li><img src="../temperaturas/'.$temp["temp_alias"].'.png" width="220px"></li>';
					}

					echo '<div class="panel-body">
                                        <ul class="list-inline">'.$nombresTemp.'</ul>
                                    </div>';
				}

				if ($row['de_colores']!='') {
					$arr_col = explode(",", $row['de_colores']);
					$arr_lengthcol = count($arr_col);

					for($i=0;$i<$arr_lengthcol;$i++)
					{
						$query="SELECT * FROM `tbl_colores` WHERE col_id='$arr_col[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$col=$result->fetch_assoc();

						$nombresCol.='<li>'.$col["col_nombre"].'</li>';
					}

					echo '<div class="panel-heading clearfix">
                                        <h4 class="panel-title text-info">COLORES</h4>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-unstyled">'.$nombresCol.'</ul>
                                    </div>';
				}

				if ($row['de_lamparas']!='') {
					$arr_lam = explode(",", $row['de_lamparas']);
					$arr_lengthlam = count($arr_lam);

					for($i=0;$i<$arr_lengthlam;$i++)
					{
						$query="SELECT * FROM `tbl_lamparas` WHERE lam_id='$arr_lam[$i]'";
						$result=mysqli_query(parent::con(),"$query");
						$lam=$result->fetch_assoc();

						$nombresLam.='<li>'.$lam["lam_nombre"].'</li>';
					}

					echo '<div class="panel-heading clearfix">
                                        <h4 class="panel-title text-info">LAMPARAS</h4>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-unstyled">'.$nombresLam.'</ul>
                                    </div>';
				}

				echo '<div class="panel-body">
						<hr>
                        <a href="datos-extra.php?lin='.$this->linea.'" class="btn btn-danger">Editar esta información</a>
                    </div>';

	}

	public function Tablas($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_estructura_tablas` WHERE et_linea='$this->linea' ORDER BY et_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		echo '<div class="table-responsive">
                <table id="example-editable" class="display table table-bordered table-striped" style="width: 100%; cellspacing: 0;">
                    <thead><tr>';
			$cont=0;
			while ($row=$result->fetch_assoc())
			{
				echo '<th>'.$row["et_titulo"].'</th>';
				$cont++;
			}

		echo '</tr></thead>';



		$query="SELECT `ct_col1`, `ct_col2`, `ct_col3`, `ct_col4`, `ct_col5`, `ct_col6`, `ct_col7`, `ct_col8`, `ct_col9`, `ct_col10` FROM `tbl_contenido_tablas` WHERE ct_linea='$this->linea' ORDER BY ct_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

		echo '<tbody>';

			while ($row=$result->fetch_assoc())
			{
				$row=array_values($row);
				echo '<tr>';
				for ($i=0; $i < $cont; $i++) { 
					echo '<td>'.$row[$i].'</td>';
				}
				echo '</tr>';
			}

		echo '</tbody></table></div>';
	}

	public function FotosProductos($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_img` WHERE im_linea='$this->linea' ORDER BY im_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><img src="../img/productos/'.$row['im_nombre'].'" width="300"></li>';
			}
	}

	public function AmbienteSimple($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ambientes` WHERE amb_linea='$this->linea' AND amb_disp='simple' ORDER BY amb_orden ASC";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt = $result->num_rows;
		if ($row_cnt>0) {

			while ($row=$result->fetch_assoc())
			{
				echo'<li><img src="../img/ambientes/'.$row['amb_nombre'].'" width="150"></li>';
			}

			echo '<br><br><a href="ambiente-pdf.php?lin='.$this->linea.'" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generar PDF Ambiente Simple</a>';
		}
	}

	public function AmbienteDoble($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ambientes` WHERE amb_linea='$this->linea' AND amb_disp='doble' ORDER BY amb_orden ASC";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt = $result->num_rows;
		if ($row_cnt>0) {

			while ($row=$result->fetch_assoc())
			{
				echo'<li><img src="../img/ambientes/'.$row['amb_nombre'].'" width="150"></li>';
			}

			echo '<br><br><a href="ambiente-doble-pdf.php?lin='.$this->linea.'" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generar PDF Ambiente Doble</a>';
		}
	}

	public function Render($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_renders` WHERE ren_linea='$this->linea'";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><img src="../img/renders/'.$row['ren_nombre'].'" width="300"></li>';
			}
	}

	public function InformacionAdicional($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_info_adicional` WHERE ia_linea='$this->linea'";
		$result=mysqli_query(parent::con(),"$query");

		while ($row=$result->fetch_assoc())
			{
				echo '<li>';
				echo '<h6 class="panel-title text-info">'.$row['ia_titulo'].'</h6><br>';
				echo '<div>'.$row['ia_contenido'].'</div>';
				echo '</li>';
			}
	}

	public function Modulos($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";
		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$file='../img/modulos/'.$row['mod_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/2;
				$alto = $imagen[1];

				echo'<div class="p-4"><p class="title-graf">'.$row['mod_texto'].'</p><img src="../img/modulos/'.$row['mod_nombre'].'" width="'.$ancho.'" style="max-width:100%;"></div>';
			}
	}

	public function Detalles($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_detalles` WHERE det_linea='$this->linea' ORDER BY det_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><p class="title-graf">'.$row['det_texto'].'</p><img src="../img/detalles/'.$row['det_nombre'].'" width="300"></li>';
			}
	}

	public function DibujosTecnicos($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_dibujos_tecnicos` WHERE dt_linea='$this->linea' ORDER BY dt_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$file='../img/dibujos-tecnicos/'.$row['dt_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/2;
				$alto = $imagen[1];

				echo'<div class="p-4"><p class="title-graf">'.$row['dt_texto'].'</p><img src="../img/dibujos-tecnicos/'.$row['dt_nombre'].'" width="'.$ancho.'" style="max-width:100%;"></div>';
			}
	}

	public function Fotometrias($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_fotometrias` WHERE fot_linea='$this->linea' ORDER BY fot_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$file='../img/fotometrias/'.$row['fot_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/2;
				$alto = $imagen[1];

				echo'<div class="p-4"><p class="title-graf">'.$row['fot_texto'].'</p><img src="../img/fotometrias/'.$row['fot_nombre'].'" width="'.$ancho.'" style="max-width:100%;"></div>';
			}
	}

	public function Luminancias($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_luminancias` WHERE lum_linea='$this->linea' ORDER BY lum_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				$file='../img/iluminancias/'.$row['lum_nombre'];
				$imagen = getimagesize($file);
				$ancho = $imagen[0]/2;
				$alto = $imagen[1];

				echo'<div class="p-4"><p class="title-graf">'.$row['lum_texto'].'</p><img src="../img/iluminancias/'.$row['lum_nombre'].'" width="'.$ancho.'" style="max-width:100%;"></div>';
			}
	}

	public function ArchivosIES($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ies` WHERE ies_linea='$this->linea' ORDER BY ies_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><a href="../archivos/ies/'.$row['ies_nombre'].'" target="_blank"><i class="fa fa-file-o"></i><br><small>'.$row['ies_nombre'].'</small></a><p><b>'.$row['ies_texto'].'</b></p></li>';
			}
	}

	public function Descargas($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_descargas` WHERE des_linea='$this->linea' ORDER BY des_orden ASC";

		$result=mysqli_query(parent::con(),"$query");

			while ($row=$result->fetch_assoc())
			{
				echo'<li><a href="../archivos/manuales/'.$row['des_nombre'].'" target="_blank"><i class="fa fa-file-pdf-o"></i><br><small>'.$row['des_nombre'].'</small></a><p><b>'.$row['des_texto'].'</b></p></li>';
			}
	}

	public function ListaPDFS()
	{
		$query="SELECT DISTINCT ln_id, ln_nombre, amb_disp FROM `tbl_lineas` 
		LEFT JOIN tbl_ambientes ON tbl_ambientes.amb_linea=tbl_lineas.ln_id
		WHERE ln_ubicacion='web y catalogo' OR ln_ubicacion='catalogo' OR ln_ubicacion='catalogo y buscador sitio' ORDER BY ln_nombre ASC";
		$result=mysqli_query(parent::con(),"$query");
		$row_cnt = mysqli_num_rows($result);

		while ($row=$result->fetch_assoc())
		{
			echo '<tr>
						<td><strong>'.$row['ln_nombre'].'</strong></td>
                        <td>';
                        $file='../pdf/Ficha Tecnica - '.$row['ln_nombre'].'.pdf';
                        if (file_exists($file)) {

                        	echo '<strong><small>'.date("d-m-Y (H:i:s)", filectime($file)).'</small></strong>';

                        	echo '<br><embed src="'.$file.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100" height="141"></embed><br><a href="'.$file.'" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Descargar</a>';
                        } else {
                        	echo 'PDF no generado';
                        }
                        echo '</td>
                        <td><a href="ficha-pdf.php?lin='.$row['ln_id'].'" class="btn btn-primary m-b-sm"><i class="fa fa-file-pdf-o"></i> PDF Plantilla 1</a><br>
                        <a href="ficha-pdf2.php?lin='.$row['ln_id'].'" class="btn btn-success m-b-sm"><i class="fa fa-file-pdf-o"></i> PDF Plantilla 2</a><br>
                        <a href="ficha-pdf3.php?lin='.$row['ln_id'].'" class="btn btn-info m-b-sm"><i class="fa fa-file-pdf-o"></i> PDF Plantilla 3</a><br>
                        <a href="generar-pdf-doble.php?lin='.$row['ln_id'].'" class="btn btn-warning m-b-sm"><i class="fa fa-file-pdf-o"></i> PDF 2 Productos</a></td>
                        <td>';

                        $fileAmb2='../pdf/Ambiente Simple - '.$row['ln_nombre'].'.pdf';
                        if (file_exists($fileAmb2)) {
                        	echo '<strong><small>'.date("d-m-Y (H:i:s)", filectime($fileAmb2)).'</small></strong>';
                        	echo '<br><embed src="'.$fileAmb2.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100" height="141"></embed><br><a href="'.$fileAmb2.'" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Descargar</a>';
                        } else {
                        	echo 'PDF no generado';
                        }

                        echo '</td>
                        <td>';

                        $fileAmb1='../pdf/Ambiente Doble - '.$row['ln_nombre'].'.pdf';
                        if (file_exists($fileAmb1)) {
                        	echo '<strong><small>'.date("d-m-Y (H:i:s)", filectime($fileAmb1)).'</small></strong>';
                        	echo '<br><embed src="'.$fileAmb1.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100" height="141"></embed><br><a href="'.$fileAmb1.'" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Descargar</a>';
                        } else {
                        	echo 'PDF no generado';
                        }

                        echo '</td>
                        <td>';
							if ($row['amb_disp']=='simple') {
								echo '<a href="ambiente-pdf.php?lin='.$row['ln_id'].'" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> PDF Ambiente Simple</a><br>';
							}

							if ($row['amb_disp']=='doble') {
								echo '<a href="ambiente-doble-pdf.php?lin='.$row['ln_id'].'" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> PDF Ambiente Doble</a>';
							}

                        echo '</td>
                </tr>';
		}
	}

	public function DescargarPDFS($dir, $zip)
	{
		//verificamos si $dir es un directorio
		  if (is_dir($dir)) {
		    //abrimos el directorio y lo asignamos a $da
		    if ($da = opendir($dir)) {
		      //leemos del directorio hasta que termine
		      while (($archivo = readdir($da)) !== false) {
		        /*Si es un directorio imprimimos la ruta
		         * y llamamos recursivamente esta función
		         * para que verifique dentro del nuevo directorio
		         * por mas directorios o archivos
		         */
		        if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
		          echo "<strong>Creando directorio: $dir$archivo</strong><br/>";
		          agregar_zip($dir . $archivo . "/", $zip);

		          /*si encuentra un archivo imprimimos la ruta donde se encuentra
		           * y agregamos el archivo al zip junto con su ruta 
		           */
		        } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
		          echo "Agregando archivo: $dir$archivo <br/>";
		          $zip->addFile($dir . $archivo, $dir . $archivo);
		        }
		      }
		      //cerramos el directorio abierto en el momento
		      closedir($da);
		    }
		  }
	}

}




class Imagenes extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;
	
	public function resizeFoto($im,$im_chica,$ancho_chica,$alto_chica) {
	
		list($imagewidth, $imageheight, $imageType) = getimagesize($im);
		$imageType = image_type_to_mime_type($imageType);
		
		
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($im); 
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($im); 
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($im); 
				break;
	  	}

		$tmp_chica=imagecreatetruecolor($ancho_chica,$alto_chica);	
		imagesavealpha($tmp_chica, true);
		$transparent = imagecolorallocatealpha( $tmp_chica, 0, 0, 0, 127 ); 
		imagefill($tmp_chica, 0, 0, $transparent);
		
		imagecopyresampled($tmp_chica,$source,0,0,0,0,$ancho_chica,$alto_chica,$imagewidth,$imageheight);
		
		switch($imageType) {
			case "image/gif":
		  		imagegif($tmp_chica,$im_chica); 
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($tmp_chica,$im_chica,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($tmp_chica,$im_chica);  
				break;
	    }
		
		chmod($im_chica, 0777);
		
	}

	public function gestionImg($img,$lin) {
		
		$this->imagen=$img;
		$this->linea=$lin;
		
		$ficha_image_location='../img/productos/grandes/'.$this->imagen;
		$chica_image_location='../img/productos/'.$this->imagen;

		$this->resizeFoto($ficha_image_location,$chica_image_location,400,320);

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img` WHERE im_linea	='$this->linea' AND im_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img`(`im_nombre`, `im_linea`) VALUES ('$this->imagen','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_img` WHERE im_linea='$this->linea' ORDER BY im_orden ASC";

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
		$sql = "UPDATE `tbl_img` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img` WHERE im_nombre='$imagen'")) {
			unlink("../img/productos/grandes/".$imagen);
			unlink("../img/productos/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/productos/grandes/".$imagen);
		unlink("../../img/productos/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_img` WHERE im_nombre='$imagen'");
	}
}


class Imagenes360 extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;
	

	public function gestionImg($img,$lin) {
		
		$this->imagen=$img;
		$this->linea=$lin;
		

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img360` WHERE im_linea	='$this->linea' AND im_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img360`(`im_nombre`, `im_linea`) VALUES ('$this->imagen','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_img360` WHERE im_linea='$this->linea' ";

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
		$sql = "UPDATE `tbl_img360` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img360` WHERE im_nombre='$imagen'")) {
			unlink("../img/360/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/360/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_img360` WHERE im_nombre='$imagen'");
	}
}

class Detalles extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;

	public function gestionImg($img,$lin) {
		
		$this->imagen=$img;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_detalles` WHERE det_linea	='$this->linea' AND det_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_detalles`(`det_nombre`, `det_linea`) VALUES ('$this->imagen','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_detalles` WHERE det_linea='$this->linea' ORDER BY det_orden ASC";

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
		$sql = "UPDATE `tbl_detalles` set ".$nombre." = '".$value."' WHERE det_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_detalles` WHERE det_nombre='$imagen'")) {
			unlink("../img/detalles/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/detalles/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_detalles` WHERE det_nombre='$imagen'");
	}
}

class Ambientes extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;
	

	public function gestionImg($img,$lin) {
		
		$this->imagen=$img;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_ambientes` WHERE amb_linea	='$this->linea' AND amb_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_ambientes`(`amb_nombre`, `amb_linea`) VALUES ('$this->imagen','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ambientes` WHERE amb_linea='$this->linea' ORDER BY amb_orden ASC";

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
		$sql = "UPDATE `tbl_ambientes` set ".$nombre." = '".$value."' WHERE amb_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_ambientes` WHERE amb_nombre='$imagen'")) {
			unlink("../img/ambientes/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/ambientes/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_ambientes` WHERE amb_nombre='$imagen'");
	}
}

class Renders extends Conexion
{	
	
	public $id;
	public $accion;
	public $imagen;
	

	public function gestionImg($img,$lin) {
		
		$this->imagen=$img;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_renders` WHERE ren_linea	='$this->linea' AND ren_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_renders`(`ren_nombre`, `ren_linea`) VALUES ('$this->imagen','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_renders` WHERE ren_linea='$this->linea'";

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
		$sql = "UPDATE `tbl_renders` set ".$nombre." = '".$value."' WHERE ren_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarImg($imagen) {

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_renders` WHERE ren_nombre='$imagen'")) {
			unlink("../img/renders/".$imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarImgUpload($imagen) {
		unlink("../../img/renders/".$imagen);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_renders` WHERE ren_nombre='$imagen'");
	}
}

class ArchivosIES extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestionIES($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_ies` WHERE ies_linea	='$this->linea' AND ies_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_ies`(`ies_nombre`, `ies_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_ies` WHERE ies_linea='$this->linea' ORDER BY ies_orden ASC";

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
		$sql = "UPDATE `tbl_ies` set ".$nombre." = '".$value."' WHERE ies_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarIES($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_ies` WHERE ies_nombre='$this->archivo'")) {
			unlink("../archivos/ies/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarIesUpload($arch) {
		$this->archivo=$arch;

		unlink("../../archivos/ies/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_ies` WHERE ies_nombre='$this->archivo'");
	}
	

	
}

class ArchivosDescargas extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestionDescargas($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_descargas` WHERE des_linea	='$this->linea' AND des_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_descargas`(`des_nombre`, `des_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_descargas` WHERE des_linea='$this->linea' ORDER BY des_orden ASC";

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
		$sql = "UPDATE `tbl_descargas` set ".$nombre." = '".$value."' WHERE des_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrarDescargas($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_descargas` WHERE des_nombre='$this->archivo'")) {
			unlink("../archivos/manuales/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarDescargasUpload($arch) {
		$this->archivo=$arch;

		unlink("../../archivos/manuales/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_descargas` WHERE des_nombre='$this->archivo'");
	}
	
}

class DibujosTecnicos extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestion($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_dibujos_tecnicos` WHERE dt_linea	='$this->linea' AND dt_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_dibujos_tecnicos`(`dt_nombre`, `dt_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_dibujos_tecnicos` WHERE dt_linea='$this->linea' ORDER BY dt_orden ASC";

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
		$sql = "UPDATE `tbl_dibujos_tecnicos` set ".$nombre." = '".$value."' WHERE dt_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_dibujos_tecnicos` WHERE dt_nombre='$this->archivo'")) {
			unlink("../img/dibujos-tecnicos/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarUpload($arch) {
		$this->archivo=$arch;

		unlink("../../img/dibujos-tecnicos/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_dibujos_tecnicos` WHERE dt_nombre='$this->archivo'");
	}	
}


class Modulos extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestion($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_modulos` WHERE mod_linea	='$this->linea' AND mod_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_modulos`(`mod_nombre`, `mod_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_modulos` WHERE mod_linea='$this->linea' ORDER BY mod_orden ASC";

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
		$sql = "UPDATE `tbl_modulos` set ".$nombre." = '".$value."' WHERE mod_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_modulos` WHERE mod_nombre='$this->archivo'")) {
			unlink("../img/modulos/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarUpload($arch) {
		$this->archivo=$arch;

		unlink("../../img/modulos/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_modulos` WHERE mod_nombre='$this->archivo'");
	}	
}

class Fotometrias extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestion($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_fotometrias` WHERE fot_linea	='$this->linea' AND fot_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_fotometrias`(`fot_nombre`, `fot_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_fotometrias` WHERE fot_linea='$this->linea' ORDER BY fot_orden ASC";

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
		$sql = "UPDATE `tbl_fotometrias` set ".$nombre." = '".$value."' WHERE fot_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_fotometrias` WHERE fot_nombre='$this->archivo'")) {
			unlink("../img/fotometrias/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarUpload($arch) {
		$this->archivo=$arch;

		unlink("../../img/fotometrias/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_fotometrias` WHERE fot_nombre='$this->archivo'");
	}	
}


class Luminancias extends Conexion
{	
	
	public $id;
	public $accion;
	public $archivo;
	
	public function gestion($arch,$lin) {
		
		$this->archivo=$arch;
		$this->linea=$lin;

		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_luminancias` WHERE lum_linea	='$this->linea' AND lum_nombre='$this->archivo'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_luminancias`(`lum_nombre`, `lum_linea`) VALUES ('$this->archivo','$this->linea')");
			}
	}


	public function getEmployees($lin)
	{
		$this->linea=$lin;

		$query="SELECT * FROM `tbl_luminancias` WHERE lum_linea='$this->linea' ORDER BY lum_orden ASC";

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
		$sql = "UPDATE `tbl_luminancias` set ".$nombre." = '".$value."' WHERE lum_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}


	public function borrar($arch) {
		
		$this->archivo=$arch;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_luminancias` WHERE lum_nombre='$this->archivo'")) {
			unlink("../img/iluminancias/".$this->archivo);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}

	public function borrarUpload($arch) {
		$this->archivo=$arch;

		unlink("../../img/iluminancias/".$this->archivo);
		$result=mysqli_query(parent::con(),"DELETE FROM `tbl_luminancias` WHERE lum_nombre='$this->archivo'");
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

class Noticias extends Conexion
{	
	
	public $id;
	public $accion;
	public $orden;
	public $imagen;
	
	public function lista_noticias()
	{
		
		$result=mysqli_query(parent::con(),"SELECT * FROM tbl_noticias");
		
		while ($reg=$result->fetch_assoc())
		{
			
			echo '<tr>';


						$this->id=$reg["nt_id"];
						$resultImg=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' AND im_orden=1 ");
						$row_cnt=$resultImg->num_rows;

						if ($row_cnt>0) {
							$img=$resultImg->fetch_assoc();
							echo '<td><img class="card-img-top img-fluid" src="../img/proyectos/'.$img["im_nombre"].'" height="100"></td>';
						} else {
							echo '<td><img class="card-img-top img-fluid" src="../img/sin-imagen.jpg" height="80"></td>';
						}



                  	echo '<td>'.$reg["nt_titulo"].'</td>';


					echo '<td><a href="fotos-noticias.php?id='.$reg["nt_id"].'" class="btn btn-info"><i class="fa fa-file-image-o"></i> Imagenes</a></td>';

					echo '<td align="center"><a href="editar-noticia.php?nt_id='.$reg["nt_id"].'" class="btn btn-success btn-sm">Editar</a></td>
					<td align="center"><a href="noticias.php?action=delete&id='.$reg["nt_id"].'" data-confirm="Está seguro que desea eliminar?" class="btn btn-danger btn-sm">Eliminar</a></td>';

					echo '<td><div class="btn-group">
							<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$reg["status"].' <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
											  
								if($reg["status"]=='papelera') {
									echo '<li><a href="noticias.php?action=publicado&id='.$reg["nt_id"].'">Publicado</a></li>';
								} else {
									echo '<li><a href="noticias.php?action=papelera&id='.$reg["nt_id"].'">A papelera</a></li>';
								}		
					echo '</ul></div></td></tr>';
                  	
		}
		
	}
	
	
	public function borrar_noticia($id_noticia)
	{
		$this->id=$id_noticia;
		mysqli_query(parent::con(),"DELETE FROM `tbl_noticias` WHERE nt_id='$this->id'");
		return "El proyecto fue borrado";
	}

	
	public function traer_noticia($id_noticia)
	{
		$this->id=$id_noticia;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_noticias` WHERE nt_id='$this->id'");
		return $result->fetch_assoc();
	}
	
	
	public function editar_noticia($id_edicion)
	{
		$this->id=$id_edicion;
		
		$contenido=$this->traer_noticia($this->id);
		
		
		// definimos las variables
		if ( !empty($_POST['titulo']) )			$titulo = $_POST['titulo']; else return 'Ingrese el título';
		if ( !empty($_POST['texto']) ) 				$texto = $_POST['texto']; else return 'Ingrese un texto';
		$link = $_POST['link'];
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		
		$alias=Varias::crear_url($titulo);
		
		$query="UPDATE `tbl_noticias` SET `nt_alias`='$alias', `nt_titulo`='$titulo', `nt_texto`='$texto', `nt_link`='$link', `status`='$estado' WHERE nt_id='$this->id'";


		if (mysqli_query(parent::con(),"$query")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}


	}
	
	public function agregar_noticia()
	{
		
		// definimos las variables
		if ( !empty($_POST['titulo']) )			$titulo = $_POST['titulo']; else return 'Ingrese el título';
		if ( !empty($_POST['texto']) ) 				$texto = $_POST['texto']; else return 'Ingrese un texto';
		$link = $_POST['link'];
		if ( !empty($_POST['estado']) ) 			$estado = $_POST['estado']; else return 'Seleccione el estado de la publicación';
		
			
		$alias=Varias::crear_url($titulo);


		$query="INSERT INTO `tbl_noticias`(`nt_alias`, `nt_titulo`, `nt_texto`,`nt_link`, `status`) VALUES ('$alias','$titulo','$texto','$link','$estado')";

		if (mysqli_query(parent::con(),"$query")) {
			return 'agregado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}


	public function status_noticia($id_not,$acc) {
		
		$this->id=$id_not;
		$this->accion=$acc;
		mysqli_query(parent::con(),"UPDATE `tbl_noticias` SET `status`='$this->accion' WHERE nt_id='$this->id' ");
		
		return "actualizado";
			
	}


	public function BotonesImgNot($id_not) {

		$this->id=$id_not;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
		$row_cnt=$result->num_rows;

		$_SESSION['id_producto']=$this->id;

		$result_not=mysqli_query(parent::con(),"SELECT nt_alias FROM `tbl_noticias` WHERE nt_id='$this->id'");
		$not=$result_not->fetch_assoc();

		if($row_cnt==0){
			$nom_foto=$not['nt_alias']; 

			echo '<p class="alert alert-danger">No hay imágenes en el proyecto</p><br><a href="upload_crop-not.php?nom_fot='.$nom_foto.'&orden=1" class="btn btn-danger btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar imagen</a><hr>';
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
			$nom_foto=$not['nt_alias'].'_'.$ft; 
			$orden=$ft+1;
			echo '<a href="upload_crop-not.php?nom_fot='.$nom_foto.'&orden='.$orden.'" class="btn btn-success btn-addon btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar otra imagen</a><hr>';
		}
	}

	public function ImgNot($id_prod) {

		$this->id=$id_prod;
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' ORDER BY im_orden ASC");
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

	public function editarOrdenImg($nombre,$value,$pk)
	{
		$data = array();
		$sql = "UPDATE `tbl_img_noticias` set ".$nombre." = '".$value."' WHERE im_id='".$pk."'";
		
		if($result = mysqli_query(parent::con(), $sql)) {
			echo 'Successfully! Record updated...';
		} else {
			die("error to update '".$params["name"]."' with '".$params["value"]."'");
		}
	}

	public function gestionImg($img,$id_not,$orden) {
		
		$this->imagen=$img;
		$this->id=$id_not;
		$this->orden=$orden;
			
		$result=mysqli_query(parent::con(),"SELECT * FROM `tbl_img_noticias` WHERE im_producto='$this->id' AND im_nombre='$this->imagen'");
		$row_cnt=$result->num_rows;
		
			if($row_cnt==0) {
				mysqli_query(parent::con(),"INSERT INTO `tbl_img_noticias`(`im_nombre`, `im_producto`, `im_orden`) VALUES ('$this->imagen','$this->id','$this->orden')");
			}

	}


	public function borrarImg($img) {
		$this->imagen=$img;

		if (mysqli_query(parent::con(),"DELETE FROM `tbl_img_noticias` WHERE im_nombre='$this->imagen'")) {

			unlink("../img/proyectos/".$this->imagen);
			return 'eliminado';
		} else {
			return 'Ocurrio un error, intente nuevamente';
		}
	}
	
}
?>