<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $school_id = $_POST['school_id'];
    $name = $_POST['name'];
    $name = trim($name); // Remove leading/trailing whitespaces
    $name = htmlspecialchars($name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];
    $fee = $_POST['fee'];
    $uniform = $_POST['uniform'];
    
    // Update the school in the database
    $query = "UPDATE schools SET name = '$name', contact_info = '$contact_info', address = '$address', fee = '$fee', uniform = '$uniform' WHERE school_id = '$school_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "School updated successfully.";
        header('Location: manage_school.php');
        exit();
    } else {
        echo "Error updating school: " . mysqli_error($conn);
    }
}

// Retrieve the school ID from the query string parameter
if (isset($_GET['school_id'])) {
    $school_id = $_GET['school_id'];
    
    // Fetch the school details from the database
    $query = "SELECT * FROM schools WHERE school_id = '$school_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $school = mysqli_fetch_assoc($result);
    } else {
        echo "School not found.";
        exit();
    }
} else {
    echo "School ID not specified.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit School</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php include('admin_navbar.php') ?>

    <div>
        <h2>Edit School</h2>
        <form method="POST" action="">
            <input type="hidden" name="school_id" value="<?php echo $school['school_id']; ?>">
            
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $school['name']; ?>" required>
            </div>
            
            <div>
                <label for="contact_info">Contact Info:</label>
                <input type="number" name="contact_info" id="contact_info" value="<?php echo $school['contact_info']; ?>" required>
            </div>
            
            <div>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo $school['address']; ?>" required>
            </div>
            
            <div>
                <label for="fee">Fee:</label>
                <input type="text" name="fee" id="fee" value="<?php echo $school['fee']; ?>" required>
            </div>
            
            <div>
                <label for="uniform">Uniform:</label>
                <input type="text" name="uniform" id="uniform" value="<?php echo $school['uniform']; ?>" required>
            </div>
            
            <button type="submit">Update School</button>
        </form>
    </div>

</body>

</html>
