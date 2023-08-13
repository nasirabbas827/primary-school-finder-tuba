<?php
// Assuming you have established a database connection
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Fetch the approved admission data for the user, including school fee
$query = "SELECT registration_form.*, schools.name AS school_name, schools.fee, cities.name AS city_name
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Admission Forms</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include 'navbar.php' ?>

    <div>
        <h2>Approved Admission Forms</h2>
        <?php if ($admissionData) { ?>
            <?php foreach ($admissionData as $admission) { ?>
                <div>
                    <div>
                        <h3>Fee Voucher</h3>
                        <div>
                            <div>
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <img src="./uploads/<?php echo $admission['student_picture']; ?>" height="200px " width="200px" alt="Student Picture">
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div>
                                <table>
                                    <tr>
                                        <td><strong>Form ID:</strong></td>
                                        <td><?php echo $admission['form_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>School Name:</strong></td>
                                        <td><?php echo $admission['school_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>City:</strong></td>
                                        <td><?php echo $admission['city_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student Name:</strong></td>
                                        <td><?php echo $admission['student_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student CNIC:</strong></td>
                                        <td><?php echo $admission['student_cnic']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student Father's Name:</strong></td>
                                        <td><?php echo $admission['student_father_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student Father's CNIC:</strong></td>
                                        <td><?php echo $admission['student_father_cnic']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student Mother's Name:</strong></td>
                                        <td><?php echo $admission['student_mother_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td><?php echo $admission['student_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contact Number:</strong></td>
                                        <td><?php echo $admission['student_contact_number']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>School Fee:</strong></td>
                                        <td><?php echo $admission['fee']; ?></td>
                                    </tr>
                                </table>
                                <div>
                                    <!-- Inside the loop -->
                                    <a href="print_manage_voucher.php?print_form_id=<?php echo $admission['form_id']; ?>" style="background-color: azure; float:right; margin:30px;" target="_blank">Print Voucher</a>

                                </div>
                            </div>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No approved admission forms found.</p>
        <?php } ?>

    </div>


</body>

</html>