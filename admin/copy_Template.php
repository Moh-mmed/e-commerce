<?php
ob_start();
//
//==================================================
//========          TEMPLATE PAGE       ============
//==================================================

//strat a session to keep the user logged in
session_start();

if (isset($_SESSION['Username'])) { // Check If Session Is Loged In
    $pageTitle = 'Members';
    require_once('init.php');
    //checkes for which 'make' the user want
    $make = isset($_GET["make"]) ? $_GET["make"] : 'manage';
    // according to 'make' value do
    if ($make === 'manage') { // Start Manage Page
        echo "manage";
    } elseif ($make === 'add') {
        echo "add";
    } elseif ($make === 'insert') {
        echo "INSERT";
    } elseif ($make === 'edit') { //Edit Page
        echo "edit";
    } elseif ($make === 'update') { // Update Action page
        echo "update";
    } elseif ($make === 'delete') {
    } else {
        $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
        redirect($message); // redirect function defined in functions.php
    };
    require_once($tmpl . "footer.php"); ?>
<?php } else { // else,the user will be directed to the index.php page 
    header('location: index.php');
    exit();
}
ob_end_flush();
?>