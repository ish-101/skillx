<?php require("auth.php"); ?>

<?php
	$id = $_SESSION['id'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("SELECT uname, name, certify, points FROM users WHERE id=:id"); 
	$stmt->execute(array(':id' => $id));
	$user = $stmt->fetch();
?>

<?php 
	$do = (!empty($_POST['title']) && !empty($_POST['category']));
	if ($do)
	{

		$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/tutorials/";
		if (!file_exists($target_dir))
		{
			mkdir($target_dir);
		}
		$target_dir .= $_POST['category'];
		if (!file_exists($target_dir))
		{
			mkdir($target_dir);
		}
		$t_number = 0;
		if (!file_exists($target_dir . "/id.txt"))
		{
			fwrite(fopen($target_dir . "/id.txt", "w"), $t_number);
		}
		$t_number = fgetc(fopen($target_dir . "/id.txt", "r"));
		$t_number++;
		fwrite(fopen($target_dir . "/id.txt", "w"), $t_number);
		$target_dir .= "/" . $t_number . "/";
		mkdir($target_dir);
		fwrite(fopen($target_dir . "/name.txt", "w"), $_POST['title']);
		fwrite(fopen($target_dir . "/cost.txt", "w"), 100);
		fwrite(fopen($target_dir . "/uname.txt", "w"), $user['uname']);

		$target_file = $target_dir . "thumb.jpg";
		move_uploaded_file($_FILES["thumb"]["tmp_name"], $target_file);
		$target_file = $target_dir . "video.mp4";
		move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);

		$points_new = $user['points'] + 500;
		$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
		$stmt = $dbh->prepare("UPDATE users SET points = :points WHERE id = :id");
		$stmt->execute(array(':points' => $points_new, ':id' => $_SESSION['id']));

		header("Location: /user.php");
	}

	if (!$do):
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="theme-color" content="#5E5DA9">
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/upload.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	
	<?php require("topbar.php"); ?>
	
	<div class="main">
		<h2>Upload Tutorial</h2>

		<form method="POST" enctype="multipart/form-data">
			<select name="category" required>
				<option value="">Choose a Category...</option>
				<option value="music">Music</option>
				<option value="graphics">Graphics</option>
				<option value="code">Code</option>
				<option value="food">Food</option>
				<option value="sports">Sports</option>
			</select>
			<input type="text" name="title" placeholder="Title" required>
			<br><br>
			<div class="big-button-container">
				<div id="video"><i class='material-icons'>videocam</i><span>Video</span></div>
				<input id="video-input" type="file" name="video" required accept="video/*">
			</div>
			<div class="big-button-container">
				<div id="thumb"><i class='material-icons'>camera</i><span>Thumbnail</span></div>
				<input id="thumb-input" type="file" name="thumb" required accept="image/*">
			</div>
			<br>
			<input type="submit" name="" required>
		</form>
	</div>

	<script type="text/javascript">
		$("#video-input").change(function(){
			if ($(this).value != "")
			{
				$("#video").addClass("file-selected");
			}
		});
		$("#thumb-input").change(function(){
			if ($(this).value != "")
			{
				$("#thumb").addClass("file-selected");
			}
		});
	</script>

</body>
</html>

<?php endif; ?>