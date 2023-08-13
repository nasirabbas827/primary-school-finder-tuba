<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to fetch all schools from the database
function getSchools($conn) {
    $query = "SELECT * FROM schools";
    $result = mysqli_query($conn, $query);

    $schools = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $schools[] = $row;
        }
    } else {
        echo "Error fetching schools: " . mysqli_error($conn);
    }

    return $schools;
}

// Fetch all schools
$schools = getSchools($conn);

// Handle form submission for adding a faculty member
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $school_id = $_POST['school_id'];
    $name = $_POST['name'];
    $name = trim($name); // Remove leading/trailing whitespaces
    $name = htmlspecialchars($name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    
    // Upload faculty picture
    $picture = $_FILES['picture']['name'];
    $picture_tmp = $_FILES['picture']['tmp_name'];
    $picture_ext = pathinfo($picture, PATHINFO_EXTENSION);
    $picture_filename = uniqid('faculty_') . '.' . $picture_ext;
    $picture_destination = 'faculty_images/' . $picture_filename;
    move_uploaded_file($picture_tmp, $picture_destination);
    
    $contact_info = $_POST['contact_info'];
    
    // Insert the faculty member into the database
    $query = "INSERT INTO faculty (school_id, name, qualification, experience, picture, contact_info) 
              VALUES ('$school_id', '$name', '$qualification', '$experience', '$picture_filename', '$contact_info')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Faculty member added successfully.";
    } else {
        echo "Error adding faculty member: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php include('admin_navbar.php') ?>

    <div>
        <form method="POST" action="" enctype="multipart/form-data">
            <div>
                <label for="school_id">Select School:</label>
                <select name="school_id" id="school_id" required>
                    <?php foreach ($schools as $school) { ?>
                        <option value="<?php echo $school['school_id']; ?>"><?php echo $school['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="qualification">Qualification:</label>
                <input type="text" name="qualification" id="qualification" required>
            </div>
            <div>
                <label for="experience">Experience:</label>
                <input type="text" name="experience" id="experience" required>
            </div>
            <div>
                <label for="picture">Picture:</label>
                <input type="file" name="picture" id="picture" required>
            </div>
            <div>
                <label for="contact_info">Contact Info:</label>
                <input type="text" name="contact_info" id="contact_info" required>
            </div>
            <button type="submit">Add Faculty</button>
        </form>
    </div>

</body>

</html>
