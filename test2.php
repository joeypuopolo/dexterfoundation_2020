<?php 
	session_start();
	if ( !isset($_SESSION['Logged_In']) && $_SESSION['Logged_In'] != 'Dexter') {
		$_SESSION['URL'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header('Location: /login/');
	}
?>
<!DOCTYPE html>
<?php $PageName = "Applications"; ?>
<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/head.php"); ?>
</head>
<body data-pagename="Applications">
	<style>
		.applications-submitted.searching .day h4 {
			display: none;
		}
		.search-bar .magnifying-glass {
			display: flex;
			align-items: center;
			font-size: 1em;
			position: absolute;
			left: 0;
			width: 2em;
			height: 100%;
			justify-content: center;
		}
		.search-bar .clear {
			position: absolute;
			right: 0;
			height: 100%;
			display: flex;
			align-items: center;
			font-size: 3em;
			padding-bottom: 0.2em;
			box-sizing: border-box;
			z-index: 1;
			top: 0;
		}
		.search-bar-wrapper {
			position: relative;
		}
		.search-bar input {
			padding-left: 2em;
		}
	</style>
	<?php include($_SERVER['DOCUMENT_ROOT']."/modules/header.php"); ?>
	<main>
	  <section class="featured-application content">
	  	<div class="wrapper">
	
	  	<?php
	  	require_once($_SERVER['DOCUMENT_ROOT'].'/db_connect.php');
	  	$ID = "";
		if(isset($_GET['id'])) {
			$ID = $_GET['id'];
		}
		$query_featured_application = "SELECT * FROM Applications WHERE ID = :ID";
		$featured_application_statement = $db->prepare($query_featured_application);
		$featured_application_statement->bindValue(':ID', $ID);
		$featured_application_statement->execute();
		$featured_applications = $featured_application_statement->fetchAll();
		$featured_application_statement->closeCursor();
	  	?>
	    <div class="application">

	     <?php foreach($featured_applications as $featured_application) : ?>  
	     	<div class="btn-wrapper">
	     		<a class="applications-page btn" href="/applications/">Back to Applications</a>
	     	</div>
			<div class="header-wrapper">
				<header>
		  			<h1>
		  				<?php echo $featured_application['Name']; ?>
		  			</h1>
		  			<h2>Application

		  			</h2>
		  		</header>
				<div class="btn-wrapper">
					<button data-id="<?php echo $featured_application['ID']; ?>" data-favorited="<?php echo $featured_application['Favorite']; ?>" class="favorite-btn btn-orange">
						<span class="checked"><span class="icon">&#9733;</span> Favorited</span>
						<span class="unchecked"><span class="icon">+</span> Add To Favorites</span>
					</button>
					
				</div>
			</div>
	       	
	  		<div class="btn-wrapper">
	  			<button class="btn print-btn" onclick="window.print()">Print</button>
	  			<br><small>To download, click to Print, then opt to save as a PDF.</small>
	  		</div>
	        <p class="date-entered">
	        	<?php 
	        	$Date_Entered = strtotime($featured_application['Date_Entered']);
				$Date = date('l, M jS Y', $Date_Entered);
				$Time = date('g:ia', $Date_Entered);
				
	        	echo "$Date<br>@ $Time";
	        	?>
	      	</p>
	        	<div class="form-fields">
	        		<?php 
	        			if ( $featured_application['Lease'] === "Blank" ) {
	        				echo "<p class='lease no-lease'>No Lease Submitted</p>";
		        		} else {
		        			
		        			echo "<p class='lease'><a target='_blank' href='http://www.dexterfoundation.com/browser/leases/".$featured_application['Lease']."'>View Lease Agreement[+]</a></p>";
		        			
		        		}
		        		echo $featured_application['Form_Fields']; 
	        		?>
	        	</div>
	      <?php endforeach; ?>
	     </div>
	    </div>
	  </section>
	  <section class="applications-submitted content">
	  	<div class="wrapper">
	  		<header>
	  			<h1>Applications Received</h1>
	  		</header>
	  		<div class="search-bar">
	  			<small><i>Search</i></small>
	  			<div class="search-bar-wrapper">
	  				<span class="magnifying-glass"> &#128269;</span>
	  				<input class="search-input" type="text">
	  				<span class="clear">&times;</span>
	  			</div>
	  		</div>
	  		<div class="favorites-list day">
	  			<div class="favorites-header">
	  				<h4>Favorites List</h4>
	  			</div>
	  			<ul></ul>
	  		</div>
	  	<?php
	  		require_once($_SERVER['DOCUMENT_ROOT'].'/db_connect.php');
	  		$query_applications = "SELECT * FROM Applications ORDER BY Date_Entered DESC LIMIT 800";
			$application_statement = $db->prepare($query_applications);
			$application_statement->execute();
			$applications = $application_statement->fetchAll();
			$application_statement->closeCursor();
	  	?>
		  	<div class="applications">
		  	 <?php 
		  	 $Current_Day = "";
		  	 $close = "";
		  	 ?><div><ul>
		     <?php foreach($applications as $application) : ?>     
		     
		      	<?php 
		      	$Date_Entered = strtotime($application['Date_Entered']);
	        	$Day = date('l', $Date_Entered);
				$Date = date('M jS Y', $Date_Entered);
				$Time = date('g:ia', $Date_Entered);

				if ($Current_Day !== $Day) {
					$Current_Day = $Day;
					echo "</ul></div><div class='day'><h4>$Day<br>$Date</h4><ul><li class='application'>";
				} else {
					echo "<li class='application'>";
				}
		      	?>
		        <a href="http://www.dexterfoundation.com/applications/?id=<?php echo $application['ID']; ?>">
			        <span class="star" data-favorite="<?php echo $application['Favorite']; ?>">&#9733;</span>
			        <?php
			        echo "<span class='name'>".$application['Name']."</span>";
			        ?>
			        <p class="date-entered"> 
			        	<?php
	        			echo "$Day<br>$Date<br>$Time"; 
	        			?>	
	        		</p>
		        		<?php 
	        			if ( $application['Lease'] != "Blank" ) {
	        				echo "<p class='lease no-lease'>(Lease Attached)</p>";
		        		} 
	        		?>
        		</a>
		      	</li>
		      <?php endforeach; ?>
				  
			    </ul>
			  </div>
		     </div>
	     </div>
	  </section>
	</main>
	
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script type="text/javascript" src="http://dexterfoundation.com/scripts.js"></script>
<script type="text/javascript">

	function search() {
		if ( $('.applications-submitted').hasClass('searching') == false ) {
			$('.applications-submitted').addClass('searching');
		}
		$('.application.hide').removeClass('hide');
		$('.application').each(function(){
			var application = $(this);
			if ( application.find('.name').length ) {
				var name = application.find('.name').html().toLowerCase();
				if ( name.includes( $('.search-bar input.search-input').val().toLowerCase() ) != true ) {
					application.addClass('hide');
				}	
			}
		});
	}
	function clearSearch() {
		$('.search-bar input.search-input').val('');
		$('.application.hide').removeClass('hide');
		$('.applications-submitted.searching').removeClass('searching');
	}
	$('.search-bar .search-input').on('change', function(){
		search();
	});
	$('.search-bar .search-input').on('blur', function(){

	});
	
	$('.search-bar .magnifying-glass').on('click', function(){
		search();
	});

	$('.search-bar .clear').on('click', function(){
		clearSearch();
	});
	$('.applications .day ul li').each(function(){
		// sets up the favorites list
		if ( $(this).find('.star').data('favorite') === "Yes" ) {
			$(this).appendTo('.favorites-list ul');
		}
	});

	function favorite(){
	// functionality for the Favorites button
		var favoriteButton = $('button.favorite-btn'),
			applicantId = favoriteButton.data('id');
		if ( favoriteButton.data('favorited') === "Yes" ) {
			favoriteButton.addClass('checked');
		} else {
			favoriteButton.addClass('unchecked');
		}
		favoriteButton.on('click', function(){
			if (favoriteButton.data('favorited') === "Yes") {
				favoriteButton.removeClass('checked');
				favoriteButton.data('favorited', 'No').addClass('unchecked');
			} else {
				favoriteButton.removeClass('unchecked');
				favoriteButton.data('favorited', 'Yes').addClass('checked');
			}
			$.post(	"/modules/favorites/",
					{Form_Name: "Application_Favorite", ID: applicantId, Favorite: favoriteButton.data('favorited')},
					function(data) {
						$('.test-console').html(data);
					}
			);
		});
	}
	favorite();
</script>
</body>
</html>