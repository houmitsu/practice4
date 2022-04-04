<!-- マイページ画面 -->
<?php

// セッションスタート
session_start();

// DB接続と関数読込
include 'dbconnect.php';
include 'function.php';

// セッションハイジャックの対策
session_regenerate_id(true);

// ログインの有無
 if(!isset($_SESSION['userId'])){
 	header('Location: ./index.php');
 	exit();
 }


// 編集内容を保存ボタン押下
if(isset($_POST['submit'])){

	//エラー変数を空にする
	$err = "";

	//空文字チェック
	if(isBlank($_POST['name'])){
		$err .= "「名前」";
	}
	//空文字チェック
	if(isBlank($_POST['email'])){
		$err .= "「メールアドレス」";
	}

	//$errが空である
	if(isBlank($err)){

		//ユーザ情報を取得
		$db = new DbconnectClass();
		$stmt = $db->getDbconnect()->prepare("
						update
							users
						set
							name			= :name,
							email			= :email,
							update_at		= now(),
							introduction 	= :introduction
						where
							user_id	= :id
						");
		$stmt->bindValue( ':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindValue( ':email', $_POST['email'], PDO::PARAM_STR);
		$stmt->bindValue( ':id', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->bindValue( ':introduction', $_POST['introduction'], PDO::PARAM_STR);
		$stmt->execute();

		//$_SESSION['actionName']に"user_edit"を格納
		$_SESSION['actonName'] = "user_edit";

		//マイページ画面(user.php)に遷移
		header("Location: ./user.php?id={$_SESSION['id']}");
		exit();
	}

	//$errが空でない
	if (isBlank($_POST['']))
		$err .="を入力してください。";

// 退会するボタン押下時
} elseif (isset($_POST['exit'])) {

	//$_SESSION['actionName']に"user_edit"を格納
	$_SESSION['actonName'] = "user_edit";

	//退会画面(user_withdrawal.php)に遷移
	header('Location: ./user_withdrawal.php');
	exit();

//編集内容を保存ボタン、退会するボタンを押していない
} else {

	//$_SESSION['actionName']に"sign_in_display"を格納
	$_SESSION['actionName'] = "sign_in_display";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/user_edit.css" type="text/css">
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
			<h1>会員情報編集</h1>
			<p><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
		</div>
		<div>
			<?php
			$id = $_GET['id'];
			//ユーザ情報を取得
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare("
						select
							user_id,
							name,
							email,
							introduction
						from
							users
						where
							user_id = :id
					");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$row = $stmt->fetch();

			$_SESSION['id'] = $row['user_id'];
			?>

			<form action="./user_edit.php?id=<?php $row['user_id']; ?>" method="post">
				<input type="hidden" name="id" value="<?php
					echo(htmlspecialchars($id, ENT_QUOTES, 'UTF-8'));?>">
				<div class="stuff">
					<div class="textBox">
						<label class="label">名前</label>
						<input class="text" type="text" name="name" value="<?php
						echo(htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')); ?>">
					</div>
					<div class="textBox">
						<label class="label">メールアドレス</label>
						<input class="text" type="text" name="email" value="<?php
						echo(htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8')); ?>">
					</div>
					<div class="textBox2">
							<label class="label2">自己紹介</label>
							<textarea class="text2" name="introduction" cols="35" rows="5">
							<?php echo(htmlspecialchars($row['introduction'], ENT_QUOTES, 'UTF-8')); ?></textarea>
					</div>
				</div>
				<div class="btn">
					<div class="save">
						<input class="save_btn" type="submit" name="submit" value="編集内容を保存">
					</div>
					<div class="withdrawal">
						<input class="withdrawal_btn" type="submit" name="exit" value="退会する">
					</div>
				</div>
			</form>
		</div>
	</main>
	<footer>
	</footer>
</body>
</html>