<?php
//strat a session to keep the user logged in 
session_start();
$noNavbar = '';
$pageTitle = "Sing In";
if (isset($_SESSION['Username'])) {
    header('location: dashboard.php');
    exit();
}
require_once('init.php'); // this file will includes everything is important for this page

//checking if the user is comming from HTTP request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $hashedPass = sha1($password); // encode the password

    //check if the user exist in the database
    // we made connection in init.php file

    // prepare SQL query 
    $STM = $db->prepare('SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password=? AND GroupID=1  LIMIT 1');
    $STM->execute([$username, $hashedPass]);
    $row = $STM->fetch();
    $count = $STM->rowCount(); // count results
    if ($count > 0) { // if result is greater than one that means there is a match
        $_SESSION['Username'] = $username; // Register Session username
        $_SESSION['UserID'] = $row['UserID']; // Register Session username
        header('location: dashboard.php');
        exit();
    }
}


?>

<div class="container login">

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="contact-form" class="contact-form rounded pt-4 pb-2 px-3" method="POST">
        <h1 class=" mt-0 mb-3 text-center ">Login</h1>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control rounded " aria-describedby="helpId" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control rounded " name="password" id="password" autocomplete="new-password">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary btn-lg btn-block shadow-lg rounded custom-style" value="Login">
        </div>
    </form>

</div>

<?php require_once("includes/templates/footer.php"); ?>