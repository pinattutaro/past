<?php
	$page=$_POST['page'];
	$id=$_POST['id'];
	$html_code="";

	if ($page=="check") {
		$english=$_POST['english'];
		$japanese=$_POST['japanese'];

		$html_code.="<div class=language>英</div>";
		$html_code.="<div class=language>日</div>";
		$html_code.="<div class=floatclear></div>";
		$html_code.="<form action=change_check/change_check.php method=post>";
		$html_code.="<div class=contents>"."<input type=text name=english_word value=".$english.">"."</div>";
		$html_code.="<div class=contents>"."<input type=text name=japanese_word value=".$japanese.">"."</div>";
		$html_code.="<div class=floatclear></div>";		   	
		$html_code.="<div class=button>"."<input type=submit value=変更>"."</div>";
		$html_code.="<input type=hidden name=id value=".$id.">";
		$html_code.="</form>";
		   	

	} else {			
		$db = 'mysql:dbname=db_english;host=localhost;charset=utf8mb4';
		$user = 'root';
		$password = '';

		try {
	    	$pdo = new PDO($db, $user, $password,
	            	[
	            	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	            	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	        		]
	    		);


			$stmt = $pdo->prepare('SELECT * FROM tb_english WHERE id = :id');
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();

			while ($row = $stmt->fetch()) {
		   		$html_code.="<div class=language>英</div>";
		   		$html_code.="<div class=language>日</div>";
		   		$html_code.="<div class=floatclear></div>";
		   		$html_code.="<form action=change_check/change_check.php method=post>";
		   		$html_code.="<div class=contents>"."<input type=text name=english_word value=".$row['english'].">"."</div>";
		   		$html_code.="<div class=contents>"."<input type=text name=japanese_word value=".$row['japanese'].">"."</div>";
		   		$html_code.="<div class=floatclear></div>";		   	
		   		$html_code.="<div class=button>"."<input type=submit value=変更>"."</div>";
		   		$html_code.="<input type=hidden name=id value=".$id.">";
		   		$html_code.="</form>";
		   	
		}


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
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="title"><h2>変更</h2></div>

	<div id="body">
		<?php echo $html_code; ?>
		<div class="button">
			<form action="../list.php" method="post">
				<input type="submit" value="取り消し">
			</form>
		</div>	
		<div class="floatclear"></div>
	</div>


		
</body>
</html>