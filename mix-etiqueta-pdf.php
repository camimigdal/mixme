<?php
ob_start();
require_once 'class/class.php';
require_once 'class/mixer.class.php';;
$ObjMix = new Mixer();

$id_mix=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
$token=filter_input(INPUT_GET,'token', FILTER_SANITIZE_SPECIAL_CHARS);

if(!isset($id_mix) && !isset($token)) header('Location: index.php');

if (!$content=$ObjMix->traerMixPdf($id_mix,$token)) {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="es-AR">
	
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Mixme</title>
        
        <!-- CSS -->
		<link rel="stylesheet" media="all" href="css/bootstrap-pdf.css" />
        <link rel="stylesheet" href="css/ficha-pdf.css">

</head>
<body>

            <div class="row pdf_content_etiqueta">
                <div class="col-xs-4 p-0">
                    
                <?php 
                $arrEtiq=$ObjMix->etiquetaMixPDF($id_mix,$token);
                if ($arrEtiq) {

                    echo '<div class="text-center m-0">
                    <h2 class="tit-mix">Mix '.$content['tm_titulo'].'</h2>
                    <h3 class="nombre-mix">'.$content['nombre'].'</h3>
                    <p class="descripcion-mix">En Mixme buscamos que vuelvas a esos sabores que mas te gustan, de la mano de una alimentación saludable y consciente.</p>';

                    echo '<hr><h5 class="subtit-mix text-left">Ingredientes</h5>
                    <ul class="list-inline list-ing">';

                    $i=1;
                    foreach($arrEtiq["ingredientes"] as $clave => $valor)
                        {
                            if ($i==4 || $i==7) {
                                echo '<br>';
                            }
                            extract($valor);
                            echo '<li>
                                    <img alt="'.$nombre.'" title="'.$nombre.'" src="img/iconos/'.$icono.'" height="30">
                                    <p class="name">'.$nombre.'</p>';
                            echo '</li>';
                            $i++;
                        }

                    echo '</ul>';

                    echo '<ul class="list-inline list-prop m-0">';
                        foreach($arrEtiq["iconos"] as $clave => $valor)
                        {
                            if ($valor) {
                                echo '<li class="text-center"><img src="img/iconos-propiedades/etiqueta/'.$clave.'.png" width="48" height="48"></li>';
                            }
                        }
                    echo '</ul>
                    </div>

                    <h5 class="subtit-mix">Información Nutricional</h5>
                    <table class="table table-striped table-hover table-sm table-valores-nutri">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Porción 40g (1/2 taza)</th>
                                <th>Cantidad</th>
                                <th>%VD (*)</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach($arrEtiq["tabla"] as $k => $valor)
                        {
                            echo '<tr>
                                    <td scope="row">'.$valor['nombre'].'</td>
                                    <td>'.$valor['cantidad'].' '.$valor['unidad'].'</td>
                                    <td>'.$valor['diario'].'%</td>
                            </tr>';
                        }
                    echo '</tbody>
                    </table>

                    <p class="text-small">*Valores diarios con base a una dieta de 2000 Kcal u 8400 Kj.
                    Sus valores pueden ser mayores o menores dependiendo de
                    sus necesidades energéticas.</p>';

                    echo '<p class="cont-neto">'.$arrEtiq["contenido-neto"].'g<br><span>Contenido Neto</span></p>';

                }
                ?>
            

                    <div class="footer-etiqueta">
                        <div class="d-flex" style="float:left;">
                            <p class="text-small">Envasado en Argentina</p>
                        </div>
                        <div class="d-flex" style="float:right;">
                            <ul class="list-inline m-0" >
                                <li><img src="img/qr-etiqueta-mix.png" width="40" height="40"></li>
                                <li><img src="img/icono-mixme.png" width="40" height="40"></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

</body>
</html>          
            
<?php
use Dompdf\Dompdf;
require 'vendor/autoload.php';
$dompdf = new DOMPDF();
$dompdf->set_paper("A4", "portrait");
$dompdf->load_html(ob_get_clean());
$dompdf->render();
$filename = "Mixme-mix-".$content['nombre'].'.pdf';
$dompdf->stream($filename);
?>
