<?php
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); 
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

$schoolID = $_GET['school_id'];
$cityID = $_GET['city_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_form'])) {
    $studentPicture = $_FILES['studentPicture']['name'];
    $studentPictureTemp = $_FILES['studentPicture']['tmp_name'];
    $studentName = $_POST['studentName'];
    $studentCNIC = $_POST['studentCNIC'];
    $studentFatherName = $_POST['studentFatherName'];
    $studentFatherCNIC = $_POST['studentFatherCNIC'];
    $studentMotherName = $_POST['studentMotherName'];
    $studentAddress = $_POST['studentAddress'];
    $studentContactNumber = $_POST['studentContactNumber'];
    $studentDomicile = $_FILES['studentDomicile']['name'];
    $studentDomicileTemp = $_FILES['studentDomicile']['tmp_name'];
    $schoolID = $_POST['schoolID'];
    $cityID = $_POST['cityID'];

    // Move uploaded files to a designated folder
    $studentPicturePath = './uploads/' . $studentPicture;
    move_uploaded_file($studentPictureTemp, $studentPicturePath);

    $studentDomicilePath = './uploads/' . $studentDomicile;
    move_uploaded_file($studentDomicileTemp, $studentDomicilePath);

    // Insert form values into the admission form table
    $insertQuery = "INSERT INTO registration_form (user_id, student_picture, student_name, student_cnic, student_father_name, student_father_cnic, student_mother_name, student_address, student_contact_number, student_domicile, school_id, city_id, register_status)
                    VALUES ('$user_id', '$studentPicture', '$studentName', '$studentCNIC', '$studentFatherName', '$studentFatherCNIC', '$studentMotherName', '$studentAddress', '$studentContactNumber', '$studentDomicile', '$schoolID', '$cityID', 'Pending')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        $successMessage = "Registration form submitted successfully. Your registration is currently pending.";
    } else {
        $errorMessage = "Error submitting registration form: " . mysqli_error($conn);
    }
    header("location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Online</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php') ?>

    <div class="container">
        <h5>Fill the Admission Form</h5>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label for="studentPicture">Student Picture</label>
                <input type="file" id="studentPicture" name="studentPicture" required>
            </div>
            <div>
                <label for="studentName">Student Name</label>
                <input type="text" id="studentName" name="studentName" required>
            </div>
            <div>
                <label for="studentCNIC">Student CNIC</label>
                <input type="text" id="studentCNIC" name="studentCNIC" required>
            </div>
            <div>
                <label for="studentFatherName">Father's Name</label>
                <input type="text" id="studentFatherName" name="studentFatherName" required>
            </div>
            <div>
                <label for="studentFatherCNIC">Father's CNIC</label>
                <input type="number" id="studentFatherCNIC" name="studentFatherCNIC" required>
            </div>
            <div>
                <label for="studentMotherName">Mother's Name</label>
                <input type="text" id="studentMotherName" name="studentMotherName" required>
            </div>
            <div>
                <label for="studentAddress">Address</label>
                <input type="text" id="studentAddress" name="studentAddress" required>
            </div>
            <div>
                <label for="studentContactNumber">Contact Number</label>
                <input type="number" id="studentContactNumber" name="studentContactNumber" required>
            </div>
            <div>
                <label for="studentDomicile">Domicile PDF File</label>
                <input type="file" id="studentDomicile" name="studentDomicile" required>
            </div>
            <input type="hidden" name="schoolID" value="<?php echo $schoolID; ?>">
            <input type="hidden" name="cityID" value="<?php echo $cityID; ?>">
            <button type="submit" name="apply_form">Submit</button>
        </form>
    </div>

</body>

</html>
