$(document).on('click', '.account-change-time', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	if (!$('#date-input').val() || !$('#time-input').val() || !$('#sessionid-input').val()) {
		$('#formerror').text('Please select date and time.').removeClass('uhide');

		return false;
	}

	$.ajax(
		{
			url: "/page-account-orders-update-form.php",
			data: $('#account-event-time-form').serialize(),
			dataType: "json",

			success: function(data)
			{
				if(data["completed"])
				{
					window.location.hash = "#account-orders-view_" + $('#orderid').val();
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

