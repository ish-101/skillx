<?php require("auth.php"); ?>

<?php
	$id = $_SESSION['id'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("SELECT uname, name, certify, points FROM users WHERE id=:id"); 
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
	<link rel="stylesheet" type="text/css" href="/css/user.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	
	<?php require("topbar.php"); ?>
	
	<div class="main">
		<img src="/users/dp/<?php echo $user["uname"] ?>.jpg" id="photo">
		<h2 id="name"><?php echo $user['name']; ?></h2>
		<?php if ($user['certify']): ?>
			<div id="certify">Certified Uploader<i class="material-icons">verified_user</i></div>
		<?php endif; ?>
		<div id="points"><i id="points-icon" class="material-icons">stars</i><span id="points-number"><?php echo $user['points']; ?></span><span id="points-word">Points</span></div>
		<a href="/buy.php" id="buy"><i id="buy-icon" class="material-icons">local_atm</i><span id="buy-text">Buy</span></a>
	</div>

</body>
</html>