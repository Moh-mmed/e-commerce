<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    $pageTitle = 'New Ad';
    require_once('init.php'); // this file will includes everything is important for this page
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Save Received Data From The Form In Variables
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
        $madeIn = filter_var($_POST['madeIn'], FILTER_SANITIZE_STRING);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $owner = $_SESSION['userid'];

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
            // you can use realpath(dirname(getcwd()));

            // adding random characters to image name thus avoiding having same names 
            $fileName = random_int(0, 100000000) . $productImgName;
            //moving the uploaded file from temp path to permanent path with new name and insert same name to DataBase
            move_uploaded_file($productImgTmpName, $_SERVER['DOCUMENT_ROOT'] . "/e-commerce/admin/uploads/product/" . $fileName); //Moves an uploaded file to a new location you should use a function that get the path of the directory
            echo "<h1 class=' mt-5 mb-5 text-center headers '>Added Item</h1>";
            //Update Database
            //Prepare A Query To Update Data In Database
            $STM = $db->prepare("INSERT INTO items (Name,Price,Currency,Add_Date,Country_Made,Description,Image,Status,Cat_ID,Member_ID) VALUES (?,?,?,now(),?,?,?,?,?,?)");
            $STM->execute([$name, $price, $currency, $madeIn, $description, $fileName, $status, $category, $owner]);
            //Echo success Message
            $message =  '<div class="container p-2"><div class ="alert alert-success">' . $STM->rowCount() . ' Records Inserted </div></div>';
            redirect($message, 'newad.php', 1); // redirect function defined in functions.php
        }
    }

?>

    <div class="new-ad">
        <h1 class="text-center">Add New Ad</h1>
        <div class="container block ">
            <div class="card">
                <h4 class="card-title py-2 px-3">Create New Add</h4>
                <div class="card-body">
                    <card class="row">
                        <div class="col-md-8">
                            <form class="new-ad" id="new-ad" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                                <!-- Start Name Field -->
                                <div class="form-group">
                                    <label for="name" class="form-label ">Name</label>
                                    <input type="text" id="name" class="form-control form-control-lg name live" data-class=".live-name" name="name" require>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Price Field -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <label for="price" class="form-label ">Price</label>
                                            <input type="text" id="price" class="form-control form-control-lg price live" name="price" data-class=".live-price" require>
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
                                            if (!empty($subCats)) {
                                                foreach ($subCats as $subCat) {
                                                    echo "<option value='" . $subCat['ID'] . "'>--- " . $subCat['Name']  . "</option>";
                                                }
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
                                        <option selected value="0">Select one</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                        <option value="Old">Old</option>
                                        <option value="Very Old">Very Old</option>
                                    </select>
                                </div>
                                <!-- End Status Field -->
                                <!-- Start Description Field -->
                                <div class="form-group">
                                    <label for="description" class="form-label ">Description</label>
                                    <textarea id="description" class="form-control form-control-lg description live" name="description" data-class=".live-desc" rows="2"></textarea>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Image Field -->
                                <div class="form-group">
                                    <label for="productImg" class="form-label ">Upload Image</label>
                                    <input type="file" id="productImg" class="form-control form-control-lg img" name="productImg" required>
                                    <!-- add multiple="multiple" in case you want to upload multiple files and name="^productImg[]"-->
                                </div>
                                <!-- End Image Field -->
                                <!-- Start Submit Field -->
                                <div class="form-group">
                                    <input type="submit" class=" btn btn-primary btn-lg btn-block shadow-lg rounded" value="Add">
                                </div>
                                <!-- End Submit Field -->
                            </form>
                        </div>
                        <div class="col-md-3">
                            <div class="card live-preview">
                                <div class="img-box">
                                    <img src="layout\images\img-01.jpg" class="card-img-top live-img" alt="...">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title live-name">Name</h5>
                                    <p class="card-text live-desc">Description</p>
                                    <div>
                                        <a href="#" class="btn btn-primary fd-pull-left">See More</a>
                                        <span class="price fa-pull-right m-1"><span class="live-curr">€</span><span class="live-price">0</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </card>
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