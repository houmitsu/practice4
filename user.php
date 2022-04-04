<!-- マイページ画面 -->
<?php

//セッションスタート
session_start();

//DB接続class読込、共通関数読込
include 'dbconnect.php';
include 'function.php';

//セッションを再作成し、古いセッションを削除する
session_regenerate_id(true);

//ログインの有無
 if (!isset($_SESSION[userId])) {
 	header("Location: ./index.php");
 	exit();
 }

//$_SESSION['actionName']に"user_data"を格納
$_SESSION['actonName'] = "user_data";



?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/user.css" type="text/css">
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
		<div>
			<h1>マイページ</h1>
		</div>

		<div>
			<h2>登録情報</h2>

			<!-- 	ARTICLEとCOLOR_MASTERを外部結合させDBに接続する -->
			<?php
			$ID = $_GET['id'];

			$db = new DbconnectClass();

			// 各データを取得し、ARTICLE.CREATE_DATEの降順で一覧を表示
			$stmt = $db->getDbconnect()->prepare("
						select
							user_id,
							name,
							email,
							introduction
						from
							users
						where
							user_id=:id
					;");
			$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
			// 実行する
			$stmt->execute();
			// 取得した1レコードを連想配列で$rowに代入
			$row = $stmt->fetch();



			?>

		<div class="user_infomation">
			<div class="name">
				<p>名前</p>
				<p><?php echo htmlspecialchars($row['name'],ENT_QUOTES,"UTF-8");?></p>
			</div>
			<?php if ($_SESSION['userId'] == $row['user_id']){?>
			<div class="email">
				<p>Eメール</tp>
				<p><?php echo htmlspecialchars($row['email'],ENT_QUOTES,"UTF-8");?></p>
			</div>
			<?php } ?>
			<div class="introduction">
				<p class="introduction_p">自己紹介</p>
				<p><?php echo nl2br(htmlspecialchars($row['introduction'],ENT_QUOTES,"UTF-8"));?></p>
			</div>
		</div>

			<div class="hensyu">
				<?php if ($_SESSION['userId'] == $row['user_id']){?>
					<a href="user_edit.php?id=<?php echo $row['user_id']; ?>">登録情報を編集する</a>
				<?php } ?>
			</div>

		<?php
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare(
					"select
						user_id,
						post_id,
						title,
						text,
						image,
						create_at
					from
						posts
					where
						user_id = :id
					order by create_at desc;");
			$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
			$stmt->execute();

			while ($row = $stmt->fetch()){
			$img = base64_encode($row['image']);
		?>
			<div class="table">
				<div class="foodimage">
					<a href="post_show.php?id=<?php echo $row['post_id']; ?>">
					<img src="data:image/jpg;base64,<?php echo $img; ?>"></a>
				</div>
				<div class="foodtitle">
					<?php echo nl2br(htmlspecialchars($row['title'],ENT_QUOTES,"UTF-8"));?>
				</div>
				<div class="foodtext">
					<?php echo htmlspecialchars($row['text'],ENT_QUOTES,"UTF-8");?>
				</div>
				<div class="date">
					<?php echo date("Y年m月d日 h時i分",strtotime($row['create_at']));?>
				</div>
			</div>
		<?php } ?>


	</div>
	<footer>
	</footer>
</body>
</html>