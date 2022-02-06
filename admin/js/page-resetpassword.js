$(document).on('click', '#submit-resetpswd-form', function (e) {
	e.preventDefault();
	$('#formsuccess').addClass('uhide');
	$('#formerror').addClass('uhide');

		$.ajax(
		{
		url: "/page-resetpassword-form.php",
		data: $('#checkout').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#password',data["password"]);
			FieldStatus('#passwordrepeat',data["passwordrepeat"]);
			
				if(data["completed"])
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
			}
		});

	});

