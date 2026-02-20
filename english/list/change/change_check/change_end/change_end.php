<?php
	$id=$_POST['id'];
	$english=$_POST['english'];
	$japanese=$_POST['japanese'];
	//echo $english; ok

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


		$stmt = $pdo->prepare('UPDATE tb_english SET english = :english, japanese = :japanese  WHERE id = :id ');
		$stmt -> bindParam(':english', $english, PDO::PARAM_STR);
		$stmt -> bindParam(':japanese', $japanese, PDO::PARAM_STR);
		$stmt -> bindParam('id', $id, PDO::PARAM_INT);
		$stmt -> execute();


	} catch (PDOException $e) {
	    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	    	exit($e->getMessage()); 
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	
	<div id="title"><h2>変更完了</h2></div>

	<div id="button">
		<form action="../../../list.php" method="post">
			<input type="submit" value="一覧へ戻る">
			<input type="hidden" name="page" value="end">
		</form>
	</div>

</body>
</html>