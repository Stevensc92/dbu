jQuery(function($){
	$('#all').click(function() {  
	  $('.check').prop('checked', $(this).is(':checked'));
	});

	$('#invert').click(function() {
	  
	  $('.check').each(function(i, check) {
	    $(check).prop('checked', !$(check).is(':checked'));
	  });
	    
	  $('#all').prop('checked', $('.check:checked').length == $('.check').length);
	    
	});

	$('.check').click(function() { 
	  $('#all').prop('checked', $('.check:checked').length == $('.check').length);
	});
});