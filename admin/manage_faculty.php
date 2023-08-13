<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to fetch all faculty members from the database
function getFaculty($conn) {
    $query = "SELECT f.*, s.name AS school_name FROM faculty f INNER JOIN schools s ON f.school_id = s.school_id";
    $result = mysqli_query($conn, $query);

    $faculty = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $faculty[] = $row;
        }
    } else {
        echo "Error fetching faculty members: " . mysqli_error($conn);
    }

    return $faculty;
}

// Fetch all faculty members
$faculty = getFaculty($conn);

// Handle delete faculty member request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_faculty'])) {
    $faculty_id = $_POST['faculty_id'];

    // Delete the faculty member from the database
    $delete_query = "DELETE FROM faculty WHERE faculty_id = '$faculty_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "Faculty member deleted successfully.";
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting faculty member: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Members</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php include('admin_navbar.php') ?>
    <div>
    <a href="add_faculty.php" class="button"> Add Faculty</a>

        <h2>Faculty Members</h2>
        <table>
            <thead>
                <tr>
                <th>Picture</th>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Contact Info</th>
                    <th>School</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faculty as $member) { ?>
                    <tr>
                    <td><img src="faculty_images/<?php echo $member['picture']; ?>" alt="Faculty Picture"></td>

                        <td><?php echo $member['name']; ?></td>
                        <td><?php echo $member['qualification']; ?></td>
                        <td><?php echo $member['experience']; ?></td>
                        <td><?php echo $member['contact_info']; ?></td>
                        <td><?php echo $member['school_name']; ?></td>
                        <td>
                            <a href="edit_faculty.php?faculty_id=<?php echo $member['faculty_id']; ?>">Edit</a>
                            <form method="POST" action="">
                                <input type="hidden" name="faculty_id" value="<?php echo $member['faculty_id']; ?>">
                                <button type="submit" name="delete_faculty">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>

