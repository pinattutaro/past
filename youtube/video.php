<?php
	session_start();

	//ログアウト
	if (isset($_POST['action'])) {
		$action=$_POST['action'];

		if ($action=="logout") {
			unset($_SESSION['id']);
		}
	}

	if (isset($_GET['id'])) {
		$video_id=$_GET['id'];
	} else if (isset($_POST['id'])) {
		$video_id=$_POST['id'];
	} else {
		header("Location: index.php");
		exit();
	}

	if (isset($_GET['page_num'])) {
		$page=$_GET['page_num'];
		//echo $page;	
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

	    		//動画の存在確認
	    		$stmt = $pdo->prepare('SELECT * FROM tb_contents WHERE id = :id AND state = "削除"');
				$stmt -> bindParam(':id', $video_id, PDO::PARAM_STR);
				$stmt -> execute();

				while ($row = $stmt->fetch()) {
						header("Location: index.php");	
				}

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


		    	//ログイン確認
				$title="";	    		
		    	if (isset($_SESSION['id'])) {
		    		$user_id=$_SESSION['id'];
		    		//echo $user_id;

					$stmt = $pdo->prepare('SELECT * FROM tb_member WHERE id=:id');
					$stmt -> bindParam(':id', $user_id, PDO::PARAM_INT);
					$stmt -> execute();	

					while ($row = $stmt->fetch()) {
						$user_name=$row['name'];

						$title.="<div class=name_frame>";
							$title.="<div class=title_name>";
								$title.=$user_name . "さん";
							$title.="</div>";

							$title.="<div class=button>";
								$title.="<form action=video.php method=post>";
									$title.="<input type=submit value=ログアウト>";
									$title.="<input type=hidden name=action value=logout>";
									$title.="<input type=hidden name=id value=" . $video_id . ">";
								$title.="</form>";
							$title.="</div>";

				    		$title.="<div class=button>";
				    			$title.="<form action=index.php method=post>";
				    				$title.="<input type=submit value=戻る>";
				    			$title.="</form>";
				    		$title.="</div>";							
						$title.="</div>";
					}
		    	} else {

		    		$title.="<div class=title>";
		    			$title.="<div class=field_login>";
		    				$title.="<form action=video.php method=post>";
					    		$title.="<input class=form type=text name=mail placeholder=メール required>";
					    		$title.="<input class=form type=password name=pass placeholder=パスワード required>";
					    		$title.="<input type=submit value=ログイン>";
					    		$title.="<input type=hidden name=action value=login>";
					    		$title.="<input type=hidden name=id value=" . $video_id . ">";
		    				$title.="</form>";
		    			$title.="</div>";

			    		$title.="<div class=button>";
			    			$title.="<form action=sign.php method=post>";
			    				$title.="<input type=submit value=サインイン>";
			    				$title.="<input type=hidden name=page value=video.php>";
			    				$title.="<input type=hidden name=video value=" . $video_id . " >";
			    			$title.="</form>";
			    		$title.="</div>";

			    		$title.="<div class=button>";
			    			$title.="<form action=index.php method=get>";
			    				$title.="<input type=submit value=戻る>";
			    				if (isset($page)) {
			    					$title.="<iuput type=hidden name=page value= ". $page . " >";
			    				}
			    			$title.="</form>";
			    		$title.="</div>";
			    	$title.="</div>";

			    	$title.="<div class=floatclear></div>";
		    	}

				//コメント
		    	if (isset($_POST['action']) && $_POST['action']=="comment") {
		    		$comment=$_POST['comment'];

		    		date_default_timezone_set('Asia/Tokyo');
		    		$date=date("Y年m月j日G時i分s秒");

					$stmt = $pdo->prepare('INSERT INTO tb_chat (user, video_id, sentence, date)  value( :user, :video_id, :sentence, :date)');
					$stmt -> bindParam(':user', $user_name, PDO::PARAM_STR);
					$stmt -> bindParam(':video_id', $video_id, PDO::PARAM_INT);
					$stmt -> bindParam(':sentence', $comment, PDO::PARAM_STR);
					$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
					$stmt -> execute();

		    	}
		
				$stmt = $pdo->prepare('SELECT tb_contents.content, tb_contents.title, tb_contents.owner_id, tb_member.name FROM tb_contents, tb_member WHERE tb_contents.owner_id=tb_member.id AND tb_contents.id = :id');
				$stmt -> bindParam(':id', $video_id, PDO::PARAM_STR);
				$stmt -> execute();

				$video="";
				while ($row = $stmt->fetch()) {
					$video.="<div class=video_frame>";
				   		$video.="<video src=video/" . $row['content'] . ".mp4 controls></video>";
				   		$video.="<div class=video_title>" . $row['title'] . "</div>";
				   		$video.="<div class=video_title>投稿者：" . $row['name'] . "</div>";
				   	$video.="</div>";

				   	$owner_id=$row['owner_id'];
				}

		    	//コメントの表示
				$stmt = $pdo->prepare('SELECT * FROM tb_chat WHERE video_id = :video_id');
				$stmt -> bindParam(':video_id', $video_id, PDO::PARAM_STR);
				$stmt -> execute();

				$comment_indication="";
				while ($row = $stmt->fetch()) {
				   	$comment_indication.="<div class=comment>";
				   		$comment_indication.=$row['user'] . "<br>";
				   		$comment_indication.=$row['sentence'];
				   	$comment_indication.="</div>";

				   	$comment_indication.="<div class=date>";
				   		$comment_indication.=$row['date'];
				   	$comment_indication.="</div>";

				   	$comment_indication.="<div class=floatclear></div>";
				}


		    	//動画の削除
		    	if (isset($_POST['action']) && $_POST['action']=="deletion") {
					$stmt = $pdo->prepare('UPDATE tb_contents SET state = "削除"  WHERE id = :id ');
					$stmt -> bindParam(':id', $video_id, PDO::PARAM_STR);
					$stmt -> execute();

					header("Location: index.php");
				}

				//ログイン済者の機能
				$comment_html="";
				$deletion="";
				if (isset($user_id)) {
					$comment_html.="<form action=video.php method=post>";
						$comment_html.="<input type=text name=comment placeholder=コメント required>";
						$comment_html.="<input type=submit value=投稿>";
						$comment_html.="<input type=hidden name=action value=comment>";
						$comment_html.="<input type=hidden name=id value=" . $video_id . ">";
					$comment_html.="</form>";

					//管理者の権限
					if ($owner_id==$user_id) {
						$deletion.="<form name=form1 action=video.php method=post onsubmit='return chk()'>";
							$deletion.="<input type=checkbox name=approval value=deletion>削除を許可する<br>";
							$deletion.="<input type=submit value=削除>";
							$deletion.="<input type=hidden name=action value=deletion>";
							$deletion.="<input type=hidden name=id value=" . $video_id . ">";
						$deletion.="</form>";
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
		function chk(){
			//alert("ok");
			if (document.form1.approval.checked == false) {
				alert("動画の削除を許可してください");
				return false;
			} else {
				return true;
			}
		}

		function show(){
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
			<div class="floatclear"></div>			
		</div>


		<div class="content">
			<?php echo $video; ?>

			<div class="sub_content">
				<?php echo $deletion; ?><br>

				<?php echo $comment_html; ?><br>

				<?php echo $comment_indication; ?>
			</div>			
		</div>
	</div>
</body>
</html>