<?php
session_start();
$pageTitle = "LogIn";
if (isset($_SESSION['user'])) { // check if the session is started or not 
    header("location: index.php");
    exit();
}
require_once('init.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    // check for the user if is registred before
    $STM = $db->prepare("SELECT Username,UserID, Password FROM users WHERE Username = ? AND Password =? LIMIT 1");
    $STM->execute([$username, $password]);
    $data = $STM->fetch();
    $rows = $STM->rowCount();
    if ($rows > 0) {
        $_SESSION['user'] = $username;
        $_SESSION['userid'] = $data['UserID'];
        header("location: index.php");
        exit();
    } else {
        $mess = "Wrong Username or password";
        redirect($mess, 'login.php', 1);
    }
}
?>
<div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,192L34.3,186.7C68.6,181,137,171,206,154.7C274.3,139,343,117,411,133.3C480,149,549,203,617,213.3C685.7,224,754,192,823,181.3C891.4,171,960,181,1029,181.3C1097.1,181,1166,171,1234,149.3C1302.9,128,1371,96,1406,80L1440,64L1440,0L1405.7,0C1371.4,0,1303,0,1234,0C1165.7,0,1097,0,1029,0C960,0,891,0,823,0C754.3,0,686,0,617,0C548.6,0,480,0,411,0C342.9,0,274,0,206,0C137.1,0,69,0,34,0L0,0Z"></path>
    </svg>
</div>
<div class="container login-page">
    <h1 class=" text-center ">Login</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="login" class="login rounded pt-4 pb-2 px-3" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control rounded " aria-describedby="helpId" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control rounded " name="password" id="password" autocomplete="new-password" require>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary btn-lg btn-block shadow-lg rounded custom-style" value="Login">
        </div>
        <div class="gotosignup ">Do not have an account?<a href="signup.php"><span> Sign Up</span></a> </div>
    </form>
</div>
<div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,192L34.3,186.7C68.6,181,137,171,206,154.7C274.3,139,343,117,411,133.3C480,149,549,203,617,213.3C685.7,224,754,192,823,181.3C891.4,171,960,181,1029,181.3C1097.1,181,1166,171,1234,149.3C1302.9,128,1371,96,1406,80L1440,64L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
    </svg>
</div>
<?php

?>
<?php require_once('includes/templates/footer.php');
