<?php
include('config.php');
$_SESSION['adminlogin']=false;
if($conn){
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $uemail=$_POST['uemail'];
        $upassword=$_POST['upassword'];
        $admin_login_query="SELECT * FROM `admin` WHERE email = '$uemail' AND password = "YOUR_OWN_API_KEY"";
        $admin_run_login=mysqli_query($conn,$admin_login_query);
        $row_run=mysqli_num_rows($admin_run_login);
        if($row_run==1){
            $_SESSION['uemail']=$uemail;
            $_SESSION['adminlogin']=true;
            header('location:admin_dashboard.php');
        }else {
            echo '<div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">Wrong
  <strong> Username or Password!</strong> Try Again!!<button type = "button" class="btn-close" data-bs-dismiss = "alert" aria-label = "Close" ></button >
</div > ';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="container">
        <div>
            <form method="post" autocomplete="off">
                <h5>Admin Login</h5>
                <label for="uemail">Username</label>
                <input type="text" name="uemail" id="uemail" required placeholder="Username">
                <label for="upassword">Password</label>
                <input type="password" name="upassword" id="upassword" required placeholder="Password">
                <div>
                   <input type="submit" value="Login"> <br> <br>
                   <p><a href="../index.php" >Back To Home</a></p>
                </div>
            </form>
        </div>
    </div>


</body>
</html>


