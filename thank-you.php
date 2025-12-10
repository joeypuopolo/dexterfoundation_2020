<?php
	
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("./modules/head.php"); ?>
</head>
<body data-pagename="Thank You">
	<?php include("./modules/header.php"); ?>
	<main>
		<section class="thank-you">
			<div class="wrapper">
				<h1>Thank You!</h1>
				<h2>We've received your application!</h2>
				<?php
					foreach( $_POST as $field => $value ) {
						if ( is_array( $field ) ) {
							foreach( $field as $item ) {
								echo $item;
							}
						} else {
							echo "<p><b>$field</b><br><i>$value</i></p>";
						}
					}
				?>
			</div>
		</section>
	</main>
	<footer>
		<?php include("./modules/footer.php"); ?>
	</footer>
</body>

</html>