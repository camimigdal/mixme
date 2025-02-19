<?php
$menuEstadisticas=true;

require_once('clases/class_admin.php');
require_once('libreria/config.php');
$objAdmin=new LoginAdmin;

// vemos si el usuario quiere desloguar
if (!empty($_GET['salir'])) {
    // borramos y destruimos todo tipo de sesion del usuario
    session_unset();
    session_destroy();
}

// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['ad_usuario'] ) && !empty($_SESSION['ad_password']) ) {
    $arrAdministradores =$objAdmin->esAdmin($_SESSION['ad_usuario'],$_SESSION['ad_password']);
}

// verificamos si esta logeado
if (empty($arrAdministradores)) {
    header( 'Location: login.php' );
    die;
}

if (isset($_GET['periodo'])) {
    $periodo=$_GET['periodo'];
} else {
    $periodo='hoy';
}

$Obj=new Estadisticas;
$ObjConfig=new Configuracion;

?>

<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>Modern | Datatables</title>
        
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
        <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
        <link href="assets/plugins/summernote-master/summernote.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/jquery-ui/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
        
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
                    <h1>Estadísticas</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Estadísticas</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">

                                
                                    <!-- Modal -->             
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="get" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Período de estadísticas</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control date-picker" name="periodo" required autocomplete="OFF">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Calcular</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>



                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                        <a href="estadisticas.php?periodo=hoy" class="btn <?php if($periodo=='hoy') echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg">Hoy</a>
                                        <a href="estadisticas.php?periodo=semana" class="btn <?php if($periodo=='semana') echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg">Últimos 7 días</a>
                                        <a href="estadisticas.php?periodo=mes" class="btn <?php if($periodo=='mes') echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg">Último mes</a>
                                        <a href="estadisticas.php?periodo=trimestre" class="btn <?php if($periodo=='trimestre') echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg">Último trimestre</a>
                                        <a href="estadisticas.php?periodo=anio" class="btn <?php if($periodo=='anio') echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg">Último año</a>
                                        <button type="button" class="btn <?php if(strpos($periodo, "Hasta")) echo 'btn-primary'; else echo 'btn-default'; ?> btn-rounded btn-lg" data-toggle="modal" data-target="#myModal"> Personalizado</button>
                                        <?php if (strpos($periodo, "Hasta")) { ?>
                                            <h2 class="text-primary"><?php echo $periodo; ?></h2>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->

                    

                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel info-box panel-white">
                                <div class="panel-body">
                                    <div class="info-box-stats">
                                        <p class="counter"><?php echo $Obj->countOrdenesPagas($periodo); ?></p>
                                        <span class="info-box-title">Ordenes pagas</span>
                                    </div>
                                    <div class="info-box-icon">
                                        <i class="icon-basket-loaded"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel info-box panel-white">
                                <div class="panel-body">
                                    <div class="info-box-stats">
                                        <p class="counter"><?php echo round($Obj->countOrdenesPagas($periodo) / $Obj->OrdenesPorDia($periodo), 2); ?></p>
                                        <span class="info-box-title">Ordenes/día</span>
                                    </div>
                                    <div class="info-box-icon">
                                        <i class="icon-basket-loaded"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel info-box panel-white">
                                <div class="panel-body">
                                    <div class="info-box-stats">
                                        <p>$<span class="counter"><?php echo number_format($Obj->countFacturacion($periodo),2,',','.') ?></span></p>
                                        <span class="info-box-title">Facturación</span>
                                    </div>
                                    <div class="info-box-icon">
                                        <i class="icon-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel info-box panel-white">
                                <div class="panel-body">
                                    <div class="info-box-stats">
                                        <p>$<span class="counter"><?php 
                                        if ($Obj->countOrdenesPagas($periodo)==0) {
                                            echo number_format(0,2,',','.');
                                        } else {
                                            echo number_format($Obj->countFacturacion($periodo) / $Obj->countOrdenesPagas($periodo),2,',','.');
                                        }
                                        ?></span></p>
                                        <span class="info-box-title">Ticket promedio</span>
                                    </div>
                                    <div class="info-box-icon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Row -->


                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Cantidad de Ordenes pagas</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="ordenespagas"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Facturación</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="facturacion"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos con mayor facturación</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="inbox-widget slimscroll">
                                        <?php $Obj->productosMasFacturacion($periodo); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos más vendidos</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="inbox-widget slimscroll">
                                        <?php $Obj->productosMasVendidos($periodo); ?>
                                    </div>
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
        <script src="assets/plugins/jquery-mockjax-master/jquery.mockjax.js"></script>
        <script src="assets/plugins/moment/moment.js"></script>
        <script src="assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/address/address.js"></script>
        <script src="assets/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="assets/plugins/summernote-master/summernote.min.js"></script>
        <script src="assets/plugins/morris/raphael.min.js"></script>
        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/js/modern.js"></script>
        <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>


        <script type="text/javascript">
            $(function() {

              $('input[name="periodo"]').daterangepicker({
                  autoUpdateInput: false,
                  locale: {
                      cancelLabel: 'Clear'
                  }
              });

              $('input[name="periodo"]').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val('Desde ' + picker.startDate.format('DD/MM/YYYY') + ' Hasta ' + picker.endDate.format('DD/MM/YYYY'));
              });

              $('input[name="periodo"]').on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
              });

            });
        </script>
        
        
        <script type="text/javascript">
            $(document).ready(function() {
                Morris.Area({
                    element: 'ordenespagas',
                    data: [
                        <?php $Obj->ordenesPagas($periodo); ?>
                    ],
                    xkey: 'day',
                    ykeys: ['ordenes'],
                    labels: ['ordenes'],
                    hideHover: 'auto',
                    lineColors: ['#8adfd0'],
                    resize: true,
                });

                Morris.Area({
                    element: 'facturacion',
                    data: [
                        <?php $Obj->facturacion($periodo); ?>
                    ],
                    xkey: 'day',
                    ykeys: ['total'],
                    labels: ['total'],
                    hideHover: 'auto',
                    lineColors: ['#8adfd0'],
                    resize: true,
                });
            });
        </script>

        <script type="text/javascript">
            $( document ).ready(function() {

                <?php 
                if (isset($result)) {
                    switch ($result) {
                        case 'agregado': 
                            echo 'toastr["success"]("Los datos fueron agregados")';
                            break;
                        case 'eliminado':
                            echo 'toastr["success"]("Se eliminaron los datos correctamente")';
                            break;
                        default:
                            echo 'toastr["error"]("'.$result.'")';
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