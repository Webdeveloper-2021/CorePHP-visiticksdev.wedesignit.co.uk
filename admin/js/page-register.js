$(document).on('click', '#submit-reg-form', function () {
	$('#formsuccess').addClass('uhide');
	$('#formerror').addClass('uhide');
		$.ajax(
		{
		url: "/page-register-form.php",
		data: $('#checkout').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#title',data["title"]);
			FieldStatus('#firstname',data["firstname"]);
			FieldStatus('#lastname',data["lastname"]);
			FieldStatus('#telephone',data["telephone"]);
			FieldStatus('#email',data["email"]);
			FieldStatus('#password',data["password"]);
			FieldStatus('#repeatpassword',data["repeatpassword"]);
			FieldStatus('#companyname',data["company"]);
			FieldStatus('#lookup_first_line',data["lookup_first_line"]);
			FieldStatus('#lookup_post_town',data["lookup_post_town"]);
			FieldStatus('#lookup_postcode',data["lookup_postcode"]);
			FieldStatus('#country',data["country"]);

				if(data["completed"])
				{
				ChangePage('page-account.php','','1');
				}
				else if(data["redirect"])
				{
					window.location.hash = data["redirect"];
				}
				else
				{
				$("#formerror").html(data["error"]);
				$('#formerror').removeClass('uhide');
				$('html, body').animate({scrollTop:$("#formerror").offset().top - 50}, 350);
					restoreBtnText();
				}

			},

			error: function(jqXHR,exception)
			{
			$('#formerror').removeClass('uhide');
			FieldStatus('#title','error');
			FieldStatus('#firstname','error');
			FieldStatus('#lastname','error');
			FieldStatus('#telephone','error');
			FieldStatus('#email','error');
			FieldStatus('#password','error');
			FieldStatus('#repeatpassword','error');
			FieldStatus('#company','error');
			FieldStatus('#lookup_first_line','error');
			FieldStatus('#lookup_post_town','error');
			FieldStatus('#lookup_postcode','error');
			FieldStatus('#country','error');
			}
		});

	});

