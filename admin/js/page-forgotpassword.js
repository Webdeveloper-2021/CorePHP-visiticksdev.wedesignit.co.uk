$(document).on('click', '#submit-forgotpswd-form', function (e) {
	e.preventDefault();
	$('#formsuccess').addClass('uhide');
	$('#formerror').addClass('uhide');

		$.ajax(
		{
		url: "/page-forgotpassword-form.php",
		data: $('#checkout').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#email',data["email"]);

				if(data["completed"])
				{
				$('#forgot-formsuccess').removeClass('uhide');
				$('#maincontent > .urow p, #maincontent #forgot-formerror, #maincontent .form-row, #maincontent .account-next').hide();

				$('html, body').animate({scrollTop:$("#formsuccess").offset().top - 50}, 350);
				}
				else
				{
				$("#formerror").html(data["error"]);
				$('#formerror').removeClass('uhide');
				$('html, body').animate({scrollTop:$("#formerror").offset().top - 50}, 350);
				}
				restoreBtnText();
			},

			error: function(jqXHR,exception)
			{
			$('#formerror').removeClass('uhide');
			FieldStatus('#email','error');
			}
		});

	});

