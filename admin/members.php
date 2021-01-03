<?php
//
//==================================================
//========          MEMBERS PAGE       ============
//==================================================
ob_start();
/* Pages That Contain Information Or Have interaction with user, Can Be Normally Accessed Directly, 
/** However Those are for functionality like: Insertion | Update Can Not Be Accessed Directly,
/** 
*/
//strat a session to keep the user logged in
session_start();

if (isset($_SESSION['Username'])) { // Check If Session Is Loged In
    $pageTitle = 'Members';
    require_once('init.php');
    //checkes for which 'make' the user want
    $make = isset($_GET["make"]) ? $_GET["make"] : 'manage';
    // according to 'make' value do
    if ($make === 'manage') { // Start Manage Page
        $regStatusQuery = '';
        if (isset($_GET['page']) && $_GET['page'] == 'pending') {
            $regStatusQuery = 'AND RegStatus = 0'; // we add a condition for fetching data from database where RegStatus= (activated)
            $countRequests = countItems('UserID', 'users', 'WHERE RegStatus =0');
            if ($countRequests == 0) { ?>
                <div class="container empty">
                    <div class="pending">
                        <p class="shadow-lg">There is no request !</p>
                    </div>
                </div>
            <?php
                exit();
            }
        }

        $countMembers = countItems('UserID', 'users', 'WHERE GroupID !=1');

        if ($countMembers == 0) { ?>
            <div class="container empty">
                <div class="pending">
                    <p class="shadow-lg">There is no Member !</p>
                </div>
                <a href='members.php?make=add' class="btn btn-primary py-1 px-3 "><i class="fas fa-plus" aria-hidden="true"></i>New Member</a>
            </div>
        <?php
            exit();
        }
        // Fetch Data From Database
        $STM = $db->prepare("SELECT * FROM users WHERE GroupID != 1 $regStatusQuery ORDER BY UserID DESC");
        $STM->execute();
        $data = $STM->fetchAll(); // fetches all data as rows
        ?>
        <div class="container member-manage">
            <h1 class="text-center headers">Manage Members</h1>
            <div class="table-responsive">
                <table class="table table-dark table-md table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Register Date</th>
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fill In Table The Data
                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $row['UserID'] . "</th>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                    <a href='members.php?make=edit&userid=" . $row['UserID'] . "' class='btn btn-success btn-sm ml-1 '><i class='fas fa-user-edit'></i>Edit</a>
                                    <a href='members.php?make=delete&userid=" . $row['UserID'] . "' class='btn btn-danger btn-sm ml-1 confirm' id='confirm'><i class='far fa-trash-alt'></i>Delete</a>";
                            echo $row['RegStatus'] == 1 ? "<button class='btn btn-success btn-sm ml-1' id='pending'><i class='far fa-check-circle'></i>Activated</button>" : "<a href='members.php?make=activate&userid=" . $row['UserID'] . "' class='btn btn-info btn-sm ml-1' ><i class='far fa-clock'></i>Pending</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href='?make=add' class="btn btn-primary py-1 px-3 "><i class="fas fa-plus" aria-hidden="true"></i>New Member</a>
            </div>
        </div>
    <?php } elseif ($make === 'add') { ?>
        <!-- Start HTML Body For Add Page -->
        <div class=" container member">
            <h1 class=" my-4 text-center headers">Add New Member</h1>
            <form class="member-add" id="member-add" method="POST" action="?make=insert">
                <!-- Start Username Field -->
                <div class="form-group ">
                    <label for="username" class="form-label ">Username</label>
                    <input type="text" id="username" class="form-control form-control-lg username" name="username" autocomplete="off" required>
                </div>
                <div class="form-group ">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg password" name="password" id="password" autocomplete="new-password" required>
                    <i class="far fa-eye" id="fa-eye"></i>
                </div>
                <!-- Start Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control form-control-lg email" name="email" required>
                </div>
                <!-- End Email Field -->
                <!-- Start Full Name Field -->
                <div class="form-group">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" id="fullname" class="form-control form-control-lg fullname" name="fullname" autocomplete="off" required>
                </div>
                <!-- Start Third Raw -->
                <div class="form-group">
                    <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Add">
                </div>
                <!-- End Third Raw -->

            </form>
        </div>

        <script src="<?php echo $js ?>addform.js"></script>
        <!-- End HTML Body For Add Page -->
        <?php } elseif ($make === 'insert') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class=' mt-5 mb-5 text-center headers '>Added Member</h1>";
            // Save Received Data From The Form In Variables
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
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
                    $message = "<div class='container p-2'><div class='alert alert-danger' >" . $username . " Is Used Before</div></div>";
                    redirect($message, 'back', 2); // redirect function defined in functions.php
                } else {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("INSERT INTO users (Username,Password,Email,FullName,Date,RegStatus) VALUES (?,?,?,?,now(),1)");
                    $STM->execute([$username, $pass, $email, $fullName]);
                    //Echo success Message
                    $message =  '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Inserted </div></div>';
                    redirect($message, 'members.php', 1); // redirect function defined in functions.php
                };
            }
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is a mistake</div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
        }
    } elseif ($make === 'edit') { //Edit Page

        // We protect Our URL From Assigning any value to $_GET['userid'] but an integer
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 'Error!';
        // Make A query to fetch corresponding data from Database 
        $STM = $db->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1');
        $STM->execute([$userid]);
        $row = $STM->fetch(); // all fetched data are stored now in $row as an array
        $count = $STM->rowCount(); // count records in the result
        if ($count > 0) { // if result is greater than one that means there is a match
        ?>
            <!-- Start HTML Body For Edit Profile Page -->

            <div class="container member">
                <h1 class="text-center headers">Update Your Profile</h1>
                <form class="update-info" id="update-info" method="POST" action="?make=update">
                    <!-- Start Username Field -->
                    <div class="form-group ">
                        <label for="username" class="form-label ">Username</label>
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <input type="text" id="username" class="form-control form-control-lg" name="username" autocomplete="off" value="<?php echo $row['Username'] ?>" required>
                    </div>
                    <div class="form-group ">
                        <label for="newpassword" class="form-label">Password</label>
                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                        <input type="password" class="form-control form-control-lg" name="newpassword" autocomplete="new-password">
                    </div>
                    <!-- Start Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control form-control-lg" name="email" autocomplete="email" value="<?php echo $row['Email'] ?>" required>

                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" id="fullname" class="form-control form-control-lg" name="fullname" autocomplete="off" value="<?php echo $row['FullName'] ?>" required>

                    </div>

                    <!-- Start Third Raw -->

                    <div class="form-group">
                        <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Update">
                    </div>

                    <!-- End Third Raw -->

                </form>
            </div>

            <script src="<?php echo $js ?>editform.js"></script>
            <!-- End HTML Body For Edit Profile Page -->

    <?php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>You have made a mistake, there is no member corresponding to these data <br> Please try again</div></div>";
            redirect($message, "back", 2); // redirect function defined in functions.php
        }
    } elseif ($make === 'update') { // Update Action page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class='text-center headers '>Update Member</h1>";
            // Save Received Data From The Form In Variables
            $userid = $_POST['userid'];
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $fullName = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);

            // Check For A New Password
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

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
            if (empty($email)) {
                $formErrors[] = 'Email Can Not Be Empty ';
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
                $formErrors[] = "Full Name Shouldn't be same as Username";
            }
            if (!empty($formErrors)) {
                $unFormErrors = array_unique($formErrors);
                foreach ($unFormErrors as $err) {
                    echo "<div class='container p-2'><div class='alert alert-danger' >" . $err . "</div></div>";
                }
            }
            if (empty($formErrors)) {
                //check if new Username exists
                $STM3 = $db->prepare("SELECT Username FROM users WHERE Username =? AND UserID !=?");
                $STM3->execute([$username, $userid]);
                $count = $STM3->rowCount();
                if ($count > 0) {
                    $message = '<div class="container p-3"><div class ="alert alert-danger">Sorry This Username is used before</div></div>';
                    redirect($message, "back", 2); // redirect function defined in functions.php 
                } else {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("UPDATE users SET Username = ?,Password=?, Email= ?, FullName=? WHERE UserID=? LIMIT 1");
                    $STM->execute([$username, $pass, $email, $fullName, $userid]);
                    //Echo success Message
                    $message = '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Updated </div></div>';
                    redirect($message, "back", 1); // redirect function defined in functions.php
                }
            }
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Please Try Again</div></div>";
            redirect($message, "back", 2); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
            redirect($message, "members.php", 2); // redirect function defined in functions.php
        }
    } elseif ($make === 'delete') { //delete page
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 'Error!';
        // check if this member exists
        $count = checkForItem('UserID', 'users', $userid);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Delete Member</h1>";
            $STM = $db->prepare('DELETE FROM users WHERE UserID = :USERID LIMIT 1');
            $STM->bindParam(":USERID", $userid);
            $STM->execute();
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Deleted </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is No Such Member</div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
        }
    } elseif ($make === 'activate') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 'Error!';
        // check if this member exists
        $count = checkForItem('UserID', 'users', $userid);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Activate Member</h1>";
            $STM = $db->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
            $STM->execute([$userid]);
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Activated </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is No Such Member</div></div>";
            redirect($message, 'memebers.php'); // redirect function defined in functions.php
        }
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