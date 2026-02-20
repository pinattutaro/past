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


		$stmt = $pdo->prepare('DELETE FROM tb_english WHERE id = :id ');
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
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
	<div id="body">
		<h2>削除完了しました。</h2>
	</div>

	<div id="back">
		<form action="../../list.php" method="post">
			<input type="submit" value="一覧へ戻る">
		</form>
	</div>
</body>
</html>
