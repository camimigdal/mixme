<?php
$mixer=true;

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

$Obj=new Mixer; 
$ObjConfig=new Configuracion;


$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
    $id=filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
}

switch ($action) {
    case 'delete' :
      $result = $Obj->Borrar($id);
      break;
    case 'publicado' :
          $result=$Obj->Status($id,$action);
          break;
    case 'papelera' :
          $result=$Obj->Status($id,$action);
          break;
    case 'archivado' :
         $result=$Obj->Status($id,$action);
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
                    <h1>Ingredientes Mixer</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Ingredientes Mixer</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                          <a href="agregar-ingrediente.php" class="btn btn-success btn-addon m-b-sm btn-rounded btn-lg"><i class="fa fa-plus"></i> Agregar ingrediente</a>
                            <div class="panel panel-white">

                                <div class="panel-body">
                                    <div class="table-responsive project-stats">  
                                       <table id="tabla" class="display table" style="width: 100%; cellspacing: 0;">
                                           <thead>
                                               <tr>
                                                   <th></th>
                                                   <th>Título</th>
                                                   <th>Categoría</th>
                                                   <th>Estado</th>
                                                   <th></th>
                                                   <th></th>
                                               </tr>
                                           </thead>
                                           <tfoot>
                                               <tr>
                                                   <th></th>
                                                   <th>Título</th>
                                                   <th>Categoría</th>
                                                   <th>Estado</th>
                                                   <th></th>
                                                   <th></th>
                                               </tr>
                                           </tfoot>
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
                      url: "include/lista-prod-mixer.php",
                      dataType: "json",       
                      success: function(response)  
                      {
                        var html_data = '';
                        for (var i = 0; i < response.length; i++) {
                            html_data+= '<tr><td><img id="thumbnil" style="width:120px;" src="'+response[i].pd_img+'"/><br><a href="fotos-productos-mixer.php?id='+response[i].pd_id+'" class="btn btn-info" style="width:120px;"><i class="fa fa-file-image-o"></i> Foto</a></td>';

                            html_data += '<td>'+response[i].pd_titulo+'</td>';
                            html_data += '<td>'+response[i].ct_titulo+'</td>';
                            
                            if (response[i].status=='pausado') {
                                html_data += '<td><a href="#" id="status" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].status+'" data-title="Estado" class="editable editable-click btn btn-warning">Pausado</a></td>';
                            } else {
                                html_data += '<td><a href="#" id="status" data-type="select" data-pk="'+response[i].pd_id+'" data-value="'+response[i].status+'" data-title="Estado" class="editable editable-click btn btn-success">Publicado</a></td>';
                            }
                             
                             html_data += '<td><a href="editar-ingrediente.php?id='+response[i].pd_id+'"><i class="fa fa-edit fa-2x"></i></a></td>';
                             html_data += '<td><a href="ingredientes-mixer.php?action=delete&id='+response[i].pd_id+'"><i class="fa fa-trash-o fa-2x"></i></a></td></tr>';
                         };

                         $('#tabla tbody').html(html_data);
                         $('#tabla').DataTable({
                            "displayLength": 50,
                            "order": [[ 2, 'asc' ]],
                            'columnDefs': [{
                                'targets': [0],
                                'orderable': false
                            },
                            {
                                'targets': [4],
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
                            {value: 'publicado', text: 'Publicado'},
                            {value: 'pausado', text: 'Pausado'}
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
                
                make_editable_select_estado('#tabla tbody','a#status','include/lista-prod-mixer.php?action=edit','Estado');

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