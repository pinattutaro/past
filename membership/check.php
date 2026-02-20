<?php 
	session_stert();

	//データベースに接続
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

	    //退会をユーザーが承認した場合
		if (isset($_POST['page'])) {
			//退会する		
		    $stmt = $pdo->prepare('UPDATE tb_member SET state = "退会"  WHERE mail = :mail ');
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> execute();
			header("Location: top.html");
			exit();
		}

###############################################################################################

		//ログインしているかをチェック
		if (isset($session['id'])) {
			//ログインしている場合
			$id=$session['id'];
			$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id = :id');
			$stmt -> bindParam(':id', $id, PDO::PARAM_STR);
			$stmt -> execute();
			
			while ($row = $stmt->fetch()) {
				//ここには各自の処理に入れ替えてください
			   	echo $row['フィルド名'] . "<br />";
			}

		} else {
			//ログインしていない場合
			header("Location: top.html");
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
<title>確認</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<!-- 警告 -->
　　<?php echo $name; ?>さん、本当に退会しますか？<br>
	退会すると二度とログインすることは出来ません。

	<!-- 戻る -->
	<div class="button">
		<form action="personal_page.php" method="post">
			<input type="submit" value="戻る">
		</form>
	</div>

	<!-- 退会 -->
	<div class="button">
		<form action="" method="post">
			<input type="submit" value="退会">
			<input type="hidden" name="page" value="1">
		</form>
	</div>

</body>
</html>
