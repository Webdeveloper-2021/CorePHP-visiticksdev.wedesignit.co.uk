let sending = false;

$(document).on('click', '#resend-confirmation-email', function (e) {
	e.preventDefault();

	if (sending)
		return false;

	sending = true;

	let orderid = $(this).data('orderid'),
		statusCont = $(this).find('span');

	statusCont.text('Sending...');

		$.ajax(
		{
		url: "/resend-confirmation-email.php",
		data: {
			id: orderid
		},
		dataType: "json",

			success: function(data)
			{
				if(data["completed"])
				{
				statusCont.text('Confirmation email was successfully resent.');
				}
				else
				{
				statusCont.text(GENERIC_ERROR_MSG);
				sending = false;
				}

			},

			error: function(jqXHR,exception)
			{
			statusCont.text(GENERIC_ERROR_MSG);
			sending = false;
			}
		});

	});

