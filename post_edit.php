<!-- 投稿画面 -->
<?php

// セッションスタート
session_start();

// DB接続と関数読込
include 'dbconnect.php';
include 'function.php';

// セッションハイジャックの対策
session_regenerate_id(true);

// ログイン有無の確認
if (!isset($_SESSION['userId'])) {
	header("Location: ./sign_in.php");
	exit();
}

// 投稿ボタン押下時
if (isset($_POST['Submit'])) {

	// トークン一致の確認
	if ($_POST['token'] === $_SESSION['token']) {

		// エラー変数を空にする
		$err = "";
	}

// 	if (empty($_FILES['pic']['name'])) {
// 		$err .= '画像を入れてください<br>';
// 	}

	// タイトルが空欄の場合
	if (isBlank($_POST['title'])) {
		$err .= 'タイトルを記入してください<br>';
	}

	// タイトルが51文字以上の場合
	if (!checkLen($_POST['title'],50)) {
		$err .= 'タイトルは50文字以内で記入してください<br>';
	}

	// 詳細の文字数が101文字以上の場合
	if (!checkLen($_POST['data'],100)) {
		$err .= '詳細は100文字以内で記入してください<br>';
	}

	//$errが空である
	if (isBlank($err)) {

// 		$content = file_get_contents($_FILES['pic']['tmp_name']);

		// POSTのデータをSESSIONに格納
// 		$_SESSION['pic']	= $content;
		$_SESSION['title']	= $_POST['title'];
		$_SESSION['data']	= $_POST['data'];



		// DBに$_SESSIONのデータを保存
		$db = new DbconnectClass();
		$stmt = $db->getDbconnect()->prepare(
				"update
					posts
				set
					update_at	= now(),
					title		= :title,
					text		= :data
				where
					post_id = :id;
				");
		$stmt->bindParam(':data',$_SESSION['data'], PDO::PARAM_STR);
		$stmt->bindParam(':title',$_SESSION['title'], PDO::PARAM_STR);
		$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->execute();

		//$_SESSION['actionName']に"post_check"を格納
		$_SESSION['actionName'] = "post_check";

		//投稿一覧画面に遷移
		header("Location: ./index.php");
		exit();
	}

// クリアボタン押下時
} elseif (isset($_POST['clear'])) {

	//セッションを空にする
	$_SESSION['pic']	= "";
	$_SESSION['title']	= "";
	$_SESSION['data']	= "";

	//$_SESSION['actionName']に"post_clear"を格納
	$_SESSION['actionName'] = "post_clear";

// 戻るボタン押下
} elseif (isset($_POST['Back'])) {

	// S_SESSION['actionName']にconfirm_backを格納
	$_SESSION['actionName'] = "confirm_back";

	// 一覧画面（input.php）に遷移
	header("Location: ./post_show.php?id={$_SESSION['id']}");
	exit();
} else {

	//$_SESSION['actionName']に"post_display"を格納
	$_SESSION['actionName'] = "post_display";
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/post_edit.css" type="text/css">
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
		<?php
			$id = $_GET['id'];
			//DBに接続して投稿情報を取得
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare(
					"select
						posts.post_id,
						posts.title,
						posts.image,
						posts.text
					from
						posts
					where
						post_id = :id;");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			//DBから取得したデータを格納
			$row = $stmt->fetch();
			$img = base64_encode($row['image']);
			$_SESSION['id']		= $row[post_id];

			if ($_SESSION['actionName'] !== "post_clear"){
				$_SESSION['title']	= $row['title'];
				$_SESSION['data']	= $row['text'];
			}
		?>
		<div>
			<h1>画像投稿フォーム</h1>
			<p><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
		</div>
		<div>
			<div class="post_edit_img">
				<img src="data:image/jpg;base64,<?php echo $img; ?>">
			</div>
			<form action="./post_edit.php?id=<?php echo $_SESSION['id']; ?>" method="post" enctype="multipart/form-data">
				<div class="Article">
					<div class="title_contents">
						<p class="itemName">タイトル</p>
						<div>
							<input class="text" type="text" name="title" value="<?php
							echo htmlspecialchars($_SESSION['title'], ENT_QUOTES, "UTF-8"); ?>">
						</div>
					</div>
					<div class="detail_contents">
						<p class="itemName">詳細</p>
						<div>
							<textarea class="textarea" name="data" cols="35" rows="5" ><?php
							echo htmlspecialchars($_SESSION['data'], ENT_QUOTES, "UTF-8"); ?>
							</textarea>
						</div>
					</div>
				</div>

				<div class="btn">
					<input class="button" type="submit" name="Back" value="戻る">
					<input class="button" type="submit" name="clear" value="クリア">
					<input class="button" type="submit" name="Submit" value="編集して投稿">
				</div>

				<!-- ｃｓｒｆの対策としてトークンを作成 -->
				<?php

				// ハッシュ化
				$token = hash(sha256, session_id());

				// 生成したトークンをセッションに保存
				$_SESSION['token'] = $token;
				?>

				<!-- トークンをhiddenでフォームに埋め込む -->
				<input type="hidden" name="token" value="<?php echo $token ?>">
			</form>
		</div>
	</main>
	<footer>
	</footer>
</body>
</html>