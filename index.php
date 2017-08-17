<?php
	session_start();
	if (!empty($_SESSION['id']))
	{
		header("Location: /categories.php");
	}

	$message = "";
	$uname = "";
	$passwd = "";
	if ((!empty($_POST['uname'])) && (!empty($_POST['passwd'])))
	{	

		$uname = $_POST['uname'];
		$passwd = $_POST['passwd'];
		
		$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');

		$stmt = $dbh->prepare("SELECT id, uname, passwd FROM users WHERE uname=:uname"); 
		$stmt->execute(array(':uname' => $uname));
		$user = $stmt->fetch();

		if ($uname == $user['uname'])
		{
			if (password_verify($passwd, $user['passwd']))
			{
				if (isset($_SESSION['id']))
				{
					session_unset();
					session_destroy();	
				}
				session_start();
				$_SESSION['id'] = $user['id'];
				header("Location: /categories.php");
			}
			else
			{
				$message = "Incorrect Password";
			}
		}
		else
		{
			$message = "Incorrect Username";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="theme-color" content="#5E5DA9">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/home.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	
	<div id="home-container">
		<div id="home-main">
			<h1 id="home-title">Skill<span>x</span></h1>
			<form id="login" method="POST">
				<div class="login-input" id="email-box">
					<div class="login-input-logo">
						<i class="material-icons">person</i>
					</div><div class="login-input-text">
						<input type="text" name="uname" placeholder="Username" required value="<?php echo $uname; ?>">
					</div>
				</div>
				<div class="login-input" id="password-box">
					<div class="login-input-logo">
						<i class="material-icons">fingerprint</i>
					</div><div class="login-input-text">
						<input type="password" name="passwd" placeholder="Password" required>
					</div>
				</div>
				<input id="submit" type="submit" name="" value="Sign In">
			</form>
		</div>
	</div>

	<?php if (!empty($message)):?>
	<div id="message">
		<?php echo $message; ?>
	</div>
	<?php endif; ?>

</body>
</html>