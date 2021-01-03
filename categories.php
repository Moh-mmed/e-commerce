<?php
ob_start();
session_start();
$pageTitle = 'Category';
require_once('init.php'); // this file will includes everything is important for this page
$categoryID = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? $_GET['pageid'] : 'error!';
$STM1 = $db->prepare("SELECT Name FROM categories WHERE ID = $categoryID ");
$STM1->execute();
$cat = $STM1->fetch();
if ($cat) {
    $items = getAllFrom('*', 'items', "WHERE Cat_ID =$categoryID", 'and Approve=1', 'Add_Date');
    if (Count($items) == 0) {
        echo " <div class='container empty'>";
        echo "<h1 class='text-center'>" . $cat['Name'] . "</h1>";
        echo  "<div class='pending'>";
        echo "<p class='shadow-lg'>There is no item !</p>";
        echo "</div></div>";
        exit();
    } else {
?>
        <div class="container category-page">
            <h1 class="text-center"><?php echo $cat['Name'] ?></h1>
            <div class="row">
                <?php
                foreach ($items as $item) { ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <div class="img-box">
                                <?php if ($item['Approve'] == 0) {
                                    echo "<span class='not-approved'>NOT APPROVED</span>";
                                } ?>
                                <img src="<?php echo "admin\uploads\product\\" . $item['Image'] ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $item['Name'] ?></h5>
                                <p class="card-text"><?php echo $item['Description'] ?></p>
                                <div>
                                    <a href="item.php?itemid=<?php echo $item['Item_ID'] ?>" class="btn btn-primary fd-pull-left">See More</a>
                                    <span class="price fa-pull-right"><?php echo getCurr($item['Currency']) . $item['Price'] ?></span>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php }
                ?>
            </div>
        </div>
<?php }
} else {
    $message = " <div class='container alert alert-danger'>There is no such category</div>";
    redirect($message, 'index.php', 2);
}

require_once("includes/templates/footer.php");
ob_end_flush();
?>