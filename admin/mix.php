<?php
require_once('clases/class_admin.php');
$objAdmin=new LoginAdmin;

if (isset($_GET['order']) && (int)$_GET['order'] > 0) {
    $order=filter_input(INPUT_GET,'order', FILTER_SANITIZE_NUMBER_INT);
}
$Obj=new Mixer;
$mixContent = $Obj->GetMixContent($order);
$mixInfo = $Obj->GetMixInfo($order);

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
                    <h1>Mix <strong class="text-danger">#<?php echo $order ?></strong></h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php">Ventas</a></li>
                            <li class="active">Mix <?php echo $order ?></li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12 text-right">
                        
                        </div>
                        <div class="col-lg-9 col-md-12">
                            <div class="panel panel-white">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="visitors-chart">
                                            <div class="panel-body">
                                                <div class="weather-widget">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="weather-top">
                                                                <h2 class="weather-day">Mix <?php echo $mixInfo["nombre"] ?><br><small><b><?php echo $mixInfo["descripcion"] ?></b></small></h2>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="productos-orden">
                                                                    <div class="table-responsive project-stats">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Ingredientes</th>
                                                                                    <th></th>
                                                                                    <th>Precio</th>
                                                                                    <th>Cantidad</th>
                                                                                    <th width="150">Total</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            <?php
    
                                                                            $numItem=count($mixContent); 
                                                                            $cant_prod=0;
                                                                            $precio_total=0;
                                                                            $peso_total=0;

                                                                            for ($i=0; $i<$numItem; $i++) {
                                                                                extract($mixContent[$i]);
                                                                                
                                                                                if ($es_base=='si') {
                                                                                    $precio_base = ($mixInfo["mayorista"]!=0) ? $pd_precio_mayo : $pd_precio;
                                                                                } else {
                                                                                    $peso_total += $cantidad * (($mixInfo["mayorista"]!=0) ? $pd_peso_mayo : $pd_peso);
                                                                                    $precio_total += $cantidad * (($mixInfo["mayorista"]!=0) ? $pd_precio_mayo : $pd_precio);
                                                                                }
                                                                            }
                                                                            if (isset($precio_base)) {
                                                                                $peso_base = $peso - $peso_total;
                                                                                $precio_base_reduc = ($peso_base * $precio_base) / (($mixInfo["mayorista"]!=0) ? 1000 : 350);
                                                                                $precio_total += $precio_base_reduc;
                                                                            }



                                                                                for ($i=0; $i<$numItem; $i++) {
                                                                                    extract($mixContent[$i]);
                                                                                    
                                                                                    if ($es_base=='si') {
                                                                                        $subtotal = $precio_base_reduc;
                                                                                    } else {
                                                                                        $subtotal = $cantidad * (($mixInfo["mayorista"]!=0) ? $pd_precio_mayo : $pd_precio);
                                                                                    }
                                                                                    $cant_prod += $cantidad;
                                                                                ?>
                                                                            
                                                                                        <tr>
                                                                                            <td>
                                                                                                <img src="<?php echo '../img/productos-mixer/'.$pd_img ?>" width="80px"/>
                                                                                            </td>
                                                                                            <td>
                                                                                                <div class="dat_comp">
                                                                                                    
                                                                                                    <?php echo '<b>'.$pd_titulo.'</b>'; ?>

                                                                                                </div>
                                                                                            </td>
                                                                                            <td>$<?php echo ($mixInfo["mayorista"]!=0) ? number_format($pd_precio_mayo,2,',','.') : number_format($pd_precio,2,',','.') ?></td>
                                                                                            <td><span class="label label-danger"><?php if($es_base=='no') echo '('.$cantidad.') '.(($mixInfo["mayorista"]!=0) ? ($pd_peso_mayo*5) : $pd_peso).'g'; else echo '(base) '.(($mixInfo["mayorista"]!=0) ? ($peso_base*5) : $peso_base).'g'; ?></span></td>
                                                                                            <td>$<?php echo ($mixInfo["mayorista"]!=0) ? number_format($subtotal*5,2,',','.') : number_format($subtotal,2,',','.') ?></td>
                                                                                        </tr>
                                                                            
                                                                            <?php } ?>
                                                                                
                                                                                <tr>
                                                                                    <td colspan="2">&nbsp;</td>
                                                                                    <td colspan="2"><div class="server-load">
                                                                                        <div class="server-stat">
                                                                                            <span>CONT. NETO</span>
                                                                                            <p class="text-danger"><?php echo (($mixInfo["mayorista"]!=0) ? ($mixInfo['peso']*5) : $mixInfo['peso']) ?>grs</p>
                                                                                        </div>
                                                                                    </div></td>
                                                                                    <td><div class="server-load">
                                                                                        <div class="server-stat">
                                                                                            <span>TOTAL</span>
                                                                                            <p class="text-danger">$<?php echo ($mixInfo["mayorista"]!=0) ? number_format($precio_total*5,2,',','.') : number_format($precio_total,2,',','.')?></p>
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
                                                
                                            <?php if ($mixInfo['pack']=='tubo') { ?>
                                                
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">Pack</h4>
                                                </div>
                                                <div class="panel-white text-center">
                                                    <img src="../img/packs/modelos/<?php echo $mixInfo['modelo'] ?>.png" alt="Tubo" width="200">
                                                </div>

                                            <?php } elseif ($mixInfo['pack']=='doypack') { ?>

                                                <div class="panel-heading">
                                                    <h4 class="panel-title">Pack</h4>
                                                </div>
                                                <div class="panel-white text-center">
                                                    <img src="../img/packs/modelos/doypack.png" alt="Doypack" width="200">
                                                </div>

                                            <?php } elseif ($mixInfo['pack']=='bolsa') { ?>

                                                <div class="panel-heading">
                                                    <h4 class="panel-title">Pack</h4>
                                                </div>
                                                <div class="panel-white text-center">
                                                    <img src="../img/packs/modelos/bolsa.png" alt="Bolsa" width="300">
                                                </div>

                                            <?php } ?>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-white" style="height: 100%;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Etiqueta</h4>
                                </div>
                                <div class="panel-body">
                                    <a href="../mix-etiqueta-pdf.php?id=<?php echo $order; ?>&token=<?php echo $mixInfo["session_id"] ?>" target="_blank" class="btn btn-success btn-lg m-b-lg"><i class="fa fa-file-pdf-o"></i> Imprimir etiqueta</a>
                                </div>
                            </div>
                        </div>
                    </div>
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