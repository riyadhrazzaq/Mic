<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Mic | Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="stylesheet" type="text/css" href="assets/plugins/owl-carousel/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="assets/plugins/owl-carousel/owl.theme.css" />
    <link rel="stylesheet" type="text/css" href="assets/plugins/headereffects/css/component.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/headereffects/css/normalize.css" />
    <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"
        media="screen" />
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- END CORE CSS FRAMEWORK -->
    <!-- BEGIN CSS TEMPLATE -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/magic_space.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/main_login.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/util.css" rel="stylesheet" type="text/css" />
    <!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<body>
<?php

include('asset_php/sessionManager.php');
include('asset_php/connect.php');
//get error msg
$msg = "";
if(isset($_GET["error"])){
$error = $_GET["error"];
if($error=="mex"){
$msg .= "Email already exists";
}
elseif($error=="mempty"){
$msg .= "Email cannot be empty";
}
elseif($error=="nempty"){
$msg .= "Name cannot be empty";
}
elseif($error=="passmissmatch"){
$msg .= "Password doesn't match";
}

}

$name = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Validate email
if(empty(trim($_POST["email"]))){
$email_err = "Please enter an email.";
header("location: register.php?error=mempty");
} else{
// Prepare a select statement
$sql = "SELECT id FROM client WHERE email = ?";

if($stmt = mysqli_prepare($db, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "s", $param_email);

// Set parameters
$param_email = trim($_POST["email"]);

// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
/* store result */
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 1){
$email_err = "This email is already taken.";
header("location: register.php?error=mex");
} else{
$email = trim($_POST["email"]);
}
} else{
echo "Oops! Something went wrong. Please try again later.";
}
}

// Close statement
mysqli_stmt_close($stmt);
}

if(empty(trim($_POST["name"]))){
$name_err = "Please enter a name.";
header("location: register.php?error=nempty");
}else{
$name = trim($_POST["name"]);
}

// Validate password
if(empty(trim($_POST["password"]))){
$password_err = "Please enter a password.";
} elseif(strlen(trim($_POST["password"])) < 6){
$password_err = "Password must have atleast 6 characters.";
} else{
$password = trim($_POST["password"]);
}

// Validate confirm password
if(empty(trim($_POST["confirm_password"]))){
$confirm_password_err = "Please confirm password.";
} else{
$confirm_password = trim($_POST["confirm_password"]);
if(empty($password_err) && ($password != $confirm_password)){
$confirm_password_err = "Password did not match.";
header("location: register.php?error=passmissmatch");

}
}

// Check input errors before inserting in database

if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

// Prepare an insert statement
$sql = "INSERT INTO client (name,email, password) VALUES (?, ?, ?)";

if($stmt = mysqli_prepare($db, $sql)){
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "sss",$param_name, $param_email, $param_password);

// Set parameters
$param_name= $name;
$param_email = $email;
$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)){
// Redirect to login page
header("location: login.php");
} else{
header("location: login.php?error=Input invalid");
}
}

// Close statement
mysqli_stmt_close($stmt);
}


// Close connection
mysqli_close($db);
}
?>


<div role="navigation" class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="compressed">
                    <div class="navbar-header">
                        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                            <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                        </button>
                        <a href="#" class="navbar-brand compressed">
                        <img src="assets/img/logo_mic_sized.png" alt="" data-src="assets/img/logo_mic_sized.png" data-src-retina="assets/img/logo2x.png" width="97" height="50"></a>
                    </div>
                    <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="tour.php">Tour</a></li>
                                <li><a href="portfolio.php">Portfolio</a></li>
                                <li><a href="contact.php">Contact</a></li>
                                <li><a href="login.php">Login</a></li>
                            </ul>
                        </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
    </div>
    <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<span class="login100-form-title p-b-26">
						Just a few steps left...
					</span>
                    <div id = "warn" style="background-color:red;color:white;text-align:center">
                        <?php if(!empty($msg)){ echo $msg; } ?>
                    </div>
					<span class="login100-form-title p-b-48">
						<i class="zmdi zmdi-font"></i>
					</span>

                    <div class="wrap-input100" >
						<input class="input100" type="text" name="name">
						<span class="focus-input100" data-placeholder="Name"></span>
					</div>

					<div class="wrap-input100">
						<input class="input100" type="text" name="email">
						<span class="focus-input100" data-placeholder="Email"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="password">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="confirm_password">
						<span class="focus-input100" data-placeholder="Confirm Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Sign Up
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <script src="assets/js/main.js"></script>
    <script src="assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="assets/plugins/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
    <script src="assets/plugins/waypoints.min.js"></script>
    <script type="text/javascript" src="assets/js/core.js"></script>

    <!--===============================================================================================-->
	<script type="text/javascript" src="assets/plugins/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/plugins/animsition.min.js"></script>
	<script type="text/javascript" src="assets/js/main.js"></script>
    <!--===============================================================================================-->
</body>
</body>
</html>
