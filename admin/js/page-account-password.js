$(document).on('click', '#submit-accpswd-form', function (e) {
	e.preventDefault();
	$('#formsuccess').addClass('uhide');
	$('#formerror').addClass('uhide');

		$.ajax(
		{
		url: "/page-account-password-form.php",
		data: $('#checkout').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#password',data["password"]);
			FieldStatus('#passwordrepeat',data["passwordrepeat"]);
			FieldStatus('#oldpassword',data["oldpassword"]);
			
				if(data["completed"] == "true")
				{
				$('#formsuccess').removeClass('uhide');
				$('#maincontent #formerror, #maincontent .form-row, #maincontent .account-next').hide();
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
			FieldStatus('#password','error');
			FieldStatus('#passwordrepeat','errorrepeat');
			FieldStatus('#oldpassword','error');
			}
		});

	});

