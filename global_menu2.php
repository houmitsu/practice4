<?php

?>
<link rel="stylesheet" href="./css/global_menu.css" type="text/css">


<div id="header">
	<a href="index.php"><img src="./img/logo.png"></a>
</div>
<div class="openbtn1"><span></span><span></span><span></span></div>
<nav id="g-nav">
<ul>
<li><a href="index.php" class="btnripple3">一覧</a></li>
<li><a href="post.php" class="btnripple3">投稿</a></li>
<li><a href="user.php?id=<?php echo $_SESSION[userId]; ?>" class="btnripple3">マイページ</a></li>
<li><a href="logout.php" class="btnripple3">ログアウト</a></li>
</ul>
</nav>
<script src="./js/jquery.min.js"></script>
<script src="./js/global_menu.js"></script>
</html>