<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<header class="masthead">
	<style type="text/css">
		.masthead .top-banner {
			flex: 0 0 100%;
			display: flex;
			justify-content: space-between;
			background-color: #33a9d1;
		    padding: 0.5em 1em;
		    box-sizing: border-box;
		    border-bottom: 1px solid white;
		}
		.masthead {
			flex-wrap: wrap;
		}
		.location-info {
		    margin: 0;
		    font-size: 0.9em;
		    color: white;
		}
		.social-media svg {
			fill: white;
			transition: 0.2s;
		}
		.social-media a:hover svg {
		    fill: #b0deed;
		}
	</style>
	<div class="top-banner">
		<h6 class="location-info">
			Serving Los Angeles County and Southern California
		</h6>
		<div class="social-media">
			<a href="https://www.facebook.com/Dexter-Foundation-231468836941028/" target="_blank">
				<svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
			</a>
		</div>
	</div>
	<div class="logo">
		<a href="/" style="display:block"><img src="https://www.dexterfoundation.com/img/logo_2020.png"></a>
	</div>
	<nav>
		<div class="close-button-wrapper">
			<span class="close-button">
				<span class="exit">&times;</span>
				Close
			</span>
		</div>
		<ul>
			<li data-pagename="Home">
				<a href="/">Home</a>
			</li>
			<li data-pagename="How To Help">
				<a href="/how-to-help/">How To Help</a>
			</li>
			<li data-pagename="About Us">
				<a href="/about-us/">About Us</a>
			</li>
			<li data-pagename="Our Page on Petfinder">
				<a target="_blank" href="https://www.adoptapet.com/shelter75425-pets.html">Our Page on<br>Adopt-a-Pet.com</a>
			</li>
			<li data-pagename="Success Stories">
				<a href="/success-stories/">Success Stories</a>
			</li>
			<li data-pagename="Contact Us">
				<a href="/contact-us/">Contact Us</a>
			</li>
			<li data-pagename="Adoption Application">
				<a href="/adoption-application/">Adoption Application</a>
			</li>
		</ul>
        <?php if (isset($_SESSION['Logged_In']) && $_SESSION['Logged_In'] === 'Dexter'): ?>
        <ul>
            <li class='admin-page'><a href='/applications/'>Applications Received</a></li>
            <li class='admin-page'><a href='/editor/'>Editor</a></li>
            <li class='admin-page'><a href='/logout/'>Logout</a></li>
        </ul>
        <?php endif; ?>
	</nav>
	<div class="mobile-button">
		<div class="hamburger">
			<span></span>
			<span></span>
			<span></span>
		</div>
		Menu
	</div>
</header>
