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
	<title>Quizmaster | Menu</title>
	<meta charset = "UTF-8">
	<meta name = "description" content = "Quiz Games">
	<meta name = "author" content = "Samuel Durrant-Walker">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
</head>
<body>

<!-- Title -->
<?php include 'header.php';?>
<!-- menu -->
<div class = "grid-container">
	<!-- Cards -->
	<a href = "join.php">
		<div class = "grid-30 tablet-grid-35 mobile-grid-80 prefix-10 tablet-prefix-10 mobile-prefix-10 grid-parent" id = "card-menu">
			<div class = "grid-90 prefix-5" id = "card-menu-title">
				<h3>Join Game</h3>
			</div>
			<div class = "grid-90 prefix-5 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
				<img id = "card-image" src = "game.png"><br><br>
			</div>
		</div>
	</a>
	
	<a href = "create.php">
		<div class = "grid-30 tablet-grid-35 mobile-grid-80 prefix-10 tablet-prefix-10 mobile-prefix-10 grid-parent hide-on-mobile" id = "card-menu">
			<div class = "grid-90 prefix-5" id = "card-menu-title">
				<h3>Create Game</h3>
			</div>
			<div class = "grid-90 prefix-5 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
				<img id = "card-image" src = "create.jpg"><br><br>
			</div>
		</div>
	</a>
	
	<a href = "profile.php">
		<div class = "grid-30 tablet-grid-35 mobile-grid-80 prefix-10 tablet-prefix-10 mobile-prefix-10 grid-parent" id = "card-menu">
			<div class = "grid-90 prefix-5" id = "card-menu-title">
				<h3>My Profile</h3>
			</div>
			<div class = "grid-90 prefix-5 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
				<img id = "card-image" src = "profile.jpg"><br><br>
			</div>
		</div>
	</a>
	
	<a href = "">
		<div class = "grid-30 tablet-grid-35 mobile-grid-80 prefix-10 tablet-prefix-10 mobile-prefix-10 grid-parent hide-on-mobile" id = "card-menu">
			<div class = "grid-90 prefix-5" id = "card-menu-title">
				<h3>Create Random Game</h3>
			</div>
			<div class = "grid-90 prefix-5 tablet-grid-90 tablet-prefix-5 mobile-grid-90 mobile-prefix-5">
				<img id = "card-image" src = "random.jpg"><br><br>
			</div>
		</div>
	</a>
</div>
<br><br><br><br>
<?php include 'footer.php';?>

</body>
</html>