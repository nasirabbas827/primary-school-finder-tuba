<?php
// Include the configuration file
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}


// Function to get the total count from a table
function getTotalCount($conn, $table) {
    $query = "SELECT COUNT(*) AS total_count FROM $table";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_count'];
    } else {
        return 0;
    }
}

// Fetch the counts from the respective tables
$totalUsers = getTotalCount($conn, 'users');
$totalSchools = getTotalCount($conn, 'schools');
$totalCities = getTotalCount($conn, 'cities');
$totalPendingRegistrations = getTotalCount($conn, 'registration_form WHERE register_status = "Pending"');
$totalFaculty = getTotalCount($conn, 'faculty');
$totalFeeVouchers = getTotalCount($conn, 'registration_form WHERE fee_voucher IS NOT NULL');
$totalCourses = getTotalCount($conn, 'course');

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        h5, p{
            text-align: center;
            font-size: 20px;
        }
    </style>
</head>
<body>
<?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>Admin Dashboard</h2>

        <div class="dashboard">
            <div class="dashboard-item">
                <h5>Total Users</h5>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Schools</h5>
                <p><?php echo $totalSchools; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Cities</h5>
                <p><?php echo $totalCities; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Pending Registrations</h5>
                <p><?php echo $totalPendingRegistrations; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Faculty</h5>
                <p><?php echo $totalFaculty; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Fee Vouchers</h5>
                <p><?php echo $totalFeeVouchers; ?></p>
            </div>
            <div class="dashboard-item">
                <h5>Total Courses</h5>
                <p><?php echo $totalCourses; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
