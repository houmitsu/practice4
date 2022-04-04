<!-- 投稿詳細画面 -->
<?php
session_start();

include 'dbconnect.php';
include 'function.php';


// セッションハイジャック対策
session_regenerate_id(true);

	//ログインの有無
if(!isset($_SESSION['userId'])){
	header('Location: ./index.php');
	exit();
}


if (isset($_POST['Submit'])){

	$_SESSION['post_id'] = $_POST['post_id'];

	//エラー変数を空にする
	$err = "";

	//文字数チェック
	if(!checkLen($_POST['text'], 150)){
		$err .= "コメントは150文字以内で入力してください。<br>";
	}

	//空文字チェック
	if(isBlank($_POST['text'])){
		$err .= "コメントを入力してください。 <br>";
	}
	if (isBlank($err)){

		//DBに接続してコメントを保存する
		$db = new DbconnectClass();
		$stmt = $db->getDbconnect()->prepare(
				"insert into comments(
					create_at,
					post_id,
					user_id,
					comment)
				values(
					now(),
					:post_id,
					:user_id,
					:comment
				)");
		$stmt->bindParam(':post_id', $_SESSION['post_id'], PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $_SESSION['userId'], PDO::PARAM_STR);
		$stmt->bindParam(':comment', $_POST['text'], PDO::PARAM_STR);
		$stmt->execute();

		$_SESSION['actionName'] = "post_show_submit";


		header("Location: ./post_show.php?id={$_SESSION['post_id']}");
		exit();
	}

// 戻るボタン押下
}elseif(isset($_POST['Back'])) {


	// S_SESSION['actionName']にconfirm_backを格納
	 	$_SESSION['actionName'] = "confirm_back";

	 // 一覧画面（input.php）に遷移
	 	header("Location: ./index.php");
	 	exit();

//クリアボタン押下時
}elseif (isset($_POST['clear'])) {

	//セッションを空にする
	$_SESSION['comment']	= "";

	//$_SESSION['actionName']に"post_clear"を格納
	$_SESSION['actionName'] = "post_clear";

//何も押していないとき
}else{

	$_SESSION['actionName'] = "post_show_display";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/post_show.css" type="text/css">
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
	<main>
		<?php

			$id = $_GET[id];

			//DBに接続して投稿情報を取得
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare(
					"select
						posts.post_id,
						posts.user_id,
						posts.title,
						posts.text,
						posts.create_at,
						posts.image,
						users.name
					from
						posts
					left join users
					on posts.user_id = users.user_id
					where
						post_id = :id;"
					);
			//一覧画面ができてからidを取得して一つだけ出力するようにする
			$stmt->bindParam(':id',$id, PDO::PARAM_INT);
 			$stmt->execute();

			//DBから取得したデータを格納
			$row = $stmt->fetch();
			$img = base64_encode($row['image']);
		?>
		<div>
			<div class="post_show_img">
				<img src="data:image/jpg;base64,<?php echo $img; ?>">
			</div>
			<div>
				<div class="title">
					<?php echo htmlspecialchars($row['title'], ENT_QUOTES, "UTF-8"); ?>
				</div>
				<div class="text">
					<?php echo htmlspecialchars($row['text'], ENT_QUOTES, "UTF-8"); ?>
				</div>

				<div class="user_detail">
					<div class="username">
						<a href="./user.php?id=<?php echo $row['user_id']; ?>">
							<?php echo htmlspecialchars($row['name'], ENT_QUOTES, "UTF-8"); ?>
						</a>
					</div>
					<div class="date">
							<?php echo date("Y年m月d日 H時i分", strtotime($row['create_at']))?>
					</div>
				</div>
				<div class="a">
					<div class="delete">
						<?php if ($_SESSION['userId'] == $row['user_id']){?>
							<a href="post_delete.php?id=<?php echo $row['post_id']; ?>">削除</a>
						<?php } ?>
					</div>
					<div class="edit">
						<?php if ($_SESSION['userId'] == $row['user_id']){?>
							<a href="post_edit.php?id=<?php echo $row['post_id']; ?>">編集</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<hr>
			<p><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
			<form action="./post_show.php?id=<?php echo $row['post_id']; ?>" method="post">
				<input type="hidden" name="post_id" value="<?php
					echo(htmlspecialchars($row['post_id'], ENT_QUOTES, 'UTF-8'));?>">
				<input type="hidden" name="user_id" value="<?php
					echo(htmlspecialchars($_SESSION['userId'], ENT_QUOTES, 'UTF-8'));?>">
				<div class="text_area">
					<textarea class="textarea" name="text" rows="5" cols="51" placeholder="コメントはここ"></textarea>
				</div>
				<div class="btn">
					<input class="button" type="submit" name="Back" value="戻る">
					<input class="button" type="submit" name="clear" value="クリア">
					<input class="button" type="submit" name="Submit" value="送信する">
				</div>
			</form>
		</div>
		<hr>
		<?php
			//DBに接続してコメントを一覧表示する
		 	$stmt = $db->getDbconnect()->prepare(
		 			"select
		 				comments.comment,
		 				comments.create_at,
		 				users.name,
		 				users.user_id
		 			from
		 				comments
		 			left join
		 				users
		 			on
		 				comments.user_id = users.user_id
		 			where
		 				post_id = :id
		 			order by
		 				create_at desc;");
		 	$stmt->bindParam(':id',$id, PDO::PARAM_INT);
		 	$stmt->execute();

		 	//繰り返し処理
		 	while ($row = $stmt->fetch()){
		?>
		<div class="comment">
				<div class="comment_userditail">
					<div class="comment_name">
						<a href="./user.php?id=<?php echo $row['user_id']; ?>">
							<?php echo htmlspecialchars($row['name'], ENT_QUOTES, "UTF-8"); ?>
						</a>
					</div>
					<div class="comment_date">
						<?php echo date("Y年m月d日 H時i分", strtotime($row['create_at'])); ?>
					</div>
				</div>
				<hr class="comment_hr">
				<div class="comment_text">
					<?php echo htmlspecialchars($row['comment'], ENT_QUOTES, "UTF-8"); ?>
				</div>
		</div>
		<?php } ?>

	</main>
	<footer>
	</footer>
</body>
</html>