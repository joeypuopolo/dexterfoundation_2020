<?php
// retracted for security
?>
<!DOCTYPE html>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="Login">
	<?php if ( $Login_Status != "") { ?>
	<div class="notice-bar"><span><?php echo $Login_Status; ?></span></div>
	<?php } ?>
	<main>
	<section class="login content form">
		<div class="wrapper">
			<header>
				<h1>Welcome.</h1>
				<h2>Please login here.</h2>

			</header>
			<form method="POST" action="https://www.dexterfoundation.com/login/">
				<fieldset>
					<ul>
						<li><label>Username</label><input type="text" name="Username"></li>
						<li><label>Password</label><input type="password" name="Password"></li>
					</ul>
					<input type="submit" value="Login" name="Login">
				</fieldset>
			</form>
		</div>

	</section>
	</main>
	
</body>

</html>
