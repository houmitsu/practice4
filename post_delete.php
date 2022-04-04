<!-- 投稿削除画面 -->
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

	$ID =$_GET['id'];
	/* 削除処理 */
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	  /* ログイン状態で、かつ退会ボタンを押した */
	  if (isset($_SESSION['userId']) && isset($_POST['is_delete']) && $_POST['is_delete'] === '1') {
	    /* データベース接続 */
	  	$db = new DbconnectClass();
	    /* 削除 */
	    $stmt = $db->getDbconnect()->prepare('delete from posts where post_id = :id;');
	    $stmt->bindValue(':id', $ID, PDO::PARAM_INT);
	    $stmt->execute();
	    header('Location: ./index.php');
	    exit;
	  }
	}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/post_delete.css" type="text/css">
	<title>ウマ娘</title>
</head>
<body>
	<header>
		<?php
			// ログイン有無で変わる
			if(isset($_SESSION['userId'])){
				include'global_menu2.php';
			}else{
				include'global_menu.php';
			}
		?>
	</header>

	<div class="main">
		<p><FONT COLOR="RED">本当に削除してもよろしいでしょうか?</FONT></p>
		<div>
			<form action="./post_delete.php?id=<?php echo $ID; ?>" method="POST">
				<div class="btn">
      				<input  type="hidden" name="is_delete" value="1">
      				<button class="button" type="button" onclick=history.back()>戻る</button>
      				<input class="button" type="submit" value="削除する">
      			</div>
    		</form>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>