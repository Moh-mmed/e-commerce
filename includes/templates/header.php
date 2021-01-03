<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
    <script src="<?php echo $js; ?>jquery-3.5.1.min.js" defer></script>
    <script src="<?php echo $js; ?>bootstrap.min.js" defer></script>
    <script src="<?php echo $js; ?>frontend.js" defer></script>
    <title><?php setTitle() ?></title>
</head>

<body>
    <?php
    if (isset($_SESSION['user'])) {
        // check if the user is activated
        $status = checkUserStatus($sessionUser); // $sessionUse is defined as $_SESSION['user'] in init.php
        if ($status == 1) {
            echo "<div class='alert alert-danger py-3'>Welcome " . $sessionUser . ",Your Account is not activated</div>";
        }
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand primary" href="index.php">Sooki</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-coll" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-coll">
            <ul class="navbar-nav mr-auto">
                <?php
                $categories = getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'Date');
                foreach ($categories as $category) {
                    echo "<li class='nav-item'><a class='nav-link' href='categories.php?pageid=" . $category['ID'] .    "'>" . $category['Name'] . "</a><li>";
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo lang('EDIT') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">Edit Profile</a>
                        <a class="dropdown-item" href="profile.php#my-products">My Products</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <?php if (isset($_SESSION['user'])) {
                            echo "<a class='dropdown-item' href='signout.php'>Sign Out</a>";
                        } ?>
                    </div>
                </li>
            </ul>
            <!-- <a class="nav-link " href="signup.php"><input class=" btn btn-primary rounded" type="button" value=''></a> -->
            <?php if (!isset($_SESSION['user'])) {
                echo "<a class='nav-link' href='login.php'><input class=' btn btn-primary rounded' type='button' value='Login'></a>";
            }
            //  "<a class='nav-link' href='profile.php'><input class=' btn btn-primary rounded' type='button' value='Profile'></a>"
            ?>
        </div>
    </nav>