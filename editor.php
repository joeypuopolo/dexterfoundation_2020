<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['Logged_In']) || $_SESSION['Logged_In'] !== 'Dexter') {
    $_SESSION['URL'] = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    header('Location: /login/');
    exit;
}

// Enable error reporting for development (remove on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // DELETE ENTRY
    if ($_POST['Form_Name'] === 'Delete_Dog_Entry') {
        $ID = filter_input(INPUT_POST, 'ID', FILTER_VALIDATE_INT);
        if ($ID === null) {
            $err_msg = "Missing ID for deletion.";
            include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
        } else {
            $query = 'DELETE FROM Dog_Listings WHERE ID = :ID';
            $stm = $db->prepare($query);
            $stm->bindValue(':ID', $ID, PDO::PARAM_INT);
            $success = $stm->execute();
            $stm->closeCursor();

            if (!$success) {
                error_log("Delete Error: " . $stm->errorInfo()[2]);
            }
        }
    }

    // UPDATE ENTRY
    if ($_POST['Form_Name'] === 'Update_Dog_Entry') {
        $Name     = trim(filter_input(INPUT_POST, 'Name'));
        $Headline = trim(filter_input(INPUT_POST, 'Headline'));
        $Bio      = trim(filter_input(INPUT_POST, 'Bio'));
        $Active   = trim(filter_input(INPUT_POST, 'Active'));
        $Sequence = filter_input(INPUT_POST, 'Sequence', FILTER_VALIDATE_INT);
        $ID       = filter_input(INPUT_POST, 'ID', FILTER_VALIDATE_INT);

        if (!$Name || !$Headline || !$Bio || !$Active || $Sequence === null || $ID === null) {
            $err_msg = "Missing required fields for update.";
            include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
        } else {
            $query = 'UPDATE Dog_Listings
                      SET Name = :Name, Headline = :Headline, Bio = :Bio, Active = :Active, Sequence = :Sequence
                      WHERE ID = :ID';
            $stm = $db->prepare($query);
            $stm->bindValue(':Name', $Name);
            $stm->bindValue(':Headline', $Headline);
            $stm->bindValue(':Bio', $Bio);
            $stm->bindValue(':Active', $Active);
            $stm->bindValue(':Sequence', $Sequence);
            $stm->bindValue(':ID', $ID, PDO::PARAM_INT);
            $success = $stm->execute();
            $stm->closeCursor();

            if (!$success) {
                error_log("Update Error: " . $stm->errorInfo()[2]);
            }
        }
    }

    // ADD ENTRY
    if ($_POST['Form_Name'] === 'Add_Dog_Entry') {
        $Name     = trim(filter_input(INPUT_POST, 'Name'));
        $Headline = trim(filter_input(INPUT_POST, 'Headline'));
        $Bio      = trim(filter_input(INPUT_POST, 'Bio'));
        $Date     = date('Y-m-d H:i:s');
        $Active   = 'Yes';
        $Sequence = 50;
        $Photo    = '';

        // Handle file upload
        if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['Photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($ext, $allowed)) {
                if ($file['size'] < 1000000) {
                    $safe_name = preg_replace('/[^A-Za-z0-9_\-]/', '', pathinfo($file['name'], PATHINFO_FILENAME));
                    $new_name = uniqid($safe_name . '_') . '.' . $ext;
                    $destination = $_SERVER['DOCUMENT_ROOT'] . "/browser/images/" . $new_name;

                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $Photo = $new_name;
                    } else {
                        $err_msg = "Error moving uploaded file.";
                        include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
                    }
                } else {
                    $err_msg = "File too large.";
                    include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
                }
            } else {
                $err_msg = "Invalid file type.";
                include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
            }
        }

        if (!$Name || !$Headline || !$Bio || !$Photo) {
            $err_msg = "Missing required fields for new entry.";
            include $_SERVER['DOCUMENT_ROOT'] . '/db_error.php';
        } else {
            $query = 'INSERT INTO Dog_Listings (Name, Photo, Headline, Bio, Date_Entered, Active, Sequence)
                      VALUES (:Name, :Photo, :Headline, :Bio, :Date_Entered, :Active, :Sequence)';
            $stm = $db->prepare($query);
            $stm->bindValue(':Name', $Name);
            $stm->bindValue(':Photo', $Photo);
            $stm->bindValue(':Headline', $Headline);
            $stm->bindValue(':Bio', $Bio);
            $stm->bindValue(':Date_Entered', $Date);
            $stm->bindValue(':Active', $Active);
            $stm->bindValue(':Sequence', $Sequence);
            $success = $stm->execute();
            $stm->closeCursor();

            if (!$success) {
                error_log("Insert Error: " . $stm->errorInfo()[2]);
            }
        }
    }
}

