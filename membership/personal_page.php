<?php
	session_start();

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

	    //ログアウト
		if (isset($_POST['secession'])) {
			unset($_SESSION['id']);
			header("Location: login/login.html");
			exit();
		}	


		//すでにログインしている場合
		if (isset($_SESSION['id'])) {
			//セッションからidを取得
			$id=$_SESSION['id'];
			//名前をデータベース取得


			$title="";
			$name="";
			$body="";
			$state;
							
			$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id = :id');
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();

			while ($row = $stmt->fetch()) {
				$name=$row['name'];
			}
			
		################################################################################################				
		} else{
		//ログインされていない場合

			if (isset($_POST['mail']) && isset($_POST['pass'])) {
			//メールとパスワードを送ってきた場合

				//メールとパスワードを取得
				$mail=$_POST['mail'];
				$pass=$_POST['pass'];

				//データベースからメールとパスワードで名前とid取得
				$name="";
				$state="解除";

				$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE mail = :mail AND pass = :pass AND state = :state');
				$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
				$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$stmt -> bindParam(':state', $state, PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
				//取得成功
					$name=$row['name'];
					//$state=$row['state'];
					$id=$row['id'];
					$_SESSION['id']=$row['id'];
				}

				//取得失敗の場合
				//トップページに移動

				/*if ($name=="" || $state=="退会") {
					echo "メールアドレス又はパスワードが間違っています。";

					echo "<form action=login/login.html method=post>";
						 echo "<input type=submit value=やり直す>";
					echo "</form>";

					exit();

				}else if ($state=="凍結") {
					echo "このアカウントは管理者によって凍結されています。";

					echo "<form action=login/login.html method=post>";
						echo "<input type=submit value=やり直す>";
					echo "</form>";

					exit();
				}*/

				if ($name=="") {
					echo "メールアドレス又はパスワードが間違っている。又は無効なユーザーです。";

					echo "<form action=login/login.html method=post>";
						 echo "<input type=submit value=やり直す>";
					echo "</form>";

					exit();		
				}

			} else {
			//メールとパスワードを送っていない場合
			//トップページに戻る	
				header("Location: login/login.html");
				exit();
			}

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
<title>Top page</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="info">
		<?php echo $name; ?>さんようこそ
	</div>

	<!-- 変更処理 -->
	<div id="buttons">
		<form action="" method="POST">
						
		</form>
	</div>

	<!-- ログアウト -->
	<div class="button">
		<form action="personal_page.php" method="post">
			<input type="submit" value="ログアウト">
			<input type="hidden" name="secession" value="1">
		</form>
	</div>

	<!-- 退会処理 -->
	<div class="button">
		<form action="secession_check.php" method="post">
			<input type="submit" value="退会">
		</form>
	</div>

	<!--変更-->
	<div class="button"> 
		<form action="change.php" method="post">
			<input type="submit" value="アカウント情報の変更">
		</form>
	</div>
			
</body>
</html>
