<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Management</title>
    <link rel="stylesheet" href="./css/style.css">


</head>
<body>
<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}


    include('admin_navbar.php'); // Modify this as per your admin navigation

    // Delete feedback entry if requested
    if (isset($_GET["delete_feedback_id"])) {
        $feedbackIdToDelete = $_GET["delete_feedback_id"];
        $deleteQuery = "DELETE FROM feedback WHERE feedback_id = '$feedbackIdToDelete'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "Feedback deleted successfully.";
        } else {
            echo "Error deleting feedback: " . mysqli_error($conn);
        }
    }

    // Fetch feedback entries from the database
    $feedbackQuery = "SELECT feedback.*, schools.name AS school_name, users.username FROM feedback
                      INNER JOIN schools ON feedback.school_id = schools.school_id
                      INNER JOIN users ON feedback.user_id = users.id
                      ORDER BY submission_date DESC";
    $feedbackResult = mysqli_query($conn, $feedbackQuery);
    ?>

    <div class="container">
        <h2>Admin Feedback Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>School Name</th>
                    <th>User Name</th>
                    <th>Feedback Text</th>
                    <th>Submission Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($feedbackResult)) {
                    echo '<tr>';
                    echo '<td>' . $row['feedback_id'] . '</td>';
                    echo '<td>' . $row['school_name'] . '</td>';
                    echo '<td>' . $row['username'] . '</td>';
                    echo '<td>' . $row['feedback_text'] . '</td>';
                    echo '<td>' . $row['submission_date'] . '</td>';
                    echo '<td><a href="?delete_feedback_id=' . $row['feedback_id'] . '">Delete</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
