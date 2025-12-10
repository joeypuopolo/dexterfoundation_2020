<?php session_start(); ?>
<!DOCTYPE html>
<?php $PageName = "Home"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="<?php echo $PageName; ?>">
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/header.php"); ?>
	<main>
		<section class="welcome content">
			<div class="wrapper">
				<header>
					<h1>The Dexter Foundation</h1>
					<h2>Serving Los Angeles County and Southern California</h2>
					<p>We are dedicated to rescuing dogs in the Southern California Area and placing them in a loving, forever home.</p>
				</header>
				<div class="img-wrapper"><img src="/images/CleoPrincess.jpg"style="max-width:400px"><img src="/images/Suki.jpg" style="max-width:400px"></div>
				<p>So many dogs are finding their way into shelters. Sadly, due to overcrowding, many never make it out. We can only save as many dogs as we have available foster homes.</p>
				<p>We need your help getting these precious dogs into foster homes and forever homes. To get started, complete the <a href="/adoption-application/">application</a>. We then schedule a quick and easy home check. We are only adopting out to the Southern California area. We save new dogs each week, so we often have dogs that we have yet to post online.</p>
				<p>We depend on volunteers and donations to keep saving lives. To help us further with our mission in supporting abandoned dogs, please consider either making a donation or looking at <a href="/how-to-help/">other easy ways to help.</a></p>
				<div class="donation-button">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="HHGEETRV566K2" />
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
					</form>
				</div>
			</div>
		</section>
		<?php include($_SERVER['DOCUMENT_ROOT']."/modules/dog-listings.php"); ?>
		<?php include($_SERVER['DOCUMENT_ROOT']."/modules/ways-to-help.php"); ?>
	</main>
	<footer>
		<?php include($_SERVER['DOCUMENT_ROOT']."/modules/footer.php"); ?>
	</footer>
</body>

</html>
