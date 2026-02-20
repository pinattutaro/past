<?php
	session_start();

	//ログイン済みか調べる
	if (!isset($_SESSION['id'])) {
		//そうでない場合
		header("Location: login/login.html");
		echo "無理";
		exit();
	}

##################################################	

	//退会を承認したか調べる
	if (isset($_POST['taikai'])) {
		echo $_SESSION['id'];
		$state="退会";

		$db = 'mysql:dbname=db_member;host=localhost;charset=utf8mb4';
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

		    $stmt = $pdo->prepare('UPDATE tb_member SET state = :state  WHERE id = :id ');
			$stmt -> bindParam(':state', $state, PDO::PARAM_STR);
			$stmt -> bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
			$stmt -> execute();


		} catch (PDOException $e) {
		//エラー発生した場合
	    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	    	exit($e->getMessage()); 
		}

		unset($_SESSION['id']);
		header("Location: top.html");

		exit();
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>警告</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div class="caution">
	　　退会すると二度とログインすることはできません。<br>
		それを理解した上で退会しますか。
	</div>

	<div class="button">
		<form action="secession_check.php" method="post">
			<input type="submit" value="退会">
			<input type="hidden" name="taikai" value="secession">
		</form>
	</div>

	<div class="button">
		<form action="personal_page.php" method="post">
			<input type="submit" value="キャンセル">
		</form>
	</div>
</body>
</html>