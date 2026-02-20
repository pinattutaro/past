<?php
	$english=$_POST['add_english'];
	$japanese=$_POST['add_japanese'];
	//echo $english;

	$html_code="";
	$html_code.="<div class=language>"."英"."</div>";
	$html_code.="<div class=language>"."日"."</div>";
	$html_code.="<div class=floatclear></div>";
	$html_code.="<div class=content>".$english."</div>";
	$html_code.="<div class=content>".$japanese."</div>";
	$html_code.="<div class=floatclear></div>";
	$html_code.="<div class=button>"."<form action=add/add.php method=post>"."<input type=submit value=追加>"."<input type=hidden name=add_english value=".$english.">"."<input type=hidden name=add_japanese value=".$japanese.">"."</form>"."</div>";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>list</title>
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body>
	<div id="title"><h2>確認</h2></div>

	<div id="body">
		<?php echo $html_code; ?>
	</div>

	<div class="button">
		<form action="../home/index.html" method="post">
			<input type="submit" value="取り消し">
		</form>
	</div>

	<div class="floatclear"></div>
</body>
</html>