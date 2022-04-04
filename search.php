<!-- 検索結果画面 -->
<?php
	session_start();

	include 'dbconnect.php';
	include 'function.php';

	session_regenerate_id(true);

	//検索が押された場合
	if (isset($_POST[submit])){
		$_SESSION['search'] = $_POST['search'];
	}

	if (isset($_SESSION['search'])){
		//var_dump($_SESSION['search']);
		$search = htmlspecialchars($_SESSION['search']);
		$search_value = $search;

		$db = new DbconnectClass();
		$stmt = $db->getDbconnect()->prepare(
				"select
					*
				from
					posts
				where
					title
				like
					'%$search%'
				order by create_at desc;");
		$stmt->execute();
		$row2 = $stmt->fetch();
	}

	if ($_SESSION['search'] == "" ){
		$msg .=  "入力してください";
	}elseIf (empty($row2) ){
		$msg = "該当なし";
	}else {
		$msg = "検索結果";
	}



?>
<!-- 一覧画面 -->
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="./css/search.css" type="text/css">
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
	<h1>検索</h1>
	<form action="./search.php" method="post">
		<input class="text" type="text" name="search" value="<?php echo $search_value ?>">
		<input class="button" type="submit" name="submit" value="検索">
	</form>
	<div class="msg">
		<p><?php echo $msg; ?></p>
	</div>

	<?php

		$db = new DbconnectClass();
				$stmt = $db->getDbconnect()->prepare(
					"select
						*
					from
						posts
					where
						title
					like
						'%$search%'
					order by create_at desc;");
				$stmt->execute();

		while ($row = $stmt->fetch()){
			$img = base64_encode($row['image']);
	?>

	<div class="post_show_item">
		<a href="post_show.php?id=<?php echo $row['post_id']; ?>">
			<div class="search_img">
				<img src="data:image/jpg;base64,<?php echo $img; ?>"><br>
			</div>
		</a>
		<div class="post_show_title">
			<?php echo $row['title'] ?><br>
		</div>
		<div class="post_show_text">
			<?php echo $row['text']; ?></p>
		</div>
	</div>
	<?php } ?>
</body>
</html>