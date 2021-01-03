<?php
ob_start();
session_start();
// No need for checking if seassion is started since any user can view all items
$pageTitle = 'View Item';
require_once('init.php'); // this file will includes everything is important for this page
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 'Error!';
$STM1 = $db->prepare("SELECT items.*,categories.Name As Cat_Name,users.Username
                       FROM items 
                       INNER JOIN categories ON categories.ID = items.Cat_ID 
                       INNER JOIN users ON users.UserID = items.Member_ID
                       WHERE items.Item_ID = ?");
$STM1->execute([$itemid]);
$item = $STM1->fetch();
$count = $STM1->rowCount(); // count records in the result
if ($count > 0) {
?>
    <div class="view-item">
        <h1 class="text-center">Item Details</h1>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card img-box ">
                        <img src="<?php echo "admin\uploads\product\\" . $item['Image'] ?>" class="card-img-top img-fluid">
                    </div>
                </div>
                <div class="col-sm-6 col-md-8">
                    <div class="card">
                        <h4 class="card-title py-2 px-3"><?php echo $item['Name'] ?></h4>
                        <div class="card-body">
                            <h5><span class='tag'>Price: </span><?php echo getCurr($item['Currency']) . $item['Price'] ?></h5>
                            <p><span class='tag'>Description:</span> <?php echo $item['Description'] ?></p>
                            <p><span class='tag'>Made in:</span> <?php echo $item['Country_Made'] ?></p>
                            <p><span class='tag'>Added on: </span><?php echo $item['Add_Date'] ?></p>
                            <p><span class='tag'>Owner: </span><a href="#"><?php echo $item['Username'] ?></a></p>
                            <p><span class='tag'>Category: </span><a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Cat_Name'] ?></a></p>
                            <div><span class='tag'>Comments:</span>
                                <div>
                                    <?php
                                    $STM2 = $db->prepare("SELECT comments.*, users.Username 
                                FROM comments 
                                INNER JOIN users ON users.UserID = comments.User_ID
                                WHERE Item_ID = ? AND Status = 1
                                ORDER BY Comment_Date DESC");
                                    $STM2->execute([$itemid]);
                                    $comments = $STM2->fetchAll();
                                    if (!empty($comments)) {
                                        foreach ($comments as $comment) { ?>
                                            <div class=" comment-field">
                                                <span class="commenter"><?php echo $comment['Username'] ?> :</span>
                                                <p><?php echo $comment['Comment'] ?></p>
                                                <span class="date"><?php echo $comment['Comment_Date'] ?></span>
                                            </div>
                                    <?php }
                                    } else {
                                        echo " <div class='card-body comment-field'><p class='card-text'>You have no comment!</p></div>";
                                    }
                                    ?>
                                    <?php
                                    if (isset($_SESSION['user'])) {
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            $userID = $_SESSION['userid'];
                                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                            if (!empty($comment) && isset($comment)) {
                                                $STM3 = $db->prepare("INSERT INTO comments (Comment_ID, Comment, Status, Comment_Date, Item_ID, User_ID) VALUES ( ?, ?,?, now(), ?, ?)");
                                                $STM3->execute([NULL, $comment, 0, $itemid, $userID]);
                                                header('refresh:1');
                                            } else {
                                                $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Comment is required</div></div>";
                                                redirect($message, "back", 2); // redirect function defined in functions.php 
                                            }
                                        }
                                    ?>
                                        <div class="add-comment">
                                            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $itemid ?>" method="post">
                                                <div class="form-group">
                                                    <div class="input-group mb-3 comment-field">
                                                        <span class="commenter"><?php echo $sessionUser ?> :</span>
                                                        <input type="text" class="form-control" name='comment' require>
                                                        <input type="submit" value="Send" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php
} else {
    $message = "<div class='container p-2'><div class='alert alert-danger p-2'>You have made a mistake, there is no item corresponding to these data <br> Please try again</div></div>";
    redirect($message, "back", 3); // redirect function defined in functions.php
}

require_once("includes/templates/footer.php");
ob_end_flush();
    ?>