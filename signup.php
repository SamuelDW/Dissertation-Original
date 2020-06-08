<!DOCTYPE html>
<html>
<head>
	<!--StyleSheets-->
	<link rel = "stylesheet" href = "mystyle.css">
	<link rel = "stylesheet" href = "unsemantic.css">
	<link href="https://fonts.googleapis.com/css?family=ZCOOL+KuaiLe&display=swap" rel="stylesheet"> 
	<!--MetaData-->
	<title>Quizmaster | Signup</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>
<header>
<div class = "grid-container" onclick = "flicker()">
	<div class = "grid-50 tablet-grid-50 prefix-25 tablet-prefix-25" id = "title">
		<h1>Quizmaster</h1>
	</div>
</div>
</header>
<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$Username = $Pass = $ConfirmPass = $Email = "";
	$UserErr = $PassErr = $ConfirmErr = $EmailErr = "";
	$SignUpBool = true;
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(empty($_POST["username"])) {
			$UserErr = "Please enter a username";
			$SignUpBool = false;
		} else {
			$Username = $_POST["username"];
			if (!preg_match("/^[\w_+]*$/", $Username)) {
			$UserErr = "Only letters numbers and underscores allowed";
			$SignUpBool = false;
			} else {
				$Username = $_POST["username"];
			}
		}
		if(empty($_POST["password"])) {
			$PassErr = "Please enter a password";
			$SignUpBool = false;
		} else {
			$Pass = $_POST["password"];
			if(!preg_match("/^[\w-_+]*$/", $Pass)) {
				$PassErr = "Password is not suitable";
				$SignUpBool = false;
			} else {
				$Pass = $_POST["password"];
			}
		}
		if(empty($_POST["confirmpassword"])) {
			$ConfirmErr = "Please retype your password";
			$SignUpBool = false;
		} else {
			$ConfirmPass = $_POST["confirmpassword"];
			if(!$ConfirmPass = $Pass ) {
				$ConfirmErr = "Passwords do not match";
				$SignUpBool = false;
			} else {
				$Pass = password_hash($Pass, PASSWORD_BCRYPT);
			}
		}	
		if(empty($_POST["email"])) {
			$EmailErr = "Please enter a email";
			$SignUpBool = false;
		} else {
			$Email = $_POST["email"];
			if(!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
				$EmailErr = "Please enter a valid email";
				$SignUpBool = false;
			}
			else {
				$Email = $_POST["email"];
			}
		}
		// Adding the user to the database
		if($SignUpBool == true) {
			$server = 'sql.rde.hull.ac.uk';
			$connectionInfo = array("Database"=>"rde_556278");
			$conn = sqlsrv_connect($server, $connectionInfo);
			
			$AddUser = "INSERT INTO Users (Username, Pass, Email) VALUES (?,?,?)";
			$params = array($Username, $Pass, $Email);
			
			$results = sqlsrv_query($conn, $AddUser, $params, array("Scrollable" => "buffered"));
			if(!$results) {
				
			} else {
				header("Location: home.php");
			}
		}
	}
?>
<!-- Form To create Account -->
<div class = "grid-container">
	<form id = "createaccount" method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  class = "grid-60 prefix-20 tablet-grid-60 tablet-prefix-20 mobile-grid-100 grid-parent">
		<fieldset>
			<legend>User Details</legend>
			<br>Username:</br>
			<input type = "text" name = "username" value = "" id = "passinput" required><br><span id = "error"> * <?php echo $UserErr;?></span><br>
			Password:<br>
			<input type = "password" name = "password" value = "" id ="passinput"><br><span id = "error"> * <?php echo $PassErr;?></span><br><br>
			Confirm Password:<br>
			<input type = "password" name = "confirmpassword" value = "" id ="passinput"><br><span id = "error"> * <?php echo $ConfirmErr;?></span><br><br>
			Email:<br>
			<input type = "email" name = "email" required><br><span id = "error"> * <?php echo $EmailErr;?></span><br><br>
		</fieldset>
		<br>
		<fieldset>
			<legend>Agreements</legend>
			Cookie Policy:<br> 
			<input type = "checkbox" name = "cookieagree" required><br>
			By checking you agree to our <a href = "">cookie policy</a><br><br>
			Privacy Agreement:<br>
			<input type = "checkbox" name = "privacyagree" required><br>
			View our <a href = "">privacy policy </a><br><br>
		</fieldset>
		<br><br>
		<input type = "submit" value = "Create Account">
		<br><br>
	</form>
</div>

</body>
</html>