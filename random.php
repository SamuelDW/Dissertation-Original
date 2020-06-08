<?php
	session_start();
?>
<?php

//setting variables

$RoundNumbers = mt_rand(1, 10);
$NoQuestions = mt_rand(5, 20);
$Teams = mt_rand(5, 50);
$Creator = $_SESSION["User"];

$createQTable = "CREATE TABLE '$Creator' ( QuestionID INT NOT NULL, 
	RoundNumber INT NOT NULL ); ";
$CreateTeamTable = "CREATE TABLE '$Creator.Teams' (TeamNo INT NULL, TeamName VARCHAR(30) NULL UNIQUE, TeamScore INT NULL, );";
?>
<!DOCTYPE html>
<html>
<head>
	<!--StyleSheets-->
	<link rel = "stylesheet" href = "mystyle.css">
	<link rel = "stylesheet" href = "unsemantic.css">
	<link href="https://fonts.googleapis.com/css?family=ZCOOL+KuaiLe&display=swap" rel="stylesheet"> 
	<!--MetaData-->
	<title>Quizmaster | Random</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>
	<div class = "grid-container">
		<div class = "grid-90 prefix-5" id = "title">
			<h1>Here the Quiz that has been created will be displayed</h1>
		</div>
	</div>
	<div class = "clear"></div>
	<div class = "clear"></div>
	<!-- Stats about the quiz that has just been generated -->
	<div class = "grid-container">
		<div class = "grid-33 grid-parent">
			<div class = "grid-90 prefix-5" id = "quizinfo">
				<h2>Rounds <?php echo $RoundNumbers; ?></h2>
			</div>
		</div>
		<div class = "grid-33 grid-parent">
			<div class = "grid-90 prefix-5"  id = "quizinfo">
				<h2>Questions <?php echo $NoQuestions; ?></h2>
			</div>
		</div>
		<div class = "grid-33 grid-parent">
			<div class = "grid-90 prefix-5"  id = "quizinfo">
				<h2>Teams <?php echo $Teams; ?></h2>
			</div>
		</div>
	</div>
	
	<!-- PHP to update user profile if hosted -->
	<?php 
		$_SESSION["Hosted"] += 1
		//do stuff
	?>
	<!-- PHP if not hosted -->
	<?php
		//Connecting
		$server = 'sql.rde.hull.ac.uk';
		$connectionInfo = array("Database"=>"rde_556278");
		$conn = sqlsrv_connect($server, $connectionInfo);
		//Preparing Statements
		$DeleteTables = "DROP TABLE '$Creator';
		DROP TABLE '$Creator.Teams';";
		
	
	?>
</body>
</html>