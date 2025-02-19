<?php
/*
* Copyright (c) 2008 http://www.webmotionuk.com / http://www.webmotionuk.co.uk
* "PHP & Jquery image upload & crop"
* Date: 2008-11-21
* Ver 1.2
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
* STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
* THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
*/
error_reporting (E_ALL ^ E_NOTICE);
require_once('libreria/config.php');
require_once('clases/class_admin.php');

//only assign a new timestamp if the session variable is empty
if (!isset($_SESSION['nombre_foto']) || strlen($_SESSION['nombre_foto'])==0){
    $_SESSION['nombre_foto'] = $_GET['nom_fot']; //assign the timestamp to the session variable
    $_SESSION['user_file_ext']= "";
}
if (!isset($_SESSION['ordenImg']) || strlen($_SESSION['ordenImg'])==0){
    $_SESSION['ordenImg'] = $_GET['orden']; //assign the timestamp to the session variable
}
#########################################################################################################
# CONSTANTS                                               #
# You can alter the options below                                   #
#########################################################################################################
$upload_dir = "../img/upload";        // The directory for the images to be saved in
$upload_path = $upload_dir."/";       // The path to where the image will be saved
$upload_path_ficha = "../img/productos-mixer/grandes/";
$upload_path_chica = "../img/productos-mixer/";       
$large_image_prefix = "original_";      // The prefix name to large image
$thumb_image_prefix = "";     // The prefix name to the thumb image
$large_image_name = $large_image_prefix.$_SESSION['nombre_foto'];     // New name of the large image (append the timestamp to the filename)
$thumb_image_name = $_SESSION['nombre_foto'];     // New name of the thumbnail image (append the timestamp to the filename)
$max_file = "3";              // Maximum file size in MB
$max_width = "1200";              // Max width allowed for the large image
$thumb_width = "1000";            // Width of thumbnail image
$thumb_height = "1000";           // Height of thumbnail image
// Only one of these image types should be allowed for upload
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$image_ext = "";  // initialise variable, do not change this.
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}


##########################################################################################################
# IMAGE FUNCTIONS                                            #
# You do not need to alter these functions                                 #
##########################################################################################################
function resizeFoto($im,$im_chica,$ancho_chica,$alto_chica) {
  
  list($imagewidth, $imageheight, $imageType) = getimagesize($im);
  $imageType = image_type_to_mime_type($imageType);
  
  
  switch($imageType) {
    case "image/gif":
      $source=imagecreatefromgif($im); 
      break;
      case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
      $source=imagecreatefromjpeg($im); 
      break;
      case "image/png":
    case "image/x-png":
      $source=imagecreatefrompng($im); 
      break;
    }

  $tmp_chica=imagecreatetruecolor($ancho_chica,$alto_chica);  
  imagesavealpha($tmp_chica, true);
  $transparent = imagecolorallocatealpha( $tmp_chica, 0, 0, 0, 127 ); 
  imagefill($tmp_chica, 0, 0, $transparent);
  
  imagecopyresampled($tmp_chica,$source,0,0,0,0,$ancho_chica,$alto_chica,$imagewidth,$imageheight);
  
  switch($imageType) {
    case "image/gif":
        imagegif($tmp_chica,$im_chica); 
      break;
        case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
        imagejpeg($tmp_chica,$im_chica,90); 
      break;
    case "image/png":
    case "image/x-png":
      imagepng($tmp_chica,$im_chica);  
      break;
    }
  
  chmod($im_chica, 0777);
  
}

