<?php
ob_start();
session_start();
$pageTitle = 'Home';
require_once('init.php'); // this file will includes everything is important for this page
$items = getAllFrom('*', 'items', 'where Approve= 1', '', 'Add_Date');
if (count($categories) > 0) {
?>
    <div class="container all-categories my-5">
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
<?php
}
require_once("includes/templates/footer.php");
ob_end_flush();
?>