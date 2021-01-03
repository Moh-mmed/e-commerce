<?php
//strat a session to keep the user logged in

// you can fine better way logout on PHP documentation 
session_start();
if (isset($_SESSION['Username'])) {
    session_unset();
    session_destroy();
    header('location: index.php');
    exit();
} else {
    header('location: index.php');
}
