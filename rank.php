<!-- ランキング画面 -->
<?php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/style.css" type="text/css">
	<title>ウマ娘</title>
</head>
<body>
	<header>
		ナビゲーション部分
	</header>
	<main>
		<div>
			<form action="" method="post">
				<table class="inputArticle">

					<!-- 画像 -->
					<tr>
						<td>
							<img alt="ramen" src="./img/ramen.jpg">
						</td>
					</tr>

					<!-- 飯の名前 -->
					<tr>
						<td class="foodTitle">飯のタイトル</td>
						<td>
							<p>ラーメン二郎横浜関内店</p>
						</td>
					</tr>
					<tr>
						<td class="itemName">飯の詳細</td>
						<td>
							<p>個人的に日本一のラーメン</p>
						</td>
					</tr>
				</table>
			</form>
			<p>ユーザー名</p>
			<p>投稿日時</p>
			<p>コメント数</p>
			<input class="button" type="submit" name="likes" value="いいね">
			<p>いいね数：100</p>
		</div>
	</main>
	<footer>
	</footer>
</body>
</html>