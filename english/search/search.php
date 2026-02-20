<?php
	$language=$_POST['language'];
	$search=$_POST['search'];
	$html_code="";
	//echo $language;
	//echo $search;
 
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

		$html_code.="<div id=mean>"."検索結果"."</div>";

		if ($language=="english") {
			$stmt = $pdo->prepare('SELECT * FROM tb_english WHERE english = :english');
			$stmt -> bindParam(':english', $search, PDO::PARAM_STR);
			$stmt -> execute();	

			$check=0;

			while ($row = $stmt->fetch()) {
				$check=$check+1;

				//$html_code.="<div id=title>"."<h2>".$search."</h2>"."</div>";
				//$html_code.="<div id=blank></div>";
				$html_code.="<div id=content>・".$row['japanese'] . "</div>"."<br>";
				
			}

			if ($check==0) {
				$html_code="その単語に該当する結果が見つかりませんでした";
			}

		}else if ($language=="japanese") {		
			$stmt = $pdo->prepare('SELECT * FROM tb_english WHERE japanese = :japanese');
			$stmt -> bindParam(':japanese', $search, PDO::PARAM_STR);
			$stmt -> execute();	


			$check=0;

			while ($row = $stmt->fetch()) {
				$check=$check+1;

				//$html_code.="<div id=title>"."<h2>".$search."</h2>"."</div>";
				//$html_code.="<div id=blank></div>";
				$html_code.="<div id=content>・".$row['english'] . "</div>"."<br>";
				
			}

			if ($check==0) {
				$html_code="その単語に該当する結果が見つかりませんでした";
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
<title>list</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="body">
		<?php echo $html_code ?>
	</div>

	<div id="button">
		<form action="../home/index.html" method="post">
			<input type="submit" value="戻る">
		</form>
	</div>
</body>
</html>