<?php
	//前ページの取得
	if (isset($_POST['page'])) {
		$page=$_POST['page'];
	} else {
		header("Location: index.php");
		exit();
	}

	//ビデオのid取得
	if ($page=="video.php") {
		$video=$_POST['video'];
	}

	//ホーム画面のページ数の取得
	if ($page=="index.php") {
		$page_num=$_POST['page_num'];
	}

	$caution="";
	$name="";
	$mail="";
	$pass="";

	//サインイン
	if (isset($_POST['sign'])) {
		$name=$_POST['name'];
		$mail=$_POST['mail'];
		$pass=$_POST['pass'];

		$db = 'mysql:dbname=db_youtube;host=localhost;charset=utf8mb4';
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

			$true_mail="true";
			while ($row = $stmt->fetch()) {
			   	if ($mail==$row['mail']) {
			   		$true_mail="false";
			   		break;
			   	}
			}

			if ($true_mail=="true") {
				$stmt = $pdo->prepare('INSERT INTO tb_member (name, mail, pass)  value( :name, :mail, :pass)');
				$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
				$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
				$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$stmt -> execute();	

				echo "<h2>成功しました</h2>";
				echo "<form action=" . $page . " method=post>
					  	<input type=submit value=戻る>
					  	<input type=hidden name=mail value=" . $mail . ">
					  	<input type=hidden name=action value=sign_login>
					  </form>";
				exit();							
			}else {
				$caution="このメールアドレスはすでに使われています";
			}
	#################################################################################################
	


		} catch (PDOException $e) {
		    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
		    	exit($e->getMessage()); 
		}
	}

	//戻るボタン
	$back_button="";

	$back_button.="<form action=" . $page . " method=get>";
			$back_button.="<input type=submit value=戻る>";
		if ($page=="video.php") {
			$back_button.="<input type=hidden name=id value=" . $video . " >";
		} else if ($page=="index.php") {
			$back_button.="<input type=hidden name=page value=" . $page_num . ">";
		}
	$back_button.="</form>";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<h2>サインイン</h2>

	<div class="field">
		<form action="sign.php" method="post">
			<?php echo "<input type=text name=name placeholder=名前 required=required value=" . $name . "><br>"; ?>
			<?php echo "<input type=text name=mail placeholder=メールアドレス required=required value=" . $mail . ">" . $caution . "<br>"; ?>
			<?php echo "<input type=text name=pass placeholder=パスワード required=required value=" . $pass . "><br>"; ?>
			<input type="submit" value="サインイン">
			<input type="hidden" name="sign" value="sign">
			<?php echo "<input type=hidden name=page value=" . $page . ">"; ?>
			<?php echo "<input type=hidden name=page_num value=" . $page_num . ">"; ?>
		</form>

		<?php echo $back_button; ?>
	</div>
</body>
</html>
