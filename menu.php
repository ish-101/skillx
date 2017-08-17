<?php require("auth.php"); ?>

<?php
	$id = $_SESSION['id'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("SELECT uname, name FROM users WHERE id=:id"); 
	$stmt->execute(array(':id' => $id));
	$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="theme-color" content="#5E5DA9">
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/menu.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

	<div class="main">
		<h1>Skill<span>x</span></h1>
		<h2>Menu</h2>
		<nav>
			<a href="/categories.php">Watch Tutorial</a>
			<a href="/upload.php">Upload Tutorial</a>
			<a href="/user.php">User Account</a>
		</nav>
	</div>

	<a id="logout" href="/logout.php">Logout</a>

</body>
</html>