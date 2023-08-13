<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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

include('navbar.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $schoolId = $_POST["school"];
    $feedback = $_POST["feedback"];

    // Insert the feedback into the database
    $insertQuery = "INSERT INTO feedback (school_id, user_id, feedback_text) VALUES ('$schoolId', '$user_id', '$feedback')";
    if (mysqli_query($conn, $insertQuery)) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error submitting feedback: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <h5>Provide Feedback</h5>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="school">Select School:</label>
            <select id="school" name="school">
                <option value="">-- Select School --</option>
                <?php
                // Fetch school names from the database and populate the options
                $query = "SELECT * FROM schools";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['school_id'] . '">' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="feedback">Your Feedback:</label>
            <textarea id="feedback" name="feedback" rows="4" cols="50" required></textarea>
        </div>
        <button type="submit">Submit Feedback</button>
    </form>
</div>
</body>
</html>
