<!-- TOP画面 -->
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
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/vegas/2.4.4/vegas.min.css">
	<link rel="stylesheet" href="./css/top.css" type="text/css">
	<title>ウマ娘</title>
</head>
<body>
	<div class="main">
		<div class="main_nav">
			<header id="header">
				<div class="header_logo">
					<a href="top.php"><img src="./img/logo.png"></a>
				</div>
				<div class="header_nav">
					<nav>
						<ul>
							<li><a href="about.php" class="btnripple3">About</a></li>
							<li><a href="index.php" class="btnripple3">一覧</a></li>
							<li><a href="sign_up.php" class="btnripple3">新規登録</a></li>
							<li><a href="sign_in.php" class="btnripple3">ログイン</a></li>
						</ul>
					</nav>
				</div>
			</header>
		</div>

		<div id="slider"></div>

	</div>

<footer>
</footer>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vegas/2.4.4/vegas.min.js"></script>
<script src="./js/top.js" ></script>

</body>
</html>
