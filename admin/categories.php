<?php
ob_start();
//
//==================================================
//========          TEMPLATE PAGE       ============
//==================================================

//strat a session to keep the user logged in
session_start();

if (isset($_SESSION['Username'])) { // Check If Session Is Loged In
    $pageTitle = 'Categories';
    require_once('init.php');
    //checkes for which 'make' the user want
    $make = isset($_GET["make"]) ? $_GET["make"] : 'manage';
    // according to 'make' value do
    if ($make === 'manage') { // Start Manage Page
        // check if there is no category
        $countCategories = countItems('ID', 'categories');

        if ($countCategories == 0) { ?>
            <div class="container empty">
                <div class="pending">
                    <p class="shadow-lg">There is no Category !</p>
                </div>
                <a href='categories.php?make=add' class="btn btn-primary py-1 px-3 "><i class="fas fa-plus" aria-hidden="true"></i>New Category</a>
            </div>
        <?php
            exit();
        }
        $sort = 'ASC';
        $sortArr = ['ASC', 'DESC'];
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArr)) {
            $sort = $_GET['sort'];
        }
        $categories
            = getAllFrom("*", "categories", "WHERE Parent=0", '', "ID", $sort);
        ?>
        <div class="container my-3 categories-manage mb-5">
            <h1 class="text-center">Manage Categories</h1>
            <div class='categories-holder shadow-lg'>
                <div class="sorting">
                    <span>Sort:
                        <a class="<?php echo $sort === 'ASC' ? 'active' : 'disactive'; ?>" href='categories.php?sort=ASC'><i class="fas fa-arrow-down"></i></a>
                        <a class="<?php echo $sort === 'DESC' ? 'active' : 'disactive'; ?>" href='categories.php?sort=DESC'><i class="fas fa-arrow-up"></i></a>
                    </span>
                </div>
                <?php
                foreach ($categories as $category) {
                    echo "<div class='category'>";
                    echo "<div class='hidden-buttons'>";
                    echo "<a href='categories.php?make=edit&catid=" . $category['ID'] . "' class='btn btn-info btn-sm ml-1 py-0 px-1'><i class='fas fa-edit'></i>Edit</a>";
                    echo "<a href='categories.php?make=delete&catid=" . $category['ID'] . "'class='confirm btn btn-danger btn-sm ml-1 py-0 px-1'><i class='fas fa-trash-alt'></i>Delete</a>";
                    echo "</div>";
                    echo "<span class='head'>" . $category['Name'] . "</span>";
                    echo "<span class='text'>";
                    echo empty($category['Description']) ? 'No Description' : $category['Description'] . "</span>";
                    echo "<span class='text'>Added At: " . $category['Date'] . "</span>";
                    echo $category['Visibility'] == 0 ? "<span class='visible'>Visible</span>" : "<span class='hidden'>Hidden</span>";
                    echo $category['Allow_Comment'] == 0 ? "<span class='visible'>Comments Enabled</span>" : "<span class='hidden'>Comments Disabled</span>";
                    echo $category['Allow_Ads'] == 0 ? "<span class='visible'>Ads Enabled</span>" : "<span class='hidden'>Ads Disabled</span>";
                    $subCats = getAllFrom('*', 'categories', "WHERE Parent = $category[ID]", '', 'Date');
                    if (!empty($subCats)) {
                        echo  "<div class='sub-cats'><ul class='navbar-nav mr-auto mt-2'>";
                        echo "<h6>Sub Categories:</h6>";
                        foreach ($subCats as $subCat) {
                            echo "<li class='nav-item ml-3'><i class='fas fa-arrow-right mr-2'></i><a href='categories.php?make=edit&catid=" . $subCat['ID'] . "'>" . $subCat['Name'] . "</a>";

                            echo "<a href='categories.php?make=edit&catid=" . $subCat['ID'] . "'><i class='fas fa-edit'></i></a>";
                            echo "<a href='categories.php?make=delete&catid=" . $subCat['ID'] . "'><i class='fas fa-trash-alt'></i></a>";
                            echo "<li>";
                        }
                        echo  "</ul></div>";
                    }
                    echo "</div>";
                }
                ?>

            </div>
            <a class='btn btn-primary py-1 px-3 mt-2' href='categories.php?make=add'><i class='fas fa-plus'></i>New Category</a>
        </div>
    <?php
    } elseif ($make === 'add') { ?>
        <!-- Start HTML Body For Add Page -->
        <div class=" container add-categories">
            <h1 class=" my-4 text-center headers">Add New Category</h1>
            <form class="categorie-add" id="categorie-add" method="POST" action="?make=insert">
                <!-- Start Name Field -->
                <div class="form-group ">
                    <label for="name" class="form-label ">Name</label>
                    <input type="text" id="name" class="form-control form-control-lg name" name="name" require>
                </div>
                <!-- End Name Field -->
                <!-- Start Category Field -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control form-control-lg" name="parent" id="parent" require>
                        <option selected value="0">Is a parent category</option>
                        <?php
                        $categories = getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID');
                        foreach ($categories as $category) {
                            echo "<option value='" . $category['ID'] . "'>" . $category['Name']  . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- End Category Field -->
                <!-- Start Ordering Field -->
                <div class="form-group">
                    <label for="ordering" class="form-label">Ordering</label>
                    <input type="text" id="ordering" class="form-control form-control-lg ordering" name="ordering">
                </div>
                <!-- End Ordering Field -->
                <!-- Start Description Field -->
                <div class="form-group">
                    <label for="description" class="form-label ">Description</label>
                    <textarea id="description" class="form-control form-control-lg description" name="description" rows="2"></textarea>
                </div>
                <!-- End Description Field -->
                <!-- Start Visibility Field -->
                <div class="form-group ">
                    <label class="form-label">Visibility</label>
                    <div class="radio-field ">
                        <label for="visibility-yes" class="form-label">Yes</label>
                        <input type="radio" id="visibility-yes" class="custom-radio mr-2" name="visibility" value="0" checked>
                        <label for="visibility-no" class="form-label">No</label>
                        <input type="radio" id="visibility-no" class="custom-radio mr-2" name="visibility" value="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Allow Comments</label>
                    <div class="radio-field">
                        <label for="comments-yes" class="form-label">Yes</label>
                        <input type="radio" id="comments-yes" class="custom-radio mr-2" name="comments" value="0" checked>
                        <label for="comments-no" class="form-label">No</label>
                        <input type="radio" id="comments-no" class="custom-radio mr-2" name="comments" value="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Allow Ads</label>
                    <div class="radio-field">
                        <label for="ads-yes" class="form-label">Yes</label>
                        <input type="radio" id="ads-yes" class="custom-radio mr-2" name="ads" value="0" checked>
                        <label for="ads-no" class="form-label">No</label>
                        <input type="radio" id="ads-no" class="custom-radio mr-2" name="ads" value="1">
                    </div>
                </div>
                <!-- End Visibility Field -->
                <!-- Start Button Field -->
                <div class="form-group">
                    <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Add">
                </div>
                <!-- End Button Field -->

            </form>
        </div>
        <!-- End HTML Body For Add Page -->
        <?php
    } elseif ($make === 'insert') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class=' mt-5 mb-5 text-center headers '>Category Added </h1>";
            // Save Received Data From The Form In Variables
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $parentCat = filter_var($_POST['parent'], FILTER_SANITIZE_NUMBER_INT);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $ordering = filter_var($_POST['ordering'], FILTER_SANITIZE_NUMBER_INT);
            $visibility = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];
            // Validate The Form Before Sending It To Database
            // Creating array of errors

            // Checks If The Item Exists
            if (!empty($name)) {
                $count = checkForItem('Name', 'categories', $name);
                if ($count == 1) {
                    $message = "<div class='container'><div class='alert alert-danger' >" . $name . " Is Used Before</div></div>";
                    redirect($message, 'back'); // redirect function defined in functions.php
                } else {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("INSERT INTO categories (Name,Parent,Description,Ordering,Visibility,Allow_Comment,Allow_Ads,Date) VALUES (?,?,?,?,?,?,?,now())");
                    $STM->execute([$name, $parentCat, $description, $ordering, $visibility, $comments, $ads]);
                    //Echo success Message
                    $message =  '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Category Inserted </div></div>';
                    redirect($message, 'categories.php'); // redirect function defined in functions.php
                };
            } else {
                $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Category's name is required</div></div>";
                redirect($message, 'back'); // redirect function defined in functions.php
            }
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Error! Failed To Load </div></div>";
            redirect($message); // redirect function defined in functions.php
        }
    } elseif ($make === 'edit') { //Edit Page
        // We protect Our URL From Assigning any value to $_GET['catid'] but an integer
        $catID = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 'Error!';
        // Make A query to fetch corresponding data from Database 
        $STM = $db->prepare('SELECT * FROM categories WHERE ID = ?');
        $STM->execute([$catID]);
        $category = $STM->fetch(); // all fetched data are stored now in $row as an array
        $count = $STM->rowCount(); // count records in the result
        if ($count > 0) { // if result is greater than one that means there is a match
        ?>
            <!-- Start HTML Body For Edit Profile Page -->
            <div class=" container edit-categories">
                <h1 class=" my-4 text-center headers">Edit Category</h1>
                <form class="categorie-edit" id="categorie-edit" method="POST" action="categories.php?make=update">
                    <input type="hidden" name="catid" value="<?php echo $category['ID'] ?>">
                    <!-- Start Name Field -->
                    <div class="form-group ">
                        <label for="name" class="form-label ">Name</label>
                        <input type="text" id="name" class="form-control form-control-lg name" name="name" require value="<?php echo $category['Name'] ?>">
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Category Field -->
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control form-control-lg" name="parent" id="parent" require>
                            <option value="0">Is a parent category</option>
                            <?php
                            $categories = getAllFrom('*', 'categories', 'WHERE Parent = 0', '', 'ID');
                            foreach ($categories as $cat) {
                                echo "<option value='" . $cat['ID'] . "'";
                                if ($cat['ID'] == $category['Parent']) {
                                    echo "selected";
                                }
                                echo ">" . $cat['Name'];
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- End Category Field -->
                    <!-- Start Description Field -->
                    <div class="form-group ">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control form-control-lg description" name="description" id="description" value="<?php echo $category['Description'] ?>">
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Ordering Field -->
                    <div class="form-group">
                        <label for="ordering" class="form-label">Ordering</label>
                        <input type="text" id="ordering" class="form-control form-control-lg ordering" name="ordering" value="<?php echo $category['Ordering'] ?>">
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Visibility Field -->
                    <div class="form-group ">
                        <label class="form-label">Visibility</label>
                        <div class="radio-field ">
                            <label for="visibility-yes" class="form-label">Yes</label>
                            <input type="radio" id="visibility-yes" class="custom-radio mr-2" name="visibility" value="0" <?php echo $category['Visibility'] == 0 ? 'checked' : ''; ?>>
                            <label for="visibility-no" class="form-label">No</label>
                            <input type="radio" id="visibility-no" class="custom-radio mr-2" name="visibility" value="1" <?php echo $category['Visibility'] == 1 ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allow Comments</label>
                        <div class="radio-field">
                            <label for="comments-yes" class="form-label">Yes</label>
                            <input type="radio" id="comments-yes" class="custom-radio mr-2" name="comments" value="0" <?php echo $category['Allow_Comment'] == 0 ? 'checked' : ''; ?>>
                            <label for="comments-no" class="form-label">No</label>
                            <input type="radio" id="comments-no" class="custom-radio mr-2" name="comments" value="1" <?php echo $category['Allow_Comment'] == 1 ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allow Ads</label>
                        <div class="radio-field">
                            <label for="ads-yes" class="form-label">Yes</label>
                            <input type="radio" id="ads-yes" class="custom-radio mr-2" name="ads" value="0" checked <?php echo $category['Allow_Ads'] == 0 ? 'checked' : ''; ?>>
                            <label for="ads-no" class="form-label">No</label>
                            <input type="radio" id="ads-no" class="custom-radio mr-2" name="ads" value="1" <?php echo $category['Allow_Ads'] == 1 ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <!-- End Visibility Field -->
                    <!-- Start Button Field -->
                    <div class="form-group">
                        <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Update">
                    </div>
                    <!-- End Button Field -->

                </form>
            </div>
            <!-- End HTML Body For Edit Profile Page -->
    <?php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>You have made a mistake, there is no category corresponding to these data <br> Please try again</div></div>";
            redirect($message, "back", 4); // redirect function defined in functions.php
        }
    } elseif ($make === 'update') { // Update Action page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h1 class='text-center headers '>Update Category</h1>";
            // Save Received Data From The Form In Variables
            $id = $_POST['catid'];
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $parentCat = filter_var($_POST['parent'], FILTER_SANITIZE_NUMBER_INT);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $ordering = filter_var($_POST['ordering'], FILTER_SANITIZE_NUMBER_INT);
            $visibility = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];
            //checking for category existence although we made a check for that before
            $catCount = checkForItem('ID', 'categories', $id);
            if ($catCount > 0) {
                if (!empty($name)) {
                    //Update Database
                    // Prepare A Query To Update Data In Database
                    $STM = $db->prepare("UPDATE categories SET Name = ?,Parent = ?,Description=?, Ordering= ?, Visibility=?, Allow_Comment=?,Allow_Ads=? WHERE ID=?");
                    $STM->execute([$name, $parentCat, $description, $ordering, $visibility, $comments, $ads, $id]);
                    //Echo success Message
                    $message = '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Updated </div></div>';
                    redirect($message, "categories.php", 2); // redirect function defined in functions.php
                } else {
                    $message = '<div class="container p-3"><div class ="alert alert-danger">Name Is Required</div></div>';
                    redirect($message, "categories.php"); // redirect function defined in functions.php 
                }
            } else {
                $message = '<div class="container p-3"><div class ="alert alert-danger">Sorry There Is No Category</div></div>';
                redirect($message, "categories.php"); // redirect function defined in functions.php 
            }
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>Edit Category First</div></div>";
            redirect($message, "categories.php", 2); // redirect function defined in functions.php
        }
    } elseif ($make === 'delete') {
        $id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 'Error!';
        // check if this member exists
        $count = checkForItem('ID', 'categories', $id);
        if ($count === 1) {
            echo "<h1 class='text-center headers '>Delete Member</h1>";
            $STM = $db->prepare('DELETE FROM categories WHERE ID = :ID LIMIT 1');
            $STM->bindParam(":ID", $id);
            $STM->execute();
            $message = "<div class='container p-2'><div class='alert alert-success p-2'>" . $STM->rowCount() . " Records Deleted </div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
        } else {
            $message = "<div class='container p-2'><div class='alert alert-danger p-2'>This Category Does Not Exist</div></div>";
            redirect($message, 'back'); // redirect function defined in functions.php
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