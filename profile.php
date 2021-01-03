<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    $pageTitle = 'Profile';
    require_once('init.php'); // this file will includes everything is important for this page
    $STM1 = $db->prepare("SELECT * FROM users WHERE Username = ? LIMIT 1");
    $STM1->execute([$sessionUser]);
    $data = $STM1->fetch();
?>

    <div class="information">
        <h1 class="text-center">My Information</h1>
        <div class="container block info">
            <div class="card">
                <h4 class="card-title py-2 px-3">My Information</h4>
                <div class="card-body">
                    <div class='text-body p-1'>
                        <span>Name: <?php echo $data['Username']; ?></span>
                        <span>Full Name: <?php echo $data['FullName']; ?></span>
                        <span>Email: <?php echo $data['Email']; ?></span>
                        <span>Registred Date: <?php echo $data['Date']; ?></span>
                        <span>Favorit Categories</span>
                    </div>
                    <a href="edit.php" class="btn btn-success px-3">Edit</a>
                </div>
            </div>
        </div>
        <div id="my-products" class="container block ads">
            <div class="card">
                <h4 class="card-title py-2 px-3">My Ads</h4>
                <div class="card-body">
                    <div class="row">
                        <!-- Add new ad  -->
                        <div class="col-sm-6 col-md-3">
                            <a href="newad.php">
                                <div class="card add-card">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                            </a>
                        </div>
                        <?php
                        //$ads = getItems('Member_ID', $data['UserID'], 0);

                        $ads = getAllFrom('*', 'items', "WHERE Member_ID = {$data['UserID']}", '', 'Add_Date');
                        if (!empty($ads)) {
                            foreach ($ads as $ad) { ?>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="card">
                                        <div class="img-box">
                                            <?php if ($ad['Approve'] == 0) {
                                                echo "<span class='not-approved'>NOT APPROVED</span>";
                                            } ?>
                                            <img src="<?php echo "admin\uploads\product\\" . $ad['Image'] ?>" class="card-img-top">
                                        </div>
                                        <div class="card-body">
                                            <h5><?php echo $ad['Name'] ?></h5>
                                            <p><?php echo $ad['Description'] ?></p>
                                            <div>
                                                <a href="item.php?itemid=<?php echo $ad['Item_ID'] ?>" class="btn btn-primary fd-pull-left">See More</a>
                                                <span class="price fa-pull-right"><?php echo getCurr($ad['Currency']) . $ad['Price'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } else {
                            echo " <div class='card-body'><p class='card-text'>You have no item!</p></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container block comment">
            <div class="card">
                <h4 class="card-title py-2 px-3">My Latest Comments</h4>
                <div class="card-body">
                    <div>
                        <?php
                        $comments = getAllFrom('*', 'comments', "WHERE User_ID ={$data['UserID']}", '', 'Comment_Date');
                        if (!empty($comments)) {
                            foreach ($comments as $comment) { ?>
                                <div class=" comment-field">
                                    <p><?php echo $comment['Comment'] ?></p>
                                    <span class="date"><?php echo $comment['Comment_Date'] ?></span>
                                </div>
                        <?php }
                        } else {
                            echo " <div class='card-body'><p class='card-text'>You have no comment!</p></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once("includes/templates/footer.php");
} else {
    header('location: login.php');
}
ob_end_flush();
?>