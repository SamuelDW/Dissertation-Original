<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<!--StyleSheets-->
	<link rel = "stylesheet" href = "mystyle.css">
	<link rel = "stylesheet" href = "unsemantic.css">
	<link href="https://fonts.googleapis.com/css?family=ZCOOL+KuaiLe&display=swap" rel="stylesheet"> 
	<!--MetaData-->
	<title>Quizmaster</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body id = "homebody">
<!-- Checking if cookies exist -->

<!-- TITLE -->
<div class = "grid-container" onclick = "flicker()">
	<div class = "grid-50 tablet-grid-50  mobile-grid-60 prefix-25 tablet-prefix-25 mobile-prefix-20" id = "title">
		<h1>Quizmaster</h1>
	</div>
</div>
<br><br>
<!--PHP for Login Form -->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Defining variables
$usernameErr = $passErr = "";
$username = $password = "";

$loginBool = true;

// Error check the username
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"])) {
		$usernameErr = "Please enter a Username";
		$loginBool = false;
	} else {
		$username = $_POST["username"];
		if (!preg_match("/^[\w-_+]*$/", $username)) {
			$usernameErr = "Only letters numbers and hyphens allowed";
			$loginBool = false;
		}
		elseif (strlen($username) > 15 OR strlen($username) < 3) {
			$usernameErr = "Username is incorrect length";
			$loginBool = false;
		}
		//Setting session variable
		else {
			$_SESSION["User"] = $username;
		}
		
	}
	//Error checking the password
		if(empty($_POST["password"])) {
			$passErr = "Please Enter a password";
			$loginBool = false;
		} 
		else {
			
			//Check if password has correct characters
			if (!preg_match("/^[\w-_+]*$/", $password)) {
				$passErr = "Password is not in correct format" ;
				$loginBool = false;
			} else {
				$password = $_POST["password"];
			}
		}
		 //if no errors present, connect to and check user
		if($loginBool == true) {
			$server = 'sql.rde.hull.ac.uk';
			$connectionInfo = array("Database"=>"rde_556278");
			$conn = sqlsrv_connect($server, $connectionInfo);
			$SelectQuery = "SELECT Username, Pass FROM Users WHERE Username = ?";
		
			//Initialize params and prepare statement
			$params = array($username);
			$results = sqlsrv_query($conn, $SelectQuery, $params, array("Scrollable" => "buffered"));
			if($results === false) {
				die (print_r(sqlsrv_errors(), true));
			}
			else {
				while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
					$user = $row['Username'];
					$PassFromDatabase = $row['Pass'];
					echo $user . "<br><br>";
					echo $PassFromDatabase . "<br><br>";

					$rowCount = sqlsrv_num_rows($results);
					if ($rowCount == 1) {
						$hashedpassword = password_hash($password, PASSWORD_BCRYPT);
						$verified = password_verify($password, $hashedpassword);
						if($verified == true) {
							header("Location: http://app.rde.hull.ac.uk/556278/Dissertation/menu.php");
						}
					}
				}
			}
		}
}
?>
<!-- Login Form -->
<div class = "grid-container">

	<form id = "login" method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  class = "grid-60 prefix-20 tablet-grid-60 tablet-prefix-20 mobile-grid-100">
		<br>Username: <br>
		<input type = "text" name = "username" value = "<?php echo $username; ?>"  id = "passinput"><br><span id = "error"> * <?php echo $usernameErr;?></span><br>
		Password: <br>
		<input type = "password" name = "password" value = "" id ="passinput"><br><span id = "error"> * <?php echo $passErr;?></span><br><br>
		<input type = "submit" name = "submit" value = "Login" id = "submitbtn" ><br><br>
		Don't have an account? <a href = "signup.php">Sign up here</a><br><br>
	</form>
</div>
<!-- Scripts -->

</script>
</body>
</html>