<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Validate form data
    if (empty($name) || empty($code) || empty($price) || empty($image)) {
        echo "Please fill in all the fields.";
        exit;
    }

    // File upload handling
    $target_directory = 'product/';
    $target_file = $target_directory . basename($image['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file size
    if ($image['size'] > 500000) {
        $uploadOk = 0;
        echo "Sorry, the file is too large.";
        exit;
    }

    // Allow only specific file formats
    $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowedFormats)) {
        $uploadOk = 0;
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        exit;
    }

    // Move uploaded file to the target directory
    if ($uploadOk == 1) {
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
           

            // Database insertion
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'test';

            $con = mysqli_connect($host, $username, $password, $database);

            if (!$con) {
                die('Could not connect to the database: ' . mysqli_connect_error());
            }

            $query = "INSERT INTO tblproduct (name, code, price, image) VALUES ('$name', '$code', '$price', '$target_file')";
            if (!mysqli_query($con, $query)) {
                echo "can't insert product";
            } 
            mysqli_close($con);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form action="admin_panel.php" method="POST" enctype="multipart/form-data" style="max-width: 400px; margin: 0 auto;">
    <div style="margin-bottom: 10px;">
        <label for="name" style="display: block; margin-bottom: 5px;">Name:</label>
        <input type="text" name="name" id="name" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 10px;">
        <label for="code" style="display: block; margin-bottom: 5px;">Code:</label>
        <input type="text" name="code" id="code" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 10px;">
        <label for="price" style="display: block; margin-bottom: 5px;">Price:</label>
        <input type="number" name="price" id="price" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 10px;">
        <label for="image" style="display: block; margin-bottom: 5px;">Image:</label>
        <input type="file" name="image" id="image" required style="width: 100%; padding: 8px;">
    </div>

    <input type="submit" value="Upload" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
</form>


</body>
</html>
