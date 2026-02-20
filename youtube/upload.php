<?php
    session_start();

    //ログインのチェック
    if (!isset($_SESSION['id'])) {
        header("Location: index.php");
    }

    $user_id=$_SESSION['id'];
    $user_name=$_POST['user_name'];
    echo $user_name;

    //アップロード処理
    if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name']) && isset($_FILES['video']) && is_uploaded_file($_FILES['video']['tmp_name'])) {
       // echo "hello";

       //時間の取得
        date_default_timezone_set('Asia/Tokyo');
        $new_name=date("YmjGis");

        $img_old_name=$_FILES['img']['tmp_name'];
        $video_old_name=$_FILES['video']['tmp_name'];

        if (move_uploaded_file($img_old_name, 'img/' . $new_name . ".png") && move_uploaded_file($video_old_name, 'video/' . $new_name . ".mp4")) {
            echo "<h2>成功</h2>";
        } else {
            echo "<h2>失敗</h2>";
        }

        //タイトルの取得
        $title=$_POST['title']; 

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

            $stmt = $pdo->prepare('INSERT INTO tb_contents (content, title, owner_id)  value( :content, :title, :owner_id)');
            $stmt -> bindParam(':content', $new_name, PDO::PARAM_INT);
            $stmt -> bindParam(':title', $title, PDO::PARAM_STR);
            $stmt -> bindParam(':owner_id', $user_id, PDO::PARAM_INT);
            $stmt -> execute();

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
    <div class="upload">
        <form action="upload.php" method="post" enctype="multipart/form-data">
            サムネイル画像<input type="file" name="img"><br>
            動画<input type="file" name="video"><br>
            タイトル<input type="text" name="title" value="無題" required><br>
            <?php echo "<input type=hidden name=user_name value=" . $user_name . ">"; ?>
            <input type="submit" value="アップロード">
        </form>       
    </div>

    <div class="button">
        <form action="index.php" method="post">
            <input type="submit" value="戻る">
        </form>
    </div>
</body>
</html>