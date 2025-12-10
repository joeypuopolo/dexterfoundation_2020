<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['Logged_In']) || $_SESSION['Logged_In'] !== 'Dexter') {
    $_SESSION['URL'] = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    header('Location: /login/');
    exit;
}

// Validate edit ID from query string
$ID = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if (!$ID) {
    header('Location: /editor/');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/db_connect.php';

// Get dog listing
$query_dogs = "SELECT * FROM Dog_Listings WHERE ID = :ID";
$dog_statement = $db->prepare($query_dogs);
$dog_statement->bindValue(':ID', $ID, PDO::PARAM_INT);
$dog_statement->execute();
$dogs = $dog_statement->fetchAll();
$dog_statement->closeCursor();

// Get applications for this dog
$query_applications = "SELECT * FROM Applications WHERE Specific_Dog = :ID ORDER BY Date_Entered DESC";
$application_statement = $db->prepare($query_applications);
$application_statement->bindValue(':ID', $ID, PDO::PARAM_INT);
$application_statement->execute();
$applications = $application_statement->fetchAll();
$application_statement->closeCursor();
?>
<!DOCTYPE html>
<?php $PageName = "Editor"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="Home">
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/header.php"); ?>
	<main>
		<section class="dog-listings content form">
			<div class="wrapper">
				<header>
					<h1>Update Entry & View Applications</h1>
				</header>
                <?php if ($applications): ?>
                <div class="applications">
                <style>
                    .dog-listings .applications ul {
                        display: block;
                    }
                    .dog-listings .applications ul li {
                        display: list-item;
                        padding: 0;
                    }
                    .dog-listings .applications ul li a {
                        display: inline-block;
                    }
                    /* .applications ul li .thumbs-down {
                        display: none;
                    }
                    .applications ul li .thumbs-down[data-good-match="No"] {
                        display: block; */
                    }
                </style>
                
                    <p>
                        <em>Received Applications</em>
                    </p>
                    <ul>
                    
                    <?php foreach($applications as $application) : ?>     
                        <li class="application">
                            <a target="_blank" href="https://www.dexterfoundation.com/applications/?id=<?php echo $application['ID']; ?>">
                                <p class="date-entered"> 
                                    <?php if ( $application['Active'] === "No" ): ?>
                                    <span class="thumbs-down" data-good-match="No">&#128533;</span>
                                    <?php endif; ?>
                                    <?php if ( $application['Favorite'] === "Yes" ): ?>
                                    <span class="star" data-favorite="Yes">&#9733;</span>
                                    <?php endif; ?>
                                    <?php
                                        $Date_Entered = strtotime($application['Date_Entered']);
                                        $Day = date('l', $Date_Entered);
                                        $Date = date('M jS Y', $Date_Entered);
                                        $Time = date('g:ia', $Date_Entered);
                                        echo $application['Name']."<br>$Day $Date @ $Time"; 
                                    ?>	
                                </p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <?php else: ?>
                    <p><em>No Applications Received</em></p>
                <?php endif; ?>
                <br><br>
				<div class="editing-entry">
                

					<?php foreach($dogs as $dog) : ?>	
						<header>
							<h4>
								<?php echo $dog['Name']; ?>
							</h4>
							<strong>
								<?php echo $dog['Headline']; ?>
							</strong>
						</header>	
						<p>Date Entered: <?php echo $dog['Date_Entered']; ?></p>	
						<div class="featured-pet" data-id="<?php echo $dog['ID']; ?>">
								<span class="photo"><img src="https://www.dexterfoundation.com/browser/images/<?php echo $dog['Photo']?>"></span>

								<p><?php echo nl2br(htmlspecialchars($dog['Bio'])); ?> </p>
						</div>
						<form action="https://www.dexterfoundation.com/editor/" method="post" id="Delete_Dog_Entry">
							<input type="hidden" name="ID" value="<?php echo $dog['ID']?>">
							<input type="hidden" name="Form_Name" value="Delete_Dog_Entry">
							<input type="submit" class="btn-orange" onClick="javascript: return confirm('Really delete this?');" value="Remove Entry" name="">
						</form>
						<form action="https://www.dexterfoundation.com/editor/" method="post" id="Update_Dog_Entry">
							<fieldset>
								<ul>
									<li>
										<label>Name</label>
										<input value="<?php echo $dog['Name']; ?>" type="text" name="Name">
									</li>
									<li>
										<label>Headline</label>
										<input value="<?php echo $dog['Headline']; ?>" type="text" name="Headline">
									</li>
									
									<li>
										<label>Bio</label>
										<textarea name="Bio"><?php echo $dog['Bio']; ?></textarea>
									</li>
									<li class="hide">
										<label>Sequence Ordering <small>Reordering for the homepage</small></label>
										<input value="<?php echo $dog['Sequence']?>" type="text" name="Sequence">
									</li>
									<li>
										<label>Active?<small>Hide an entry without deleting</small></label>
										<select name="Active">
											<option value="<?php echo $dog['Active']?>"><?php echo $dog['Active']?></option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
									</li>
								</ul>
								<input type="hidden" name="ID" value="<?php echo $dog['ID']?>">
								<input type="hidden" name="Form_Name" value="Update_Dog_Entry">
								<input type="submit" value="Update Entry" name="">
							</fieldset>
						</form>
					<?php endforeach; ?>
				</div>
			</div>
		</section>	
	</main>
	
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script type="text/javascript" src="https://www.dexterfoundation.com/scripts.js"></script>
</body>

</html>
