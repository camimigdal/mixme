<?php
$menuPreguntas=true;

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

$preguntas=new Preguntas;


if (isset($_GET['status'])) {
    $status=filter_input(INPUT_GET,'status', FILTER_SANITIZE_SPECIAL_CHARS);
} 

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
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
                    <h1>Preguntas</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Preguntas</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <a href="preguntas.php?status=pendiente">
                            <div class="panel panel-blue panel-compras">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Para contestar</h2>
                                </div>
                                <div class="panel-body">
                                    <?php $preguntas->countStatus("pendiente"); ?> preguntas
                                </div>
                            </div>    
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="preguntas.php?status=contestada">
                            <div class="panel panel-green panel-compras">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Contestadas</h2>
                                </div>
                                <div class="panel-body">
                                <?php $preguntas->countStatus("contestada"); ?> preguntas
                                </div>
                            </div>    
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="preguntas.php?status=rechazada">
                            <div class="panel panel-red panel-compras">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Rechazadas</h2>
                                </div>
                                <div class="panel-body">
                                <?php $preguntas->countStatus("rechazada"); ?> preguntas
                                </div>
                            </div>    
                            </a>
                        </div>
                    </div><!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <hr>
                                    
                                    <?php 
                                    if (isset($status)) {
                                        switch ($status) {
                                            case "pendiente":
                                                echo '<h2 class="m-b-lg text-info">Preguntas para contestar</h2>';
                                                break;
                                                case "contestada":
                                                    echo '<h2 class="m-b-lg text-success">Preguntas contestadas</h2>';
                                                    break;
                                                    case "rechazada":
                                                        echo '<h2 class="m-b-lg text-danger">Preguntas rechazadas</h2>';
                                                        break;
                                        }
                                    } else {
                                        echo '<h2 class="m-b-lg text-info">Preguntas para contestar</h2>';
                                    }
                                    ?>

                                    <div class="table-responsive project-stats">  
                                       <table id="tabla" class="display table" style="width: 100%; cellspacing: 0;">
                                           <thead>
                                               <tr>
                                                   <th>Producto</th>
                                                   <th>Fecha</th>
                                                   <th>De:</th>
                                                   <th>Pregunta/Respuesta</th>
                                                   <th>Estado</th>
                                                   <th></th>
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
                      url: 'include/lista-preguntas.php'+'<?php 
                            if (isset($_GET['status'])) {
                                echo "?status=".$_GET['status'];
                            }
                            ?>',
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {
                            html_data += '<tr><td><a href="https://www.luzdesing.com.ar/productos/vista-previa/'+response[i].pd_id+'" target="_blank"><img src="'+response[i].pd_thumbnail+'"></a></td>';
                            html_data += '<td>'+response[i].comment_date+'</td>';
                            html_data += '<td>'+response[i].comment_author+' ('+response[i].comment_author_email+')</td>';
                            html_data += '<td class="text-danger"><strong>P:</strong> '+response[i].comment_content+'<br><span class="text-success">'+response[i].respuesta+'</span></td>';

                            switch (response[i].comment_approved) {
                                case 'pendiente':
                                    html_data += '<td><a href="#" id="comment_approved" data-type="select" data-pk="'+response[i].comment_ID+'" data-value="'+response[i].comment_approved+'" data-title="Estado" class="editable editable-click label label-info"><span class="hidden">'+response[i].comment_approved+'</span> '+response[i].comment_approved+'</a></td>';
                                    break;
                                case 'contestada':
                                    html_data += '<td><a href="#" id="comment_approved" data-type="select" data-pk="'+response[i].comment_ID+'" data-value="'+response[i].comment_approved+'" data-title="Estado" class="editable editable-click label label-success"><span class="hidden">'+response[i].comment_approved+'</span> '+response[i].comment_approved+'</a></td>';
                                    break;
                                case 'rechazada':
                                    html_data += '<td><a href="#" id="comment_approved" data-type="select" data-pk="'+response[i].comment_ID+'" data-value="'+response[i].comment_approved+'" data-title="Estado" class="editable editable-click label label-danger"><span class="hidden">'+response[i].comment_approved+'</span> '+response[i].comment_approved+'</a></td>';
                                    break;
                                default:
                                    break;
                            }

                            html_data += '<td><a href="contestar-pregunta.php?id_preg='+response[i].comment_ID+'" class="btn btn-success btn-sm"><i class="fa fa-mail-reply"></i> Responder</a></td></tr>';
                         };

                         $('#tabla tbody').html(html_data);
                         $('#tabla').DataTable({
                            "displayLength": 50,
                            "order": [[1, 'desc']],
                            'columnDefs': [{
                                'targets': [0],
                                'orderable': false
                            },
                            {
                                'targets': [5],
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
                            {value: 'pendiente', text: 'pendiente'},
                            {value: 'rechazada', text: 'rechazada'}
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
                
                make_editable_select_estado('#tabla tbody','a#comment_approved','include/lista-preguntas.php?action=edit','Estado');

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