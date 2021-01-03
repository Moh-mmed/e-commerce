<?php
ob_start();
//
//==================================================
//========          ITEMS PAGE       ============
//==================================================

//strat a session to keep the user logged in
session_start();

if (isset($_SESSION['Username'])) { // Check If Session Is Loged In
    $pageTitle = 'Items';
    require_once('init.php');
    //checkes for which 'make' the user want
    $make = isset($_GET["make"]) ? $_GET["make"] : 'manage';
    // according to 'make' value do
    if ($make === 'manage') { // Start Manage Page
        // check if there is no items
        $count = countItems('Item_ID', 'items');
        if ($count == 0) { ?>
            <div class="container empty">
                <div class="pending">
                    <p class="shadow-lg">There is no Item !</p>
                </div>
                <a href='items.php?make=add' class="btn btn-primary py-1 px-3 "><i class="fas fa-plus" aria-hidden="true"></i>New Item</a>
            </div>
        <?php
            exit();
        }

        // Fetch Data From Database
        $STM = $db->prepare("SELECT items.*, categories.Name As Category_Name, users.Username 
                                 FROM items 
                                 INNER JOIN categories ON items.Cat_ID = categories.ID 
                                 INNER JOIN users ON items.Member_ID = users.UserID 
                                 ORDER BY Item_ID DESC");
        $STM->execute();
        $items = $STM->fetchAll(); // fetches all items as rows
        ?>
        <div class="container manage-items pb-5">
            <h1 class="text-center headers">Manage Items</h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-md table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Made In</th>
                            <th scope="col">Owner</th>
                            <th scope="col">Category</th>
                            <th scope="col">Description</th>
                            <th scope="col">Added At</th>
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Fill In Table The Data
                        foreach ($items as $row) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $row['Item_ID'] . "</th>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . getCurr($row['Currency']) . " " . $row['Price'] . "</td>";
                            echo "<td>" . $row['Country_Made'] . "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Category_Name'] . "</td>";
                            echo "<td>" . $row['Description'] . "</td>";
                            echo "<td>" . $row['Add_Date'] . "</td>";
                            echo "<td>
                                    <a href='items.php?make=edit&itemid=" . $row['Item_ID'] . "' class='btn btn-success btn-sm ml-1 '><i class='fas fa-user-edit'></i>Edit</a>
                                    <a href='items.php?make=delete&itemid=" . $row['Item_ID'] . "' class='btn btn-danger btn-sm ml-1 confirm' id='confirm' ><i class='far fa-trash-alt'></i>Delete</a>";
                            echo $row['Approve'] == 1 ? "<button class='btn btn-success btn-sm ml-1' id='pending'><i class='far fa-check-circle'></i>Approved</button>" : "<a href='items.php?make=approve&itemid=" . $row['Item_ID'] . "' class='btn btn-info btn-sm ml-1' ><i class='far fa-clock'></i>Pending</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href='items.php?make=add' class="btn btn-primary py-1 px-3 "><i class="fas fa-plus" aria-hidden="true"></i>New Item</a>
            </div>
        </div>

    <?php } elseif ($make === 'add') { ?>
        <!-- Start HTML Body For Add Page -->
        <div class=" container add-items">
            <h1 class=" my-4 text-center headers">Add New Item</h1>
            <form class="item-add" id="item-add" method="POST" action="items.php?make=insert" enctype="multipart/form-data">

                <!-- Start Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label ">Name</label>
                    <input type="text" id="name" class="form-control form-control-lg name" name="name" require>
                </div>
                <!-- End Name Field -->
                <!-- Start Price Field -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-9">
                            <label for="price" class="form-label ">Price</label>
                            <input type="text" id="price" class="form-control form-control-lg price" name="price" require>
                        </div>
                        <div class="col-sm-3">
                            <label for="currency" class="form-label ">Currency</label>
                            <select class="form-control form-control-lg" name="currency" id="currency">
                                <option selected value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="DZD">DZD</option>
                                <option value="CAD">CAD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End Price Field -->
                <!-- Start Country Made Field -->
                <div class="form-group">
                    <label for="madeIn" class="form-label ">Made In</label>
                    <input type="text" id="madeIn" class="form-control form-control-lg madeIn" name="madeIn" require>
                </div>
                <!-- End Country Made Field -->
                <!-- Start Owner Field -->
                <div class="form-group">
                    <label for="owner">Owner</label>
                    <select class="form-control form-control-lg" name="owner" id="owner" require>
                        <option selected value='0'>Select one</option>
                        <?php
                        $allMembers = getAllFrom('*', 'users', '', '', 'UserID');
                        foreach ($allMembers as $user) {
                            echo "<option value='" . $user['UserID'] . "'>" . $user['Username']  . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- End Owner Field -->
                <!-- Start Category Field -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control form-control-lg" name="category" id="category" require>
                        <option selected value="0">Select one</option>
                        <?php
                        $categories = getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID');
                        foreach ($categories as $category) {
                            echo "<option value='" . $category['ID'] . "'>" . $category['Name']  . "</option>";
                            $subCats = getAllFrom('*', 'categories', "WHERE Parent = {$category['ID']}", '', 'ID');
                            foreach ($subCats as $subCat) {
                                echo "<option value='" . $subCat['ID'] . "'>--- " . $subCat['Name']  . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- End Category Field -->
                <!-- Start Status Field -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control form-control-lg" name="status" id="status" required>
                        <option selected value="0">Select one</option>
                        <option value="New">New</option>
                        <option value="Used">Used</option>
                        <option value="Old">Old</option>
                        <option value="Very Old">Very Old</option>
                    </select>
                </div>
                <!-- End Status Field -->
                <!-- Start Tags Field -->
                <div class="form-group">
                    <label for="tags" class="form-label ">Tags</label>
                    <input type="text" id="tags" class="form-control form-control-lg tags" name="tags">
                </div>
                <!-- End Tags Field -->
                <!-- Start Description Field -->
                <div class="form-group">
                    <label for="description" class="form-label ">Description</label>
                    <textarea id="description" class="form-control form-control-lg description" name="description" rows="2" required></textarea>
                </div>
                <!-- End Description Field -->
                <!-- Start Image Field -->
                <div class="form-group">
                    <label for="productImg" class="form-label ">Upload Image</label>
                    <input type="file" id="productImg" class="form-control form-control-lg img" name="productImg" required>
                </div>
                <!-- End Image Field -->
                <!-- Start Submit Field -->
                <div class="form-group">
                    <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Add">
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
        <!-- End HTML Body For Add Page -->

        <?php } elseif ($make === 'insert') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Save Received Data From The Form In Variables
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
            $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
            $madeIn = filter_var($_POST['madeIn'], FILTER_SANITIZE_STRING);
            $owner = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

            // Image Upload
            $productImg = $_FILES['productImg'];

            // get image data from $productImg array

            $productImgName = $productImg['name']; // name of image
            $productImgSize = $productImg['size']; // size of image
            $productImgTmpName = $productImg['tmp_name']; // temporary name 
            $productImgType = $productImg['type']; // extension type

            // List Of allowed types to be upload
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];

            // get the extension from img-name
            $imgNameArr = explode('.', $productImgName);
            $extension = strtolower(end($imgNameArr));
            /*
                expload():  to slice string (img name) to chunks in (.)
                end():      to get the least item in the array which is the extension
                strtolower: convert the extension to lowercase to avoid having problems an matching with allowed extensions
            */

            // Validate The Form Before Sending It To Database
            // Creating array of errors
            $formErrors = [];
            $characters = ['[', ']', '(', ')', '{', '}', '=', '+', '*', '/', '\\', '|'];
            if (empty($name)) {
                $formErrors[] = 'Name Can Not Be Empty';
            }
            foreach (str_split($name, 1) as $char) {
                if (in_array($char, $characters)) {
                    $formErrors[] = "Name Shouldn't Include <i>'$@/
                                \+{}()[]'</i>";
                }
            }
            if (empty($price)) {
                $formErrors[] = 'Price Can Not Be Empty';
            }
            if (empty($madeIn)) {
                $formErrors[] = 'Made In Can Not Be Empty ';
            }
            if ($owner == 0) {
                $formErrors[] = 'Owner Can Not Be Empty ';
            }
            if ($category == 0) {
                $formErrors[] = 'Category Can Not Be Empty ';
            }
            if ($status == 0) {
                $formErrors[] = 'Status Can Not Be Empty ';
            }
            if (empty($description)) {
                $formErrors[] = 'Description Can Not Be Empty ';
            }
            //check if extension is one of listed extensions for image types
            if (!empty($productImgName) && !in_array($extension, $allowedExtensions)) {
                $formErrors[] = 'Image is not valid';
            }
            if (empty($productImgName)) {
                $formErrors[] = 'Image is required';
            }
            if ($productImgSize > 40194304) { // size in bytes
                $formErrors[] = 'Image size should not exceed 4MB';
            }

            if (!empty($formErrors)) {
                foreach ($formErrors as $err) {
                    echo "<div class='container p-2'><div class='alert alert-danger' >" . $err . "</div></div>";
                }
            }
            if (empty($formErrors)) {
                // the way images are uploaded is to insert its name to DATABASE and save the image in UPLOADS files by moving it from temporary path to real path with new created name 

                // adding random characters to image name thus avoiding having same names 
                $fileName = random_int(0, 100000000) . $productImgName;
                //moving the uploaded file from temp path to permanent path with new name and insert same name to DataBase
                move_uploaded_file($productImgTmpName, "uploads\product\\" . $fileName); //Moves an uploaded file to a new location
                echo "<h1 class=' mt-5 mb-5 text-center headers '>Added Item</h1>";
                //Update Database
                // Prepare A Query To Update Data In Database
                $STM = $db->prepare("INSERT INTO items (Name,Price,Currency,Add_Date,Country_Made,Description,Image,Status,Cat_ID,Member_ID) VALUES (?,?,?,now(),?,?,?,?,?,?)");
                $STM->execute([$name, $price, $currency, $madeIn, $description, $fileName, $status, $category, $owner]);
                //Echo success Message
                $message =  '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Inserted </div></div>';
                redirect($message, 'items.php', 1); // redirect function defined in functions.php
            }
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
            redirect($message); // redirect function defined in functions.php
        }
    } elseif ($make === 'edit') { //Edit Page
        // We protect Our URL From Assigning any value to $_GET['itemid'] but an integer
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 'Error!';
        // Make A query to fetch corresponding data from Database  
        $STM =  $db->prepare('SELECT * FROM items WHERE Item_ID = ?');
        $STM->execute([$itemid]);
        $item = $STM->fetch(); // all fetched data are stored now in $row as an array
        $count = $STM->rowCount(); // count records in the result
        if ($count > 0) { // if result is greater than one that means there is a match
        ?>
            <!-- Start HTML Body For Edit Profile Page -->
            <div class=" container edit-items">
                <h1 class=" my-4 text-center headers">Edit Item</h1>
                <form class="item-edit" id="item-edit" method="POST" action="items.php?make=update">
                    <!-- Start Name Field -->
                    <div class="form-group">
                        <input type="hidden" name="itemid" value="<?php echo $item['Item_ID'] ?>">
                        <label for="name" class="form-label ">Name</label>
                        <input type="text" id="name" class="form-control form-control-lg name" name="name" value="<?php echo $item['Name'] ?>" require>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Price Field -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-9">
                                <label for="price" class="form-label ">Price</label>
                                <input type="text" id="price" class="form-control form-control-lg price" name="price" value="<?php echo $item['Price'] ?>" require>
                            </div>
                            <div class="col-sm-3">
                                <label for="currency" class="form-label ">Currency</label>
                                <select class="form-control form-control-lg" name="currency" id="currency">
                                    <option selected value="EUR">EUR</option>
                                    <option value="USD" <?php echo $item['Currency'] === 'USD' ? 'Selected' : ''; ?>>USD</option>
                                    <option value="DZD" <?php echo $item['Currency'] === 'DZD' ? 'Selected' : ''; ?>>DZD</option>
                                    <option value="CAD" <?php echo $item['Currency'] === 'CAD' ? 'Selected' : ''; ?>>CAD</option>
                                    <option value="GBP" <?php echo $item['Currency'] === 'GBP' ? 'Selected' : ''; ?>>GBP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Made Field -->
                    <div class="form-group">
                        <label for="madeIn" class="form-label ">Made In</label>
                        <input type="text" id="madeIn" class="form-control form-control-lg madeIn" name="madeIn" value="<?php echo $item['Country_Made'] ?>" require>
                    </div>
                    <!-- End Country Made Field -->
                    <!-- Start Owner Field -->
                    <div class="form-group">
                        <label for="owner">Owner</label>
                        <select class="form-control form-control-lg" name="owner" id="owner" require>
                            <?php
                            $users
                                = getAllFrom('*', 'users', '', '', 'UserID');
                            foreach ($users as $user) {
                                echo "<option value='" . $user['UserID'] . "'";
                                echo  $user['UserID'] == $item['Member_ID'] ? 'Selected' : '';
                                echo " >" . $user['Username']  . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- End Owner Field -->
                    <!-- Start Category Field -->
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control form-control-lg" name="category" id="category" require>
                            <?php
                            $categories =
                                getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID');
                            foreach ($categories as $category) {
                                echo "<option value='" . $category['ID'] . "'";
                                echo  $category['ID'] == $item['Cat_ID'] ? 'Selected' : '';
                                echo ">" . $category['Name']  . "</option>";
                                $subCats = getAllFrom('*', 'categories', "WHERE Parent = {$category['ID']}", '', 'ID');
                                foreach ($subCats as $subCat) {
                                    echo "<option value='" . $subCat['ID'] . "'";
                                    echo
                                        $subCat['ID'] == $item['Cat_ID'] ? 'Selected' : '';
                                    echo ">--- " . $subCat['Name']  . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- End Category Field -->
                    <!-- Start Status Field -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control form-control-lg" name="status" id="status">
                            <option value="New" <?php echo $item['Status'] === 'New' ? 'Selected' : ''; ?>>New</option>
                            <option value="Used" <?php echo $item['Status'] === 'Used' ? 'Selected' : ''; ?>>Used</option>
                            <option value="Old" <?php echo $item['Status'] === 'Old' ? 'Selected' : ''; ?>>Old</option>
                            <option value="Very Old" <?php echo $item['Status'] === 'Very Old' ? 'Selected' : ''; ?>>Very Old</option>
                        </select>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Description Field -->
                    <div class="form-group">
                        <label for="description" class="form-label ">Description</label>
                        <textarea id="description" class="form-control form-control-lg description" name="description" rows="2"><?php echo $item['Description'] ?></textarea>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group">
                        <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Update">
                    </div>
                    <!-- End Submit Field -->
                </form>

                <!-- Start Comments Section -->
                <?php
                // Fetch Data From Database
                $STM = $db->prepare("SELECT comments.*, users.Username
                            FROM comments 
                            INNER JOIN users ON users.UserID = comments.User_ID
                            WHERE Item_ID = ?");
                $STM->execute([$itemid]);
                $comments = $STM->fetchAll(); // fetches all data as rows
                if (!empty($comments)) { ?>
                    <div class="manage-comments">
                        <h1 class=" text-center headers">Manage Comments</h1>
                        <div class="table-responsive">
                            <table class="table table-dark table-md table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Comment</th>
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
                                        echo "<td>" . $comment['Comment'] . "</td>";
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
                <?php  } ?>
                <!-- End Comments Section -->
            </div>
            <!-- End HTML Body For Edit Profile Page -->
    <?php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>You have made a mistake, there is no item corresponding to these data <br> Please try again</div></div>";
            redirect($message, "back", 4); // redirect function defined in functions.php
        }
    } elseif ($make === 'update') { // Update Action page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class='text-center headers '>Item Updated</h1>";
            // Save Received Data From The Form In Variables
            $item_id = $_POST['itemid'];
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
            $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
            $madeIn = filter_var($_POST['madeIn'], FILTER_SANITIZE_STRING);
            $owner = filter_var($_POST['owner'], FILTER_SANITIZE_STRING);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

            // Validate The Form Before Sending It To Database
            // Creating array of errors
            $formErrors = [];
            $characters = ['[', ']', '(', ')', '{', '}', '=', '+', '*', '/', '\\', '|'];
            if (empty($name)) {
                $formErrors[] = 'Name Can Not Be Empty';
            }
            foreach (str_split($name, 1) as $char) {
                if (in_array($char, $characters)) {
                    $formErrors[] = "Name Shouldn't Include <i>'$@/
                                \+{}()[]'</i>";
                }
            }
            if (empty($price)) {
                $formErrors[] = 'Price Can Not Be Empty';
            }
            if (empty($madeIn)) {
                $formErrors[] = 'Made In Can Not Be Empty ';
            }
            if ($owner == 0) {
                $formErrors[] = 'Owner Can Not Be Empty ';
            }
            if ($category == 0) {
                $formErrors[] = 'Category Can Not Be Empty ';
            }
            if ($status == 0) {
                $formErrors[] = 'Status Can Not Be Empty ';
            }
            if (empty($description)) {
                $formErrors[] = 'Description Can Not Be Empty ';
            }
            if (!empty($formErrors)) {
                foreach ($formErrors as $err) {
                    echo "<div class='container p-2'><div class='alert alert-danger' >" . $err . "</div></div>";
                }
            }
            if (empty($formErrors)) {
                //checking for item existence although we made a check for that before
                $catCount = checkForItem('Item_ID', 'items', $item_id);
                if ($catCount > 0) {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("UPDATE items SET Name = ?,Price=?, Currency= ?, Country_Made=?, Member_ID=?,Cat_ID=?,Status=?,Description=? WHERE Item_ID= $item_id");
                    $STM->execute([$name, $price, $currency, $madeIn, $owner, $category, $status, $description]);
                    //Echo success Message
                    $message = '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Updated </div></div>';
                    redirect($message, "items.php", 2); // redirect function defined in functions.php
                } else {
                    $message = '<div class="container p-3"><div class ="alert alert-danger">Sorry There Is No Item</div></div>';
                    redirect($message, "item.php", 1); // redirect function defined in functions.php 
                }
            }
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Edit Item First</div></div>";
            redirect($message, "items.php", 1); // redirect function defined in functions.php
        }
    } elseif ($make === 'delete') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : '';
        $count = checkForItem('Item_ID', 'items', $itemid);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Item Deleted</h1>";
            $STM = $db->prepare('DELETE FROM items WHERE Item_ID = :ID LIMIT 1');
            $STM->bindParam(":ID", $itemid);
            $STM->execute();
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Deleted </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is no item to delete</div></div>";
            redirect($message, 'items.php'); // redirect function defined in functions.php
        }
    } elseif ($make === 'approve') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 'Error!';
        $count = checkForItem('Item_ID', 'items', $itemid);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Item's Approved</h1>";
            $STM = $db->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
            $STM->execute([$itemid]);
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Approved </div></div>";
            redirect($message, 'back', 1); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>There is No Such Member</div></div>";
            redirect($message, 'items.php'); // redirect function defined in functions.php
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