$(document).on('click', '.add-to-cart-event', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	if (!$('#date-input').val() || !$('#time-input').val()) {
		$('#formerror').text('Please select date and time.').removeClass('uhide');
		restoreBtnText();
		return false;
	}

	$.ajax(
	{
	url: "/add-to-cart-event.php",
	data: $('#event-time-form').serialize(),
	dataType: "json",

		success: function(data)
		{
			if(data["completed"])
			{
				window.location.hash = "#cart";
			}
			else
			{
			$('#formerror').text(data['error']).removeClass('uhide');
				restoreBtnText();
			}
		},

		error: function(jqXHR,exception)
		{
		$('#formerror').text(GENERIC_ERROR_MSG).removeClass('uhide');
		}
	});

});

