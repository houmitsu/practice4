<!-- ログイン画面 -->
<?php

	//セッションスタート
	session_start();

	//DB接続class読込、共通関数読込
	include 'dbconnect.php';
	include 'function.php';

	if ($_SESSION['actionName'] == "signup") {
		$msg = "登録完了しました！";
	}

	//ログインボタンを押した
	if(isset($_POST['login'])){

		//エラー変数を空にする
		$err = "";

		//空文字チェック
		if(isBlank($_POST['email'])){
			$err .= "「Eメール」";
		}
		//空文字チェック
		if(isBlank($_POST['password'])){
			$err .= "「パスワード」";
		}

		//$errが空である
		if(isBlank($err)){

			//ユーザ情報を取得
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare("
					select
						user_id,
						name,
						email
					from
						users
					where
						email=:email
					and
						pass=:user_pass;");
			$stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
			$stmt->bindParam(":user_pass", $_POST['password'], PDO::PARAM_STR);
			$stmt->execute();

			//ユーザ情報を取得できた場合
			if($row = $stmt->fetch()){

				//$_SESSIONにACCOUNTデータを格納
				$_SESSION['userId']		= $row['user_id'];
				$_SESSION['name']	= $row['name'];
				$_SESSION['email']		= $row['email'];

				//$_SESSION['actionName']に"sign_in"を格納
				$_SESSION['actonName'] = "sign_in";

				//一覧画面(index.php)に遷移
				header('Location: ./index.php');
				exit();

			//ユーザ情報を取得できなかった場合
			}else{
				$err .= "Eメールまたはパスワードに誤りがあります。";
			}
		//$errが空でない
		}else{
			$err .="を入力してください。";
		}
	//ログインボタン押していない
	}else{
		//$_SESSION['actionName']に"sign_in_display"を格納
		$_SESSION['actionName'] = "sign_in_display";
	}

?>




<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="./css/sign_in.css" type="text/css">
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

		<div class="sign_in_massage">
			<p><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></p>
		</div>

		<div class="main">
			<div>
				<h1>ログイン</h1>
			</div>
			<div>
				<h2>会員の方はこちらからログイン</h2>
			</div>

			<form action="./sign_in.php" method="post">
			<p class="err_text"><FONT COLOR="RED"><?php echo htmlspecialchars($err, ENT_QUOTES); ?></FONT></p>

				<div class="stuff">
					<div class="textBox">
					 <label class="label">Eメール</label>
					  <input
					         class="text"
					         type="textbox"
					         name="email"
					         placeholder="Eメール"
					         onkeyup="this.setAttribute('value', this.value);"
					         value=""/>
					</div>
					<div class="textBox">
					  <label class="label">パスワード</label>
					  <input
					         class="text"
					         type="password"
					         name="password"
					         placeholder="パスワード"
					         onkeyup="this.setAttribute('value', this.value);"
					         value=""/>
					</div>
				</div>

				<div class="login">
					<input class="button" type="submit" name="login" value="ログイン">
				</div>
			</form>

			<div>
				<h2 class="sec">会員登録がお済でない方</h2>
			</div>
			<div>
				<p>新規登録は<a href="http://192.168.0.142/trunk/sign_up.php" class="btn">こちら</a>から。</p>
			</div>
		</div>
	<script src="./js/jquery.min.js"></script>
	<script src="./js/sign_in.js"></script>
</body>
</html>