function resizeImage($image,$width,$height,$scale) {
  list($imagewidth, $imageheight, $imageType) = getimagesize($image);
  $imageType = image_type_to_mime_type($imageType);
  $newImageWidth = ceil($width * $scale);
  $newImageHeight = ceil($height * $scale);
  $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
  imagesavealpha($newImage, true);
  $transparent = imagecolorallocatealpha( $newImage, 0, 0, 0, 127 ); 
  imagefill($newImage, 0, 0, $transparent);
  
  switch($imageType) {
    case "image/gif":
      $source=imagecreatefromgif($image); 
      break;
      case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
      $source=imagecreatefromjpeg($image); 
      break;
      case "image/png":
    case "image/x-png":
      $source=imagecreatefrompng($image); 
      break;
    }
  imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
  
  switch($imageType) {
    case "image/gif":
        imagegif($newImage,$image); 
      break;
        case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
        imagejpeg($newImage,$image,90); 
      break;
    case "image/png":
    case "image/x-png":
      imagepng($newImage,$image);  
      break;
    }
  
  chmod($image, 0777);
  return $image;
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
  list($imagewidth, $imageheight, $imageType) = getimagesize($image);
  $imageType = image_type_to_mime_type($imageType);
  
  $newImageWidth = ceil($width * $scale);
  $newImageHeight = ceil($height * $scale);
  $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
  imagesavealpha($newImage, true);
  $transparent = imagecolorallocatealpha( $newImage, 0, 0, 0, 127 ); 
  imagefill($newImage, 0, 0, $transparent);
  switch($imageType) {
    case "image/gif":
      $source=imagecreatefromgif($image); 
      break;
      case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
      $source=imagecreatefromjpeg($image); 
      break;
      case "image/png":
    case "image/x-png":
      $source=imagecreatefrompng($image); 
      break;
    }
  imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
  switch($imageType) {
    case "image/gif":
        imagegif($newImage,$thumb_image_name); 
      break;
        case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
        imagejpeg($newImage,$thumb_image_name,90); 
      break;
    case "image/png":
    case "image/x-png":
      imagepng($newImage,$thumb_image_name);  
      break;
    }
  chmod($thumb_image_name, 0777);
  return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
  $size = getimagesize($image);
  $height = $size[1];
  return $height;
}
//You do not need to alter these functions
function getWidth($image) {
  $size = getimagesize($image);
  $width = $size[0];
  return $width;
}

//Image Locations
$large_image_location = $upload_path.$large_image_name.$_SESSION['user_file_ext'];
$thumb_image_location = $upload_path_ficha.$thumb_image_name.$_SESSION['user_file_ext'];
$chica_image_location = $upload_path_chica.$thumb_image_name.$_SESSION['user_file_ext'];
$im_nombre=$_SESSION['nombre_foto'].$_SESSION['user_file_ext'];

//Create the upload directory with the right permissions if it doesn't exist
if(!is_dir($upload_dir)){
  mkdir($upload_dir, 0777);
  chmod($upload_dir, 0777);
}

//Check to see if any images with the same name already exist
if (file_exists($large_image_location)){
  if(file_exists($thumb_image_location)){
    $thumb_photo_exists = "<img src=\"".$upload_path_ficha.$thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Thumbnail Image\"/>";
  }else{
    $thumb_photo_exists = "";
  }
    $large_photo_exists = "<img src=\"".$upload_path.$large_image_name.$_SESSION['user_file_ext']."\" alt=\"Large Image\"/>";
} else {
    $large_photo_exists = "";
  $thumb_photo_exists = "";
}

