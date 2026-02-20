<?php
	$name=$_POST['name'];
	$age=$_POST['age'];
	$gender=$_POST['gender'];
	$mail=$_POST['mail'];
	$pass=$_POST['pass'];
	//echo $name;

	$db = 'mysql:dbname=db_member;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = '';

	try {
	    	$pdo = new PDO($db, $user, $password,
	        	     [
	            	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	            	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        	     ]
	    	);
		

			$stmt = $pdo->prepare('INSERT INTO tb_member (name, age, gender, mail, pass)  value( :name, :age, :gender, :mail, :pass)');
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':age', $age, PDO::PARAM_STR);
			$stmt -> bindParam(':gender', $gender, PDO::PARAM_STR);
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
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
<title>Top page</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
　　<h2>新規登録完了しました</h2>
	
	<form action="../top.html" method="post">
		<input type="submit" value="トップページへ戻る">
	</form>
</body>
</html>