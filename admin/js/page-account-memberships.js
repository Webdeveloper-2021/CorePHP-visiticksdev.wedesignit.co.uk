$(document).on('click', '.membership-renew', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	$.ajax(
		{
			url: "/page-account-memberships-form.php",
			data: {
				id: $(this).data('id'),
				membershipid: $(this).data('membership-id')
			},
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

