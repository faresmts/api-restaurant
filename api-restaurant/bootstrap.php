<?php

ini_set('display_errors', 1);
ini_set('display_status_errors', 1);
error_log(E_ERROR); 

define('HOST', 'localhost');
define('DB', 'alfredinho');
define('USER', 'root');
define('PASSWORD', '=password');

define('DS', DIRECTORY_SEPARATOR);
define('DIR_APP', __DIR__ );
define('DIR_PROJECT', 'alfredinho');

if(file_exists('autoload.php')){
    include('autoload.php');
}else{
    echo 'error to include autoload.php';exit;
}