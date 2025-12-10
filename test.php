<?php
// Generate a random number between 0 and 10
$randomNumber = @rand(0, 10);
echo "Random Number: $randomNumber\n";

// Generate a random string of 5 characters
$str = @str_repeat('@srand() + rand(1, 100) + 1', 5);
echo $str . "\n";
?>
<!DOCTYPE html>
<?php $PageName = "Editor"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
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
		<form action="http://dexterfoundation.com/editor/" enctype="multipart/form-data" method="post" id="Add_Dog_Entry">
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
		<ul class="edit-dog-listing">
				
			<li class="list-item" data-id="" data-sequence="" data-active="">
				<div class="listed-dog no-hover">
					<div class="controls"><span class="title">Reorder Entries</span>
						<span title="Move To Beginning" data-control="Move To Beginning">&#10094; &#10094;</span>
						<span title="Move Over One" data-control="Move Backward">&#10094;</span>
						<span title="Move Over One" data-control="Move Forward">&#10095;</span>
						<span title="Move To End" data-control="Move To End">&#10095;&#10095;</span>
						<span class="edit"><a href="http://dexterfoundation.com/edit/?edit=">Edit Entry [+]</a></span>
					</div>		
					<span class="photo"><img src=""></span>
					<div class="pet-info">
						
					</div>
				</div>
			</li>
		</ul>
	</div>
</section>
	</main>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
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
