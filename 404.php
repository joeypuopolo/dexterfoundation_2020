<?php
include($_SERVER['DOCUMENT_ROOT'].'/settings.php');
?>
<!DOCTYPE html>
<?php $PageName = "404 Error"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/modules/head.php'); ?>
</head>
<body data-pagename="404">
	<?php include($_SERVER['DOCUMENT_ROOT'].'/modules/header.php'); ?>
	<main>
		<section class="error content center-align">
			<div class="wrapper">
				<header>
					<h1>Oops!</h1>
					<h2>Page Not Found</h2>
				</header>
				<div class="btn-wrapper flex">
					<button class="btn" onclick="goBack()">Back</button>
					<a class="btn" href="<?php DOMAIN ?>/">Home</a>
				</div>
			</div>
		</section>
		
		<script>
			function goBack() {
			  window.history.back();
			}
		</script> 
	</main>
	<footer>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/modules/footer.php'); ?>
	</footer>
</body>

</html>