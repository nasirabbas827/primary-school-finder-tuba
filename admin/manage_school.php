<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to fetch all schools from the database
function getSchools($conn) {
    $query = "SELECT schools.school_id, schools.name, schools.picture, schools.contact_info, schools.address, schools.fee, schools.uniform, cities.name AS city_name FROM schools INNER JOIN cities ON schools.city_id = cities.id";
    $result = mysqli_query($conn, $query);

    $schools = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $schools[] = $row;
        }
    } else {
        echo "Error fetching schools: " . mysqli_error($conn);
    }

    return $schools;
}

// Fetch all schools
$schools = getSchools($conn);

// Handle delete school request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_school'])) {
    $school_id = $_POST['school_id'];

    // Delete the school from the database
    $delete_query = "DELETE FROM schools WHERE school_id = '$school_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "School deleted successfully.";
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting school: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schools</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('admin_navbar.php') ?>

    <div>
        <a href="add_school.php" class="button"> Add Schools</a>
        <h2>Manage Schools</h2>
        
        <!-- Search Form -->
        <form method="GET" action="">
            <label for="search_id">Search by School ID:</label>
            <input type="text" name="search_id" id="search_id">
            <button type="submit" class="button">Search</button>
        </form>
        <?php
        $searchResults = array();
        if (isset($_GET['search_id']) && $_GET['search_id'] != '') {
            foreach ($schools as $school) {
                if ($school['school_id'] == $_GET['search_id']) {
                    $searchResults[] = $school;
                }
            }
            if (empty($searchResults)) {
                echo "<p>No search results found.</p>";
            }
        }
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>School ID</th>
                    <th>Name</th>
                    <th>Picture</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>Fee</th>
                    <th>Uniform</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($schools as $school) {
                    if (isset($_GET['search_id']) && $_GET['search_id'] != '' && $school['school_id'] != $_GET['search_id']) {
                        continue; // Skip this iteration if search does not match
                    }
                ?>
                    <tr>
                        <td><?php echo $school['school_id']; ?></td>
                        <td><?php echo $school['name']; ?></td>
                        <td><img src="school_images/<?php echo $school['picture']; ?>" alt="School Picture"></td>
                        <td><?php echo $school['contact_info']; ?></td>
                        <td><?php echo $school['address']; ?></td>
                        <td><?php echo $school['fee']; ?></td>
                        <td><?php echo $school['uniform']; ?></td>
                        <td><?php echo $school['city_name']; ?></td>
                        <td>
                            <a href="edit_school.php?school_id=<?php echo $school['school_id']; ?>">Edit</a>
                            <form method="POST" action="">
                                <input type="hidden" name="school_id" value="<?php echo $school['school_id']; ?>">
                                <button type="submit" name="delete_school">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