// Fetch all dog entries
$query_dogs = "SELECT * FROM Dog_Listings ORDER BY Sequence ASC, ID ASC";
$dog_statement = $db->prepare($query_dogs);
$dog_statement->execute();
$dogs = $dog_statement->fetchAll();
$dog_statement->closeCursor();
?>
<!DOCTYPE html>
<?php $PageName = "Editor"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
	<style>
		.dog-listings ul > li[data-active=No] .listed-dog {
		    background-color: #b0deed;
		    color: hsl(0deg 0% 18%);
		}
		 section.dog-listings.editing ul li[data-active=No] a {
		 	color: hsl(0deg 0% 18%);
		 }
		 section.dog-listings.editing ul li[data-active=No] a:hover {
		 	color: white;
		 }
		 section.dog-listings.editing ul li[data-active=No] .photo {
		 	opacity: 0.6;
		 }
	</style>
</head>
<body data-pagename="Editor">
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/header.php"); ?>
	<main>
		<section class="dog-listings editing content form">
	<div class="wrapper">
		<header>
			<h1>Add & Edit Entries</h1>
		</header>
		<h3>
			Add A New Entry
		</h3>
		<form action="https://www.dexterfoundation.com/editor/" enctype="multipart/form-data" method="post" id="Add_Dog_Entry">
			<fieldset>
				<ul>
					<li>
						<label>Name</label>
						<input type="text" name="Name">
					</li>
					<li>
						<label>Headline</label>
						<input type="text" name="Headline">
					</li>
					<li>
						<label>Photo</label>
						<input type="file" name="Photo">
					</li>
					<li>
						<label>Bio</label>
						<textarea name="Bio"></textarea>
					</li>
				</ul>
				<input type="hidden" name="Form_Name" value="Add_Dog_Entry">
				<input type="submit" value="Add Entry" name="">
			</fieldset>
		</form>
		<h3>
			Edit & Reorder Current Entries
		</h3>
		<div class="btn-wrapper">
			<style>
				.reorder-btn.unchecked .checked, .reorder-btn.checked .unchecked {
					display: none;
				}
			</style>
			<button class="btn-orange reorder-btn hide unchecked">
				<span class="checked"><span class="icon">&#128190;</span> Reordering Saved</span>
				<span class="unchecked"><span class="icon">&#128190;</span> Save Reordering</span>
			</button>

		</div>
        <div class="unhide-dogs">
            <input type="checkbox" value="Hidden" checked="">
            <span class="label">Inactive entries hidden</span>
            <style> 
                ul.edit-dog-listing.hide-inactive li[data-active="No"]{
                    display: none;
                } 
                .unhide-dogs {
                    display: inline-flex;
                    margin: 3em 0;
                    cursor: pointer;
                }
                .unhide-dogs input[type='checkbox'] {
                    margin: 0;
                    line-height: 1em;
                    pointer-events: none;
                }
                .unhide-dogs span.label {
                    display: inline-block;
                    line-height: 1em;
                    margin-left: 0.5em;
                    font-style: italic;
                }
            </style>
            <script>
                document.querySelector(".unhide-dogs").addEventListener("click", ()=>{
                    
                    if ( document.querySelector(".unhide-dogs input[type='checkbox']").checked ) {
                        document.querySelector(".unhide-dogs input[type='checkbox']").removeAttribute("checked");
                        document.querySelector("ul.edit-dog-listing").classList.remove("hide-inactive");
                        document.querySelector(".unhide-dogs span.label").innerHTML = "Hide inactive entries";
                    } else {
                        document.querySelector(".unhide-dogs input[type='checkbox']").setAttribute("checked", "checked");
	                    document.querySelector("ul.edit-dog-listing").classList.add("hide-inactive");
                        document.querySelector(".unhide-dogs span.label").innerHTML = "Inactive entries hidden";
                    }
                });
            </script>
        </div>
		<ul class="edit-dog-listing hide-inactive">
			<?php foreach($dogs as $dog) : ?>			
			<li class="list-item" data-id="<?php echo $dog['ID']; ?>" data-sequence="<?php echo $dog['Sequence']; ?>" data-active="<?php echo $dog['Active']; ?>">
				<div class="listed-dog no-hover">
					<div class="controls"><span class="title"><i>Reorder Entries</i></span>
						<span title="Move To Beginning" data-control="Move To Beginning">&#10094; &#10094;</span>
						<span title="Move Over One" data-control="Move Backward">&#10094;</span>
						<span title="Move Over One" data-control="Move Forward">&#10095;</span>
						<span title="Move To End" data-control="Move To End">&#10095;&#10095;</span>
						<span class="edit"><a href="https://www.dexterfoundation.com/edit/?edit=<?php echo $dog['ID']; ?>">View/Edit [+]</a></span>
					</div>		
					<span class="photo"><img src="<?php echo "https://www.dexterfoundation.com/browser/images/" . $dog['Photo']?>"></span>
					<div class="pet-info">
						<span class="name">
							<?php echo $dog['Name']; ?>
						</span>
						<span class="title">
							<?php echo $dog['Headline']; ?>
						</span>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
	</main>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
	<script type="text/javascript" src="https://www.dexterfoundation.com/scripts.js"></script>
	<script type="text/javascript">

		var ordering = [];

		function indexing(){
			ordering = [];
			var i = 0;
			$('ul.edit-dog-listing li.list-item').each(function(){
				i++;
				$(this).data('index', i);
				ordering.push([$(this).data('id'), $(this).data('index')]);
			});
		}
		indexing();
		$('.controls span[data-control]').on('click', function(){
			$('.reorder-btn.hide').removeClass('hide');
			$('.reorder-btn').removeClass('checked');
			$('.reorder-btn').addClass('unchecked');
			if ( $(this).data('control') === "Move To Beginning" ) {
				$(this).parents('li.list-item').prependTo('ul.edit-dog-listing');
				indexing();
			}
			if ( $(this).data('control') === "Move Forward" ) {
				var next = $(this).parents('li.list-item').next();
				$(this).parents('li.list-item').insertAfter(next);
				indexing();
			}
			if ( $(this).data('control') === "Move Backward" ) {
				var prev = $(this).parents('li.list-item').prev();
				$(this).parents('li.list-item').insertBefore(prev);
				indexing();
			}
			if ( $(this).data('control') === "Move To End" ) {
				$(this).parents('li.list-item').appendTo('ul.edit-dog-listing');
				indexing();
			}
		});

		$('button.reorder-btn').on('click', function(){
			$('button.reorder-btn').addClass('checked');
			$('button.reorder-btn').removeClass('unchecked');
			var reorder = JSON.stringify({ordering});
			console.log(reorder);
			$.post(	"/modules/reorder-entries/",
					{Form_Name: "Reorder_Entries", Order: reorder},
					function(data) {
						$('.test-console').html(data);
					}
			);
		});
		
	</script>
</body>

</html>
