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
	<title>Quizmaster | <?php echo $_SESSION["User"]; ?></title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body style = "width:100%">
<?php include 'header.php'; ?>
<!-- getting and displaying user details -->
<div class = "grid-container">
	<?php
	//Connecting to server
	$server = 'sql.rde.hull.ac.uk';
	$connectionInfo = array("Database"=>"rde_556278");
	$conn = sqlsrv_connect($server, $connectionInfo);
	//Getting User Details
	$describeQuery = "SELECT * FROM Users WHERE Username = '".$_SESSION['User']."';";
	$results = sqlsrv_query($conn, $describeQuery);
		while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
			echo "<div class = 'grid-80 prefix-10 tablet-grid-60 tablet-prefix-20 mobile-grid-90 mobile-prefix-5 grid-parent' id = 'statistics'>";
				echo "<h2>".$row['Username']."'s Stats and Details</h2>";
			echo "</div>";
			echo "<div class = 'grid-80 prefix-10 tablet-grid-60 tablet-prefix-20 mobile-grid-90  mobile-prefix-5 grid-parent' id = 'userinfo'>";
				echo "<div class = 'grid-33'>";
					echo "<h2>Username:</h2>";
					echo "<h3>".$row['Username']. "</h3>";
				echo "</div>";
				echo "<div class = 'grid-33'>";
					echo "<h2>Email:</h2>";
					echo "<h3>".$row['Email']. "</h3>";
				echo "</div>";
				echo "<div class = 'grid-33'>";
					echo "<h2>Wins:</h2>";
					echo "<h3>".$row['Wins']. "</h3>";
				echo "</div>";
				echo "<div class = 'grid-33'>";
					echo "<h2>Losses:</h2>";
					echo "<h3>".$row['Losses']. "</h3>";
				echo "</div>";
				echo "<div class = 'grid-33 '>";
					echo "<h2>Hosted:</h2>";
					echo "<h3>".$row['Hosted']. "</h3>";
				echo "</div>";
				echo "<div class = 'grid-33'>";
					echo "<h2>W/L:</h2>";
					echo "<h3>".round($row['Wins']/$row['Losses'], 3)."</h3>";
				echo "</div>";
			echo "</div>";
		}
		sqlsrv_close($conn);
?>
</div>

<!-- PHP for Leaderboards -->
<?php 
//Creating Table
echo "<div class = 'grid-container' id = 'leaderboard'>";
echo "<div class = 'grid-80 prefix-10 tablet-grid-60 tablet-prefix-20 mobile-grid-100'>";
echo "<table id = 'ranks'>";
echo "<tr><th>Position</th><th><h3>User</h3></th><th><h3>Wins</h3></th><th><h3>Losses</h3></th><th><h3>Win/Loss Ratio</h3></th></tr>";
//Connecting to server
	$server = 'sql.rde.hull.ac.uk';
	$connectionInfo = array("Database"=>"rde_556278");
	$conn = sqlsrv_connect($server, $connectionInfo);
	
	$rankQuery = "SELECT Username, Wins, Losses FROM Users
	ORDER BY Wins DESC";
	$results = sqlsrv_query($conn, $rankQuery);
	$i = 0;
	$x = 1;
	while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
		if($i < 10) {
		echo "<div class = 'grid-80 prefix-10 tablet-grid-60 tablet-prefix-20 mobile-grid-90'>";
		echo "<tr>";
		echo "<td><h3>".$x++."</h3></td>";
		echo "<td><h3>".$row['Username']."</h3></td>";
		echo "<td><h3 id = 'win'>".$row['Wins']."</h3></td>";
		echo "<td><h3 id = 'loss'>".$row['Losses']."</h3></td>";
		echo "<td><h3>".round($row['Wins']/$row['Losses'],3)."</h3></td>";
		echo "</tr>";
		echo "</div>";
		$i++;
		}
		if($i > 10) {
			break;
		}
	}
echo "</table>";
echo "</div>";
echo "</div>";
sqlsrv_close($conn);
?>

<!-- PHP FOR DELETE ACCOUNT -->
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST["delete"])) {
		//Connecting to server
	$server = 'sql.rde.hull.ac.uk';
	$connectionInfo = array("Database"=>"rde_556278");
	$conn = sqlsrv_connect($server, $connectionInfo);
	$DeleteQuery = "DELETE FROM Users WHERE Username = '".$_SESSION['User']."';";
	$results = sqlsrv_query($conn, $DeleteQuery);
	sqlsrv_close($conn);
	header("Location: http://app.rde.hull.ac.uk/556278/Dissertation/home.php");
	}
	else {
		
	}
}
?>
<!-- Delete Account -->
<div class = "grid-container">
	<form id = "delete_account" method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "grid-80 prefix-10 tablet-grid-60 tablet-prefix-20 grid-parent">
		<br>By deleting your account you lose all progress, data and such, do you wish to delete your account?<br><br>
		<input type = "checkbox" value = "deleteaccount" name = "delete">  Yes<br><br>
		By clicking delete, you will lose all access to this account<br><br>
		<input type = "submit" value = "Delete"><br><br>
	</form>
</div>
<?php include 'footer.php'; ?>
<!-- jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
<script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js">
</body>
</html>