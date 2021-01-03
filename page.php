<?php
session_start();
/*
   To make Categories contain all =>[ Manage | Edit | Update | Add | Insert | Delete | Status ]

   using GET method to decide which page should the browser open, but is not exactly a seperate page, instead is a content in this page. so according to the 'make' the user will have a specific content  
*/

// This Is the structure which is used ini every page
// if entred 'make' value is valid, make value will be set else 'make' value will be set to manage which is the pranciple page
$make = isset($_GET["make"]) ? $_GET["make"] : 'manage';

// according to 'make' value do
if ($make === 'manage') {
   require_once('init.php');
} elseif ($make === 'update') {
} elseif ($make === 'delete') {
} else {
   echo "Error!";
}
