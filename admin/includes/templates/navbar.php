<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand primary" href="index.php">Sooki</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-coll" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav-coll">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item ">
                <a class="nav-link" href="index.php"><?php echo lang('HOME') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES') ?></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo lang('EDIT') ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../index.php" target="_blank"><?php echo lang("SHOP_VIEW") ?></a>
                    <a class="dropdown-item" href="members.php?make=edit&userid=<?php echo $_SESSION['UserID'] ?>">Edit Profile</a>
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="signout.php"><?php echo lang("SIGN_OUT") ?></a>
                </div>
            </li>
        </ul>
    </div>
</nav>