<?php
$menuDatos=true;

require_once('clases/class_admin.php');
require_once('libreria/config.php');
$objAdmin=new LoginAdmin;
// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['ad_usuario'] ) && !empty($_SESSION['ad_password']) ) {
    $arrAdministradores =$objAdmin->esAdmin($_SESSION['ad_usuario'],$_SESSION['ad_password']);
}
// verificamos si esta logeado
if (empty($arrAdministradores)) {
    header( 'Location: login.php' );
    die;
}
 
$ObjConfig=new Configuracion;

$ordenes=new Ordenes();

if (isset($_POST['submit'])) {
	$result=$ordenes->datosTransferencia();
} 

$contenido=$ordenes->traerDatosTransferencia();

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
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
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
        <link href="assets/plugins/dropzone/dropzone.min.css" rel="stylesheet" type="text/css"/>
        
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
                    <h1>Datos transferencia</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Datos transferencia</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    
                                    <form action="datos-transferencia.php" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    
                                    <div class="col-xs-12">
                                        <button name="submit" type="submit" value="agregar" class="btn btn-danger">ACTUALIZAR</button>
                                    </div>
                                    <div class="col-xs-7">
                                                    <div class="form-group">
                                                        <label for="banco" class="col-sm-4 control-label">Banco:</label>
                                                        <div class="col-sm-8">
                                                            <input name="banco" type="text" value="<? if(isset($_POST['banco'])) echo $_POST['banco']; else echo $contenido['banco']; ?>" class="form-control"/>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="tipo" class="col-sm-4 control-label">Tipo de cuenta:</label>
                                                        <div class="col-sm-8">
                                                            <input name="tipo" type="text" value="<? if(isset($_POST['tipo'])) echo $_POST['tipo']; else echo $contenido['tipo']; ?>" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="num_cuenta" class="col-sm-4 control-label">Nº de cuenta / Sucursal:</label>
                                                        <div class="col-sm-8">
                                                            <input name="num_cuenta" type="text" value="<? if(isset($_POST['num_cuenta'])) echo $_POST['num_cuenta']; else echo $contenido['num_cuenta']; ?>" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cbu" class="col-sm-4 control-label">CBU:</label>
                                                        <div class="col-sm-8">
                                                            <input name="cbu" type="text" value="<? if(isset($_POST['cbu'])) echo $_POST['cbu']; else echo $contenido['cbu']; ?>" class="form-control"/>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="titular" class="col-sm-4 control-label">Titular:</label>
                                                        <div class="col-sm-8">
                                                        <input name="titular" type="text" value="<? if(isset($_POST['titular'])) echo $_POST['titular']; else echo $contenido['titular']; ?>" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cuit" class="col-sm-4 control-label">CUIT:</label>
                                                        <div class="col-sm-8">
                                                            <input name="cuit" type="text" value="<? if(isset($_POST['cuit'])) echo $_POST['cuit']; else echo $contenido['cuit']; ?>" class="form-control"/>
                                                        </div>
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
        <script src="assets/plugins/dropzone/dropzone.min.js"></script>
        <script src="assets/js/modern.js"></script>


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