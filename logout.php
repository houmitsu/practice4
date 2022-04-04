<?php

// セッション開始
session_start();

//DB接続class読込、共通関数読込
include 'dbconnect.php';
include 'function.php';

// セッション変数のクリア
$_SESSION = array();
// セッションクリア
session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/logout.css" type="text/css">
	<title>ウマ娘</title>
</head>
<body>
	<header>
		<?php
			if(isset($SESSION['userId'])){
				include'global_menu2.php';
			}else{
				include'global_menu.php';
			}
		?>
	</header>
	<main>
		<h2>ログアウトしました</h2>
		<p><a href='sign_in.php'>ログインページに戻る</a></p>

	</main>
	<footer>
	</footer>
</body>
</html>