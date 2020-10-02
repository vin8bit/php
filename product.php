<?php
include 'config.php';
include 'connection.php';

$productSaved = FALSE;
$flag = 0;
$code1 = 'zas';
if (isset($_POST['submit'])) {
    /*
     * Read posted values.
     */
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
	$productCode = isset($_POST['code']) ? $_POST['code'] : '';
    $productQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
	$productPrice = isset($_POST['price']) ? $_POST['price'] : 0;
    $productDescription = isset($_POST['description']) ? $_POST['description'] : '';
	$productCategory = isset($_POST['category']) ? $_POST['category'] : '';

    /*
     * Validate posted values.
     */
    if (empty($productName)) {
        $errors[] = ' <font color= "red"> Please provide a product name.</font>';
    }

    if ($productQuantity == 0) {
        $errors[] = ' <font color= "red">Please provide the quantity.</font>';
    }

	if ($productPrice == 0) {
        $errors[] = ' <font color= "red"> Please provide a price.</font>';
    }
	
    if (empty($productDescription)) {
        $errors[] = ' <font color= "red">Please provide a description.</font>';
    }
	
	if (empty($productCategory)) {
        $errors[] = ' <font color= "red"> Please provide a category.</font>';
    }
	if (empty($productCode)) {
        $errors[] = ' <font color= "red"> Please provide a Product code.</font>';
    }else{
		
		$sql = "SELECT * FROM products WHERE code ='". $productCode."'" ;
        $result = $connection->query($sql);
        while($res = $result->fetch_assoc()) {         
            $code1 = $res['code'];
			if($code1 === $productCode){
			  $flag =1;
			}
        }$result->close();
	}
	
    /*
     * Create "uploads" directory if it doesn't exist.
     */
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    /*
     * List of file names to be filled in by the upload script 
     * below and to be saved in the db table "products_images" afterwards.
     */
    $filenamesToSave = [];

    $allowedMimeTypes = explode(',', UPLOAD_ALLOWED_MIME_TYPES);

    /*
     * Upload files.
     */
    if (!empty($_FILES)) {
        if (isset($_FILES['file']['error'])) {
            foreach ($_FILES['file']['error'] as $uploadedFileKey => $uploadedFileError) {
                if ($uploadedFileError === UPLOAD_ERR_NO_FILE) {
                    $errors[] = 'You did not provide any files.';
                } elseif ($uploadedFileError === UPLOAD_ERR_OK) {
                    $uploadedFileName = basename($_FILES['file']['name'][$uploadedFileKey]);

                    if ($_FILES['file']['size'][$uploadedFileKey] <= UPLOAD_MAX_FILE_SIZE) {
                        $uploadedFileType = $_FILES['file']['type'][$uploadedFileKey];
                        $uploadedFileTempName = $_FILES['file']['tmp_name'][$uploadedFileKey];

                        $uploadedFilePath = rtrim(UPLOAD_DIR, '/') . '/' . $uploadedFileName;

                        if (in_array($uploadedFileType, $allowedMimeTypes)) {
                            if (!move_uploaded_file($uploadedFileTempName, $uploadedFilePath)) {
                                $errors[] = 'The file "' . $uploadedFileName . '" could not be uploaded.';
                            } else {
                                $filenamesToSave[] = $uploadedFilePath;
                            }
                        } else {
                            $errors[] = 'The extension of the file "' . $uploadedFileName . '" is not valid. Allowed extensions: JPG, JPEG, PNG, or GIF.';
                        }
                    } else {
                        $errors[] = 'The size of the file "' . $uploadedFileName . '" must be of max. ' . (UPLOAD_MAX_FILE_SIZE / 1024) . ' KB';
                    }
                }
            }
        }
    }

    /*
     * Save product and images.
     */
	 if($flag == 0){
    if (!isset($errors) && !empty($filenamesToSave)) {
        /*
         * The SQL statement to be prepared. Notice the so-called markers, 
         * e.g. the "?" signs. They will be replaced later with the 
         * corresponding values when using mysqli_stmt::bind_param.
         * 
         * @link http://php.net/manual/en/mysqli.prepare.php
         */
        foreach ($filenamesToSave as $filename) {
        $sql = 'INSERT INTO products (
                    name,
                    quantity,
					price,
                    description,
					category,
					code,
                    filename
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?
                )';

        /*
         * Prepare the SQL statement for execution - ONLY ONCE.
         * 
         * @link http://php.net/manual/en/mysqli.prepare.php
         */
        $statement = $connection->prepare($sql);

        /*
         * Bind variables for the parameter markers (?) in the 
         * SQL statement that was passed to prepare(). The first 
         * argument of bind_param() is a string that contains one 
         * or more characters which specify the types for the 
         * corresponding bind variables.
         * 
         * @link http://php.net/manual/en/mysqli-stmt.bind-param.php
         */
        $statement->bind_param('siissss', $productName,$productQuantity, $productPrice, $productDescription, $productCategory, $productCode, $filename );
        /*
         * Execute the prepared SQL statement.
         * When executed any parameter markers which exist will 
         * automatically be replaced with the appropriate data.
         * 
         * @link http://php.net/manual/en/mysqli-stmt.execute.php
         */
        $statement->execute();

        // Read the id of the inserted product.
        $lastInsertId = $connection->insert_id;

        /*
         * Close the prepared statement. It also deallocates the statement handle.
         * If the statement has pending or unread results, it cancels them 
         * so that the next query can be executed.
         * 
         * @link http://php.net/manual/en/mysqli-stmt.close.php
         */
        $statement->close();}

        /*
         * Save a record for each uploaded file.
         */
      /*  foreach ($filenamesToSave as $filename) {
            $sql = 'INSERT INTO products_images (
                        product_id,
                        filename,
						codem
                    ) VALUES (
                        ?, ?,?
                    )';

            $statement = $connection->prepare($sql);

            $statement->bind_param('iss', $lastInsertId, $filename, $productCode);

            $statement->execute();

            $statement->close();
        }*/

        /*
         * Close the previously opened database connection.
         * 
         * @link http://php.net/manual/en/mysqli.close.php
         */
        $connection->close();

        $productSaved = TRUE;

        /*
         * Reset the posted values, so that the default ones are now showed in the form.
         * See the "value" attribute of each html input.
         */
        $filenamesToSave = $productName = $productQuantity = $productPrice = $productDescription = $productCategory = $productCode = NULL;
    }
	else { if(empty($filenamesToSave)){$errors[] = ' <font color= "red"> Please select a image.</font>';} }
	 }
	 else{ $errors[] = ' <font color= "red"> Product code is already added.</font>'; }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
        <meta charset="UTF-8" />
        <!-- The above 3 meta tags must come first in the head -->

        <title>Save product details</title>

        
        <link rel = "stylesheet" href = "css/product.css" type="text/css">

    </head>
    <body>

        <div class="form-container">
            <h2>Add a product</h2>

            <div class="messages">
                <?php
                if (isset($errors)) {
                    echo implode('<br/>',$errors);
                } elseif ($productSaved) {
                    echo "<font color= 'blue'>The product details were successfully saved.</font>";
                }
                ?>
            </div>

            <form action="product.php" method="post" enctype="multipart/form-data">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($productName) ? $productName : ''; ?>">
				
				<label for="code">Code</label>
                <input type="text" id="code" name="code" value="<?php echo isset($productCode) ? $productCode : ''; ?>">

                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="0" value="<?php echo isset($productQuantity) ? $productQuantity : '0'; ?>">
				
				<label for="price">Price</label>
                <input type="number" id="price" name="price" min="0" value="<?php echo isset($productPrice) ? $productPrice : '0'; ?>">

                <label for="description">Description</label>
                <input type="text" id="description" name="description" value="<?php echo isset($productDescription) ? $productDescription : ''; ?>">
				
				<label for="category">Category</label>
                <input type="text" id="category" name="category" value="<?php echo isset($productCategory) ? $productCategory : ''; ?>">

                <label for="file">Images</label>
                <input type="file" id="file" name="file[]" multiple>

                <button type="submit" id="submit" name="submit" class="button">
                    Add
                </button>
            </form>
        </div>

    </body>
</html>