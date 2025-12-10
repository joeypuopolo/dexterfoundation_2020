<?php
	session_start();
	unset($_SESSION['Logged_In']);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="Logout">
	<main>
	<section class="login content form">
		<div class="wrapper" style="text-align: center;">
			<header><h1>You are now logged out.</h1></header>
            <a class="btn btn-orange" href="/">Go Back Home</a>
		</div>

	</section>
	</main>
	
</body>

</html>
