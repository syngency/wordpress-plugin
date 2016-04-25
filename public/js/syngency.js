jQuery(document).ready(function($){ 
	$('[data-gender]').click(function(e) { 
		e.preventDefault();
		if ( $(this).data('gender') == 'all' )
		{
			$('.syngency-division-model').removeClass('hide');
		}
		else
		{
			$('.syngency-division-model').addClass('hide');
			$('.syngency-division-model.' + $(this).data('gender')).removeClass('hide');
		}
	}); 
});