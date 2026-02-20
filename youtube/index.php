<?php
	session_start();	

    	if (isset($_POST['action'])) {
    		$action=$_POST['action'];

    		if ($action=="logout") {
    			unset($_SESSION['id']);
    		}
    	}

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

	    	//ログイン
	    	if (isset($_POST['action'])) {
	    		$action=$_POST['action'];

	    		//普通のログイン
	    		if ($action=="login") {
	    			$mail=$_POST['mail'];
	    			$pass=$_POST['pass'];

				$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE mail = :mail AND pass = :pass ');
				$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
				$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
					$_SESSION['id']=$row['id'];
				}
	    		}

			//サインインからのログイン
			if ($action=="sign_login") {
				$mail=$_POST['mail'];

				$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE mail = :mail ');
				$stmt -> bindParam(':mail', $mail, PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
					$_SESSION['id']=$row['id'];
				}
			}	    		
	    	}

	    	//ページの取得
	    	if (isset($_GET['page'])) {
	    		$page=$_GET['page'];
	    	} else {
	    		$page=1;
	    	}

	    	$title="";
	    	//ログイン確認
	    	if (isset($_SESSION['id'])) {
	    		$id=$_SESSION['id'];

			$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id=:id');
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();	

			while ($row = $stmt->fetch()) {
				//名前の取得
				$user_name=$row['name'];

				$title.="<div class=title_name>";
					$title.=$user_name . "さん";
				$title.="</div>";

				$title.="<div class=title>";
					$title.="<form action=index.php method=post>";
						$title.="<input type=submit value=ログアウト>";
						$title.="<input type=hidden name=action value=logout>";
					$title.="</form>";
				$title.="</div>";

				$title.="<div class=title>";
					$title.="<form action=upload.php method=post>";
						$title.="<input type=submit value=投稿>";
						$title.="<input type=hidden name=action value=upload>";
						$title.="<input type=hidden name=page_num value=" . $page . ">";
						$title.="<input type=hidden name=user_name value=" . $user_name . ">";                                  
					$title.="</form>";
				$title.="</div>";
			}
	    	} else {
	    			$title.="<div class=title>";
	    				$title.="<form action=index.php method=post>";
				    		$title.="<input class=form type=text name=mail placeholder=メール required>";
				    		$title.="<input class=form type=password name=pass placeholder=パスワード required>";
				    		$title.="<input type=submit value=ログイン>";
				    		$title.="<input type=hidden name=action value=login>";
	    				$title.="</form>";
	    			$title.="</div>";

		    		$title.="<div class=title>";
		    			$title.="<form action=sign.php method=post>";
		    				$title.="<input type=submit value=サインイン>";
		    				$title.="<input type=hidden name=page value=index.php>";
		    				$title.="<input type=hidden name=page_num value=" . $page . ">";
		    			$title.="</form>";
		    		$title.="</div>"; 	
	    	}
	    	$title.="<div class=floatclear></div>";
		
	    	$page_start=$page*8-8;

		$stmt = $pdo->prepare('SELECT tb_contents.*, tb_member.name FROM tb_contents, tb_member WHERE tb_contents.owner_id=tb_member.id AND state = "0" LIMIT :start,8 ');
		$stmt -> bindParam(':start', $page_start, PDO::PARAM_INT);
		$stmt -> execute();

		$img="";
		$num=0;
		while ($row = $stmt->fetch()) {
			


			$num=$num+1;

			$img.="<div class=img_frame>";
				$img.="<form action=video.php method=get>";
		   			$img.="<input type=image src=img/" . $row['content'] . ".png>";
		   			$img.="<input type=hidden name=id value=" . $row['id'] . ">";
		   			$img.="<input type=hidden name=page_num value=" . $page . ">";
		   		$img.="</form>";
		   		$img.="<span>" . $row['title'] . "</span><br>";			   		
		   		$img.="投稿者：" . $row['name'];
		   	$img.="</div>";

		   	if ($num%4==0) {
		   		$img.="<div class=floatclear></div>";
		   	}	
		}

		$img.="<div class=floatclear></div>";

		$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_contents');

		$stmt -> execute();

		$row = $stmt->fetchColumn();
			$max_page=ceil($row/8);

			$all_page="";
			for ($i=1; $i <= $max_page; $i++) { 
				if ($i==$page) {
					$all_page.=$i . " ";
				} else {
					$all_page.="<a href=index.php?page=" . $i . ">" . $i . "</a> ";
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
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
	<script type="text/javascript">
		function show() {
			window.location.href = 'index.php';
		}
	</script>
<body>
　　<div class="body">
		<div class="title_frame">
			<div class="title_page" onclick="show()">
				Mytube
			</div>

			<?php echo $title; ?>			
		</div>


		<div class="content">
			<?php echo $img; ?>
		</div>
	</div>

	<div class="all_page">
		<?php echo $all_page;?>
	</div>

</body>
</html>
