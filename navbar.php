<div class="navbar">
    <ul>
        <?php
        // Check if user is logged in
        if (isset($_SESSION["id"])) {
            echo '<li><a href="home.php">Home</a></li>';
            echo '<li><a href="update_profile.php">Update Profile</a></li>';
            echo '<li><a href="form_status.php">Form Status</a></li>';
            echo '<li><a href="upload_voucher.php">Fee Voucher</a></li>';
            echo '<li><a href="feedback.php">Feedback</a></li>';
            echo '<li><a href="logout.php">Logout</a></li>';
            
        } else {
            echo '<li><a href="index.php">Home</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
            echo '<li><a href="login.php">Login</a></li>';
            echo '<li><a href="./admin/admin_login.php">Admin Login</a></li>';
        }
        ?>
    </ul>
</div>
