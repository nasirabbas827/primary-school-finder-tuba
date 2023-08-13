<?php
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Fetch user data from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data: " . mysqli_error($conn);
}

// Define variables and initialize with user data
$username = $user['username'];
$email = $user['email'];
$phone = $user['phone'];

// Initialize error variables
$username_err = $email_err = $phone_err = $password_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate password
    if (!empty(trim($_POST["password"]))) {
        $password = trim($_POST["password"]);
        if (strlen($password) < 6) {
            $password_err = "Password must be at least 6 characters.";
        }
    }

    // Update profile if there are no errors
    if (empty($username_err) && empty($email_err) && empty($phone_err) && empty($password_err)) {

        // Prepare update statement
        $updateQuery = "UPDATE users SET username = ?, email = ?, phone = ?";
        $updateParams = [$username, $email, $phone];
        if (!empty($password)) {
            $updateQuery .= ", password = ?";
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $updateParams[] = $hashed_password;
        }
        $updateQuery .= " WHERE id = ?";
        $updateParams[] = $user_id;

        // Execute update statement
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssi", ...$updateParams);
        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Profile updated successfully.";
        } else {
            $errorMessage = "Error updating profile: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include 'navbar.php' ?>

    <div class="container">
        <h5>Update Profile</h5>
        <?php if (isset($successMessage)) { ?>
            <div>
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <?php if (isset($errorMessage)) { ?>
            <div>
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div>
            <div>
                <label>Phone</label>
                <input type="number" name="phone" value="<?php echo $phone; ?>">
                <span><?php echo $phone_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Update">
            </div>
        </form>
    </div>
</body>

</html>