if (isset($_POST["upload"])) { 
  //Get the file information
  $userfile_name = $_FILES['image']['name'];
  $userfile_tmp = $_FILES['image']['tmp_name'];
  $userfile_size = $_FILES['image']['size'];
  $userfile_type = $_FILES['image']['type'];
  $filename = basename($_FILES['image']['name']);
  $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
  
  //Only process if the file is a JPG, PNG or GIF and below the allowed limit
  if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
    
    foreach ($allowed_image_types as $mime_type => $ext) {
      //loop through the specified image types and if they match the extension then break out
      //everything is ok so go and check file size
      if($file_ext==$ext && $userfile_type==$mime_type){
        $error = "";
        break;
      }else{
        $error = "Solo imagenes <strong>".$image_ext."</strong> aceptadas para subir<br />";
      }
    }
    //check if the file size is above the allowed limit
    if ($userfile_size > ($max_file*1048576)) {
      $error.= "La imagen debe ser menor a ".$max_file."MB";
    }
    
  }else{
    $error= "Seleccione una imagen para subir";
  }
  //Everything is ok, so we can upload the image.
  if (strlen($error)==0){
    
    if (isset($_FILES['image']['name'])){
      //this file could now has an unknown file extension (we hope it's one of the ones set above!)     
      $large_image_location = $upload_path.$large_image_name.".".$file_ext;
      $thumb_image_location = $upload_path_ficha.$thumb_image_name.".".$file_ext;
      $chica_image_location = $upload_path_chica.$thumb_image_name.".".$file_ext;
      $im_nombre=$_SESSION['nombre_foto'].".".$file_ext;

      
      //put the file ext in the session so we know what file to look for once its uploaded
      $_SESSION['user_file_ext']=".".$file_ext;
      
      move_uploaded_file($userfile_tmp, $large_image_location);
      chmod($large_image_location, 0777);
      
      $width = getWidth($large_image_location);
      $height = getHeight($large_image_location);
      //Scale the image if it is greater than the width set above
      if ($width > $max_width){
        $scale = $max_width/$width;
        $uploaded = resizeImage($large_image_location,$width,$height,$scale);
      }else{
        $scale = 1;
        $uploaded = resizeImage($large_image_location,$width,$height,$scale);
      }
      //Delete the thumbnail file so the user can create a new one
      if (file_exists($thumb_image_location)) {
        unlink($thumb_image_location);
      }
    }
    //Refresh the page to show the new uploaded image
    header("location:".$_SERVER["PHP_SELF"]);
    exit();
  }
}

if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
  //Get the new coordinates to crop the image.
  $x1 = $_POST["x1"];
  $y1 = $_POST["y1"];
  $x2 = $_POST["x2"];
  $y2 = $_POST["y2"];
  $w = $_POST["w"];
  $h = $_POST["h"];
  //Scale the image to the thumb_width set above
  $scale = $thumb_width/$w;
  $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
  
  resizeFoto($thumb_image_location,$chica_image_location,500,500);
  
  $pd_id=$_SESSION['id_producto'];
  $orden_img=$_SESSION['ordenImg'];

  $Obj=new Mixer;
  
  $Obj->gestionImg($im_nombre,$pd_id,$orden_img);
  
  unset($_SESSION['nombre_foto']);
  unset($_SESSION['id_producto']);
  unset($_SESSION['ordenImg']);
  
  header("Location: fotos-productos-mixer.php?id=".$pd_id);
  exit();
}


