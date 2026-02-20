<?php
	$id=$_POST['id'];
	//echo $id;

	$db = 'mysql:dbname=db_english;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = '';

	try {
	    
	    $pdo = new PDO($db, $user, $password,
	      	     [
	       	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	       	     ]
	   	);

		$stmt = $pdo->prepare('SELECT * FROM tb_english WHERE id = :id');
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> execute();

		$html_code="";

		while ($row = $stmt->fetch()) {
		   	$html_code.= "<div class=language>英</div>";
		   	$html_code.= "<div class=language>日</div>";
		   	$html_code.="<div class=floatclear></div>";
		   	$html_code.="<div class=word>".$row['english']."</div>";
		   	$html_code.="<div class=word>".$row['japanese']."</div>";
		   	$html_code.="<div class=floatclear></div>";
		}

	
	} catch (PDOException $e) {
	    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	    	exit($e->getMessage()); 
	}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>list</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="body">
		<div id="title"><h2>確認</h2></div>

		<div id="contents">
			<?php echo $html_code; ?>
		</div>

		<div class="button">
			<form action="../list.php" method="post">
				<input type="submit" value="取り消し">
			</form>
		</div>

		<div class="button">
			<form action="delete/delete.php" method="post">
				<input type="submit" value="削除">
				<?php echo "<input type=hidden name=id value=".$id.">"; ?>
			</form>
		</div>
		<div class="floatclear"></div>
	</div>
</body>
</html>