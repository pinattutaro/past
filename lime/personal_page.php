<?php
	session_start();
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

		//ログイン済みか確認
		if (isset($_SESSION['id'])) {
			//ログイン済み
			$id=$_SESSION['id'];

			$stmt = $pdo->prepare('SELECT * FROM tb_lime WHERE id = :id ');
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();

			while ($row = $stmt->fetch()) {
				$name=$row['name'];
			}

		//メールアドレスとパスワードを送ったか
		} else if (isset($_POST['mail']) && isset($_POST['pass'])) {
			$mail=$_POST['mail'];
			$pass=$_POST['pass'];

			$stmt = $pdo->prepare('SELECT * FROM tb_lime WHERE mail = :mail AND pass = :pass ');
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt -> execute();

			while ($row = $stmt->fetch()) {
				$name=$row['name'];
				$id=$row['id'];
			}

			//メールアドレスとパスワードが合っているか
			if (isset($name)) {
				//合っている
				$_SESSION['id']=$id;
			} else {
				//間違っている
				echo "メールアドレス又はパスワードが間違っています。";

				echo "<div class=button>";
					echo "<form action=login.html method=post>";
						echo "<input type=submit value=戻る>";
					echo "</form>";
				echo "</div>";

				exit();
			}

		//不正アクセス
		} else {
			header("Location: login.html");
			exit();
		}


		//ログアウト
		if (isset($_POST['logout'])) {
			unset($_SESSION['id']);
			header("Location: login.html");
			exit();
		}

		//申請
		if (isset($_POST['application'])) {
			$application_id=$_POST["application_id"];
			$stmt = $pdo->prepare('INSERT INTO tb_friend (from_id, to_id)  value( :from_id, :to_id)');
			$stmt -> bindParam(':from_id', $id, PDO::PARAM_INT);
			$stmt -> bindParam(':to_id', $application_id, PDO::PARAM_INT);
			$stmt -> execute();

			echo "<script type='text/javascript'>alert('id" . $application_id ."に申請しました')</script>";
		}

		//申請中
		$applying_contents="";

		$stmt = $pdo->prepare('SELECT tb_lime.name ,tb_friend.from_id FROM tb_lime ,tb_friend WHERE tb_lime.id = tb_friend.from_id AND tb_friend.state = "application" AND tb_friend.to_id=:to_id');
		$stmt -> bindParam(':to_id', $id, PDO::PARAM_INT);
		$stmt -> execute();

		while ($row = $stmt->fetch()) {
			$applying_contents.="<div class=blank_2>a</div>";

			$applying_contents.="<div class=applying_contents>";
				$applying_contents.=$row['name'];
			$applying_contents.="</div>";

			$applying_contents.="<div class=applying_contents>";
				
				$applying_contents.="<form action=personal_page.php method=post>";
					$applying_contents.="<input type=submit value=承認>";
					$applying_contents.="<input type=hidden name=approval value=friend>";
					$applying_contents.="<input type=hidden name=approval_id value=" . $row['from_id'] .">";					
				$applying_contents.="</form>";
			$applying_contents.="</div>";

			$applying_contents.="<div class=applying_contents>";
				$applying_contents.="<form action=personal_page.php method=post>";
					$applying_contents.="<input type=submit value=拒否>";
					$applying_contents.="<input type=hidden name=approval value=refusal>";
					$applying_contents.="<input type=hidden name=approval_id value=" . $row['from_id'] . ">";
				$applying_contents.="</form>";
			$applying_contents.="</div>";

			$applying_contents.="<div class=floatclear></div>";

			$applying_contents.="<br>";
		}



		//友達申請の承認と拒否
		if (isset($_POST['approval']) && isset($_POST['approval_id'])) {
			//echo "ok";
			$approval=$_POST['approval'];
			$approval_id=$_POST['approval_id'];

			$stmt = $pdo->prepare('UPDATE tb_friend SET state = :state  WHERE from_id = :from_id ');
			$stmt -> bindParam(':state', $approval, PDO::PARAM_STR);
			$stmt -> bindParam(':from_id', $approval_id, PDO::PARAM_INT);
			$stmt -> execute();

			if ($approval=="friend") {
				echo "<script type='text/javascript'>alert('id" . $approval_id ."を友達に追加しました')</script>";
			} else if ($approval=="refusal") {
				echo "<script type='text/javascript'>alert('id" . $approval_id ."の申請を断りました')</script>";
			}
		}



		//友達
			$friend_contents="";
			$friend_id;

			//誘われた
			$stmt = $pdo->prepare('SELECT tb_lime.name ,tb_friend.from_id FROM tb_lime ,tb_friend WHERE tb_lime.id = tb_friend.from_id AND state = "friend" AND tb_friend.to_id = :to_id');
			$stmt -> bindParam(':to_id', $id, PDO::PARAM_INT);
			$stmt -> execute();

			while ($row = $stmt->fetch())	{
				//$friend_contents.="<div class=blank_2>a</div>";

				$friend_contents.="<div class=friend_contents>";
					$friend_contents.=$row['name'];
				$friend_contents.="</div>";

				$friend_contents.="<div class=friend_contents>";
					$friend_contents.="<form action=chat.php method=post>";
						$friend_contents.="<input type=submit value=話す>";
						$friend_contents.="<input type=hidden name=friend_id value=" . $row['from_id'] . ">";
					$friend_contents.="</form>";
				$friend_contents.="</div>";

				$friend_contents.="<div class=floatclear></div>";
			}

			//誘った
			$stmt = $pdo->prepare('SELECT tb_lime.name ,tb_friend.to_id FROM tb_lime ,tb_friend WHERE tb_lime.id = tb_friend.to_id AND state = "friend" AND tb_friend.from_id = :from_id');
			$stmt -> bindParam(':from_id', $id, PDO::PARAM_INT);
			$stmt -> execute();

			while ($row = $stmt->fetch()) {
				//$friend_contents.="<div class=blank_2>a</div>";

				$friend_contents.="<div class=friend_contents>";
					$friend_contents.=$row['name'];
				$friend_contents.="</div>";

				$friend_contents.="<div class=friend_contents>";
					$friend_contents.="<form action=chat.php method=post>";
						$friend_contents.="<input type=submit value=話す>";
						$friend_contents.="<input type=hidden name=friend_id value=" . $row['to_id'] . ">";
					$friend_contents.="</form>";
				$friend_contents.="</div>";

				$friend_contents.="<div class=floatclear></div>";
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
<title>personal_page</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div class="blank_0"></div>

	<div class="body">
		<div class="title">
			<?php echo $name . "(" . $id . ")"; ?>
		</div>

		<div class="applying">
			申請中<br>
			<?php echo $applying_contents; ?>
		</div>

		<div class="friend">
			友達<br>
			<?php echo $friend_contents; ?>
		</div>

		<div class="application">
			<form action="personal_page.php" method="post">
				id<input type="text" name="application_id" required>
				<input type="submit" value="申請">
				<input type="hidden" name="application" value="1">			
			</form>
		</div>

		<div class="logout">
			<form action="personal_page.php" method="post">
				<input type="submit" value="ログアウト">
				<input type="hidden" name="logout" value="1">		
			</form>
		</div>
	</div>

	<div class="floatclear"></div>
</body>
</html>