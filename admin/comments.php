<?php
//
//==================================================
//========          COMMENTS PAGE       ============
//==================================================
ob_start();
/* Pages That Contain Information Or Have interaction with user, Can Be Normally Accessed Directly, 
/** However Those are for functionality like: Insertion | Update Can Not Be Accessed Directly,
/** 
*/
//strat a session to keep the user logged in
session_start();

if (isset($_SESSION['Username'])) { // Check If Session Is Loged In
    $pageTitle = 'Comments';
    require_once('init.php');
    //checkes for which 'make' the user want
    $make = isset($_GET["make"]) ? $_GET["make"] : 'manage';
    // according to 'make' value do
    if ($make === 'manage') { // Start Manage Page
        // check if there is no category
        $countComments = countItems('Comment_ID', 'comments');

        if ($countComments == 0) { ?>
            <div class="container empty">
                <div class="pending">
                    <p class="shadow-lg">There is no Comment !</p>
                </div>
            </div>
        <?php
            exit();
        }
        // Fetch Data From Database
        $STM = $db->prepare("SELECT comments.*, users.Username, items.Name AS Item_Name 
                            FROM comments 
                            INNER JOIN users ON users.UserID = comments.User_ID 
                            INNER JOIN items ON items.Item_ID = comments.Item_ID
                            ORDER BY Comment_ID DESC");
        $STM->execute();
        $comments = $STM->fetchAll(); // fetches all data as rows
        ?>
        <div class="container manage-comments">
            <h1 class=" text-center headers">Manage Comments</h1>
            <div class="table-responsive">
                <table class="table table-dark table-md table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Added Date</th>
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fill In Table The Data
                        foreach ($comments as $comment) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $comment['Comment_ID'] . "</th>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['Item_Name'] . "</td>";
                            echo "<td>" . $comment['Username'] . "</td>";
                            echo "<td>" . $comment['Comment_Date'] . "</td>";
                            echo "<td>
                                    <a href='comments.php?make=edit&commentid=" . $comment['Comment_ID'] . "' class='btn btn-success btn-sm ml-1 '><i class='fas fa-user-edit'></i>Edit</a>
                                    <a href='comments.php?make=delete&commentid=" . $comment['Comment_ID'] . "' class='btn btn-danger btn-sm ml-1 confirm' id='confirm'><i class='far fa-trash-alt'></i>Delete</a>";
                            echo $comment['Status'] == 1 ? "<button class='btn btn-success btn-sm ml-1' id='pending'><i class='far fa-check-circle'></i>Approved</button>" : "<a href='comments.php?make=approve&commentid=" . $comment['Comment_ID'] . "' class='btn btn-info btn-sm ml-1' ><i class='far fa-clock'></i>Pending</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } elseif ($make === 'edit') { //Edit Page
        $comment_id = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 'Error!';
        // Make A query to fetch corresponding data from Database 
        $STM = $db->prepare('SELECT * FROM comments WHERE Comment_ID = ? LIMIT 1');
        $STM->execute([$comment_id]);
        $row = $STM->fetch(); // all fetched data are stored now in $row as an array
        $count = $STM->rowCount(); // count records in the result
        if ($count > 0) { // if result is greater than one that means there is a match
        ?>
            <!-- Start HTML Body For Edit Profile Page -->
            <div class="container edit-comment">
                <h1 class="text-center headers">Update Comment</h1>
                <form class="update-info" id="update-info" method="POST" action="?make=update">
                    <!-- Start Full Name Field -->
                    <div class="form-group">
                        <input type="hidden" name="comment_id" value="<?php echo $comment_id ?>">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class='form-control' name="comment" id="comment" cols="30" rows="8"><?php echo $row['Comment'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Update">
                    </div>
                </form>
            </div>
            <!-- End HTML Body For Edit Profile Page -->
    <?php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>You have made a mistake, there is no comment corresponding to these data <br> Please try again</div></div>";
            redirect($message, "back", 2); // redirect function defined in functions.php
        }
    } elseif ($make === 'update') { // Update Action page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class='text-center headers '>Update Member</h1>";
            // Save Received Data From The Form In Variables
            $comment_id = $_POST['comment_id'];
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

            if (!empty($comment)) {
                //check if new Comment exists
                $count = checkForItem('Comment_ID', 'comments', $comment_id);
                if ($count == 1) {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("UPDATE comments SET Comment = ? WHERE Comment_ID=? LIMIT 1");
                    $STM->execute([$comment, $comment_id]);
                    //Echo success Message
                    $message = '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Updated </div></div>';
                    redirect($message, "comments.php", 1); // redirect function defined in functions.php
                } else {
                    $message = '<div class="container p-3"><div class ="alert alert-danger">Sorry this no such comment</div></div>';
                    redirect($message, "back", 2); // redirect function defined in functions.php 
                }
            } else {
                $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Comment Is Required</div></div>";
                redirect($message, "back", 2); // redirect function defined in functions.php
            }
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Edit your comment first</div></div>";
            redirect($message, "comments.php", 2); // redirect function defined in functions.php
        }
    } elseif ($make === 'delete') { //delete page
        $comment_id = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 'Error!';
        // check if this comment exists
        $count = checkForItem('Comment_ID', 'comments', $comment_id);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Comment Deleted</h1>";
            $STM = $db->prepare('DELETE FROM comments WHERE Comment_ID = :USERID LIMIT 1');
            $STM->bindParam(":USERID", $comment_id);
            $STM->execute();
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Deleted </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is no such comment</div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
        }
    } elseif ($make === 'approve') {
        $comment_id = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 'Error!';
        // check if this comment exists
        $count = checkForItem('Comment_ID', 'comments', $comment_id);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Comment Approved</h1>";
            $STM = $db->prepare('UPDATE comments SET Status = 1 WHERE Comment_ID = ?');
            $STM->execute([$comment_id]);
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Approved </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is no such comment</div></div>";
            redirect($message, 'comments.php', 1); // redirect function defined in functions.php
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