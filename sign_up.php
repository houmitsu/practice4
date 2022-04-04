<!-- 新規登録画面 -->
<?php

	//セッション開始
	session_start();

	//DB接続class読込、共通関数読込
	include 'dbconnect.php';
	include 'function.php';

	//登録ボタンを押したとき
	if(isset($_POST['signup'])){

		//エラー変数を空にする
		$err = "";

		//空文字チェック
		if(isBlank($_POST['name'])){
			$err .= "お名前を入力してください。<br>";
		}

		//空文字チェック
		if(isBlank($_POST['email'])){
			$err .= "Eメールを入力してください。<br>";
		}

		if(!checkEmail($_POST['email'])){
			$err .= "Eメールの形式が正しくありません。<br>";
		}

		//空文字チェック
		if(isBlank($_POST['pass'])){
			$err .= "パスワードを入力してください。<br>";
		}

		//エラー変数が空のとき
		if(isBlank($err)){

			$name = $_POST['name'];
			$email = $_POST['email'];
			$pass = $_POST['pass'];

			$db = new DbconnectClass();

			$stmt = $db->getDbconnect()->prepare(
					'select * from users where email = :email;');
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			$member = $stmt->fetch();
			if ($member['email'] === $email) {
				$err .= '同じメールアドレスが存在します。';
			} else {

					$stmt = $db->getDbconnect()->prepare(
							'insert into users(
								create_at,
								name,
								email,
								pass,
								del_flg)
							value(
								now(),
								?,
								?,
								?,
								0)');
					$stmt->execute(array($name,$email,$pass));
					$stmt = null;
					$db = null;

					$_SESSION['actionName'] = "signup";

					header('Location: ./sign_in.php');
					exit();
			}

		//エラー変数が空でないとき
		}else{
			$err .="";
		}

	//登録ボタンを押していないとき
	}else{
		$_SESSION['actionName'] = "signup_display";
	}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="./css/sign_up.css" type="text/css">
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
		<div>
			<h1>新規登録</h1>
		</div>
		<form action="./sign_up.php" method="post">
		<p class="err_text"><FONT COLOR="RED"><?php echo $err; ?></FONT></p>

			<div class="stuff">
			<div class="textBox">
			  <label class="label">お名前</label>
			  <label class="error"></label>
			  <input
			         class="text"
			         type="textbox"
			         name="name"
			         placeholder="お名前"
			         onkeyup="this.setAttribute('value', this.value);"
			         value=""/>

			</div>
			<div class="textBox">
			  <label class="label">Eメール</label>
			  <label class="error"></label>
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
			  <label class="error"></label>
			  <input
			         class="text"
			         type="password"
			         name="pass"
			         placeholder="パスワード"
			         onkeyup="this.setAttribute('value', this.value);"
			         value=""/>

			</div>
			</div>
			<div class="touroku">
			<input class="button" type="submit" name="signup" value="登録する">
			</div>
		</form>
		<div>
			<h2 class="sec">既に登録済みの方</h2>
		</div>
		<div class="btn">
			<p>ログインは<a href="http://192.168.0.142/trunk/sign_in.php" class="btn">こちら</a>から</p>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>