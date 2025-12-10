<?php
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if($id == null) {
	$err_msg = "All Values Not Entered<br>";
	include('./db_error.php')
} else {
	require_once('./db_connect.php');
	$query = 'DELETE FROM Dog_Listings WHERE ID = :ID';
	$stm = $db->prepare($query);
	$stm->bindValue(':ID', $id);

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
	<form action="./delete_entry.php" id="delete_entry" method="post">
			<fieldset>
		<ul>
			<li>
				<label>ID</label>
				<input type="text" name="ID">
			</li>
		</ul>
		<input type="submit" value="Delete" name="">
	</fieldset>
	</form>

</section>

</body>
</html>