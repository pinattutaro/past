<?php
	$english=$_POST['add_english'];
	$japanese=$_POST['add_japanese'];
	//echo $japanese;

	$db = 'mysql:dbname=db_english;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = '';

	try {
	    	// データベースに接続
	    	$pdo = new PDO($db, $user, $password,
	        	     [
	            	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	            	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        	     ]
	    	);

	    $stmt = $pdo->prepare('INSERT INTO tb_english (english, japanese)  value( :english, :japanese)');
		$stmt -> bindParam(':english', $english, PDO::PARAM_STR);
		$stmt -> bindParam(':japanese', $japanese, PDO::PARAM_STR);
		$stmt -> execute();



	} catch (PDOException $e) {
		//エラー発生した場合
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
	<h2>追加完了しました</h2>
		<form action="../../home/index.html" method="post">
			<input type=submit value=戻る>
		</form>
</body>
</html>