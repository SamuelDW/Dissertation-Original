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
	<title>Quizmaster | Game</title>
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
	//Arrays for questions and answers
	$questions = array();
	$answers = array();
	
	$server = 'sql.rde.hull.ac.uk';
	$connectionInfo = array("Database"=>"rde_556278");
	$conn = sqlsrv_connect($server, $connectionInfo);
	
	$GetGameQuery = "SELECT * FROM ".$_SESSION['JoinedGame']."";
	$results = sqlsrv_query($conn, $GetGameQuery);
	if(!$results) {
		echo "FAIL";
	} else {
		echo "Success<br>";
		$questionNumber = 1;
	
		while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
			echo "<div class = 'grid-container'>";
				// question holder
				echo "<div class = 'mobile-grid-90 mobile-prefix-5 ".$questionNumber." ' id = 'questionholder'>";
					echo "<h2>".$row['Question']."</h2>";
				echo "</div>";
				//question holder
				//Answer holder
				
					$answerplace = rand(0, 3);
					echo $answerplace;
				for($x = 0; $x <= 3; $x++) {
					if($x == $answerplace) {
						echo "<div class = 'mobile-grid-35 mobile-prefix-10 grid-parent' id = 'answercorrect' onclick = 'correct(this)'>";
							echo "<div class = 'mobile-grid-90 mobile-prefix-5' id = 'answerproper'>";
								echo "<p>".$row['QuestionAns']."</p>";
							echo "</div>";
						echo "</div>";
					} else {
						echo "<div class = 'mobile-grid-35 mobile-prefix-10 grid-parent' id = 'answerwrong' onclick = 'wrong(this)'>";
							echo "<div class = 'mobile-grid-90 mobile-prefix-5' id = 'answer'>";
								echo "<p>".$row['QuestionAns']."</p>";
							echo "</div>";
						echo "</div>";
					}
				}
				//Answer Holder
				echo "<br>";
				
				echo "<br>";
			
			echo "</div>";
			$questionNumber++;
		}
	}
	
	sqlsrv_close($conn);
?>

<div class = "grid-container">
	<div class = "grid-50 prefix-25 tablet-grid-50 tablet-prefix-25" id = "quitholder">
		<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="text-align:center;">
			<input type = "submit" value = "Quit" id = "quitquiz">
			<br><br>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>
<!-- Correct Script -->
<script>
function correct() {
	document.getElementById("answercorrect").style.backgroundColor = "green";
}
</script>
<!-- Wrong Script -->
<script>
function wrong() {
	document.getElementById("answerwrong").style.backgroundColor = "red";
}
</script>
</body>
</html>
