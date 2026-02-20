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

	    	if (isset($_SESSION['id'])) {
	    		$id=$_SESSION['id'];

		    	if (isset($_POST['friend_id'])) {
		    		$friend_id=$_POST['friend_id'];
		    		//$_SESSION['friend_id']=$friend_id;
		    		//echo $friend_id;
		    	} else {
		    		header("Location: personal_page.php");
		    		exit();
		    	}

				$stmt = $pdo->prepare('SELECT * FROM tb_lime WHERE id = :id');
				$stmt -> bindParam(':id', $friend_id, PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
				   	$friend_name=$row['name'];
				}

				//echo $friend_id;

				//メッセージを送る
					if (isset($_POST['send'])) {
						//echo $friend_id;
						$message=$_POST['message'];
						date_default_timezone_set('Asia/Tokyo');
						$date=date("m-d H:i");

						$stmt = $pdo->prepare('INSERT INTO tb_chat (from_id, to_id, message, date)  value( :from_id, :to_id, :message, :date)');
						$stmt -> bindParam(':from_id', $id, PDO::PARAM_INT);
						$stmt -> bindParam(':to_id', $friend_id, PDO::PARAM_INT);
						$stmt -> bindParam(':message', $message, PDO::PARAM_STR);
						$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
						$stmt -> execute();
						
					}

				//メッセージを受け取る
					$chat="";

					$stmt = $pdo->prepare('SELECT * FROM tb_chat WHERE (from_id = :id OR to_id = :id) AND (from_id = :friend_id OR to_id = :friend_id)');
					$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
					$stmt -> bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
					$stmt -> execute();

					while ($row = $stmt->fetch()) {
						if ($row['from_id']==$id) {
							$chat.="<div class=blank_3></div>";
							$chat.="<div class=my_message>";

								$chat.="<div class=dat>";
									$chat.=$row['date'] . "自分";
								$chat.="</div>";

								$chat.=$row['message'];

							$chat.="</div>";

							$chat.="<div class=floatclear></div>";
						} else {
							$chat.="<div class=friends_message>";

								$chat.="<div class=dat>";
									$chat.="相手" . $row['date'];
								$chat.="</div>";

								$chat.="<div class=floatclear></div>";

								$chat.=$row['message'];

							$chat.="</div>";
							$chat.="<div class=blank_3></div>";	

							$chat.="<div class=floatclear></div>";						
						}
						
					}



	    	} else {
				header("Location: login.html");
				exit();
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

	<!--タイトル-->
	<div class="blank_0"></div>

	<div class="title_2">
		<div class="button_2">
			<form action="personal_page.php" method="post">
				<input type="submit" value="戻る">
			</form>
		</div>

		<div class="name">
			<?php echo $friend_name; ?>			
		</div>

		<div class="button_2">
			<form action="chat.php" method="post">
				<input type="submit" value="更新">
				<input type="hidden" name="friend_id" value=<?php echo $friend_id ?>>
			</form>
		</div>

		<div class="floatclear"></div>
	</div>

	<div class="floatclear"></div>

	<!--メッセージ-->
	<div class="blank_0"></div>

	<div class="chat">
		<div class="chat">
			<?php echo $chat ?>
		</div>
	</div>

	<div class="floatclear"></div>

	<!--送る-->
	<div class="blank_0"></div>

	<div class="send">
		<form action="chat.php" method="post">
			<input type="text" name="message" size="15" required>
			<input type="submit" value="送る">
			<input type="hidden" name="send" value="send">
			<input type="hidden" name="friend_id" value=<?php echo $friend_id; ?>>
		</form>
	</div>

	<div class="floatclear"></div>

</body>
</html>