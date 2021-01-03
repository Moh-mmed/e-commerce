<?php
//
//==================================================
//========          DASHBOARD PAGE       ============
//==================================================
ob_start(); // turn on output buffering 
;
//strat a session to keep the user logged in
session_start();

// if the user came from an opend session, dashboard will be completely available 
if (isset($_SESSION['Username'])) :
    $pageTitle = "Dashboard";
    require_once('init.php') ?>
    <?php
    $latestMember = 5;
    $latestRegistredMembers = getLatestRecs('*', 'users', 'UserID', $latestMember, 'WHERE GroupID !=1');
    $latestItem = 5;
    $latestAddedeItems = getLatestRecs('*', 'items', 'Item_ID', $latestItem);
    ?>
    <!-- Start page's HTML body -->
    <div class="container my-3 home-stats">
        <h1 class="text-center">Dashboard</h1>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-3 py-3">
                <a href="members.php">
                    <div class="stat py-3 px-1 members shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-center">Members</h3>
                            <span class="text-center"><?php echo countItems('UserID', 'users', 'WHERE GroupID !=1'); ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-12  col-md-6 col-lg-3 py-3">
                <a href="members.php?make=manage&page=pending">
                    <div class="stat py-3 px-1 requests shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-center">Requests</h3>
                            <span class="text-center"><?php echo countItems('UserID', 'users', 'WHERE RegStatus =0'); ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-12  col-md-6 col-lg-3 py-3">
                <a href="comments.php">
                    <div class="stat py-3 px-1 comments  shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-center">Comments</h3>
                            <span class="text-center"><?php echo countItems('Comment_ID', 'Comments'); ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-12  col-md-6 col-lg-3 py-3">
                <a href="items.php">
                    <div class="stat py-3 px-1 items shadow-lg">
                        <div class="card-body">
                            <h3 class="card-title text-center">Items</h3>
                            <span class="text-center"><?php echo countItems('Item_ID', 'items'); ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="container my-3 latest">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="panel-body p-3 shadow-sm">
                    <div class="panel-heading">
                        <i class="fas fa-user"></i>
                        <span>Recent <?php echo count($latestRegistredMembers) <= $latestMember ? count($latestRegistredMembers) : $latestMember ?> Registred Member</span>
                        <span class="toggle-info fa-pull-right" id="toggle-info"><i class="fas fa-chevron-up"></i></span>
                    </div>
                    <div class="panel-text">
                        <?php
                        if (!empty($latestRegistredMembers)) {
                            foreach ($latestRegistredMembers as $member) {
                                echo "<div class='member'>";
                                echo "<span class='fullname'>" . $member['Username'] . "</span>";
                                echo "<a href='members.php?make=edit&userid=" . $member['UserID'] . "'><button class='btn btn-success btn-sm fa-pull-right'><i class='fas fa-user-edit mr-1'></i>Edit</button></a>";
                                echo $member['RegStatus'] == 1 ? "<button class='btn btn-success btn-sm mr-1 fa-pull-right'><i class='far fa-check-circle'></i>Activated</button>" : "<a href='members.php?make=activate&userid=" . $member['UserID'] . "' class='btn btn-info btn-sm mr-1 fa-pull-right' ><i class='far fa-clock'></i>Pending</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class'member'><span>There is no member to show!</span></div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="panel-body p-3 shadow-sm">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i>
                        <span>Recent <?php echo count($latestAddedeItems) <= $latestItem ? count($latestAddedeItems) : $latestItem ?> Items</span>
                        <span class="toggle-info fa-pull-right" id="toggle-info"><i class="fas fa-chevron-up"></i></span>
                    </div>
                    <div class="panel-text">
                        <?php
                        if (!empty($latestAddedeItems)) {
                            foreach ($latestAddedeItems as $item) {
                                echo "<div class='member'>";
                                echo "<span class='fullname'>" . $item['Name'] . "</span>";
                                echo "<a href='items.php?make=edit&itemid=" . $item['Item_ID'] . "'><button class='btn btn-success btn-sm fa-pull-right'><i class='fas fa-user-edit mr-1'></i>Edit</button></a>";
                                echo $item['Approve'] == 1 ? "<button class='btn btn-success btn-sm mr-1 fa-pull-right'><i class='far fa-check-circle'></i>Activated</button>" : "<a href='items.php?make=approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info btn-sm mr-1 fa-pull-right' ><i class='far fa-clock'></i>Pending</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class'member'><span>There is no item to show!</span></div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- End page's HTML body -->

    <?php require_once($tmpl . "footer.php"); ?>
<?php else : // else,the user will be directed to the index.php page 
    header('location: index.php');
    exit();
endif;
ob_end_flush()
?>