<?php
	$name=$_POST['name'];
	$age=$_POST['age'];
	$gender=$_POST['gender'];
	$mail=$_POST['mail'];
	$pass=$_POST['pass'];
	$name_id;
	$html_button="<div class=button><input type=submit value=サインイン><div/>";
	$mail_check=[];

	if ($gender=="not") {
		$gender="指定しない";
	} else if ($gender=="man") {
		$gender="男";
	} else if ($gender=="woman") {
		$gender="女";
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
		
		$stmt = $pdo->query( "SELECT * FROM tb_member" );

		while ($row = $stmt->fetch()) {
			$name_id=$row['id']+1;
			$mail_check[]=$row['mail'];
		}


	} catch (PDOException $e) {
	    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	    	exit($e->getMessage()); 
	}

	//名前と年の無指定時のプログラム
	if ($name=="") {
		$name="無名" . $name_id;
	}

	if ($age=="") {
		$age="指定しない";
	} else {
		$age=$age . "歳";
	}

	//記入漏れの確認
	if ($mail=="") {
		echo "メールアドレスを記入してください。<br>";
		$html_button="";
	}

	if ($pass=="") {
		echo "パスワードを設定してください。<br>";
		$html_button="";
	}

	//メールアドレスのかぶりを調べる
	for ($i=0; $i < count($mail_check); $i++) { 
		if ($mail==$mail_check[$i]) {
			echo "既に同じメールアドレスのユーザーが存在しています。<br>";
			$html_button="";
			break;
		}
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
	<div id="body">

		<div id="title"><h2>確認</h2></div>

		<form action="enter.php" method="post">
			<div id="name">
				名前<br>
				<?php 
					echo $name; 
					echo "<input type=hidden name=name value=" . $name . ">";
				?>
			</div>

			<div class="blank"></div>

			<div id="age">
				年齢<br>
				<?php 
					echo $age; 
					echo "<input type=hidden name=age value=" . $age . ">";
				?>
			</div>

			<div class="blank"></div>

			<div id="gender">
				性別<br>
				<?php 
					echo $gender; 
					echo "<input type=hidden name=gender value=" . $gender . ">";
				?>
			</div>

			<div class="blank"></div>

			<div id="mail">
				メールアドレス<br>
				<?php 
					echo $mail;
					echo "<input type=hidden name=mail value=" . $mail . ">";
				?>
			</div>

			<div class="blank"></div>

			<div id="pass">
				パスワード<br>
				<?php 
					echo $pass; 
					echo "<input type=hidden name=pass value=" . $pass . ">";
				?>
			</div>

			<div class="blank"></div>

			<?php echo $html_button; ?>
		</form>

		<form action="sign.php" method="post">
			<div class="button">
				<input type="submit" value="戻る">
			</div>
		</form>
	</div>
</body>
</html>