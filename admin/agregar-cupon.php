<?php
$menuCupon=true;

require_once('clases/class_admin.php');
require_once('libreria/config.php');

$Obj=new Cupones;
$ObjConfig=new Configuracion;


if (!empty($_POST['submit'])) {
    $result=$Obj->Agregar();
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
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
        <link href="assets/plugins/summernote-master/summernote.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
        
        <!-- Theme Styles -->
        <link href="assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/themes/white.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>

        <script src="assets/plugins/jquery/jquery-2.1.3.min.js"></script>
        <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="edit/ckeditor.js"></script>
        
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
                    <h1>Agregar código descuento</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Agregar código descuento</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">

                                  <form method="post" enctype="multipart/form-data" class="form-horizontal" >
                                        <div class="form-group">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button type="submit" name="submit" value="agregar" class="btn btn-success btn-lg">PUBLICAR</button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="codigo" class="col-sm-2 control-label">Código:</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="codigo" value="<?php if(isset($_POST['codigo'])) echo $_POST['codigo']; ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <textarea name="descripcion" ><?php if(isset($_POST['descripcion'])) echo $_POST['descripcion']; ?></textarea>
                                                <script>
															CKEDITOR.replace( 'descripcion', {
																/*
																 * Style sheet for the contents
																 */
																contentsCss: 'assets/outputxhtml/outputxhtml.css',
																
																removePlugins: 'bidi,font,forms,flash,horizontalrule,iframe,justify,table,tabletools,smiley',
																removeButtons: 'Anchor,Image,Blockquote,Link,Unlink,NumberedList,BulletedList,Underline,Strike,Outdent,Indent,About',
																format_tags: 'p',
																/*
																 * Core styles.
																 */
																coreStyles_bold: {
																	element: 'strong'
																},
																coreStyles_italic: {
																	element: 'em'
                                                                },
                                                                enterMode: CKEDITOR.ENTER_BR,
																/*
																 * Styles combo.
																 */
																stylesSet: [
																	{ name: 'Negrita', element: 'strong' },
																	{ name: 'Italica', element: 'em' }
																],
                                                                height: 300
															});
														</script>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="descuento" class="col-sm-2 control-label">Descuento en %:</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">%</div>
                                                    <input type="text" class="form-control" name="descuento" value="<?php if(isset($_POST['descuento'])) echo $_POST['descuento']; ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="dates" class="col-sm-2 control-label">Válidez</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control date-picker" name="dates" value="<?php if(isset($_POST['dates'])) echo $_POST['dates']; ?>" required autocomplete="off">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="estado">Estado</label>
                                            <div class="col-sm-10">
                                                <select class="form-control m-b-sm" name="estado">
                                                    <option value="publicado">Publicado</option>
                                                    <option value="papelera">En papelera</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                                <button type="submit" name="submit" value="agregar" class="btn btn-success btn-lg">PUBLICAR</button>
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
        <script src="assets/plugins/jquery-mockjax-master/jquery.mockjax.js"></script>
        <script src="assets/plugins/moment/moment.js"></script>
        <script src="assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
        <script src="assets/plugins/x-editable/inputs-ext/address/address.js"></script>
        <script src="assets/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script src="assets/plugins/toastr/toastr.min.js"></script>
        <script src="assets/plugins/summernote-master/summernote.min.js"></script>
        <script src="assets/js/modern.js"></script>
        <script src="assets/js/pages/form-elements.js"></script>
        <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>


        <script type="text/javascript">
            $(function() {

              $('input[name="dates"]').daterangepicker({
                  autoUpdateInput: false,
                  locale: {
                      cancelLabel: 'Clear'
                  }
              });

              $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val('Desde ' + picker.startDate.format('DD/MM/YYYY') + ' Hasta ' + picker.endDate.format('DD/MM/YYYY'));
              });

              $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
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