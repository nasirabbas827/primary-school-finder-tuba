<?php
include('config.php');

// Check if the user is already logged in
if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
  header("location: home.php"); // Redirect to home page if already logged in
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>

<?php
include('navbar.php');

?>
    <div class="image">
        <h1>Welcome to Online School Finding </h1>
    </div>
    <div class="body-content">
        
        <h2>About Us</h2>
        <p>At Online School Finding, we are dedicated to connecting students with exceptional educational opportunities, providing a seamless platform to discover and enroll in the best online schools tailored to their unique learning needs.</p>

        <h2>Our Teachers</h2>

        <div class="products">

            <div class="product">
                <img height="300px" src="./images/teacher2.jpg" alt="teacher1"  >
                <h3>Angel Gardner</h3>
                <p>Science Teacher</p>
            </div>

            <div class="product">
                <img height="300px" src="./images/teacher1.jpg" alt="teacher2"  >
                <h3>Jane Smith</h3>
                <p>Math Teacher</p>
            </div>

        </div>


    </div>
    <div class="footer">
        <h3>Copyright @ 2023 By Online School Finding Application</h3>
    </div>
</body>

</html>