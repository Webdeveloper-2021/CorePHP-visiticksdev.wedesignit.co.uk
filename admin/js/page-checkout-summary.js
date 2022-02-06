$(document).on('click', '.pay-now', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	$.ajax(
		{
			url: "/page-checkout-summary-form.php",
			data: $('#checkout-summary').serialize(),
			dataType: "json",

			success: function(data)
			{
				if (data['redirect']) {
					ChangePage(data['redirect']);
					return;
				}

				if (data['redirectAway']) {
					window.location.href = data['redirectAway'];
					return;
				}

				if (data['redirectAwayPOST']) {
					$.redirectPost(data['redirectAwayPOST_URL'], data['redirectAwayPOST_data']);

					return;
				}

				if(data["completed"])
				{
					window.location.hash = "#confirmation";
				}
				else
				{
					$('#formerror').html(data['error']).removeClass('uhide');
					restoreBtnText();
				}
			},

			error: function(jqXHR,exception)
			{
				$('#formerror').text(GENERIC_ERROR_MSG).removeClass('uhide');
			}
		});

});