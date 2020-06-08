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
	<title>Quizmaster | Create</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>

	<?php include 'header.php'; ?>

	<!-- PHP for form -->
	<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	//Setting Variables
	$subtopics = array();
	$Quizname = $TeamNumber = $RoundNumber = $QuestQuant = $QuestionType = $Password = "";
	$QuiznameErr = $TeamNumberErr = $RoundNumberErr = $QuestQuantErr = $QuestionTypeErr  = $PasswordErr= "";
	$CreateBool = true;
	
	//Error Checking Creation
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//Error Checking Quizname
		if(empty($_POST["quizname"])) {
			$QuiznameErr = "Please Enter a Quizname";
			$CreateBool = false;
		} else {
			$Quizname = $_POST["quizname"];
			if (!preg_match("/^[\w-_+]*$/", $Quizname)) {
				$QuiznameErr = "Only letters, numbers and hyphens allowed";
				$CreateBool = false;
			} else {
				$Quizname = $_POST["quizname"];
			}
		}
		//Error Checking Team Number
		if(empty($_POST["TeamNumber"])) {
			$TeamNumberErr = "Please enter a team number limit";
			$CreateBool = false;
		} elseif(!empty($_POST["TeamNumber"]) && isset($_POST["NoLimit"])) {
			$TeamNumberErr = "No Limit can not be selected if Number is selected";
			$CreateBool = false;
		} elseif(empty($_POST["TeamNumber"]) && isset($_POST["NoLimit"])) {
			$TeamNumber = 10000;
		} elseif(!empty($_POST["TeamNumber"]) && !isset($_POST["NoLimit"])) {
			$TeamNumber = $_POST["TeamNumber"];
		}
		//Error Checking Number of Rounds
		if(empty($_POST["quantityRound"])) {
			$RoundNumberErr = "Please enter an amount for rounds";
			$CreateBool = false;
		} else {
			$RoundNumber = $_POST["quantityRound"];
		}
		//Error Checking Number of Questions
		if(empty($_POST["quantityQuest"])) {
			$QuestQuantErr = "Please enter a number of questions";
			$CreateBool = false;
		} else {
			$QuestQuant = $_POST["quantityQuest"];
		}
		//Error Checking Question Type
		if(!isset($_POST["AllQuest"])) {
			$QuestionTypeErr = "Please select the type of questions you would like";
			$CreateBool = false;
		}
		if(isset($_POST["AllQuest"]) && isset($_POST["Quest"])) {
			$QuestionTypeErr = "Please select either all or some of the options";
			$CreateBool = false;
		} else {
			$QuestionType = "all";
		}
		//Error Checking Selected Topics
		if(isset($_POST["acs"])) {
			print_r($_POST["acs"]);
			foreach($_POST["acs"] as $subtopic) {
				array_push($subtopics, $subtopic);
			}
		} elseif(!isset($_POST["acs"])) {
			$CategoryErr = "Please select topics for your quiz";
			$CreateBool = false;
		}
		foreach($subtopics as $topic) {
			echo $topic . " ";
		}
		//Error Checking Password
		if(empty($_POST["password"])) {
			$PasswordErr = "Please enter a password";
			$CreateBool = false;
		} else {
			$Password = $_POST["password"];
			if(!preg_match("/^[\w-_+]*$/", $Password)) {
				$PasswordErr = "Only Letters and numbers allowed";
				$CreateBool = false;
			} else {
				$Password = $_POST["password"];
				$_SESSION["QuizPass"] = $Password;
			}
		}
		//Creating Queries and Tables if no errors present
		if($CreateBool == true) {
			$user = $_SESSION['User'];
			//Quiz Table Create Query
			$QuizTableCreate = "CREATE TABLE ".$Quizname." (
			RoundNumber INT NULL,
			RoundTopic VARCHAR(30) NULL,
			Question TEXT NULL,
			QuestionAns TEXT NULL);";
			//Session Variable for Quizname
			$_SESSION["Quizname"] = $Quizname;
			
			//Team Table Create Query 
			$TeamTableCreate = "CREATE TABLE ".$Quizname."_Teams (
			TeamName VARCHAR(30) NULL,
			TeamPoints INT NULL,
			Correct INT NULL);";
			//Session Variable for Teams
			$_SESSION["TeamTable"] = $Quizname."_Teams";
			
			// Connecting to the Server
			$server = 'sql.rde.hull.ac.uk';
			$connectionInfo = array("Database"=>"rde_556278");
			$conn = sqlsrv_connect($server, $connectionInfo);
			//Sending the Queries
			// QUIZTABLE CREATE
			$result = sqlsrv_query($conn, $QuizTableCreate);
			if(!$result) {
				echo "FAIL";
				echo $QuizTableCreate;
			} else {
				//Inserting values into table for quiz questions
				sqlsrv_free_stmt($result);
				$roundnumber = 1;
				foreach($subtopics as $topic) {
					$SelectInsert = "INSERT TOP (".$QuestQuant.")
					INTO ".$Quizname." (Question, QuestionAns, RoundTopic)
					SELECT QuestionText, QuestionAns, SubTopic FROM QuestionsAll
					WHERE SubTopic = '".$topic."' 
					ORDER BY NEWID();
					UPDATE ".$Quizname."
					SET RoundNumber = ".$roundnumber."
					WHERE RoundTopic = '".$topic."';";
					$resultinsert = sqlsrv_query($conn, $SelectInsert);
					if(!$resultinsert) {
						echo "FAIL <br>";
						echo $SelectInsert;
					}
					$roundnumber++;
				}
			}
			//Creating Team Tables In Database
			$result = sqlsrv_query($conn, $TeamTableCreate);
			if(!$result) {
				echo "FAIL";
				echo $TeamTableCreate;
			}
			//Adding Information To ACTIVE_GAMES
			$ActiveGame = "INSERT INTO ACTIVE_GAMES (Quizname, Pass, Host)
			VALUES ('".$Quizname."', '".$Password."', '".$_SESSION['User']."')";
			$result = sqlsrv_query($conn, $ActiveGame);
			if(!$result) {
				echo "FAIL";
				echo $ActiveGame;
			}
			//Redirecting to the Hosting page
			header("Location: host.php");
		}
	}
	?>
	
	<!-- form for creating quiz -->
	<div class = "grid-container">
	<form id = "create" method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "grid-60 prefix-20 tablet-grid-60 tablet-prefix-20 mobile-grid-100">
		<fieldset>
			<legend>Quiz Options</legend>
				Quiz Name:<br>
				<input type = "text" name = "quizname" required><br><span id = "error"> * <?php echo $QuiznameErr;?></span><br>
				Number of Teams:<br>
				<input type = "number" min = "1" max = "50" name = "TeamNumber"><br>
				<input type = "checkbox" name = "NoLimit" Value = "TeamLimit"> No Limit<br><span id = "error"> * <?php echo $TeamNumberErr;?></span><br> 
				Number of Rounds:<br>
				<input type = "number" min = "1" max = "20" id = "button" name = "quantityRound" required><br><span id = "error"> * <?php echo $RoundNumberErr;?></span><br>
				Number of Questions:<br>
				<input type = "number" min = "1" max = "50" id = "button" name = "quantityQuest" required><br><span id = "error"> * <?php echo $QuestQuantErr;?></span><br>
				Question Type:<br>
				<input type = "checkbox" name = "AllQuest" Value = "All" checked> All
				<input type = "checkbox" name = "Quest" Value = "Music"> Music
				<input type = "checkbox" name = "Quest" Value = "Pic"> Picture
				<input type = "checkbox" name = "Quest" Value = "Year"> Year
				<input type = "checkbox" name = "Quest" Value = "Generic"> Generic
				<br><span id = "error"> * <?php echo $QuestionTypeErr;?></span><br>
		</fieldset>
		<br>
		Please Select the Categories you would like to include below<br><br>
		<input type = "button" value = "Unselect All"  onclick = "UnselectAll()">
		<br><br>
	
	</div>
	
	<?php 
	//variables 
		//Connecting to server
		$server = 'sql.rde.hull.ac.uk';
		$connectionInfo = array("Database"=>"rde_556278");
		$conn = sqlsrv_connect($server, $connectionInfo);
		
		echo '<div class = "grid-container" id = "TopicsArea">';
		//Setting up Query
		$describeQuery = "SELECT * FROM TopicsAll ORDER BY MainTopic ASC";
		$results = sqlsrv_query($conn, $describeQuery);
		while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
			echo "<div class = 'grid-10 tablet-grid-20 tablet-prefix-5 prefix-5 grid-parent' id = 'categorycard' name = ".$row["MainTopic"].">";
			echo "<div class = 'grid-100' id = 'cardtitle'>";
			echo "<h4 id = 'titletext'>" . $row['SubTopic'] . "</h4>";
			echo "<p>" .$row['MainTopic']. "</p>";
			echo "</div>";
			echo "<div class = 'grid-100' id = 'cardimage'>";
			echo "<img src =".$row['ImageLocation']." id = 'image'>";
			echo "</div>";
			echo "<div class = 'grid-100' id = 'checked'>";
			echo "<input type = 'checkbox' value = ".$row['SubTopic']." name = 'acs[]' class='checkbox'>";
			echo "</div>";
			echo "</div>";
		}
		echo '</div>';
		
		sqlsrv_close($conn);
	?>
	<div class = "grid-container">
		<div class = "grid-30 tablet-grid-30 prefix-40 tablet-prefix-35" id = "create">
			Create Password:<br>
			<input type = "text" name = "password" required><span id = "error"> * <?php echo $PasswordErr;?></span><br>
			<input type = "button" name = "saveQuiz" value = "Save" disabled>
			<input type = "submit" name = "createquiz" value = "Create">
		</div>
	</div>
	</form>
	<br><br>
	
<?php include 'footer.php'; ?>
<!-- Scripts -->
<script>
function UnselectAll() {
	var items = document.getElementsByName("acs[]");
	for(var i =0; i < items.length; i++) {
		if(items[i].type == 'checkbox')
			items[i].checked=false;
	}
}
</script>

<!-- jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
<script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js">
</body>
</html>