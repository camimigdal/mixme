<?php
if (!defined('WEB_ROOT')
|| !isset($_GET['step'])) {
exit;
}
?>

            <section id="checkout">
                
                <div class="js-decorate co-checkoutprogressindicator">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <ul>
                                    <li class="inactive step-1 layer"><span>1</span>Datos</li>
                                    <li class="active step-2 layer"><span>2</span>Confirmar</li>
                                    <li class="inactive step-3 layer"><span>3</span>Pagar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container py-5">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=3" method="post" name="frmCheckout" id="frmCheckout">

                                    <div class="review-block">
                                        <h6>E-MAIL</h4>
                                        <p><?php echo $_POST['per_email']; ?></p>
                                    </div>
                              
                            <?php if($_POST['envio']=='D') { ?>
                                    
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>ENTREGA</h4>
                                            <p><?php echo $_POST['nombre_correo']; ?></p>
                                            <p><?php echo $_POST['descripcion_correo']; ?></p>
                                        </div>
                                    </div>

                                    <div class="review-block">
                                        <?php  
                                        if (isset($_POST['chkDatos'])) { ?>

                                            <div class="mt-3">
                                                <h6>DATOS DE FACTURACIÓN Y ENTREGA</h4>
                                                <p>DNI/CUIT <?php echo $_POST['envio_dni'] ?></p>
                                                <p><?php echo $_POST['envio_nombre'].' '.$_POST['envio_apellido']; ?></p>
                                                <p>Tel <?php echo $_POST['envio_telefono']; ?></p>
                                                <p><?php echo $_POST['envio_direccion'].' '.$_POST['envio_calle_num'];

                                                    if (!empty($_POST['envio_piso'])) {
                                                        echo ' '.$_POST['envio_piso'];
                                                    }
                                                    if (!empty($_POST['envio_dpto'])) {
                                                        echo ' '.$_POST['envio_dpto'];
                                                    }
                                                
                                                echo ', CP '.$_POST['env_codpostal']; ?></p>
                                                <p><?php echo $_POST['envio_ciudad'].', '.$_POST['envio_provincia']; ?></p>
                                            </div>

                                        <?php } else { ?>

                                            <div class="mt-3">
                                                <h6>DATOS DE FACTURACIÓN</h4>
                                                <p>DNI/CUIT <?php echo $_POST['per_dni'] ?></p>
                                                <p><?php echo $_POST['per_nombre'].' '.$_POST['per_apellido']; ?></p>
                                                <p>Tel <?php echo $_POST['per_telefono']; ?></p>
                                                <p><?php echo $_POST['per_direccion'].' '.$_POST['per_calle_num'];

                                                    if (!empty($_POST['per_piso'])) {
                                                        echo ' '.$_POST['per_piso'];
                                                    }
                                                    if (!empty($_POST['per_dpto'])) {
                                                        echo ' '.$_POST['per_dpto'];
                                                    }
                                                
                                                echo ', CP '.$_POST['per_codpostal']; ?></p>
                                                <p><?php echo $_POST['per_ciudad'].', '.$_POST['per_provincia']; ?></p>
                                            </div>

                                            <div class="mt-3">
                                                <h6>DATOS DE ENTREGA</h4>
                                                <p>DNI/CUIT <?php echo $_POST['envio_dni'] ?></p>
                                                <p><?php echo $_POST['envio_nombre'].' '.$_POST['envio_apellido']; ?></p>
                                                <p>Tel <?php echo $_POST['envio_telefono']; ?></p>
                                                <p><?php echo $_POST['envio_direccion'].' '.$_POST['envio_calle_num'];

                                                    if (!empty($_POST['envio_piso'])) {
                                                        echo ' '.$_POST['envio_piso'];
                                                    }
                                                    if (!empty($_POST['envio_dpto'])) {
                                                        echo ' '.$_POST['envio_dpto'];
                                                    }
                                                
                                                echo ', CP '.$_POST['env_codpostal']; ?></p>
                                                <p><?php echo $_POST['envio_ciudad'].', '.$_POST['envio_provincia']; ?></p>
                                            </div>

                                        <?php } ?>

                                    </div>

                            <?php } elseif($_POST['envio']=='S') { ?>

                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>ENTREGA</h4>
                                            <p>Retiro personal en Mixme</p>
                                            <p>Av. Argentina 5659, Villa Lugano, CABA, Argentina</p>
                                        </div>
                                    </div>
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>DATOS DE FACTURACIÓN</h4>
                                            <p>DNI/CUIT <?php echo $_POST['envio_dni'] ?></p>
                                            <p><?php echo $_POST['per_nombre'].' '.$_POST['per_apellido']; ?></p>
                                            <p>Tel <?php echo $_POST['per_telefono']; ?></p>
                                            <p><?php echo $_POST['per_direccion'].' '.$_POST['per_calle_num'];

                                                if (!empty($_POST['per_piso'])) {
                                                    echo ' '.$_POST['per_piso'];
                                                }
                                                if (!empty($_POST['per_dpto'])) {
                                                    echo ' '.$_POST['per_dpto'];
                                                }
                                                
                                            echo ', CP '.$_POST['per_codpostal']; ?></p>
                                            <p><?php echo $_POST['per_ciudad'].', '.$_POST['per_provincia']; ?></p>
                                        </div>
                                    </div>

                            <?php } ?>


                                <div class="review-block">
                                    <div class="mt-3">
                                        <h6>MEDIO DE PAGO</h4>
                                        <?php 
                                            switch ($_POST['opcion_pago']) {
                                                case 'mp':
                                                    echo "<p><strong>Mercado Pago</strong> (al momento de pagar eliges el medio de pago que más te guste)</p>";
                                                    break;
                                                case 'tp':
                                                    echo "<p><strong>Todo Pago</strong></p>";
                                                    break;
                                                case 'transferencia':
                                                    echo "<p>Transferencia bancaria (te llegarán los datos a tu email)</p>";
                                                    break;
                                                case 'efectivo':
                                                    echo "<p>Efectivo contra entrega</p>";
                                                    break;
                                                case 'otro':
                                                    echo "<p>Otros medios de pago (el vendedor se contacta)</p>";
                                                    break;
                                        } ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($_POST['mensaje'])) { ?>
                                    <div class="review-block">
                                        <div class="mt-3">
                                            <h6>NOTAS DE PEDIDO</h4>
                                            <p><?php echo $_POST['mensaje']; ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <a href="javascript:history.back(1)" class="btn btn-outline-primary btn-sm my-3">Editar</a>
                            </form>  
                            
                            <?php require_once "checkout/$includeFilePayment"; ?>
                            
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="sticky-top">
                                <h4>Vas a pagar</h4>
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
                                            $ <?php echo number_format($_POST['costo_envio'],2,',','.');  ?>
                                        </div>
                                    </div>

                                    <?php 
                            
                                    $numItemDesc=count($orderDiscount);
                                    for ($i=0; $i<$numItemDesc; $i++) {
                                        extract($orderDiscount[$i]);
                                        
                                        echo '<div class="cart-widget-block cart-widget-row cart-widget-title cart-widget-maintitle">
                                                <div class="cart-widget-label">
                                                    <small class="text-primary">'.$desc_descripcion.'</small>
                                                </div>
                                                <div class="cart-widget-value cart-widget-ship-value">
                                                    <small class="text-primary">$ -'.number_format($desc_precio,2,',','.').'</small>
                                                </div>
                                            </div>';
                                    }

                                    ?>
                                    
                                    <div class="cart-widget-mainblock cart-products-payment_total">
                                        <div class="cart-widget-row cart-widget-title cart-widget-maintitle cart-products-ordertotal">
                                            <div class="cart-widget-label">
                                                Total
                                            </div>
                                            <div class="cart-widget-value cart-widget-total-value">
                                                $ <?php echo number_format($orderAmount,2,',','.');  ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>

            