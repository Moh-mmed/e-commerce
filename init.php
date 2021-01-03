<!-- This file is important when it comes to changing directory name so you do not need to go to all includes and chage their links -->
<?php
// Error reporting

ini_set('display_errors', 1);
error_reporting(E_ALL);

//database connecting
require_once('admin/connect.php');

// save $_session['user'] in variable
$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}


//Pathes
$tmpl = 'includes/templates/'; // templates path
$css = 'layout/style/'; // css path
$js = 'layout/js/'; // js path
$lang = 'includes/languages/'; // languages path
$functs = 'includes/functions/'; // functions path

// include important things

require_once($functs . "functions.php");
require_once($lang . 'english.php');
require_once($tmpl . "header.php");
