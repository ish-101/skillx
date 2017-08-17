<?php require("auth.php"); ?>

<?php
	$id = $_SESSION['id'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("SELECT points FROM users WHERE id=:id"); 
	$stmt->execute(array(':id' => $id));
	$user = $stmt->fetch();


	if (empty($_GET['p'])):
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="theme-color" content="#5E5DA9">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/buy.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	
	<?php require("topbar.php"); ?>
	
	<div class="main">
		<h2>Buy Points</h2>
		<a href="?p=500" class="offer">
			<div class="offer-dollar">
				<div class="offer-dollar-icon"><i class="material-icons">attach_money</i></div>
				<div class="offer-dollar-number">25</div>
			</div><div class="offer-point">
				<div class="offer-point-number">500</div>
				<div class="offer-point-icon"><i class="material-icons">star</i></div>
			</div>
		</a>
		<a href="?p=1000" class="offer">
			<div class="offer-dollar">
				<div class="offer-dollar-icon"><i class="material-icons">attach_money</i></div>
				<div class="offer-dollar-number">40</div>
			</div><div class="offer-point">
				<div class="offer-point-number">1000</div>
				<div class="offer-point-icon"><i class="material-icons">star</i></div>
			</div>
		</a>
		<a href="?p=4000" class="offer">
			<div class="offer-dollar">
				<div class="offer-dollar-icon"><i class="material-icons">attach_money</i></div>
				<div class="offer-dollar-number">99</div>
			</div><div class="offer-point">
				<div class="offer-point-number">4000</div>
				<div class="offer-point-icon"><i class="material-icons">star</i></div>
			</div>
		</a>
	</div>

</body>
</html>

<?php 

else:
	
	$points_new = $user['points'] + $_GET['p'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("UPDATE users SET points = :points WHERE id = :id");
	$stmt->execute(array(':points' => $points_new, ':id' => $_SESSION['id']));
	header("Location: /user.php");

endif;

?>