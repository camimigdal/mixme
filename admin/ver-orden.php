<?php
$menuCompras=true;
require_once("../class/checkout.class.php");
require_once('clases/class_admin.php');
$objAdmin=new LoginAdmin;

$ordenes=new Ordenes;


if (isset($_GET['id_orden']) && (int)$_GET['id_orden'] > 0) {
    $id_orden=filter_input(INPUT_GET,'id_orden', FILTER_SANITIZE_NUMBER_INT);
}
$ObjCheckout = new Checkout();
$orderContent = $ObjCheckout->GetOrderContent($id_orden);

if (isset($_GET['result']) && $_GET['result']=='agregado') {
    $result=$_GET['result'];
}

if (isset($_GET['action']) && (int)$_GET['action'] > 0) {
    $action=filter_input(INPUT_GET,'action', FILTER_SANITIZE_NUMBER_INT);
    $result=$ordenes->statusOrden($id_orden,$action);
    if (isset($result) && $result=='agregado') {
        header( 'Location: ver-orden.php?id_orden='.$id_orden.'&result=agregado' );
    }
}

if (!empty($_POST['submit']) && $_POST['submit']=='agregar_nota') {
    $result=$ordenes->agregarNota($id_orden);
}
if (!empty($_POST['submit']) && $_POST['submit']=='actualizar_pago') {
    $result=$ordenes->actualizarPago($id_orden);
}
if (!empty($_POST['submit']) && $_POST['submit']=='enviar_codigo') {
    $result=$ordenes->enviarCodigo($id_orden);
}

$orderInfo = $ObjCheckout->GetOrderInfo($id_orden);
?>

