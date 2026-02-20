<?php
	if (isset($_POST['page'])) {
		$mail=$_POST['mail'];
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
			
		    $stmt = $pdo->prepare('UPDATE tb_member SET state = "退会"  WHERE mail = :mail ');
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> execute();

		
		} catch (PDOException $e) {	
			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage()); 
		}
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>sginin</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="body">
		<form action="check.php" method="post">
			<div id="name">
				名前<br>
				<input type="text" name="name">
			</div>

			<div id="age">
				年齢<br>
				<input type="text" name="age">歳
			</div>

			<div id="gender">
				性別<br>
				<input type="radio" name="gender" value="not" checked="checked">指定しない<br>
				<input type="radio" name="gender" value="man">男<br>
				<input type="radio" name="gender" value="woman">女
			</div>

			<div id="mail">
				メールアドレス(要記入)<br>
				<input type="text" name="mail">
			</div>

			<div id="pass">
				パスワード(要記入)<br>
				<input type="password" name="pass">
			</div>

			<div class="button">
				<input type="submit" value="サインイン">
			</div>
		</form>

		<form action="../top.html" method="post">
			<div class="button">
				<input type="submit" value="topへ戻る">
			</div>
		</form>
	</div>
</body>
</html>