if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
//get the file locations 
  $large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
  $thumb_image_location = $upload_path_ficha.$thumb_image_prefix.$_GET['t'];
  $chica_image_location = $upload_path_chica.$thumb_image_prefix.$_GET['t'];

  if (file_exists($large_image_location)) {
    unlink($large_image_location);
  }
  if (file_exists($thumb_image_location)) {
    unlink($thumb_image_location);
  }
  if (file_exists($chica_image_location)) {
    unlink($chica_image_location);
  }
  header("location:".$_SERVER["PHP_SELF"]);
  exit(); 
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
        <link rel="stylesheet" type="text/css" href="assets/css/imgareaselect-animated.css" />
        
        <!-- Theme Styles -->
        <link href="assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/themes/white.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>

        <script src="assets/plugins/jquery/jquery-2.1.3.min.js"></script>
        <script src="assets/js/jquery.imgareaselect.pack.js"></script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="page-header-fixed small-sidebar">
        

        <div class="overlay"></div>
      
        <main class="page-content content-wrap">

            <?php require_once('include/nav-top.php'); ?>
            <?php require_once('include/nav.php'); ?>

            <div class="page-inner">
                <div class="page-title">
                    <h1>Fotos de ingredientes mixer</h1>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Fotos de ingredientes mixer</li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                  

                                    <?php
                                    //Display error message if there are any

                                    if(strlen($error)>0){
                                        echo '<div class="alert alert-danger" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <ul><li><strong>Error!</strong></li><li><i class="fa fa-exclamation-triangle"></i> '.$error.'</li></ul>
                                        </div>';
                                                        
                                    }
                      
                                    if(strlen($large_photo_exists)>0 && strlen($thumb_photo_exists)>0){

                                        echo $large_photo_exists."&nbsp;".$thumb_photo_exists;
                                        echo "<p><a href=\"".$_SERVER["PHP_SELF"]."?a=delete&t=".$_SESSION['nombre_foto'].$_SESSION['user_file_ext']."\">Delete images</a></p>";
                                        echo "<p><a href=\"".$_SERVER["PHP_SELF"]."\">Upload another</a></p>";
                                        //Clear the time stamp session and user file extension
                                        $_SESSION['nombre_foto']= "";
                                        $_SESSION['user_file_ext']= "";

                                    }else{
                                            if(strlen($large_photo_exists)>0){?>
                                            <h3>Crear imagen</h3>
                                            <p>- Primero haga una selección sobre la imagen cargada, luego puede modificar el tamaño y mover la selección a su gusto para crear la foto final.<br>
                                            - La previsualización de la foto final se muestra debajo.<br>
                                            - Una vez conforme con el recorte haga click en el boton rojo a continuación.</p>
                                            
                                            <div class="clearfix">
                                                <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                                                    <input type="hidden" name="x1" value="" id="x1" />
                                                    <input type="hidden" name="y1" value="" id="y1" />
                                                    <input type="hidden" name="x2" value="" id="x2" />
                                                    <input type="hidden" name="y2" value="" id="y2" />
                                                    <input type="hidden" name="w" value="" id="w" />
                                                    <input type="hidden" name="h" value="" id="h" />
                                                        <hr>
                                                        <input type="submit" name="upload_thumbnail" value="Terminar recorte y guardar foto" id="save_thumb" class="btn btn-danger bt-lg" />
                                                        <hr>
                                                </form>
                                            </div>
                                                
                                                             
                                            <div class="clearfix">
                                                <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" id="thumbnail" alt="Create Thumbnail" />
                                                
                                                <div style="border:1px #000 solid; position:relative; overflow:hidden; width:250px; height:250px; margin-top:15px;">
                                                    <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="position: relative;" alt="Thumbnail Preview" />
                                                </div>
                                            </div>
                                            <hr />
                                        
                                        <?php } ?>

                                        <div class="col-xs-7">
                                        <form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="form-horizontal">
                                                
                                                <div class="form-group">
                                                    <label for="image" class="col-sm-4 control-label">Foto original para cortar:</label>
                                                    <div class="col-sm-8">
                                                        <input type="file" name="image" size="30" /> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-4">
                                                        <input type="submit" name="upload" value="CARGAR" class="btn btn-success"/> 
                                                    </div>
                                                </div>
                                                
                                                
                                        </form>
                                        </div>
                                    <?php } ?>





                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->
                </div><!-- Main Wrapper -->

                <?php require_once('include/footer.php'); ?>

            </div><!-- Page Inner -->
        </main><!-- Page Content -->


        
	

        <!-- Javascripts -->
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
        

        <?php
        //Only display the javacript if an image has been uploaded
        if(strlen($large_photo_exists)>0){
          $current_large_image_width = getWidth($large_image_location);
          $current_large_image_height = getHeight($large_image_location);?>
        <script type="text/javascript">
        function preview(img, selection) { 
          var scaleX = 250 / selection.width; 
          var scaleY = 250 / selection.height; 
          
          $('#thumbnail + div > img').css({ 
            width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px', 
            height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
          });
          $('#x1').val(selection.x1);
          $('#y1').val(selection.y1);
          $('#x2').val(selection.x2);
          $('#y2').val(selection.y2);
          $('#w').val(selection.width);
          $('#h').val(selection.height);
        } 

        $(document).ready(function () { 
          $('#save_thumb').click(function() {
            var x1 = $('#x1').val();
            var y1 = $('#y1').val();
            var x2 = $('#x2').val();
            var y2 = $('#y2').val();
            var w = $('#w').val();
            var h = $('#h').val();
            if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
              alert("Debe hacer una selección sobre la imagen");
              return false;
            }else{
              return true;
            }
          });
        }); 

        $(window).load(function () { 
          $('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
        });

        </script>
        <?php }?>

    </body>
</html>