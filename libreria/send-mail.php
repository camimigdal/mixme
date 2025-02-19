<?php
if (!isset($_POST['nombre'],$_POST['email'],$_POST['telefono'],$_POST['asunto'],$_POST['mensaje'])) {
    exit;
}

        $nombre=filter_input(INPUT_POST,'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
        $email=filter_input(INPUT_POST,'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $telefono=filter_input(INPUT_POST,'telefono', FILTER_SANITIZE_SPECIAL_CHARS);
        $asunto=filter_input(INPUT_POST,'asunto', FILTER_SANITIZE_SPECIAL_CHARS);
        $mensaje=filter_input(INPUT_POST,'mensaje', FILTER_SANITIZE_SPECIAL_CHARS);

        require "class.phpmailer.php";
        require "class.smtp.php";
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
        $mail->AddAddress('info@luzdesingiluminacion.com');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Formulario luzdesing.com.ar - '.$asunto;

        $mensaje = '
        <html>
        <head>
          <title>Luz desing - Mensaje del formulario web</title>
        </head>
        <body>
        	<strong>MENSAJE ENVIADO DESDE luzdesing.com.ar</strong><br><br>
        	<p>NOMBRE: '.$nombre.'<br>
        	EMAIL: '.$email.'<br>
        	MENSAJE: '.$mensaje.'</p>
        
        </body>
        </html>';

        $mail->Body = $mensaje;
        $mail->Send();
?>