<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>Modern | Forms - File Upload</title>
        
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="description" content="Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
        <link href="assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
        <link href="assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>	
        <link href="assets/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/> 
        <link href="assets/plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/> 
        <link href="assets/plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
        <link href="assets/plugins/summernote-master/summernote.css" rel="stylesheet" type="text/css"/>
        
        <!-- Theme Styles -->
        <link href="assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/themes/white.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="page-header-fixed small-sidebar">
        <div class="overlay"></div>
        
        <?php require_once('include/search.php'); ?>
        

        <main class="page-content content-wrap">

            <?php require_once('include/nav-top.php'); ?>
            <?php require_once('include/nav.php'); ?>


            
            <div class="page-inner">
                <div class="page-title">
                    <h1>Orden <strong class="text-danger">#<?php echo $id_orden ?></strong> <small><?php echo date("d M Y", strtotime($orderInfo["fecha_alta"])) ?></small></h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php">Ventas</a></li>
                            <li class="active">Orden N°<?php echo $id_orden ?></li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    
                                    <?php $ordenes->comboEstados($id_orden,$orderInfo["or_estado"]); ?>
								
                                    <?php 
                                            if (!empty($orderInfo["or_notas"]) and $orderInfo["or_notas"]!='<p><br></p>') {
                                                echo '<hr><div class="alert alert-danger alert-dismissible fade in" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h4>Notas:</h4>
                                                    '.$orderInfo["or_notas"].'
                                                </div>';
                                            }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <?php if ($orderInfo["or_estado"]>2 && $orderInfo["or_estado"]<7) { ?>
                                <a href="etiqueta-orden.php?id_orden=<?php echo $id_orden; ?>" target="_blank" class="btn btn-default btn-lg m-b-lg"><i class="fa fa-file-pdf-o"></i> Imprimir etiqueta</a>
                            <?php } ?>
                        </div>
                        <div class="col-lg-9 col-md-12">
                            <div class="panel panel-white">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="visitors-chart">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">Comprador</h4>
                                            </div>
                                            <div class="panel-body">
                                                <div class="weather-widget">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="weather-top">
                                                                <h2 class="weather-day"><?php echo $orderInfo["or_nombre"].' '.$orderInfo["or_apellido"]; ?>
                                                                <?php 
                                                                if ($orderInfo["mayorista"]!=0) {
                                                                    echo '<span class="label label-danger">Mayorista</span>';
                                                                } 
                                                                ?>
                                                                <br><small>DNI/CUIT <b><?php echo $orderInfo["or_dni"]; ?></b></small></h2>
                                                            </div>
                                                            <?php if ($orderInfo["mayorista"]!=0) { 
                                                                $cliente = $ObjCheckout->GetMayoristaInfo($orderInfo["mayorista"]); ?>
                                                                <div class="weather-top">
                                                                    <h2 class="weather-day">Login:
                                                                        <br><small>Nombre y Apellido: <b><?php echo $cliente["cl_nombre"]; ?></b></small>
                                                                        <br><small>Email: <b><?php echo $cliente["cl_mail"]; ?></b></small>
                                                                        <br><small>Teléfono: <b><?php echo $cliente["cl_telefono"]; ?></b></small>
                                                                        <br><small>Empresa: <b><?php echo $cliente["cl_empresa"]; ?></b></small>
                                                                    </h2>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <ul class="list-unstyled weather-info">
                                                                <li>Teléfono <span class="pull-right"><b><?php echo $orderInfo["or_telefono"]; ?></b></span></li>
                                                                <li>Email <span class="pull-right"><b><?php echo $orderInfo["or_email"]; ?></b></span></li>
                                                                <li><?php echo $orderInfo['or_calle'].' '.$orderInfo['or_calle_num'];

                                                                        if (!empty($orderInfo['or_piso'])) {
                                                                            echo ' '.$orderInfo['or_piso'];
                                                                        }
                                                                        if (!empty($orderInfo['or_depto'])) {
                                                                            echo ' '.$orderInfo['or_depto'];
                                                                        }
                                                                    
                                                                    echo ', CP '.$orderInfo['or_codpostal']; ?><br>
                                                                    <?php echo $orderInfo['or_ciudad'].', '.$orderInfo['or_provincia']; ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="productos-orden">
                                                                    <div class="table-responsive project-stats">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Producto</th>
                                                                                    <th></th>
                                                                                    <th>Precio</th>
                                                                                    <th>Cantidad</th>
                                                                                    <th width="150">Total</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            <?php
    
                                                                            $numItem=count($orderContent); 
                                                                            $cant_prod=0;
                                                                            $total=0;
                                                                                for ($i=0; $i<$numItem; $i++) {
                                                                                    extract($orderContent[$i]);
                                                                                    
                                                                                    $total += $precio * $cantidad;
                                                                                    $cant_prod += $cantidad;
                                                                                ?>
                                                                            
                                                                                        <tr>
                                                                                            <td>
                                                                                                <img src="<?php echo '../img/productos/'.$im_nombre ?>" width="80px"/>
                                                                                            </td>
                                                                                            <td>
                                                                                                <div class="dat_comp">
                                                                                                    
                                                                                                    <?php echo '<b>'.$codigo.'</b>' ?><br>
                                                                                                    <?php echo $pd_titulo ?><br>
                                                                                                    <?php if ($variacion) {
                                                                                                        echo '<b>'.$variacion.'</b>';
                                                                                                    } ?>
                                                                                                    <?php if ($status=='mix') {
                                                                                                        $idMix = str_replace("mix-", "", $codigo);
                                                                                                        echo '<br><a href="mix.php?order='.$idMix.'" class="btn btn-success btn-addon m-b-sm btn-rounded btn-lg">Ver mix</a>';
                                                                                                    } ?>

                                                                                                </div>
                                                                                            </td>
                                                                                            <td>$<?php echo number_format($precio,2,',','.') ?></td>
                                                                                            <td><span class="label label-danger"><?php echo $cantidad ?></span></td>
                                                                                            <td>$<?php echo number_format($precio * $cantidad,2,',','.') ?></td>
                                                                                        </tr>
                                                                            
                                                                            <?php } ?>

                                                                                <tr>
                                                                                    <td colspan="2">&nbsp;</td>
                                                                                    <th colspan="2">COSTO DE ENVÍO</td>
                                                                                    <th>$<?php echo number_format($orderInfo["env_valor"],2,',','.') ?></td>
                                                                                </tr>
                                                                            


                                                                                <?php

                                                                                $orderDiscount=$ObjCheckout->GetOrderDiscount($id_orden);
                                                                                $numItemDesc=count($orderDiscount);  

                                                                                    for ($i=0; $i<$numItemDesc; $i++) {
                                                                                        extract($orderDiscount[$i]);
                                                                                    ?>
                                                                                
                                                                                            <tr>
                                                                                                <th colspan="4" class="text-danger"><?php echo $desc_descripcion ?></td>
                                                                                                <th class="text-danger">$-<?php echo number_format($desc_precio,2,',','.') ?></td>
                                                                                            </tr>
                                                                                
                                                                                <?php } ?>



                                                                                <tr>
                                                                                    <td colspan="4">&nbsp;</td>
                                                                                    <td><div class="server-load">
                                                                                        <div class="server-stat">
                                                                                            <span>TOTAL COMPRA</span>
                                                                                            <p class="text-danger">$<?php echo number_format($orderInfo["total_compra"],2,',','.'); ?></p>
                                                                                        </div>
                                                                                    </div></td>
                                                                                </tr>
                                                                                
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="stats-info">
                                            <div class="panel-body">
                                                <ul class="list-unstyled">
                                                    <?php 
                                                    
                                                    switch ($orderInfo["or_medio_pago"]) {
                                                        case 'mp':
                                                            echo '<li><img src="assets/images/mp-icon.svg" width="36" class="m-r-md"> MERCADO PAGO</li>';
                                                            break;
                                                        case 'tp':
                                                            echo '<li><img src="assets/images/tp-icon.svg" width="36" class="m-r-md"> TODO PAGO</li>';
                                                            break;
                                                        case 'transferencia':
                                                            echo '<li><img src="assets/images/transf-icon.svg" width="36" class="m-r-md"> TRANSFERENCIA BANCARIA</li>';
                                                            break;
                                                        case 'efectivo':
                                                            echo '<li><img src="assets/images/money-icon.svg" width="36" class="m-r-md"> EFECTIVO CONTRA ENTREGA</li>';
                                                            break;
                                                    } ?>


                                                <?php if ($orderInfo["or_estado"]==2) { ?>
                                                    <li>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>ID DE PAGO</span>
                                                                    <p><div class="input-group m-b-sm">
                                                                            <b class="input-group-addon" id="hash">#</b>
                                                                            <input type="text" class="form-control" name="id_pago" id="id_pago" aria-describedby="hash" value="<?php echo $orderInfo["pago_id"]; ?>" >
                                                                        </div>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>FORMA DE PAGO</span>
                                                                    <p><input type="text" class="form-control" name="forma_pago" id="forma_pago" value="<?php echo $orderInfo["pago_forma"]; ?>" ></p>
                                                                </div>
                                                            </div>
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>ESTADO</span>
                                                                    <p><select id="estado_pago" name="estado_pago" class="form-control" >
                                                                        <option value="<?php echo $orderInfo["pago_status"] ?>" <?php if ($orderInfo["pago_status"]=="") { echo 'selected'; } ?> ></option>
                                                                        <option value="pending" <?php if ($orderInfo['pago_status']=="pending") { echo 'selected'; }; ?> >Pendiente</option>
                                                                        <option value="approved" <?php if ($orderInfo['pago_status']=="approved") { echo 'selected'; }; ?> >Aprobado</option>
                                                                        <option value="rejected" <?php if ($orderInfo['pago_status']=="rejected") { echo 'selected'; }; ?> >Rechazado</option>
                                                                        <option value="cancelled" <?php if ($orderInfo['pago_status']=="cancelled") { echo 'selected'; }; ?> >Cancelado</option>
                                                                        <option value="charged_back" <?php if ($orderInfo['pago_status']=="charged_back") { echo 'selected'; }; ?> >Devolución</option>
                                                                    </select></p>
                                                                </div>
                                                            </div>
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>TOTAL PAGADO</span>
                                                                    <p class="text-danger"><div class="input-group m-b-sm">
                                                                            <b class="input-group-addon text-danger" id="hash">$</b>
                                                                            <input type="text" class="form-control text-danger" name="total_pagado" id="total_pagado" aria-describedby="hash" value="<?php echo $orderInfo["total_pagado"]; ?>" >
                                                                        </div>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-sm-12 text-right">
                                                                    <button type="submit" name="submit" value="actualizar_pago" class="btn btn-success btn-lg">Actualizar datos y enviar confirmación</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </li>

                                                <?php } else { ?>
                                                    
                                                    <li>
                                                        <div class="server-load">
                                                            <div class="server-stat">
                                                                <span>ID DE PAGO</span>
                                                                <p>#<?php echo $orderInfo["pago_id"]; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="server-load">
                                                            <div class="server-stat">
                                                                <span>FORMA DE PAGO</span>
                                                                <p><?php echo $orderInfo["pago_forma"]; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="server-load">
                                                            <div class="server-stat">
                                                                <span>ESTADO</span>
                                                                <p><?php echo $orderInfo["pago_status"]; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="server-load">
                                                            <div class="server-stat">
                                                                <span>TOTAL PAGADO</span>
                                                                <p class="text-danger">$<?php echo number_format($orderInfo["total_pagado"],2,',','.'); ?></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                
                                                <?php } ?>

                                                </ul>
                                                
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-white" style="height: 100%;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Entrega</h4>
                                    <div class="panel-control">
                                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Expand/Collapse" class="panel-collapse"><i class="icon-arrow-down"></i></a>
                                    </div>
                                </div>
                                <div class="panel-body">

                                <?php if ($orderInfo["env_tipo"]=='D') { ?>


                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Modalidad</span>
                                                    <?php 
                                                        switch ($orderInfo["env_tipo"]) {
                                                            case 'D':
                                                                echo '<p>Envío a domicilio</p>';
                                                                break;
                                                            case 'S':
                                                                echo '<p>Retiro en sucursal</p>';
                                                                break;
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Correo</span>
                                                    <p><?php echo $orderInfo["env_nom_correo"]; ?></p>
                                                </div>
                                                <div class="server-stat">
                                                    <span>Descripción</span>
                                                    <p><?php echo $orderInfo["env_descripcion"]; ?></p>
                                                </div>
                                            </div>

                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Tiempos de entrega</span>
                                                    <p><?php echo $orderInfo["env_horas_entrega"]; ?></p>
                                                </div>
                                            </div>


                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>COSTO DE ENVÍO</span>
                                                    <p class="text-danger">$<?php echo number_format($orderInfo["env_valor"],2,',','.'); ?></p>
                                                </div>
                                            </div>

                                            <ul class="list-unstyled weather-days weather-info">
                                                <li><h4 class="panel-title">Enviar a:</h4></li>
                                                <li><b><?php echo $orderInfo["env_nombre"].' '.$orderInfo["env_apellido"]; ?></b><br>
                                                <?php echo $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
                                                    if (!empty($orderInfo['env_piso'])) {
                                                        echo ' '.$orderInfo['env_piso'];
                                                    }
                                                    if (!empty($orderInfo['env_depto'])) {
                                                        echo ' '.$orderInfo['env_depto'];
                                                    }          
                                                    echo ', CP '.$orderInfo['env_codpostal']; ?><br>
                                                    <?php echo $orderInfo['env_localidad'].', '.$orderInfo['env_provincia']; ?><br>
                                                    Teléfono <b><?php echo $orderInfo["or_telefono"]; ?></b>
                                                </li>
                                            </ul>
                                            <hr>
                                            <ul class="list-unstyled">
                                                <?php if (empty($orderInfo["env_seguimiento"])) { ?>
                                                    <li>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>CÓDIGO DE SEGUIMIENTO</span>
                                                                    <div class="input-group m-b-sm">
                                                                        <b class="input-group-addon" id="hash">#</b>
                                                                        <input type="text" class="form-control" name="cod_seguimiento" id="cod_seguimiento" aria-describedby="hash" value="<?php echo $orderInfo["env_seguimiento"]; ?>" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <span>LINK DE SEGUIMIENTO</span>
                                                                    <div class="input-group m-b-sm">
                                                                        <input type="text" class="form-control" name="link_seguimiento" id="link_seguimiento" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="server-load">
                                                                <div class="server-stat">
                                                                    <button type="submit" name="submit" value="enviar_codigo" class="btn btn-success btn-lg">ENVIAR CÓDIGO</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </li>

                                                <?php } else { ?>
                                                    
                                                    <li>
                                                        <div class="server-load">
                                                            <div class="server-stat">
                                                                <span>CÓDIGO DE SEGUIMIENTO</span>
                                                                <p class="text-danger">#<?php echo $orderInfo["env_seguimiento"]; ?></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                
                                                <?php } ?>
                                            </ul>


                                        
                                <?php } elseif ($orderInfo["env_tipo"]=='S') {?>
                                        
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>Retiro personal</span>
                                                    <p>Retiro en sucursal</p>
                                                </div>
                                            </div>
                                            <div class="server-load">
                                                <div class="server-stat">
                                                    <span>COSTO DE ENVÍO</span>
                                                    <p class="text-danger">$<?php echo number_format($orderInfo["env_valor"],2,',','.'); ?></p>
                                                </div>
                                            </div>

                                            <ul class="list-unstyled weather-days weather-info">
                                                <li><h4 class="panel-title">Retira:</h4></li>
                                                <li><b><?php echo $orderInfo["env_nombre"].' '.$orderInfo["env_apellido"]; ?></b><br>
                                                <?php echo $orderInfo['env_calle'].' '.$orderInfo['env_numero'];
                                                    if (!empty($orderInfo['env_piso'])) {
                                                        echo ' '.$orderInfo['env_piso'];
                                                    }
                                                    if (!empty($orderInfo['env_depto'])) {
                                                        echo ' '.$orderInfo['env_depto'];
                                                    }          
                                                    echo ', CP '.$orderInfo['env_codpostal']; ?><br>
                                                    <?php echo $orderInfo['env_localidad'].', '.$orderInfo['env_provincia']; ?><br>
                                                    Teléfono <b><?php echo $orderInfo["or_telefono"]; ?></b>
                                                </li>
                                            </ul>
                                
                                <?php } ?>

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                            
                                            <h1>Notas de la orden</h1>
                                            <hr>
                                            <form method="post" enctype="multipart/form-data" class="form-horizontal">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea name="notas" class="summernote"><?php if(isset($_POST['notas'])) echo $_POST['notas']; else echo $orderInfo['or_notas']; ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-12 text-right">
                                                        <button type="submit" name="submit" value="agregar_nota" class="btn btn-success btn-lg">Agregar a nota</button>
                                                    </div>
                                                </div>
                                            </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->
                </div><!-- Main Wrapper -->

                <?php require_once('include/footer.php'); ?>

            </div><!-- Page Inner -->
        </main><!-- Page Content -->
        
	

        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.1.3.min.js"></script>
        <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="assets/plugins/pace-master/pace.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/switchery/switchery.min.js"></script>
        <script src="assets/plugins/uniform/jquery.uniform.min.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/classie.js"></script>
        <script src="assets/plugins/waves/waves.min.js"></script>
        <script src="assets/plugins/3d-bold-navigation/js/main.js"></script>
        <script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/summernote-master/summernote.min.js"></script>
        <script src="assets/js/modern.js"></script>


        <script type="text/javascript">
            $(document).ready(function() {
                $('.summernote').summernote({
                    height: 200,
                    callbacks: {
                        onPaste: function(e) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            setTimeout(function() {
                                document.execCommand('insertText', false, bufferText);
                            }, 10);
                        }
                    }
                });
            });
            $( document ).ready(function() {

                <?php 
                if (isset($result)) {
                    switch ($result) {
                        case 'agregado': 
                            echo 'toastr["success"]("Orden actualizada")';
                            break;
                        case 'eliminado':
                            echo 'toastr["success"]("Se eliminaron los datos correctamente")';
                            break;
                        default:
                            echo 'toastr["danger"]("Error: '.$result.'")';
                            break;
                    } 
                }?>

                toastr.options = {
                  "closeButton": false,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-center",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }
        });
        </script>

        
    </body>
</html>