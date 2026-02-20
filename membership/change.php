<?php
	session_start();
	//ログインしているか
	if (!isset($_SESSION['id'])) {
		header("Location: login/login.html");
		exit();
	}

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
		

	    //変更
	    if (isset($_POST['change'])) {
			$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id = :id');
			$stmt -> bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
			$stmt -> execute();

			   	$name=$_POST['name'];
			   	$age=$_POST['age'];
			   	$gender=$_POST['gender'];
			   	$mail=$_POST['mail'];
			   	$pass=$_POST['pass'];

			//変更
			$stmt = $pdo->prepare('UPDATE tb_member SET name = :name, age = :age, gender = :gender, mail = :mail, pass = :pass  WHERE id = :id ');
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':age', $age, PDO::PARAM_INT);
			$stmt -> bindParam(':gender', $gender, PDO::PARAM_STR);
			$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt -> bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
			$stmt -> execute();

			echo "変更完了";

			echo "<div class=button>";
				echo "<form action=personal_page.php method=post>";
				   echo "<input type=submit value=戻る>";
				echo "</form>";
			echo "</div>";
			exit();		

	    }

			//アカウント情報の取得
				$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id = :id');
				$stmt -> bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
				   	$name=$row['name'];
				   	$age=$row['age'];
				   	$gender=$row['gender'];
				   	$mail=$row['mail'];
				   	$pass=$row['pass'];
				}

				//ラジオボタンの初期値の設定
				$html_gender="";
				if ($gender=="指定しない") {
					$html_gender.="<input type=radio name=gender value=指定しない checked>指定しない<br>";
					$html_gender.="<input type=radio name=gender value=男>男<br>";
					$html_gender.="<input type=radio name=gender value=女>女";
				} else if ($gender=="男") {
					$html_gender.="<input type=radio name=gender value=指定しない>指定しない<br>";
					$html_gender.="<input type=radio name=gender value=男 checked>男<br>";
					$html_gender.="<input type=radio name=gender value=女>女";
				} else {
					$html_gender.="<input type=radio name=gender value=指定しない>指定しない<br>";
					$html_gender.="<input type=radio name=gender value=男>男<br>";
					$html_gender.="<input type=radio name=gender value=女 checked>女";
				}
	
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
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<!--<div class="caution"><?php //echo $caution; ?></div>-->

	<div id="block_change">
		<form action="change.php" method="post">
			<div class="chenge_text">
				名前<br>
				<?php echo "<input type=text name=name value=". $name ." required >"; ?>
			</div>	


			<div class="chenge_text">
				年齢<br>
				<?php echo "<input type=text name=age value=". $age ." required >"; ?>
			</div>


			<div class="chenge_text">
				性別<br>
				<?php echo $html_gender; ?>
			</div>

			<div class="chenge_text">
				メールアドレス<br>
				<?php echo "<input type=text name=mail value=". $mail ." required >"; ?>
			</div>	

			<div class="chenge_text">
				パスワード<br>
				<?php echo "<input type=text name=pass value=". $pass ." required >"; ?>
			</div>

			<input type="submit" value="変更">
			<input type="hidden" name="change" value="1">											
		</form>
	</div>

	<div class="button">
		<form action="personal_page.php" method="post">
			<input type="submit" value="キャンセル">
		</form>
	</div>
</body>
</html>
