<?php 

class login{
	
	private $con;

	function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
		$this->con = $db->connect();
	}
	
	public function loginUser($user,$telefono,$email,$empresa) {

		$usuario = $this->con->real_escape_string($user);
		$telefono = $this->con->real_escape_string($telefono);
		$email = $this->con->real_escape_string($email);
		$empresa = $this->con->real_escape_string($empresa);
		$sid = session_id();

	    if ($stmt = $this->con->prepare("SELECT `id_cliente`, `session_id`, `cl_nombre`, `cl_mail`
	        FROM `tbl_clientes_mayoristas`
	       WHERE `cl_mail` = ? AND `session_id` = ?
	        LIMIT 1")) {
	        $stmt->bind_param('ss', $email, $sid);  
	        $stmt->execute();  
	        $stmt->store_result();
	 
	        $stmt->bind_result($db_userid, $db_session, $db_nombre, $db_email);
	        $stmt->fetch();

	        if ($stmt->num_rows == 0) {

				$stmt = $this->con->prepare("INSERT INTO `tbl_clientes_mayoristas` (`session_id`, `cl_nombre`, `cl_mail`, `cl_telefono`, `cl_empresa`) 
                VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss", $sid, $usuario, $email, $telefono, $empresa);
                $result = $stmt->execute();
                $stmt->close();
            
                if ($result) {
                    $new_order_id = $this->con->insert_id;
                    if ($new_order_id) {
                        
						$_SESSION['mayoristas'] = $new_order_id;
	                    $user = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $usuario);
	                    $_SESSION['user'] = $user;

	                    return true;

                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

	        } elseif ($stmt->num_rows == 1) {
	            $_SESSION['mayoristas'] = $db_userid;
	        } else {
				unset($_SESSION['mayoristas']);
	            return false;
	        }
	    }
	}


	public function login_check() {

	    // Revisa si todas las variables de sesión están configuradas.
	    if (isset($_SESSION['mayoristas'], $_SESSION['user'])) {
	 
	        $user_id = $_SESSION['mayoristas'];
	        $user = $_SESSION['user'];
			$sid = session_id();
	 
	        if ($stmt = $this->con->prepare("SELECT cl_nombre 
	            FROM tbl_clientes_mayoristas 
	            WHERE id_cliente = ? AND `session_id` = ? LIMIT 1")) {
	            $stmt->bind_param('is', $user_id, $sid);
	            $stmt->execute();   // Ejecuta la consulta preparada.
	            $stmt->store_result();
	 
	            if ($stmt->num_rows == 1) {
	                // ¡¡Conectado!! 
					return true;
	            } else {
	                // No conectado.
	                return false;
	            }
	        } else {
	            // No conectado.
	            return false;
	        }
	    } else {
	        // No conectado.
	        return false;
	    }
	}
}
?>