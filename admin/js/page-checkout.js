$(document).on('click', '.summary', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	$.ajax(
		{
			url: "/page-checkout-form.php",
			data: $('#order-checkout').serialize(),
			dataType: "json",

			success: function(data)
			{
				if (data['redirect'])
					ChangePage(data['redirect']);

				FieldStatus('#title',data["title"]);
				FieldStatus('#firstname',data["firstname"]);
				FieldStatus('#lastname',data["lastname"]);
				FieldStatus('#phone',data["phone"]);
				FieldStatus('#postcode input',data["postcode"]);
				FieldStatus('#email',data["email"]);
				FieldStatus('#cardnumber',data["cardnumber"]);
				FieldStatus('#cardname',data["cardname"]);
				FieldStatus('#expiryyear',data["expiryyear"]);
				FieldStatus('#expirymonth',data["expirymonth"]);
				FieldStatus('#cardcvv',data["cardcvv"]);
				FieldStatus('#password',data["password"]);
				FieldStatus('#passwordrepeat',data["passwordrepeat"]);
				FieldStatus('#companyname',data["company"]);
				FieldStatus('#lookup_first_line',data["lookup_first_line"]);
				FieldStatus('#lookup_post_town',data["lookup_post_town"]);
				FieldStatus('#lookup_postcode',data["lookup_postcode"]);
				FieldStatus('#country',data["country"]);

				if(data["completed"])
				{
					window.location.hash = data['url'];
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