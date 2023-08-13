<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to fetch all courses from the database
function getCourses($conn) {
    $query = "SELECT c.*, s.name AS school_name, f.name AS faculty_name
              FROM course c
              INNER JOIN schools s ON c.school_id = s.school_id
              INNER JOIN faculty f ON c.faculty_id = f.faculty_id";
    $result = mysqli_query($conn, $query);

    $courses = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    } else {
        echo "Error fetching courses: " . mysqli_error($conn);
    }

    return $courses;
}

// Fetch all courses
$courses = getCourses($conn);

// Handle delete course request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];

    // Delete the course from the database
    $delete_query = "DELETE FROM course WHERE course_id = '$course_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "Course deleted successfully.";
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting course: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php include('admin_navbar.php') ?>

    <div>
    <a href="add_courses.php" class="button"> Add Courses</a>

        <h2>Course List</h2>
        <table>
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Subject Name</th>
                    <th>Faculty</th>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Publisher</th>
                    <th>Course Fee</th>
                    <th>Course Duration</th>
                    <th>School</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course) { ?>
                    <tr>
                        <td><?php echo $course['course_id']; ?></td>
                        <td><?php echo $course['subject_name']; ?></td>
                        <td><?php echo $course['faculty_name']; ?></td>
                        <td><?php echo $course['book_name']; ?></td>
                        <td><?php echo $course['author_name']; ?></td>
                        <td><?php echo $course['publisher']; ?></td>
                        <td><?php echo $course['course_fee']; ?></td>
                        <td><?php echo $course['course_duration']; ?></td>
                        <td><?php echo $course['school_name']; ?></td>
                        <td>
                            <a href="edit_course.php?course_id=<?php echo $course['course_id']; ?>">Edit</a>
                            <form method="POST" action="">
                                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                <button type="submit" name="delete_course">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>

