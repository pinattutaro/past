<?php
	$db='mysql:dbname=db_english;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = '';

	try{
		$pdo=new PDO($db,$user,$password,
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

			]
		);

		$stmt = $pdo->query( "SELECT * FROM tb_english" );

		$html_code="";
		while ($row = $stmt->fetch()) {
			$html_code.="<div class=list>";
				$html_code.= "<div class=english>" . $row['english'] . "</div>";
				$html_code.= "<div class=japanese>" . $row['japanese'] . "</div>";
				$html_code.="<div class=change_delete>";
				$html_code.="  <form action=change/change.php method=post>";
				$html_code.="    <input type=submit name=change value=変更>";
				$html_code.="    <input type=hidden name=id value=".$row['id'].">";
				$html_code.="    <input type=hidden name=page value=list>";
				$html_code.="  </form>";
				$html_code.="</div>";
				$html_code.="<div class=change_delete>"."<form action=delete/delete_check.php method=post>"."<input type=submit name=delete value=削除>"."<input type=hidden name=id value=".$row['id'].">"."</form>"."</div>";
				//送信先でボタンのvalueを受け取る
				$html_code.="<div class=floatclear></div>";
			$html_code.="</div>";
		}

		if ($html_code=="") {
			$html_code.="<h4>登録されていません</h4>";
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
<title>list</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="body">
		<div id=title><h2>英単語リスト</h2></div>
		<div class=language>英</div>
		<div class=language>日</div>
		<div class=button>変更</div>
		<div class=button>削除</div>
		<div class=floatclear></div>

		<?php echo $html_code; ?>		

		<div id=back>
			<form action="../home/index.html" method="post">
				<input type=submit value=戻る>
			</form>
		</div>
	</div>
</body>
</html>
