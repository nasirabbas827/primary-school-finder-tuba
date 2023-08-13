<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to fetch all registration forms from the database
function getRegistrationForms($conn) {
    $query = "SELECT rf.*, s.name AS school_name, c.name AS city_name, u.username 
              FROM registration_form rf
              INNER JOIN users u ON rf.user_id = u.id
              INNER JOIN schools s ON rf.school_id = s.school_id
              INNER JOIN cities c ON rf.city_id = c.id";
    $result = mysqli_query($conn, $query);

    $registrationForms = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $registrationForms[] = $row;
        }
    } else {
        echo "Error fetching registration forms: " . mysqli_error($conn);
    }

    return $registrationForms;
}

// Fetch all registration forms
$registrationForms = getRegistrationForms($conn);

// Handle form submission for updating the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $formId = $_POST['form_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $updateQuery = "UPDATE registration_form SET register_status = '$status' WHERE form_id = '$formId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

if (isset($_GET['download_domicile'])) {
    $domicileFileName = $_GET['download_domicile'];
    $domicileFilePath = '../uploads/' . $domicileFileName;

    if (file_exists($domicileFilePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($domicileFilePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($domicileFilePath));
        readfile($domicileFilePath);
        exit;
    } else {
        echo 'File not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Form</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include('admin_navbar.php') ?>

    <div>
        <h2>Registration Forms</h2>
        <div>
            <table>
                <thead>
                    <tr>
                    <th>Student Picture</th>
                        <th>Username</th>
                        <th>School Name</th>
                        <th>City Name</th>
                        <th>Student Name</th>
                        <th>Student CNIC</th>
                        <th>Father's Name</th>
                        <th>Father's CNIC</th>
                        <th>Mother's Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Domicile</th>
                        <th>Register Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrationForms as $form) { ?>
                        <tr>
                        <td>
                                <img src="../uploads/<?php echo $form['student_picture']; ?>" height="90px" width="70px" alt="Student Picture">
                            </td> 
                            <td><?php echo $form['username']; ?></td>
                            <td><?php echo $form['school_name']; ?></td>
                            <td><?php echo $form['city_name']; ?></td>

                            <td><?php echo $form['student_name']; ?></td>
                            <td><?php echo $form['student_cnic']; ?></td>
                            <td><?php echo $form['student_father_name']; ?></td>
                            <td><?php echo $form['student_father_cnic']; ?></td>
                            <td><?php echo $form['student_mother_name']; ?></td>
                            <td><?php echo $form['student_address']; ?></td>
                            <td><?php echo $form['student_contact_number']; ?></td>
                            <td>
    <a href="admission_form.php?download_domicile=<?php echo $form['student_domicile']; ?>">
        Download Domicile
    </a>
</td>

                            <td><?php echo $form['register_status']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="form_id" value="<?php echo $form['form_id']; ?>">
                                    <select name="status">
                                        <option value="Pending" <?php if ($form['register_status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Approved" <?php if ($form['register_status'] === 'Approved') echo 'selected'; ?>>Approved</option>
                                        <option value="Rejected" <?php if ($form['register_status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>


