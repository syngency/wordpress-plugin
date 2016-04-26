jQuery(document).ready(function($){ 
	// Gender filter
	$('.syngency-gender-filter a').click(function(e) { 
		e.preventDefault();
		var gender = $(this).attr('href').substring(1);
		$('.syngency-gender-filter a').removeClass('active');
		if ( gender == 'all' )
		{
			$('.syngency-division-model').removeClass('hide');
			$('.syngency-index-filter a').removeClass('active');
		}
		else
		{
			$('.syngency-division-model').addClass('hide');
			$('[data-gender="' + gender + '"]').removeClass('hide');
		}
		$(this).addClass('active');
	});

	// Index filter
	$('.syngency-index-filter a').click(function(e) {
		e.preventDefault();
		var index = $(this).attr('href')[1];
		$('.syngency-index-filter a').removeClass('active');
		$('.syngency-gender-filter a').removeClass('active');
		$('.syngency-division-model').addClass('hide');
		$('[data-index="' + index + '"]').removeClass('hide');
		$(this).addClass('active');
	});

	// Gallery switcher
	$('.syngency-model-galleries a').click(function(e) {
		e.preventDefault();
		var gallery = $(this).attr('href');
		$('.syngency-model-galleries a').removeClass('active');
		$('.syngency-model-gallery').addClass('hide');
		$(gallery).removeClass('hide');
		$(this).addClass('active');
	});
});