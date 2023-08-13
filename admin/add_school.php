<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Fetch the list of cities from the database
$query = "SELECT id, name FROM cities";
$result = mysqli_query($conn, $query);

$cities = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[$row['id']] = $row['name'];
    }
} else {
    echo "Error fetching cities: " . mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $name = $_POST['name'];
    $name = trim($name); // Remove leading/trailing whitespaces
    $name = htmlspecialchars($name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $picture = $_FILES['picture']['name'];
    $picture_tmp = $_FILES['picture']['tmp_name'];
    $picture_ext = pathinfo($picture, PATHINFO_EXTENSION);
    
    // Generate a unique filename for the picture
    $picture_filename = uniqid('school_') . '.' . $picture_ext;
    
    // Move the uploaded picture to the desired location
    $picture_destination = 'school_images/' . $picture_filename;
    move_uploaded_file($picture_tmp, $picture_destination);
    
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];
    $fee = $_POST['fee'];
    $uniform = $_POST['uniform'];
    $city_id = $_POST['city_id'];
    
    // Insert the school into the database
    $query = "INSERT INTO schools (name, picture, contact_info, address, fee, uniform, city_id) 
              VALUES ('$name', '$picture_filename', '$contact_info', '$address', '$fee', '$uniform', '$city_id')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "School added successfully.";
    } else {
        echo "Error adding school: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add School</title>
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
<?php include('admin_navbar.php') ?>

    <div>
        <!-- HTML form for adding a school -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="picture">Picture:</label>
                <input type="file" name="picture" id="picture" required>
            </div>
            <div>
    <label for="contact_info">Contact Info:</label>
    <input type="text" name="contact_info" id="contact_info" required>
</div>

            <div>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
            </div>
            <div>
                <label for="fee">Fee:</label>
                <input type="number" name="fee" id="fee" required>
            </div>
            <div>
                <label for="uniform">Uniform:</label>
                <input type="text" name="uniform" id="uniform" required>
            </div>
            <div>
                <label for="city_id">City:</label>
                <select name="city_id" id="city_id" required>
                    <?php foreach ($cities as $city_id => $city_name) { ?>
                        <option value="<?php echo $city_id; ?>"><?php echo $city_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit">Add School</button>
        </form>
    </div>

</body>

</html>
