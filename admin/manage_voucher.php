<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}



// Fetch the admission data with fee vouchers
$query = "SELECT registration_form.*, schools.name AS school_name, cities.name AS city_name 
          FROM registration_form
          INNER JOIN schools ON registration_form.school_id = schools.school_id
          INNER JOIN cities ON registration_form.city_id = cities.id
          WHERE registration_form.fee_voucher IS NOT NULL";
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
    <title>Fee Vouchers</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('admin_navbar.php') ?>

    <div>
        <h2>Fee Vouchers</h2>
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
                        <th>School Name</th>
                        <th>City</th>
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
                            <td><?php echo $admission['school_name']; ?></td>
                            <td><?php echo $admission['city_name']; ?></td>
                            <td>
                                <a href="../<?php echo $admission['fee_voucher']; ?>" target="_blank">View Fee Voucher</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No fee vouchers found.</p>
        <?php } ?>
    </div>

</body>

</html>
