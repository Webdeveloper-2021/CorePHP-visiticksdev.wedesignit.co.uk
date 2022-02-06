$(document).on('click', '#submit-accdetails-form', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');
	$('#formsuccess').addClass('uhide');

		$.ajax(
		{
		url: "/page-account-details-form.php",
		data: $('#checkout').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#title',data["title"]);
			FieldStatus('#firstname',data["firstname"]);
			FieldStatus('#lastname',data["lastname"]);
			FieldStatus('#phone',data["phone"]);
			FieldStatus('#postcode',data["postcode"]);
			FieldStatus('#company',data["company"]);
			FieldStatus('#lookup_first_line',data["lookup_first_line"]);
			FieldStatus('#lookup_post_town',data["lookup_post_town"]);
			FieldStatus('#lookup_postcode',data["lookup_postcode"]);
			FieldStatus('#country',data["country"]);

				if(data["completed"])
				{
				$('#formsuccess').removeClass('uhide');
				$('html, body').animate({scrollTop:$("#formsuccess").offset().top - 50}, 350);
				}
				else
				{
				$('#formerror').removeClass('uhide');
				$('html, body').animate({scrollTop:$("#formerror").offset().top - 50}, 350);
				}

				restoreBtnText();
			},

			error: function(jqXHR,exception)
			{
			$('#formerror').removeClass('uhide');
			FieldStatus('#title','error');
			FieldStatus('#firstname','error');
			FieldStatus('#lastname','error');
			FieldStatus('#phone','error');
			FieldStatus('#postcode','error');
			FieldStatus('#company','error');
			FieldStatus('#lookup_first_line','error');
			FieldStatus('#lookup_post_town','error');
			FieldStatus('#lookup_postcode','error');
			FieldStatus('#country','error');
			}
		});

	});

