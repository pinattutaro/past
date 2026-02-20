<?php
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
		   	echo $row['フィルド名'] . "<br />";
		}

	} catch (PDOException $e) {
	    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	    	exit($e->getMessage()); 
	}

	//一括取得


?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>sample</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>

</body>
</html>