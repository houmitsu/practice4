<!-- 投稿一覧画面 -->
<?php
	session_start();

	include 'dbconnect.php';
	include 'function.php';

	session_regenerate_id(true);

	if (isset($_POST['Post'])) {

		// S_SESSION['actionName']にpost_goを格納
		$_SESSION['actionName'] = "post_go";

		// 一覧画面（input.php）に遷移
		header("Location: ./post.php");
		exit();
	}

 	if (isset($_POST['submit'])) {
 		if (!empty($_POST['search'])) {
 			$_SESSION['search'] = $_POST['search'];

 			$_SESSION['actionName'] = "index_submit";

 			header("Location: ./search.php");
 		} else {
 			$err = "入力してください";
 		}
 	}

?>
<!-- 一覧画面 -->
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="./css/index.css" type="text/css">
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

	<h1>投稿一覧</h1>
	<div class="search_btn">
		<div class="search">
			<form action="./index.php" method="post">
				<input class="text" type="text" name="search" value="<?php echo $search_value; ?>">
				<input class="button" type="submit" name="submit" value="検索">
			</form>
			<div class="msg">
				<p><?php echo $err; ?></p>
			</div>
		</div>
		<div class="newpost">
			<form action="./index.php" method="post">
				<div class="back_btn">
					<input class="button" type="submit" name="Post" value="新しく投稿する">
				</div>
			</form>
		</div>
	</div>

	<?php
	$db = new DbconnectClass();

	//一ページに表示する記事の数をmax_viewに定数として定義
	define('max_view',4);

	//必要なページ数を求める
	$count = $db->getDbconnect()->prepare(
			"select count(*) as count from posts;");
	$count->execute();
	$total_count = $count->fetch(PDO::FETCH_ASSOC);
	$pages = ceil($total_count['count'] / max_view);

	//現在いるページのページ番号を取得
	if(!isset($_GET['page_id'])){
		$now = 1;
	}else{
		$now = $_GET['page_id'];
	}

	$select = $db->getDbconnect()->prepare(
			"select
				posts.post_id,
				posts.title,
				posts.text,
				posts.image,
				posts.create_at,
				posts.user_id,
				users.name
			from
				posts
			left join
				users
			on
				posts.user_id = users.user_id
			order by create_at desc limit :start,:max;");

	if ($now == 1){
		//1ページ目の処理
		$select->bindValue(":start",$now -1,PDO::PARAM_INT);
		$select->bindValue(":max",max_view,PDO::PARAM_INT);
	} else {
		//1ページ目以外の処理
		$select->bindValue(":start",($now -1 ) * max_view,PDO::PARAM_INT);
		$select->bindValue(":max",max_view,PDO::PARAM_INT);
	}

	$select->execute();

	while ($row = $select->fetch()){
	$img = base64_encode($row['image']);
	?>

		<div class="table">
			<div class="foodimage">
				<a href="post_show.php?id=<?php echo $row['post_id']; ?>">
				<img src="data:image/jpeg;base64,<?php echo $img; ?>"></a>
			</div>
			<div class="username">
				<a href="./user.php?id=<?php echo $row['user_id']; ?>">
				<?php echo htmlspecialchars($row['name'],ENT_QUOTES,"UTF-8");?>
				</a>
			</div>
			<div class="foodtitle">
				<?php echo htmlspecialchars($row['title'],ENT_QUOTES,"UTF-8");?>
			</div>
			<div class="foodtext">
				<?php echo nl2br(htmlspecialchars($row['text'],ENT_QUOTES,"UTF-8"));?>
			</div>
			<div class="date">
				<?php echo date("Y年m月d日 h時i分",strtotime($row['create_at']));?>
			</div>
		</div>
	<?php } ?>

	<?php
	//ページネーションを表示
	for ( $n = 1; $n <= $pages; $n ++){
		if ( $n == $now ){
			echo "<div class='page'><span style='padding: 40px 10px ; display: block; text-align: center;'>$now</span>";
		}else{
			echo "<a href='./index.php?page_id=$n' style='padding: 40px 10px ; display: block; text-align: center;'>$n</a></div>";
		}
	}
	?>
</body>
</html>