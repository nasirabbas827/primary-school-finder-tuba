<?php
// Assuming you have established a database connection
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Fetch the pending admission data for the user
$query = "SELECT registration_form.*, schools.name AS school_name, cities.name AS city_name 
          FROM registration_form
          INNER JOIN schools ON registration_form.school_id = schools.school_id
          INNER JOIN cities ON registration_form.city_id = cities.id
          WHERE registration_form.user_id = '$user_id' AND registration_form.register_status = 'Approved'";
$result = mysqli_query($conn, $query);

if ($result) {
    $admissionData = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error fetching admission data: " . mysqli_error($conn);
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_id = $_POST["form_id"];

    // Validate and process the uploaded fee voucher
    if (isset($_FILES["voucher"]) && $_FILES["voucher"]["error"] == UPLOAD_ERR_OK) {
        $file_name = $_FILES["voucher"]["name"];
        $file_tmp = $_FILES["voucher"]["tmp_name"];

        // Move the uploaded file to the desired location
        $upload_directory = "fee_vouchers/";
        $target_file = $upload_directory . basename($file_name);

        if (move_uploaded_file($file_tmp, $target_file)) {
            // Update the fee voucher path in the database
            $update_query = "UPDATE registration_form SET fee_voucher = '$target_file' WHERE form_id = '$form_id'";
            $update_result = mysqli_query($conn, $update_query);

            if ($update_result) {
                // Fee voucher uploaded successfully
                echo "Fee voucher uploaded successfully.";
            } else {
                echo "Error updating fee voucher path: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading fee voucher.";
        }
    } else {
        echo "Please select a fee voucher file to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Fee Voucher</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('navbar.php') ?>

    <div>
        <h2>Upload Fee Voucher</h2>
        <?php if ($admissionData) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Form ID</th>
                        <th>Student Name</th>
                        <th>Student CNIC</th>
                        <th>Student Father's Name</th>
                        <th>Student Father's CNIC</th>
                        <th>Student Mother's Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admissionData as $admission) { ?>
                        <tr>
                            <td><?php echo $admission['form_id']; ?></td>
                            <td><?php echo $admission['student_name']; ?></td>
                            <td><?php echo $admission['student_cnic']; ?></td>
                            <td><?php echo $admission['student_father_name']; ?></td>
                            <td><?php echo $admission['student_father_cnic']; ?></td>
                            <td><?php echo $admission['student_mother_name']; ?></td>
                            <td><?php echo $admission['student_address']; ?></td>
                            <td><?php echo $admission['student_contact_number']; ?></td>
                            <td>
                                <form action="upload_voucher.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <input type="file" name="voucher" required>
                                    </div>
                                    <input type="hidden" name="form_id" value="<?php echo $admission['form_id']; ?>">
                                    <button type="submit">Upload Fee Voucher</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No pending admission data found.</p>
        <?php } ?>
    </div>

</body>

</html>
