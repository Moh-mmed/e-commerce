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
<div class="top-svg">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#007BFF" fill-opacity="1" d="M0,96L60,80C120,64,240,32,360,48C480,64,600,128,720,144C840,160,960,128,1080,144C1200,160,1320,224,1380,256L1440,288L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path>
    </svg>
</div>
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
<div class="bottom-svg">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#007BFF" fill-opacity="1" d="M0,256L60,261.3C120,267,240,277,360,266.7C480,256,600,224,720,224C840,224,960,256,1080,250.7C1200,245,1320,203,1380,181.3L1440,160L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
    </svg>
</div>
<?php require_once("includes/templates/footer.php"); ?>