<?php 
include_once('class/login.class.php');
$objLog=new login();
$user = 'mayorista';
$email = 'generico@email.com';
$telefono = '11111111';
$empresa = 'sin empresa';
 
if(isset($user, $telefono, $email, $empresa)) {
        // verificamos que los datos ingresados correspondan a un usuario
        if (!$objLog->loginUser($user,$telefono,$email,$empresa)) {
            header('Location: '.WEB_ROOT.'index.php');
        } else {
            header('Location: '.WEB_ROOT.'index.php');
        }
}

if (!$logueado=$objLog->login_check()) {
    unset($_SESSION['mayoristas']);
}

?>