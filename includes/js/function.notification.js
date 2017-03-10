jQuery(function($){
	var alert = $('#alert'); 
	if(alert.length > 0){
		alert.hide().slideDown(500);

		alert.delay(1500).slideUp(500);

		alert.find('.close').click(function(e){
			alert.slideUp(500);
		})
	}

	var modal = $('#modal');

	if (modal.length > 0)
	{
		modal.hide().slideDown(500);

		modal.find('close').click(function(e){
			modal.slideUp();
		});
	}
});