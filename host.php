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
	<title>Quizmaster | Hosting</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>
<!-- Header -->
<?php include 'header.php'; ?>

<div class = "grid-container">
<!-- showing the teams to the host -->
	<div class = "grid-35 tablet-grid-35 prefix-10 tablet-prefix-10 grid-parent" id = "teamcolumn">
	<?php
		//Creating Tables
		echo "<div class = 'grid-90 tablet-grid-90 prefix-5 tablet-prefix-5 grid-parent'>";
			echo "<table id = 'teamshost'>";
				echo "<tr><th>Team Name</th><th>Team Points</th><th>Correct</th><tr>";
					//Connecting to server
					$server = 'sql.rde.hull.ac.uk';
					$connectionInfo = array("Database"=>"rde_556278");
					$conn = sqlsrv_connect($server, $connectionInfo);
					
					$TeamQuery = "SELECT * FROM ".$_SESSION['TeamTable']."";
					$results = sqlsrv_query($conn, $TeamQuery);
					while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
						echo "<div class = 'grid-80 tablet-grid-90 tablet-prefix-5 prefix-10'>";
						echo "<tr>";
						echo "<td><h3>".$row['TeamName']."</h3></td>";
						echo "<td><h3>".$row['TeamPoints']."</h3></td>";
						echo "<td><h3>".$row['Correct']."</h3></td>";
						echo "</tr>";
						echo "</div>";
					}
			echo "</table>";
		echo "</div>";
	?>
	</div>
	<!-- Options to the quiz host to modify quiz -->
	<div class = "grid-35 tablet-grid-35 prefix-10 tablet-suffix-10 tablet-prefix-10 grid-parent" id = "quizoptionscolumn">
		<div class = "grid-100">
			<h2 id = "modifiers">Modifiers</h2>
		</div>
		<div class = "grid-90 prefix-5">
			<form>
				<span>Correct Points </span><input type = "number"><br><br>
				<span>Incorrect Points </span><input type = "number"><br><br>
				<span>First Correct</span><input type = "number"><br><br>
				<input type = "submit" value = "Change"><br><br>
			</form>
		</div>
	</div>
	<!-- Displaying the questions to the host -->
	<div class = "grid-90 tablet-grid-90 prefix-5 tablet-prefix-5 " id = "quizcolumn">
	<?php
		echo "<div class = 'grid-90 tablet-grid-90 prefix-5 tablet-prefix-5 grid-parent'>";
			echo "<table id = 'quizquestions'>";
				echo "<tr><th>Round Number</th><th>Question</th><th>Question Ans</th></tr>";
				
				$QuizQuery = "SELECT * FROM ".$_SESSION['Quizname']."";
				$results = sqlsrv_query($conn, $QuizQuery);
				while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
					echo "<div class = 'grid-80 tablet-grid-90 tablet-prefix-5 prefix-10'>";
					echo "<tr>";
					echo "<td>".$row['RoundNumber']."</td>";
					echo "<td>".$row['Question']."</td>";
					echo "<td>".$row['QuestionAns']."</td>";
					echo "</tr>";
					echo "</div>";
				}
			echo "</table>";
		echo "</div>";
	?>
	</div>
</div>
<?php 
if($_SERVER["REQUEST_METHOD"] == "POST") {
	//Deleting Tables that have been created Query
	$DeleteCreatedTables = "DROP TABLE ".$_SESSION['Quizname'].";
	DROP TABLE ".$_SESSION['TeamTable'].";";

	//Connecting to Server
	$server = 'sql.rde.hull.ac.uk';
	$connectionInfo = array("Database"=>"rde_556278");
	$conn = sqlsrv_connect($server, $connectionInfo);

	$results = sqlsrv_query($conn, $DeleteCreatedTables);
	if(!$results) {
		echo "Fail";
		echo $DeleteCreatedTables;
	} else {
		$DropGame = "DELETE FROM ACTIVE_GAMES WHERE Quizname = '".$_SESSION['Quizname']."' AND Host = '".$_SESSION['User']."'";#
		$results = sqlsrv_query($conn, $DropGame);
		header("Location: menu.php");
	}
}
?>

<div class = "grid-container" id = "delete-quiz">
	<div class = "grid-50 prefix-25">
		<form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input id = "deletequizbutt" type = "submit" value = "Delete Quiz">
		</form>
	</div>
</div>


<!-- Footer --> 
<?php include 'footer.php'; ?>
</body>
</html>