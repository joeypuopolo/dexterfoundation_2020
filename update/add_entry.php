<?php
$Name = filter_input(INPUT_POST, "Name");
$Photo = filter_input(INPUT_POST, "Photo");
$Headline = filter_input(INPUT_POST, "Headline");
$Bio = filter_input(INPUT_POST, "Bio");
$Date_Entered = date('Y-m-d H:i:s');

echo "$Name, $Photo, $Headline, $Bio, $Date_Entered";
if($Name == null || $Photo == null || $Headline == null || $Bio == null){
	$err_msg = "All Values Not Entered<br>";
	include('./db_error.php');
} else {
	require_once('./db_connect.php');
	$query = 'INSERT INTO Dog_Listings(Name, Photo, Headline, Bio, ID, Date_Entered) VALUES(:Name, :Photo, :Headline, :Bio, :ID, :Date_Entered)';
	$stm = $db->prepare($query);
	
	$stm->bindValue(':Name', $Name);
	$stm->bindValue(':Photo', $Photo);
	$stm->bindValue(':Headline', $Headline);
	$stm->bindValue(':Bio', $Bio);
	$stm->bindValue(':ID', null, PDO::PARAM_INT);
	$stm->bindValue(':Date_Entered', $Date_Entered);
	$execute_success $stm->execute();
	$stm->closeCursor();
	if(!$execute_success){
		print_r($stm->errorInfo()[2]);
	}
}
require_once('./db_connect.php');
$query_dogs = 'SELECT * FROM Dog_Listings ORDER BY ID';
$dog_statement = $db->prepare($query_dogs);
$dog_statement->execute();
$dogs = $dog_statement->fetchAll();
$dog_statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Entry</title>
</head>
<body>
<section class="data-entry-form">
	<table>
		<tr>
			<th>ID</th>
			<th>Headline</th>
			<th>Photo</th>
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
	<h3>Update Entry</h3>
	<form action="./update_entry.php" name="update_entry" method="post">
			<fieldset>
		<ul>
			<li>
				<label>Name</label>
				<input type="text" name="Name">
			</li>
			<li>
				<label>Headline</label><input type="text" name="Headline">
			</li>
			<li>
				<label>Photo</label><input type="text" name="Photo">
			</li>
			<li>
				<label>Bio</label>
				<textarea resize="none" name="Bio"></textarea>
			</li>
		</ul>
		<input type="submit" value="Update" name="">
	</fieldset>
	</form>

</section>

</body>
</html>