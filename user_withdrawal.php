<!-- 退会画面 -->
<?php

	/* セッション開始 */
	session_start();


	/* データベース情報を取り込み */
	include 'dbconnect.php';

	/* 未ログイン状態ならトップへリダイレクト */
 	if (!isset($_SESSION['userId'])) {
 	  header('Location: ./top.php');
	  exit;
	}

	/* 退会処理 */
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	  /* ログイン状態で、かつ退会ボタンを押した */
	  if (isset($_SESSION['userId']) && isset($_POST['is_delete']) && $_POST['is_delete'] === '1') {
	    /* データベース接続 */
	  	$db = new DbconnectClass();
	    /* 退会 */
	    $stmt = $db->getDbconnect()->prepare('DELETE FROM users WHERE name = ?');
	    $stmt->bindValue(1, $_SESSION['name']);
	    $stmt->execute();

	    session_destroy(); // セッションを破壊

	    header('Location: ./top.php');
	    exit;
	  }
	}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="./css/user_withdrawal.css" type="text/css">
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
	<div class="main">
	<div id="wrapper">
		<div>
			<div>
				<div class="title">本当に退会するだなも？</div>
			</div>
			<div class="text">
				<p>退会すると、会員情報や<br><br>
					これまでの投稿が閲覧できなくなるだなも。<br><br>
					退会する場合は「退会する」をクリックするだなも。</p>
			</div>
		</div>

		<div class="tanuki">
			<img src="./img/tanuki.jpg">
		</div>

		<div>
			<form action="./user_withdrawal.php" method="POST">
      			<input type="hidden" name="is_delete" value="1">
      			<div class="taikai">
      				<input class="button" type="submit" value="退会する">
      			</div>
    			</form>
		</div>
	</div>
	</div>
	<footer>
	</footer>
</body>
</html>