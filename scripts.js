$('.masthead .mobile-button').on('click', function(){
	$('.masthead nav').addClass('active');
});
$('.close-button span.exit').on('click', function(){
	$('.masthead nav').removeClass('active');
});