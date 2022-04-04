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

		// セッションを空にする
		$_SESSION['title'] = "";
		$_SESSION['data'] = "";
		$_SESSION['pic'] = "";

		// エラー変数を空にする
		$err = "";
	}

	if (empty($_FILES['pic']['name'])) {
		$err .= '画像を入れてください<br>';
	}

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

		$content = file_get_contents($_FILES['pic']['tmp_name']);

		// POSTのデータをSESSIONに格納
		$_SESSION['pic']	= $content;
		$_SESSION['title']	= $_POST['title'];
		$_SESSION['data']	= $_POST['data'];

		// DBに$_SESSIONのデータを保存
		$db = new DbconnectClass();
		$stmt = $db->getDbconnect()->prepare(
				"insert into posts (
					create_at,
					update_at,
					title,
					text,
					image,
					user_id)
				values(
					now(),
					now(),
					:title,
					:data,
					:pic,
					:id)");
		$stmt->bindParam(':pic',$_SESSION['pic'], PDO::PARAM_STR);
		$stmt->bindParam(':data',$_SESSION['data'], PDO::PARAM_STR);
		$stmt->bindParam(':title',$_SESSION['title'], PDO::PARAM_STR);
		$stmt->bindParam(':id',$_SESSION['userId'], PDO::PARAM_STR);
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
	header("Location: ./index.php");
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
	<link rel="stylesheet" href="./css/post.css" type="text/css">
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
			<h1>画像投稿フォーム</h1>
			<p class="err_text"><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
		</div>
		<div>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="inputArticle">
					<div class="pic">
						<p class="foodPic">画像</p>
						<div>
							<input type="file" name="pic" >
						</div>
					</div>
					<div class="title">
						<p class="itemName_title">タイトル</p>
						<div>
							<input class="text" type="text" name="title" placeholder="タイトルはここ">
						</div>
					</div>
					<div class="detail">
						<p class="itemName_detail">詳細</p>
						<div>
							<textarea class="textarea" name="data" cols="35" rows="5" placeholder="詳細はここ"></textarea>
						</div>
					</div>
				</table>
				<div class="btn">
					<div class="back_btn">
						<input class="button" type="submit" name="Back" value="戻る">
					</div>
					<div class="clea_btn">
						<input class="button" type="submit" name="clear" value="クリア">
					</div>
					<div class="toukou_btn">
						<input class="button" type="submit" name="Submit" value="投稿"
					></div>
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
			<hr>
		</div>
	</main>
	<footer>
	</footer>
</body>
</html>