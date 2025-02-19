<?php
$menuCompras=true;

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

$ordenes=new Ordenes;


if (isset($_GET['status'])) {
    $status=filter_input(INPUT_GET,'status', FILTER_SANITIZE_NUMBER_INT);
} 

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
}

switch ($action) {
    case 'delete' :
      $result = $Obj->Borrar($id);
      break;
    case 'view' :
  }

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
        <link href="assets/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css"/>
        
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
                    <h1>Ventas</h1>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-xs-12 text-right m-b-md">
                            <a href="index.php?status=1" class="btn btn-danger btn-lg"><strong>Iniciadas</strong><br><small><?php $ordenes->countStatus(1); ?> ordenes</small></a>
                            <a href="index.php?status=2" class="btn btn-danger btn-lg"><strong>Esperando pago</strong><br><small><?php $ordenes->countStatus(2); ?> ordenes</small></a>
                            <a href="index.php?status=8" class="btn btn-danger btn-lg"><strong>Canceladas</strong><br><small><?php $ordenes->countStatus(8); ?> ordenes</small></a>
                            <a href="index.php?status=9" class="btn btn-danger btn-lg"><strong>Rechazadas</strong><br><small><?php $ordenes->countStatus(9); ?> ordenes</small></a>
                            <a href="index.php?status=7" class="btn btn-default btn-lg"><strong>Archivadas</strong><br><small><?php $ordenes->countStatus(7); ?> ordenes</small></a>
                        </div>
                        
                    </div><!-- Row -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">

                                    

                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            
                                            <div class="panel panel-blue panel-ventas">
                                            <a href="index.php?status=3">
                                                <div class="panel-heading">
                                                    <h2 class="panel-title">Pagadas</h2>
                                                </div>
                                                <div class="panel-body">
                                                    <?php $ordenes->countStatus(3); ?> ordenes
                                                </div>
                                                </a>
                                            </div>    
                                            
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            
                                            <div class="panel panel-purple panel-ventas">
                                            <a href="index.php?status=4">
                                                <div class="panel-heading">
                                                    <h2 class="panel-title">Listas para despachar</h2>
                                                </div>
                                                <div class="panel-body">
                                                <?php $ordenes->countStatus(4); ?> ordenes
                                                </div>
                                                </a>
                                            </div>    
                                            
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            
                                            <div class="panel panel-yellow panel-ventas">
                                                <a href="index.php?status=5">
                                                    <div class="panel-heading">
                                                        <h2 class="panel-title">Despachadas</h2>
                                                    </div>
                                                    <div class="panel-body">
                                                    <?php $ordenes->countStatus(5); ?> ordenes
                                                    </div>
                                                </a>
                                            </div>    
                                            
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            
                                            <div class="panel panel-green panel-ventas">
                                            <a href="index.php?status=6">
                                                <div class="panel-heading">
                                                    <h2 class="panel-title">Finalizadas</h2>
                                                </div>
                                                <div class="panel-body">
                                                <?php $ordenes->countStatus(6); ?> ordenes
                                                </div>
                                                </a>
                                            </div>    
                                            
                                        </div>
                                    </div><!-- Row -->
                                    <hr>
                                    
                                    <?php 
                                    if (isset($status)) {
                                        switch ($status) {
                                            case 1:
                                                echo '<h2 class="m-b-lg text-danger">Ordenes iniciadas abandonadas</h2>';
                                                break;
                                                case 2:
                                                    echo '<h2 class="m-b-lg text-danger">Ordenes en proceso de pago</h2>';
                                                    break;
                                                    case 3:
                                                        echo '<h2 class="m-b-lg text-info">Ordenes pagadas para preparar</h2>';
                                                        break;
                                                        case 4:
                                                            echo '<h2 class="m-b-lg text-primary">Ordenes listas para despachar</h2>';
                                                            break;
                                                            case 5:
                                                                echo '<h2 class="m-b-lg text-warning">Ordenes despachadas</h2>';
                                                                break;
                                                                case 6:
                                                                    echo '<h2 class="m-b-lg text-success">Ordenes pagadas y entregadas</h2>';
                                                                    break;
                                                                    case 7:
                                                                        echo '<h2 class="m-b-lg text-muted">Ordenes archivadas</h2>';
                                                                        break;
                                                                        case 8:
                                                                            echo '<h2 class="m-b-lg text-danger">Ordenes canceladas</h2>';
                                                                            break;
                                                                            case 9:
                                                                                echo '<h2 class="m-b-lg text-danger">Ordenes rechazadas</h2>';
                                                                                break;
                                        }
                                    } else {
                                        echo '<h2 class="m-b-lg">Ventas</h2>';
                                    }
                                    ?>

                                    <div class="table-responsive project-stats">  
                                       <table id="tabla" class="display table" style="width: 100%; cellspacing: 0;">
                                           <thead>
                                               <tr>
                                                   <th>Orden</th>
                                                   <th>Fecha</th>
                                                   <th>Productos</th>
                                                   <th>Pago | Envío</th>
                                                   <th>Total</th>
                                                   <th>Comprador</th>
                                                   <th>Estado de la orden</th>
                                                   <th width="200" style="width: 200px;text-align: center;">Cambiar estado</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                           </tbody>
                                        </table>
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
        <script src="assets/js/modern.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {

                function getEmployee() {
                    $.ajax({
                      type: "GET",  
                      url: 'include/lista-ordenes.php'+'<?php 
                            if (isset($_GET['status'])) {
                                echo "?status=".$_GET['status'];
                            }
                            ?>',
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {
                            html_data+= '<tr><th><a href="ver-orden.php?id_orden='+response[i].id_orden+'">'+response[i].id_orden+'</a></th>';

                            html_data += '<td>'+response[i].fecha_alta+'</td>';

                            html_data += '<td>'+response[i].productos+'</td>';
                            html_data += '<td>'+response[i].or_medio_pago+response[i].env_tipo+'</td>';
                            html_data += '<td class="text-danger"><strong>$</strong>'+response[i].total_compra+'</td>';

                            html_data += '<td>'+response[i].or_nombre+' '+response[i].or_apellido+' '+response[i].mayorista+'</td>';
                            
                            switch (response[i].or_estado) {
                                case '1':
                                    html_data += '<td><span class="label label-danger">'+response[i].st_nombre+'</span> <div class="progress progress-sm"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%"></div></div></td>';
                                    break;
                                case '2':
                                    html_data += '<td><span class="label label-danger">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div></div></td>';
                                    break;
                                case '3':
                                    html_data += '<td><span class="label label-info">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div></div></td>';
                                    break;
                                case '4':
                                    html_data += '<td><span class="label label-primary">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div></div></td>';
                                    break;
                                case '5':
                                    html_data += '<td><span class="label label-warning">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div></div></td>';
                                    break;
                                case '6':
                                    html_data += '<td><span class="label label-success">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></td>';
                                    break;
                                case '7':
                                    html_data += '<td><span class="label label-default text-default">'+response[i].st_nombre+'</span></td>';
                                    break;
                                case '8':
                                    html_data += '<td><span class="label label-danger">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div></div></td>';
                                    break;
                                case '9':
                                    html_data += '<td><span class="label label-danger">'+response[i].st_nombre+'</span><div class="progress progress-sm"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div></div></td>';
                                    break;
                                default:
                                    break;
                            }


                            html_data += '<td align="center"><a href="#" id="or_estado" data-type="select" data-pk="'+response[i].id_orden+'" data-value="'+response[i].or_estado+'" data-title="Seleccione nuevo estado" class="editable editable-click"><span class="hidden">'+response[i].or_estado+'</span> Cambiar estado</a></td>';

                            html_data += '</tr>';
                         };

                         $('#tabla tbody').html(html_data);
                         $('#tabla').DataTable({
                            "displayLength": 50,
                            "order": [[0, 'desc']],
                            'columnDefs': [{
                                'targets': [2],
                                'orderable': false
                            },
                            {
                                'targets': [3],
                                'orderable': false
                            },
                            {
                                'targets': [7],
                                'orderable': false
                            }],
                            "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                            }
                         });
                      },
                     error: function(jqXHR, textStatus, errorThrown) {
                         toastr["error"]("No hay datos cargados")
                     }
                    });
                }
                
                
                
                function make_editable_select_estado(table_selector,column_selector,ajax_url,title) {
                    $(table_selector).editable({   
                        selector: column_selector,
                        url: ajax_url,
                        title: title,
                        type: "POST",
                        source: [
                            {value: '2', text: 'Esperando pago'},
                            {value: '3', text: 'Pago recibido'},
                            {value: '4', text: 'Lista para despachar'},
                            {value: '5', text: 'Despachada'},
                            {value: '6', text: 'Finalizada'},
                            {value: '7', text: 'Archivada'},
                            {value: '8', text: 'Cancelada'},
                            {value: '9', text: 'Rechazada'}
                        ],
                        showbuttons: false,
                        dataType: 'json',
                        success: function(response)  
                          {
                            toastr["success"]("datos actualizados");
                          }
                      });
                      $.fn.editable.defaults.mode = 'popup';
                    }
                
                getEmployee();
                
                make_editable_select_estado('#tabla tbody','a#or_estado','include/lista-ordenes.php?action=edit','Nuevo estado');

            });

        </script>


        <script type="text/javascript">
            $( document ).ready(function() {
            
                <?php 
                if (isset($result)) {
                    switch ($result) {
                        case 'agregado': 
                            echo 'toastr["success"]("Los datos fueron cargados correctamentes")';
                            break;
                        case 'eliminado':
                            echo 'toastr["success"]("Los datos fueron eliminados")';
                            break;
                        case 'actualizado':
                            echo 'toastr["success"]("Los datos fueron actualizados")';
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