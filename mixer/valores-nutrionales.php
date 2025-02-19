<?php
require_once("../class/mixer.class.php");

$ObjMixer = new Mixer();
$valNutri=$ObjMixer->getValNutri();

if ($valNutri) {

    echo '<div class="text-center">
    <div class="btn-rotate"><a href="#mix-content" class="text-primary"><img src="'.WEB_ROOT.'img/rotate.svg" width="50" height="40" class="icon-rotate"></a></div>
    <h2 class="title-tubo pt-3"></h2>
    <p class="descripcion-tubo py-2">En Mixme buscamos que vuelvas a esos sabores que mas te gustan, de la mano de una alimentación saludable y consciente.</p>';

    echo '<hr><ul class="list-inline">';
        foreach($valNutri["iconos"] as $clave => $valor)
        {
            if ($valor) {
                echo '<li class="list-inline-item"><img src="'.WEB_ROOT.'img/iconos-propiedades/etiqueta/'.$clave.'.svg" width="50" height="50" alt="" class="img-responsive"></li>';
            }
        }
    echo '</ul><hr>
    </div>

    <h5>Información Nutricional</h5>
    <table class="table table-striped table-hover table-sm table-valores-nutri">
        <thead class="thead-inverse">
            <tr>
                <th>Porción 40g <small>(1/2 taza)</small></th>
                <th>Cantidad</th>
                <th>%VD (*)</th>
            </tr>
        </thead>
        <tbody>';
        foreach($valNutri["tabla"] as $k => $valor)
        {
            echo '<tr>
                    <td scope="row">'.$valor['nombre'].'</td>
                    <td>'.$valor['cantidad'].' '.$valor['unidad'].'</td>
                    <td>'.$valor['diario'].'%</td>
            </tr>';
        }
    echo '</tbody>
    </table>

    <p class="text-small text-left"><small>*Valores diarios con base a una dieta de 2000 Kcal u 8400 Kj.
    Sus valores pueden ser mayores o menores dependiendo de
    sus necesidades energéticas.</small></p>';

    echo '<p class="cont-neto pt-3">'.$valNutri["contenido-neto"].'g<br><span>Contenido Neto</span></p>';

}

?>
