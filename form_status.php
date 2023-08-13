<?php
// Assuming you have established a database connection
include('config.php');
// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

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
          WHERE registration_form.user_id = '$user_id' AND registration_form.register_status = 'Pending'";
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
    <title>Pending Admission Data</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('navbar.php') ?>

    <div>
        <h2>Pending Admission Data</h2>
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
                        <th>Student Picture</th>
                        <th>Domicile</th>
                        <th>City</th>
                        <th>School Name</th>
                        <th>Registration Status</th>
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
                            <td><img src="./uploads/<?php echo $admission['student_picture']; ?>" alt="Student Picture" height="100px" width="100px"></td>
                            <td><a href="./uploads/<?php echo $admission['student_domicile']; ?>" target="_blank">View Domicile</a></td>
                            <td><?php echo $admission['city_name']; ?></td>
                            <td><?php echo $admission['school_name']; ?></td>
                            <td><?php echo $admission['register_status']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h4>No pending admission data found.</h4>
        <?php } ?>
    </div>

</body>

</html>

