<!-- ABOUT画面 -->
<?php

//セッションスタート
session_start();

//DB接続class読込、共通関数読込
include 'dbconnect.php';
include 'function.php';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="./css/about.css" type="text/css">
	<title>ウマ娘</title>
</head>
<body>
	<header>
		<?php
			if(isset($_SESSION['userId'])){
				include'global_menu2.php';
			}else{
				include'global_menu.php';
			}
		?>
	</header>
	<main>
		<div>
			<h1>ウマ娘について</h1>		</div>
		<div class="text">
			このサイトは全国各地の「うまい」を届けるべくして生まれました。<br>
			あなたの「うまい」を届け、そして、あなたの新しい「うまい」を見つけてください。
		</div>
	</main>
	<footer>
	</footer>
</body>
</html>
