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
	<title>Quizmaster | Join</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>
<!-- Header -->
<?php include 'header.php'; ?>

<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	//Setting Variables
	$EnteredPass = $Teamname = "";
	$PassErr = $TeamnameErr = "";
	$JoinBool = true;
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(empty($_POST["pass"])) {
			$PassErr = "Please Enter a Password";
			$JoinBool = false;
		} else {
			$EnteredPass = $_POST["pass"];
			if(!preg_match("/^[\w-_+]*$/", $EnteredPass)) {
				$PassErr = "Please Enter Only Letters and Numbers";
				$JoinBool = false;
			} else {
				$EnteredPass = $_POST["pass"];
			}
		}
		if(empty($_POST["teamname"])) {
			$TeamnameErr = "Please enter a teamname";
			$JoinBool = false;
		} else {
			$Teamname = $_POST["teamname"];
			if(!preg_match("/^[\w-_+]*/", $Teamname)) {
				$TeamnameErr = "Please Enter Only Letters and Numbers";
				$JoinBool = false;
			} else {
				$Teamname = $_POST["teamname"];
			}
		}
		
		
		if($JoinBool == true) {
			$server = 'sql.rde.hull.ac.uk';
			$connectionInfo = array("Database"=>"rde_556278");
			$conn = sqlsrv_connect($server, $connectionInfo);
			
			$params = array($EnteredPass);
			$SearchQuery = "SELECT Quizname FROM ACTIVE_GAMES WHERE Pass = ? ";
			$results = sqlsrv_query($conn, $SearchQuery, $params, array("Scrollable" => "buffered"));
			if($results === false) {
				$PassErr = "Game Does Not Exist";
			} else {
				while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
					echo $row['Quizname'];
					$JoinGameQuery = "INSERT INTO ".$row['Quizname']."_Teams (TeamName, TeamPoints, Correct)
					VALUES (?, ?, ?)";
					$params = array($Teamname, 0, 0);
					$results = sqlsrv_query($conn, $JoinGameQuery, $params, array("Scrollable" => "buffered"));
					if($results === false) {
						echo "FAIL";
					} else {
						$_SESSION["JoinedGame"] = $row['Quizname'];
						header("Location: game.php");
					}
				}
			}
		}
	}
?>
<div class = "grid-container">
	<div class = "grid-60 prefix-20 tablet-grid-80 tablet-prefix-10 mobile-grid-90 mobile-prefix-5" id = "gamesearch">
		<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Teamname:<input type = "text" name = "teamname" id = "gamesearchbar"><span id = "error"> * <?php echo $TeamnameErr;?></span><br>
			Game Search:<input type -= "text" id = "gamesearchbar" name = "pass"><br><span id = "error"> * <?php echo $PassErr;?></span><br>
			<input type = "submit" value = "Enter">
		</form>
	</div>
</div>

<br><br>
<!-- Footer --> 
<?php include 'footer.php'; ?>
</body>
</html>