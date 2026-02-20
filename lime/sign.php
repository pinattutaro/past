<?php
	$db = 'mysql:dbname=db_lime;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = '';
	try {
	    	$pdo = new PDO($db, $user, $password,
	        	     [
	            	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	            	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        	     ]
	    	);
		

		if (isset($_POST['sign'])) {
			$name=$_POST['name'];
			$mail=$_POST['mail'];
			$pass=$_POST['pass'];

			$stmt = $pdo->prepare('INSERT INTO tb_lime (name, mail, pass)  value( :name, :mail, :pass)');
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt -> execute();

			header("Location: login.html");
			exit();			

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
<title>sign</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div class="blank_0"></div>

	<div class="body">
	　　<!--タイトル-->
		<div class="title">
			新規登録
		</div>

		<!--記入欄-->
		<div class="field">
			<form action="sign.php" method="post">		
				<!--名前-->
				<div class="text">
					名前<br>
					<input type="text" name="name" maxlength="10" required>
				</div>

				<!--空白-->
				<div class="blank_1"></div>

				<!--メールアドレス-->
				<div class="text">
					メールアドレス<br>
					<input type="text" name="mail" maxlength="10" required>
				</div>

				<!--空白-->
				<div class="blank_1"></div>

				<!--パスワード-->
				<div class="pass">
					パスワード<br>
					<input type="password" name="pass" maxlength="10" required>
				</div>

				<!--サインイン-->
				<div class="button">
					<input type="submit" value="サインイン">
					<input type="hidden" name="sign" value="1">
				</div>
			</form>

		</div>

	<!--空白-->
	<div class="blank_1"></div>

		<!--キャンセル-->
		<div class="button">
			<form action="login.html">
				<input type="submit" value="キャンセル">
			</form>
		</div>
	</div>

	<div class="floatclear"></div>
</body>
</html>