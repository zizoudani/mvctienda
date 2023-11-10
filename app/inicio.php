<?php
ini_set('display_errors', 1);

//Constantes iniciales
define('ROOT', DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . ROOT);
define('URL', '/var/www/proyecto12');
define('VIEWS', URL . APP . 'views' . ROOT);
define('ENCRIPTKEY', 'elperrodesanroque');


//Carga las clases iniciales
require_once 'libs/MySQLdb.php';
require_once 'libs/Controller.php';
require_once 'libs/Application.php';
require_once 'libs/Session.php';
require_once 'libs/AdminSession.php';
require_once 'libs/Validate.php';
<<<<<<< HEAD
=======



>>>>>>> b72df3f4451484118b2d016106b46addc118b8de
