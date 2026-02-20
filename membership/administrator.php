<?php
	if (!isset($_POST['pass']) || !isset($_POST['page'])) {
		header("Location: administrator_check.html");
		exit();
	}

	$pass=$_POST['pass'];
	$page=$_POST['page'];
	$html_body="";

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

	    	if ($page=="cancellation") {
	    		$id=$_POST['id'];
	    		$state="解除";
				$stmt = $pdo->prepare('UPDATE tb_member SET state = :state WHERE id = :id');
				$stmt -> bindParam(':state', $state, PDO::PARAM_STR);
				$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();

	    	}
	    		
	    	if ($page=="freeze") {
	    		$id=$_POST['id'];
	    		$state="凍結";
				$stmt = $pdo->prepare('UPDATE tb_member SET state = :state WHERE id = :id');
				$stmt -> bindParam(':state', $state, PDO::PARAM_STR);
				$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();	
	    	}
	

		if ($pass=="1234") {
			$html_body.="<div id=title_2>一覧</div>";

			$html_body.="<div class=id>";
				$html_body.="id";
			$html_body.="</div>";

			$html_body.="<div class=name>";
				$html_body.="name";
			$html_body.="</div>";

			$html_body.="<div class=mail>";
				$html_body.="mail";
			$html_body.="</div>";

			$html_body.="<div class=age>";
				$html_body.="age";
			$html_body.="</div>";

			$html_body.="<div class=gender>";
				$html_body.="gender";
			$html_body.="</div>";

			$html_body.="<div class=pass>";
				$html_body.="pass";
			$html_body.="</div>";

			$html_body.="<div class=state>";
				$html_body.="state";
			$html_body.="</div>";

			$html_body.="<div class=floatclear></div>";

		
			$stmt = $pdo->query( "SELECT * FROM tb_member" );

			while ($row = $stmt->fetch()) {
				$html_body.="<div class=id>";
					$html_body.=$row['id'];
				$html_body.="</div>";

				$html_body.="<div class=name>";
					$html_body.=$row['name'];
				$html_body.="</div>";

				$html_body.="<div class=mail>";
					$html_body.=$row['mail'];
				$html_body.="</div>";

				$html_body.="<div class=age>";
					$html_body.=$row['age'];
				$html_body.="</div>";

				$html_body.="<div class=gender>";
					$html_body.=$row['gender'];
				$html_body.="</div>";

				$html_body.="<div class=pass>";
					$html_body.=$row['pass'];
				$html_body.="</div>";

				if ($row['state']=="凍結") {	
					$html_body.="<form action=administrator.php method=post>";
						$html_body.="<div class=state>";
							$html_body.="<input type=submit value=凍結>";
							$html_body.="<input type=hidden name=page value=cancellation>";
							$html_body.="<input type=hidden name=pass value=1234>";
							$html_body.="<input type=hidden name=id value=" . $row['id'] . ">";
						$html_body.="</div>";
					$html_body.="</form>";

				} else if($row['state']=="解除") {
					$html_body.="<form action=administrator.php method=post>";
						$html_body.="<div class=state>";
							$html_body.="<input type=submit value=解除>";
							$html_body.="<input type=hidden name=page value=freeze>";
							$html_body.="<input type=hidden name=pass value=1234>";
							$html_body.="<input type=hidden name=id value=" . $row['id'] . ">";
						$html_body.="</div>";
					$html_body.="</form>";
				} else {
						$html_body.="<div class=state>";
							$html_body.="退会";
						$html_body.="</div>";
				}

				$html_body.="<div class=floatclear></div>";
			}

		} else  {
			$html_body.="パスワードが間違っています。";
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
<title>sampul</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>

	<?php echo $html_body; ?>

	<div class=button>
		<form action=administrator_check.html method=post>
			<input type=submit value=戻る>
		</form>
	</div>

</body>
</html>