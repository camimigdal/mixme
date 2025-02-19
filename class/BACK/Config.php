<?php
session_start();

// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', 'root');
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'bd_mixme');

define('DB_USERNAME', 'zuaibkjh_webmaster');
define('DB_PASSWORD', 'j8)}H~7xqJ]y');
define('DB_HOST', 'localhost');
define('DB_NAME', 'zuaibkjh_website');

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'class/Config.php'), '', $thisFile);
$srvRoot  = str_replace('class/Config.php', '', $thisFile);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);
//define('HTTP_SERVER', 'http://localhost:8888/mixme/web/'); //DIRECTORIO RAIZ DEL SITIO LOCAL  
define('HTTP_SERVER', 'https://mixme.com.ar/'); //DIRECTORIO RAIZ DEL SITIO LOCAL  

?>