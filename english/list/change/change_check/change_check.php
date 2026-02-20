<?php
	$english=$_POST['english_word'];
	$japanese=$_POST['japanese_word'];
	$id=$_POST['id'];
	//echo $english;
	$html_code="";
	$html_code.="<div class=language>英</div>";
	$html_code.="<div class=language>日</div>";
	$html_code.="<div class=floatclear></div>";
	$html_code.="<div class=contents>".$english."</div>";
	$html_code.="<div class=contents>".$japanese."</div>";
	$html_code.="<div class=floatclear></div>";
	$html_code.="<div class=button>"."<form action=../change.php method=post>"."<input type=submit value=戻る>"."<input type=hidden name=id value=".$id.">"."<input type=hidden name=english value=".$english.">"."<input type=hidden name=japanese value=".$japanese.">"."<input type=hidden name=page value=check>"."</form>"."</div>";
	$html_code.="<div class=button>"."<form action=change_end/change_end.php method=post>"."<input type=submit value=変更>"."<input type=hidden name=id value=".$id.">"."<input type=hidden name=english value=".$english.">"."<input type=hidden name=japanese value=".$japanese.">"."</form>"."</div>";
	$html_code.="<div class=floatclear></div>"


?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>これはテストです。</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="title"><h2>確認</h2></div>
	<div id="body">
		<?php echo $html_code; ?>
	</div>


</body>
</html>