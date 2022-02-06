$(document).on('submit', '#login-form', function (e) {
	e.preventDefault();
		$('#formsuccess').addClass('uhide');
		$('#formerror').addClass('uhide');

		$.ajax(
		{
		url: "/page-login-form.php",
		data: $('#login-form').serialize(),
		dataType: "json",

			success: function(data)
			{
			FieldStatus('#email',data["email"]);
			FieldStatus('#password',data["password"]);
			
				if(data["completed"])
				{
				ChangePage('page-account.php','',2);
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
				}
				restoreBtnText();

			},

			error: function(jqXHR,exception)
			{
			$('#formerror').removeClass('uhide');
			FieldStatus('#email','error');
			FieldStatus('#password','error');
			}
		});

	});

