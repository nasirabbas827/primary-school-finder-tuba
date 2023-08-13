<?php
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: admin_login.php');
    exit();
}

// Function to delete a city from the database
function deleteCity($conn, $cityId) {
    $query = "DELETE FROM cities WHERE id = '$cityId'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Function to update a city in the database
function updateCity($conn, $cityId, $newName) {
    $query = "UPDATE cities SET name = '$newName' WHERE id = '$cityId'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {
    // Editing city
    $cityId = $_GET['edit'];
    $editCity = true;
    $cityToUpdate = mysqli_query($conn, "SELECT * FROM cities WHERE id='$cityId'");
    $row = mysqli_fetch_assoc($cityToUpdate);
    $cityName = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['add'])) {
        // Adding a new city
        $city = $_POST['city'];
        $city = trim($city); // Remove leading/trailing whitespaces
        $city = htmlspecialchars($city); // Convert special characters to HTML entities

        // Insert the city into the database
        $query = "INSERT INTO cities (name) VALUES ('$city')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "City added successfully.";
        } else {
            echo "Error adding city: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['update'])) {
        // Updating an existing city
        $cityId = $_POST['city_id'];
        $newCityName = $_POST['new_city_name'];

        $updated = updateCity($conn, $cityId, $newCityName);
        if ($updated) {
            echo "City updated successfully.";
        } else {
            echo "Error updating city: " . mysqli_error($conn);
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    // Deleting city
    $cityId = $_GET['delete'];
    $deleted = deleteCity($conn, $cityId);
    if ($deleted) {
        echo "City deleted successfully.";
    } else {
        echo "Error deleting city: " . mysqli_error($conn);
    }
}

// Retrieve all cities from the database
$query = "SELECT * FROM cities";
$result = mysqli_query($conn, $query);
$cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management</title>
    <link rel="stylesheet" href="./css/style.css">
    
</head>

<body>
<?php include('admin_navbar.php') ?>

    <div>
        <?php if (isset($editCity) && $editCity) : ?>
            <h2>Edit City</h2>
            <form method="POST" action="">
                <input type="hidden" name="city_id" value="<?php echo $cityId; ?>">
                <div>
                    <label for="new_city_name">New City Name:</label>
                    <input type="text" name="new_city_name" id="new_city_name" value="<?php echo $cityName; ?>" required>
                </div>
                <button type="submit" name="update">Update City</button>
            </form>
        <?php else : ?>
            <h2>Add City</h2>
            <form method="POST" action="">
                <div>
                    <label for="city">City:</label>
                    <input type="text" name="city" id="city" required>
                </div>
                <button type="submit" name="add">Add City</button>
            </form>
        <?php endif; ?>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>City ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?php echo $city['id']; ?></td>
                    <td><?php echo $city['name']; ?></td>
                    <td>
                    <a href="?edit=<?php echo $city['id']; ?>">Edit</a>

                        <a href="?delete=<?php echo $city['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
