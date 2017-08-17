<?php require("auth.php"); ?>

<?php
	$id = $_SESSION['id'];
	$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
	$stmt = $dbh->prepare("SELECT uname, name, points FROM users WHERE id=:id"); 
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
	<link rel="stylesheet" type="text/css" href="/css/categories.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	
	<?php require("topbar.php"); ?>

	<div class="main">

		<?php if (empty($_GET['c'])): ?>
		<div class="main">
			<a href="?c=music" class="category">
				<h3>Music</h3>
				<i class="material-icons">music_note</i>
			</a><a href="?c=graphics" class="category">
				<h3>Graphics</h3>
				<i class="material-icons">palette</i>
			</a><a href="?c=code"" class="category">
				<h3>Code</h3>
				<i class="material-icons">code</i>
			</a><a href="?c=food" class="category">
				<h3>Food</h3>
				<i class="material-icons">restaurant</i>
			</a><a href="?c=sports" class="category">
				<h3>Sports</h3>
				<i class="material-icons">pool</i>
			</a>
		</div>
		<?php else: ?>

			<?php if (empty($_GET['f'])) : ?>

				<a href="?" id="back"><div id="back-fg"><i class="material-icons">arrow_back</i><span>All Categories</span></div><div id="back-bg"></div></a>
				<br><br><br><br>
				<h2 id="title"><?php echo ucwords($_GET['c']);?></h2>
				<?php if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/tutorials/" . $_GET['c'])): 
					$tutorials = glob($_SERVER["DOCUMENT_ROOT"] . "/tutorials/" . $_GET['c'] . "/*" , GLOB_ONLYDIR);
					if (empty($tutorials)):?>
						<div id="not">
							<div id="not-big">\(o_o)/</div>
							<div id="not-small">No tutorials found!</div>
						</div>
					<?php else: 
							foreach ($tutorials as $tutorial):
								$name = fgets(fopen($tutorial . "/name.txt", "r"));
								$fname = explode('/', $tutorial);
								$fname = end($fname);
								$img = "/tutorials/" . $_GET['c'] . "/" . $fname . "/thumb.jpg";
								$cost = fgets(fopen($tutorial . "/cost.txt", "r"));
								$uploader_name = fgets(fopen($tutorial . "/uname.txt", "r"));

								$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
								$stmt = $dbh->prepare("SELECT name FROM users WHERE uname=:uname"); 
								$stmt->execute(array(':uname' => $uploader_name));
								$uploader = $stmt->fetch();

								if ($uploader_name != $user['uname']):
							?>
								<a href="?c=<?php echo $_GET['c']; ?>&f=<?php echo $fname;?>" class="tutorial">
									<h3><?php echo $name; ?></h3>
									<h4>By <?php echo $uploader['name']; ?></h4>
									<img class="thumb" class src="<?php echo $img;?>">
									<div class="cost"><i class="material-icons">star</i><span><?php echo $cost; ?></span></div>
								</a>
							<?php endif; endforeach;
					endif;		
				else: ?>
					<div id="not">
						<div id="not-big">\(o_o)/</div>
						<div id="not-small">No tutorials found!</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif;?>

		<?php if (!empty($_GET['f'])) : ?>
			<br><br>
			<?php
				$link = $_SERVER["DOCUMENT_ROOT"] . "/tutorials/" . $_GET['c'] . "/" . $_GET['f'];
				$cost = fgets(fopen($link . "/cost.txt", "r"));
				$name = fgets(fopen($link . "/name.txt", "r"));
				$uploader_name = fgets(fopen($link . "/uname.txt", "r"));
				if ($cost <= $user['points'])
				{

					$points_new = $user['points'] - $cost;

					$dbh = new PDO('mysql:host=localhost;dbname=skillx', 'root');
					
					$stmt = $dbh->prepare("UPDATE users SET points = :points WHERE id = :id");
					$stmt->execute(array(':points' => $points_new, ':id' => $_SESSION['id']));

					$stmt = $dbh->prepare("SELECT id, points FROM users WHERE uname=:uname"); 
					$stmt->execute(array(':uname' => $uploader_name));
					$uploader = $stmt->fetch();
					$uploader_points_new = $uploader['points'] + 10;
					$stmt = $dbh->prepare("UPDATE users SET points = :points WHERE id = :id");
					$stmt->execute(array(':points' => $uploader_points_new, ':id' => $uploader['id']));

					$video =  "/tutorials/" . $_GET['c'] . "/" . $_GET['f'] . "/video.mp4";
					echo 
					"<a href='?c=" . $_GET['c'] . "' id='back'><div id='back-fg'><i class='material-icons'>arrow_back</i><span>" . ucwords($_GET['c']) . "</span></div><div id='back-bg'></div></a>" .
					"<br><br><h2>" .  $name . "</h2>" .
					"
					<video width='100%' controls>
						<source src='". $video . "' type='video/mp4'>
					</video>
					<script>
						$('#topbar-points span').html(" . $points_new . ");
					</script>
					"
					;
				}
				else
				{
					header("Location: /buy.php");
				}
			?>
		<?php endif; ?>


	</div>

</body>
</html>

