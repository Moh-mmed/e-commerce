<!-- This file is important when it comes to changing directory name so you do not need to go to all includes and chage their links -->
<?php
//database connecting
require_once('connect.php');

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

// include navbar file in all pages except the ones with noNavbar variable
if (!isset($noNavbar)) {
    require_once($tmpl . "navbar.php");
};
