<?php


		$email='martinpandelo@gmail.com';
		
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
        $mail->AddAddress($email);
        $mail->AddCC('martinpandelo@gmail.com');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Luz Desing - Detalle de la compra';

        $mensaje = '
        <html>
        <head>
          <title>Luz Desing - Detalle de la compra</title>
        </head>
        <body>
        
        <table width="600" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #CCC; margin:0 auto;">
        <tr>
            <td colspan="4" height="116px" align="left" width="600px" valign="top" ><img src="'.WEB_ROOT.'img/mail/header_mail.jpg" width="600" height="116" alt="Lightning Iluminación"></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 31px; background: #dd5700; text-align: left; font: bold 16px Arial, Helvetica, sans-serif; color: #fff; padding:5px 10px 5px 10px;">Felicitaciones! Ya tienes tu pedido</td>
        </tr>
        <tr>
            <td colspan="4" height="10px" style="padding:20px 10px 0 10px; height:auto; font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#6a7884""><strong>Estimado Martin:</strong><p>Este es el resumen de tu compra.</p></td>
        </tr>
        
        <tr>
            <td height="10px">&nbsp;</td>
        </tr>
        <tr>
            <td width="600" style="background:#dd5700; font: normal 12px Arial, Helvetica, sans-serif; color:#fff; padding:10px; text-align:left;">&copy; 2019 Lightning Iluminación - Todos los derechos reservados</td>
        </tr>
        <tr>
            <td style="font: normal 11px Arial, Helvetica, sans-serif; color: #606c73; padding:10px; text-align:left;">
                        Por favor no responda este mail. Para consultas ingrese en <a href="'.WEB_ROOT.'">lightningilumina.com.ar</a></td>
        </tr>

        </table>
        
        </body>
        </html>';

        $mail->Body = $mensaje;
        $mail->Send();
		
		
?>
