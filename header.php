<header id = "header">
	<div class = "grid-container">
		<a href = "menu.php">
			<div class = "grid-33 tablet-grid-33 mobile-grid-50" id = "header-title">
				<h1>Return</h1>
			</div>
		</a>
		<div class = "grid-33 tablet-grid-33 hide-on-mobile" id = "header-title">
			<h1><?php echo $_SESSION['User']; ?></h1>
		</div>
		<a href = "logout.php">
		<div class = "grid-33 tablet-grid-33 hide-on-mobile" id = "header-title">
			<h1><?php echo "Logout"?></h1>
		</div>
		</a>
	</div>
</header>