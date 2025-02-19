<?php
require_once("../class/mixer.class.php");

$ObjMixer = new Mixer();
$info=$ObjMixer->getMasInfo();

echo '<div class="modal-header">
        <h5 class="modal-title">'.$info['pd_titulo'].'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        '.$info['pd_descripcion'].'
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        </div>';


?>

