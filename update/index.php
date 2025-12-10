<?php
require('db_connect.php');
$query_dogs = "SELECT * FROM Dog_Listings ORDER BY ID";
$dog_statement = $db->prepare($query_dogs);
$dog_statement->execute();
$dogs = $dog_statement->fetchAll();
$dog_statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>

	<?php include("./modules/head.php"); ?>
	
</head>
<body data-pagename="Home">

	<?php include("./modules/header.php"); ?>

	<section class="dog-listings">
		<ul>
			<?php foreach($dogs as $dog) : ?>			
			<li>
				<div class="listed-dog">
					<a href="http://dexterfoundation.com/profile.php?id=<?php echo $dog['ID']; ?>">		
						<span class="title">
							<?php echo $dog['Headline']; ?>
						</span>
						<img src="/img/doggies/<?php echo $dog['Photo']?>">
						<span class="name">
							<?php echo $dog['Name']; ?>
							<br><i><?php echo $dog['Date_Entered']?></i>
						</span>
					</a>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</section>

</body>
<footer>

	<?php include("./modules/footer.php"); ?>

</footer>
</html>
