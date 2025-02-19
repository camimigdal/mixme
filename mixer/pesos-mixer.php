<?php
require_once("../class/mixer.class.php");

if (isset($_REQUEST['type'])) {
    $type=strip_tags($_REQUEST['type'], ENT_QUOTES);
    switch ($type) {
        case '100':
            $typeNombre = 'granola';
            break;
            case '101':
                $typeNombre = 'semillas';
                break;
                case '102':
                    $typeNombre = 'frutos-secos';
                    break;
    }
}
$ObjMixer = new Mixer();
$peso=$ObjMixer->getPacksList();


if (isset($_SESSION['mayoristas'])) { ?>
    
            <div class="custom-control custom-radio py-5 pr-3 col-12">
                <input type="radio" name="peso" id="peso1" data-pack="bolsa" class="custom-control-input" value="1000" required <?php if ($peso==1000) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item d-flex align-items-center" for="peso1">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/bolsa-muestra-mixer.png" width="200"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price"><small><strong>Diseñá tu mezcla por 1Kg.</strong><br>Recordá que cada bolsa trae 5kg.</small></h4>
                            <div class="packs-item-name">Bolsa 5Kg</div>
                        </span>
                    </div>
                </label>
            </div>

<?php } else {

    switch ($typeNombre) {
        case 'granola': ?>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso1" data-pack="tubo" class="custom-control-input" value="350" required <?php if ($peso==350) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso1">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/tubo-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">350g</h4>
                            <div class="packs-item-name">Tubo Mixme</div>
                            <div class="packs-item-desc"><p><small>En el siguiente paso podés elegir el diseño que más te guste</small></p></div>
                        </span>
                    </div>
                </label>
            </div>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso2" data-pack="doypack" class="custom-control-input" value="400" required <?php if ($peso==400) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso2">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/doypack-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">400g</h4>
                            <div class="packs-item-name">Doypack</div>
                            <div class="packs-item-desc"><small></small></div>
                        </span>
                    </div>
                </label>
            </div>

        <?php    break;
        
        case 'frutos-secos': ?>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso1" data-pack="tubo" class="custom-control-input" value="400" required <?php if ($peso==400) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso1">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/tubo-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">400g</h4>
                            <div class="packs-item-name">Tubo Mixme</div>
                            <div class="packs-item-desc"><p><small>En el siguiente paso podés elegir el diseño que más te guste</small></p></div>
                        </span>
                    </div>
                </label>
            </div>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso2" data-pack="doypack" class="custom-control-input" value="350" required <?php if ($peso==350) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso2">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/doypack-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">350g</h4>
                            <div class="packs-item-name">Doypack</div>
                            <div class="packs-item-desc"><small></small></div>
                        </span>
                    </div>
                </label>
            </div>

        <?php    break;
        
        case 'semillas': ?>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso1" data-pack="tubo" class="custom-control-input" value="500" required <?php if ($peso==500) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso1">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/tubo-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">500g</h4>
                            <div class="packs-item-name">Tubo Mixme</div>
                            <div class="packs-item-desc"><p><small>En el siguiente paso podés elegir el diseño que más te guste</small></p></div>
                        </span>
                    </div>
                </label>
            </div>

            <div class="custom-control custom-radio py-5 pr-3 col-6">
                <input type="radio" name="peso" id="peso2" data-pack="doypack" class="custom-control-input" value="350" required <?php if ($peso==350) echo 'checked'; ?>>
                <label class="custom-control-label label-packs-item" for="peso2">
                    <div class="pr-3 text-center"><img alt="" src="<?php echo WEB_ROOT ?>img/doypack-muestra-mixer.png" width="100%"></div>
                    <div class="packs-item">
                        <span>
                            <h4 class="packs-item-price">350g</h4>
                            <div class="packs-item-name">Doypack</div>
                            <div class="packs-item-desc"><small></small></div>
                        </span>
                    </div>
                </label>
            </div>

        <?php    break;
    }

}
?>