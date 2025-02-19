<?php
if (!defined('WEB_ROOT')
    || !isset($_GET['step'])) {
	exit;
}

$cant_prod=0;
$total=0;
$peso=0;
for ($i=0; $i<$cartItem; $i++) {
    extract($cartContent[$i]);
                                                        
    $total += $pr_precio * $cantidad;
    $peso += $pd_peso * $cantidad;
    $cant_prod += $cantidad;
}

?>

            <section id="checkout">
                <div class="js-decorate co-checkoutprogressindicator">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <ul>
                                    <li class="active step-1 layer"><span>1</span>Datos</li>
                                    <li class="inactive step-2 layer"><span>2</span>Confirmar</li>
                                    <li class="inactive step-3 layer"><span>3</span>Pagar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container py-5">
                    <div class="row">
                        <div class="col-12 col-md-8">

                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" method="post" name="frmCheckout" id="frmCheckout" class="form-horizontal">

                                <h4>Código de descuento</h4>
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="descuento" class="text-primary">Si tienes un código de descuento ingresalo aquí:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="descuento" id="descuento" value="<?php if(isset($descuento)) echo $descuento ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" name="submitdesc" id="submitdesc">Validar</button>
                                            </div>
                                        </div>
                                        <div id="result-descuentos" class="text-left"></div>
                                    </div>
                                </div>

                                <h4>Contacto</h4>
                                <hr>
                                <div class="form-row" id="checkoutEmail">
                                    <div class="form-group col-12">
                                        <label for="per_email">Tu email</label>
                                        <input type="email" class="form-control" name="per_email" id="per_email" value="<?php if(isset($per_email)) echo $per_email ?>" >
                                    </div>
                                </div>


                                <h4 class="mt-4">Entrega</h4>
                                <hr>
                                
                                <div id="envios">
                                        <h5><i data-feather="truck" class="mr-2 text-secondary"></i> Envíos a domicilio</h5>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Tu código postal</span>
                                            </div>
                                            <input onblur="loadEnvios()" type="number" class="form-control" name="envio_codpostal" id="envio_codpostal" placeholder="aquí" aria-label="Tu código postal" aria-describedby="submitship" value="<?php if(isset($_SESSION['codPostal'])) echo $_SESSION['codPostal'] ?>">
                                            <input type="hidden" id="cantproductos_envio" name="cantproductos_envio" value="<?php echo $cant_prod ?>" >
                                            <input type="hidden" id="peso_envio" name="peso_envio" value="<?php echo $peso ?>" >
                                            <input type="hidden" id="total_envio" name="total_envio" value="<?php echo $total ?>" >
                                        </div>
                                        <span id="errorShip" class="text-danger"></span>
                                        <div id="result-envios" class="text-left"></div>
                                    <hr>
                                        <h5><i data-feather="home" class="mr-2 text-secondary"></i> Retiro personal</h5>
                                        <div class="custom-control custom-radio py-2 pr-3 pl-5 my-2 border">
                                            <input type="radio" name="envio" id="envio30" data-id="30" class="custom-control-input" value="S" >

                                            <input type="hidden" name="provincia30" id="provincia30" value="CABA - Ciudad Autónoma de Buenos Aires">
                                            <input type="hidden" name="id_correo30" id="id_correo30" value="Mixme">
                                            <input type="hidden" name="nombre_correo30" id="nombre_correo30" value="Mixme">
                                            <input type="hidden" name="descripcion_correo30" id="descripcion_correo30" value="Retiro personal en Mixme">
                                            <input type="hidden" name="despacho30" id="despacho30" value="-">
                                            <input type="hidden" name="modalidad30" id="modalidad30" value="-">
                                            <input type="hidden" name="servicio30" id="servicio30" value="-">
                                            <input type="hidden" name="horas_entrega30" id="horas_entrega30" value="-">
                                            <input type="hidden" name="costo_envio30" id="costo_envio30" value="0">
                                
                                            <label class="custom-control-label label-shipping-method-item pl-3" for="envio30">
                                                <div class="shipping-method-item">
                                                    <span>
                                                        <h4 class="shipping-method-item-price">Gratis</h4>
                                                        <div class="shipping-method-item-name">Retiro en sucursal</div>
                                                        <div class="shipping-method-item-desc"><small>Av. Argentina 5659, Villa Lugano, CABA, Argentina</small></div>
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                </div>

                                
                                
                                <!-- DATOS DE ENTREGA -->
                                <div id="datosEnvio" class="panel-collapse collapse">
                                    <h4 class="mt-5">Datos del Destinatario</h4>
                                    <hr>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="envio_nombre">Nombre</label>
                                                <input class="form-control" name="envio_nombre" type="text" id="envio_nombre" value="<?php if(isset($envio_nombre)) echo $envio_nombre ?>" >
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="envio_apellido">Apellido</label>
                                                <input class="form-control" name="envio_apellido" type="text" id="envio_apellido" value="<?php if(isset($envio_apellido)) echo $envio_apellido ?>" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="envio_telefono">Teléfono</label>
                                                <input type="number" class="form-control mb-2" name="envio_telefono" id="envio_telefono" value="<?php if(isset($envio_telefono)) echo $envio_telefono ?>" min="0">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="envio_dni">DNI/CUIT</label>
                                                <input class="form-control" name="envio_dni" type="number" id="envio_dni" value="<?php if(isset($envio_dni)) echo $envio_dni ?>" min="0" max="99999999999" >
                                            </div>
                                        </div>

                                    <h4 class="mt-5">Domicilio del Destinatario</h4>
                                    <hr>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="envio_direccion">Calle</label>
                                                <input type="text" class="form-control" name="envio_direccion" id="envio_direccion" value="<?php if(isset($envio_direccion)) echo $envio_direccion ?>" >
                                            </div>
                                            <div class="form-group col">
                                                <label for="envio_calle_num">Número</label>
                                                <input type="number" class="form-control" name="envio_calle_num" id="envio_calle_num" value="<?php if(isset($envio_calle_num)) echo $envio_calle_num ?>" min="0" max="999999" >
                                            </div>
                                            <div class="form-group col">
                                                <label for="envio_piso">Piso</label>
                                                <input type="number" class="form-control" name="envio_piso" id="envio_piso" value="<?php if(isset($envio_piso)) echo $envio_piso ?>" min="0" max="999">
                                            </div>
                                            <div class="form-group col">
                                                <label for="envio_dpto">Dpto/Of</label>
                                                <input type="text" class="form-control" name="envio_dpto" id="envio_dpto" value="<?php if(isset($envio_dpto)) echo $envio_dpto ?>" maxlength="3" size="10">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="envio_ciudad">Ciudad</label>
                                                <input type="text" class="form-control" name="envio_ciudad" id="envio_ciudad" value="<?php if(isset($envio_ciudad)) echo $envio_ciudad ?>" >
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="envio_provincia">Provincia</label>
                                                <select id="envio_provincia" name="envio_provincia" class="form-control">
                                                    <?php if(isset($envio_provincia)) $provinciaEnv=$envio_provincia; else $provinciaEnv='';
                                                    $ObjCart->comb_provincias($provinciaEnv); ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="env_codpostal">Cód. Postal</label>
                                                <input type="number" class="form-control" name="env_codpostal" id="env_codpostal" value="<?php if(isset($env_codpostal)) echo $env_codpostal ?>" >
                                            </div>
                                        </div>
                                </div>
                                <!-- FIN DATOS DE ENTREGA -->



                                <!-- DATOS DE FACTURACION -->
                                <div id="datosFacturacion" class="panel-collapse collapse">

                                    <h4 class="mt-5">Datos de Facturación</h4>
                                    <hr>

                                    <div class="custom-control custom-switch custom-control-inline switch-mismos-datos">
                                        <input type="checkbox" name="chkDatos" id="chkDatos" value="true" class="custom-control-input" checked>
                                        <label class="custom-control-label" for="chkDatos">Mis datos de facturación y entrega son los mismos</label>
                                    </div>

                                    <div id="formDatosFacturacion" class="panel-collapse collapse">
                                        <h6 class="my-3 font-weight-bold">Persona que pagará el pedido:</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="per_nombre">Nombre</label>
                                                <input class="form-control" name="per_nombre" type="text" id="per_nombre" value="<?php if(isset($per_nombre)) echo $per_nombre ?>" >
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="per_apellido">Apellido</label>
                                                <input class="form-control" name="per_apellido" type="text" id="per_apellido" value="<?php if(isset($per_apellido)) echo $per_apellido ?>" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="per_telefono">Teléfono</label>
                                                <input type="number" class="form-control" name="per_telefono" id="per_telefono" value="<?php if(isset($per_telefono)) echo $per_telefono ?>" >
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="per_dni">DNI/CUIT</label>
                                                <input class="form-control" name="per_dni" type="number" id="per_dni" value="<?php if(isset($per_dni)) echo $per_dni ?>" min="0" max="99999999999" >
                                            </div>
                                        </div>

                                        <h6 class="my-3 font-weight-bold"">Domicilio de la persona que pagará el pedido:</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="per_direccion">Calle</label>
                                                <input type="text" class="form-control" name="per_direccion"  id="per_direccion" value="<?php if(isset($per_direccion)) echo $per_direccion ?>" >
                                            </div>
                                            <div class="form-group col">
                                                <label for="per_calle_num">Número</label>
                                                <input type="number" class="form-control" name="per_calle_num" id="per_calle_num" value="<?php if(isset($per_calle_num)) echo $per_calle_num ?>" min="0" max="999999" >
                                            </div>
                                            <div class="form-group col">
                                                <label for="per_piso">Piso</label>
                                                <input type="number" class="form-control" name="per_piso" id="per_piso" value="<?php if(isset($per_piso)) echo $per_piso ?>" min="0" max="999">
                                            </div>
                                            <div class="form-group col">
                                                <label for="per_dpto">Dpto/Of</label>
                                                <input type="text" class="form-control" name="per_dpto" id="per_dpto" value="<?php if(isset($per_dpto)) echo $per_dpto ?>" maxlength="3" size="10">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="per_ciudad">Ciudad</label>
                                                <input type="text" class="form-control" name="per_ciudad" id="per_ciudad" value="<?php if(isset($per_ciudad)) echo $per_ciudad ?>" >
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="per_provincia">Provincia</label>
                                                <select id="per_provincia" name="per_provincia" class="form-control">
                                                    <?php if(isset($per_provincia)) $provinciaPer=$per_provincia; else $provinciaPer='';
                                                    $ObjCart->comb_provincias($provinciaPer); ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="per_codpostal">Cód. Postal</label>
                                                <input type="number" class="form-control" name="per_codpostal" id="per_codpostal" value="<?php if(isset($per_codpostal)) echo $per_codpostal ?>" >
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- FIN DATOS DE FACTURACION -->
                                
                                
                                <h4 class="mt-5">Medio de Pago:</h4>
                                <hr>
                                <h6 class="my-3 font-weight-bold">Tarjetas de crédito o débito y otros medios de pago con:</h6>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="opcion_pago" id="opcion_pago1" value="mp" class="custom-control-input" <?php if(isset($opcion_pago) and $opcion_pago=='mp') echo 'checked' ?> >
                                    <label class="custom-control-label" for="opcion_pago1">Mercado Pago</label>
                                    <a href="https://www.mercadopago.com.ar/promociones" target="_blank" class="form-text text-muted mt-0 mb-2"><img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/468X60.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="100%" style="max-width: 468px" /></a>
                                </div>
                                <?php if (isset($_SESSION['mayoristas'])) { ?>
                                    <h6 class="my-3 font-weight-bold">Otros medios de pago:</h6>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="opcion_pago" id="opcion_pago2" value="otro" class="custom-control-input" <?php if(isset($opcion_pago) and $opcion_pago=='otro') echo 'checked' ?> >
                                        <label class="custom-control-label" for="opcion_pago2">Otros medios de pago
                                        <small class="form-text text-muted mt-0 mb-2">Quiero que me contacten</small></label>
                                    </div>
                                <?php } else { ?>
                                    <h6 class="my-3 font-weight-bold">Mediante una transferencia bancaria:</h6>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="opcion_pago" id="opcion_pago2" value="transferencia" class="custom-control-input" <?php if(isset($opcion_pago) and $opcion_pago=='transferencia') echo 'checked' ?> >
                                        <label class="custom-control-label" for="opcion_pago2">Transferencia Bancaria
                                        <small class="form-text text-muted mt-0 mb-2">Cuando realices la compra te llegarán los datos para hacer la transferencia.</small></label>
                                    </div>
                                <?php } ?>
                                
                                <div id="habilita-efectivo"></div>
                                

                                <h4 class="mt-5">Mensaje/Aclaraciones:</h4>
                                <hr>
                                <textarea name="mensaje" id="mensaje" class="form-control"><?php if(isset($mensaje)) echo $mensaje ?></textarea>
                                
                                <input type="hidden" name="totalSinEnvio" id="totalSinEnvio" value="<?php echo $total ?>">
                                
                                <input type="hidden" name="id_correo">
                                <input type="hidden" name="nombre_correo">
                                <input type="hidden" name="descripcion_correo">
                                <input type="hidden" name="despacho">
                                <input type="hidden" name="modalidad">
                                <input type="hidden" name="servicio">
                                <input type="hidden" name="horas_entrega">
                                <input type="hidden" name="costo_envio">

                                <div class="action_cart mt-3 mb-5 text-right">
                                     <button class="btn btn-primary btn-lg rounded-pill" name="btnStep1" type="submit" id="btnStep1">Continuar</button>
                                </div>
                                                
                            </form>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="sticky-top">
                                <h4>Resumen</h4>
                                <hr>

                                <section class="cart-widget">
                                    <?php
                                        $cant_prod=0;
                                        $total=0;
                                        for ($i=0; $i<$cartItem; $i++) {
                                            extract($cartContent[$i]);

                                            $total += $pr_precio * $cantidad;
                                            $cant_prod += $cantidad;
                                    ?>
                                                <div class="line-item">
                                                    <div class="media mt-2">
                                                        <img class="mr-2 align-self-center" src="<?php echo WEB_ROOT ?>img/productos/<?php echo $im_nombre ?>" alt="..." style="width: 70px;">
                                                        <div class="media-body">
                                                            <p><?php echo $pd_titulo;
                                                            if ($variacion) {
                                                                echo ' - '.$variacion;
                                                            }
                                                            ?></p>      
                                                            <p>$<?php echo number_format($pr_precio,2,',','.'); ?> x <?php echo $cantidad ?></p>                               
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php } ?>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Cantidad de productos: <?php echo $cant_prod ?>
                                        </div>
                                    </div>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Total Productos
                                        </div>
                                        <div class="cart-widget-value">
                                            $ <?php echo number_format($total,2,',','.'); ?>
                                        </div>
                                    </div>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            Costo de envío
                                        </div>
                                        <div class="cart-widget-value cart-widget-ship-value">
                                            A convenir
                                        </div>
                                    </div>
                                    <div class="cart-widget-mainblock cart-products-payment_total">
                                        <div class="cart-widget-row cart-widget-title cart-widget-maintitle cart-products-ordertotal">
                                            <div class="cart-widget-label">
                                                Total
                                            </div>
                                            <div class="cart-widget-value cart-widget-total-value">
                                                $ <?php echo number_format($total,2,',','.');  ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                        <div class="cart-widget-label">
                                            <small class="text-primary">* Los descuentos se aplican en el paso siguiente</small>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

