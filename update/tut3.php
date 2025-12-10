<?php
require('./db_connect.php');
$query_dogs = 'SELECT * FROM Dog_Listings ORDER BY ID';
$dog_statement = $db->prepare($query_dogs);
$dog_statement->execute();
$dogs = $dog_statement->fetchAll();
$dog_statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tutorial</title>
</head>
<body>
	<h3>list</h3>
	<table>
		<tr>
			<th>ID</th>
			<th>Headline</th>
			<th>Pic</th>
			<th>Name</th>
			<th>Bio</th>
		</tr>
		<?php foreach($dogs as $dog) : ?>
			<tr>
				<td><?php echo $dog['ID']; ?></td>
				<td><?php echo $dog['Headline']; ?></td>
				<td><?php echo $dog['Photo']; ?></td>
				<td><?php echo $dog['Name']; ?></td>
				<td><?php echo $dog['Bio']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<h3>New Dog Entry</h3>	
	<form action="./add_entry.php" method="post" id="Add_Dog_Entry">
		<fieldset>
			<ul>
				<li>
					<label>Headline</label>
					<input type="text" name="Headline">
				</li>
				<li>
					<label>Pic</label>
					<input type="text" name="Photo">
				</li>
				<li>
					<label>Name</label>
					<input type="text" name="Name">
				</li>
				<li>
					<label>Bio</label>
					<textarea name="Bio"></textarea>
				</li>
			</ul>
			<input type="submit" value="Add Entry" name="">
		</fieldset>
	</form>

</body>
</html>