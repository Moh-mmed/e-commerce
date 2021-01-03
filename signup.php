<?php
session_start();
if (isset($_SESSION['user'])) { // check if the session is started or not 
    header("location: index.php");
    exit();
}
$pageTitle = 'Sign Up';
require_once('init.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h1 class=' mt-5 mb-5 text-center headers '>Signing Up</h1>";
    // Save Received Data From The Form In Variables
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $pass = sha1($password);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fullName = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    // Validate The Form Before Sending It To Database
    // Creating array of errors
    $formErrors = [];
    $characters = ['[', ']', '(', ')', '{', '}', '=', '+', '*', '/', '\\', '|'];
    if (empty($username)) {
        $formErrors[] = 'Username Can Not Be Empty';
    }
    if (strlen($username) < 4 || strlen($username) > 15) {
        $formErrors[] = "Username Must Be Greater Than 4 And Less Than 15 Characters";
    }

    foreach (str_split($username, 1) as $char) {
        if (in_array($char, $characters)) {
            $formErrors[] = "Username Shouldn't Include <i>'$@/
                                \+{}()[]'</i>";
        }
    }

    foreach (str_split($password, 1) as $char) {
        global $upp;
        global $int;
        if (ctype_upper($char)) {
            $upp = 1;
        }
        if (is_numeric($char)) {
            $int = 1;
        };
    }
    if (!($upp === 1 && $int === 1)) {
        $formErrors[] = "Password Should Contain At Least One Uppercase Letter And One Number"; //"Password Should Contain An Uppercase Letter";
    }
    if (strlen($password) < 6) {
        $formErrors[] = 'Password Must Be Greater Than 6 Characters';
    }
    if (empty($password)) {
        $formErrors[] = 'Password Can Not Be Empty';
    }

    if (empty($email)) {
        $formErrors[] = 'Email Can Not Be Empty ';
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== true) {
        $formErrors[] = 'Email is not valid';
    }
    if (strpos($email, '@') === 'false' || empty($email)) {
        $formErrors[] = 'example: username@gmail.com';
    }
    if (empty($fullName)) {
        $formErrors[] = 'Full Name Can Not Be Empty ';
    }
    if (strlen($fullName) < 4 || strlen($username) > 15) {
        $formErrors[] = "Fullname Must Be Greater Than 4 And Less Than 15 Characters";
    }
    foreach (str_split($fullName, 1) as $char) {
        if (in_array($char, $characters)) {
            $formErrors[] = " Full Name  include <i>'$@/
                                \+{}()[]'</i> ";
        }
    }
    if ($fullName === $username) {
        $formErrors[] = "Full Name Shouldn't Include <i>'$@/
                                \+{}()[]'</i>";
    }
    if (!empty($formErrors)) {
        $unFormErrors = array_unique($formErrors);
        foreach ($unFormErrors as $err) {
            echo "<div class='container'><div class='alert alert-danger' >" . $err . "</div></div>";
        }
    }
    if (empty($formErrors)) {
        // Checks If The Item Exists
        $count = checkForItem('Username', 'users', $username);
        if ($count == 1) {
            $message = "<div class='container py-2'><div class='alert alert-danger' >" . $username . " Is Used Before</div></div>";
            redirect($message, 'back', 2); // redirect function defined in functions.php
        } else {
            //Update Database
            // Prepare A Query To Update Data In Database
            $STM = $db->prepare("INSERT INTO users (Username,Password,Email,FullName,Date) VALUES (?,?,?,?,now())");
            $STM->execute([$username, $pass, $email, $fullName]);
            //Echo success Message
            $message =  '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Inserted </div></div>';
            redirect($message, 'login.php', 1); // redirect function defined in functions.php
        };
    }
    $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is a mistake</div></div>";
    redirect($message, 'back', 3); // redirect function defined in functions.php
    exit();
}
// else {
//     $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
//     redirect($message, 'signup.php', 2); // redirect function defined in functions.php
//     exit();
// }
?>
<div class="container signup-page">
    <h1 class=" mt-4 mb-3 text-center ">Sign Up</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="signup" class="signup rounded pt-4 pb-2 px-3" method="POST">
        <!-- Start Username Field -->
        <div class="form-group ">
            <label for="username" class="form-label ">Username</label>
            <input pattern=".{4,15}" title="Should be more than 4 and less than 15 characters" type="text" id="username" class="form-control username" name="username" autocomplete="off" required>
            <small><span class="length">- Should be more than 4 and less than 15 characters</span></small>
        </div>
        <div class="form-group ">
            <label for="password" class="form-label">Password</label>
            <input minlength="6" type="password" class="form-control password" name="password" id="password" autocomplete="new-password" required>
            <small><span class='length'>- Should be more than 6 characters</span></small>
            <i class="far fa-eye" id="fa-eye"></i>
        </div>
        <!-- Start Email Field -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control email" name="email" required>
        </div>
        <!-- End Email Field -->
        <!-- Start Full Name Field -->
        <div class="form-group">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" id="fullname" class="form-control fullname" name="fullname" autocomplete="off" required>
            <small><span class="same">- Should not be same USERNAME</span></small>
        </div>
        <!-- Start Third Raw -->
        <div class="form-group">
            <input type="submit" class=" btn btn-primary  btn-block shadow-lg rounded" value="Sign Up">
        </div>
        <!-- End Third Raw -->
        <div class="gotologin ">Have an account?<a href="login.php"><span> Log In</span></a> </div>
    </form>
</div>
<script src="<?php echo $js ?>signup.js"></script>

<?php require_once('includes/templates/footer.php');
