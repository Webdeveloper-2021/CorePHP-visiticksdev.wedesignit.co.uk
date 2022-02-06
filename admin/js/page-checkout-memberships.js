$(document).on('click', '.add-member', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	let membershipId = $(this).data('membership-id'),
		members = parseInt($('#membership_' + membershipId).attr('data-members')) + 1,
		maxMembers = $('#membership_' + membershipId).data('max-members'),
		number = 'additional_' + Date.now();

	$('#membership_' + membershipId).find('.member').last().after($('#addonMember_' + membershipId).html().replaceAll('[number]', number));
	$('#membership_' + membershipId).attr('data-members', members);

	if (members >= maxMembers) {
		$(this).hide();
	}
});

$(document).on('click', '.remove-member', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	if (confirm('Are you sure you want to remove this member?')) {
		let membershipId = $(this).data('membership-id'),
			members = parseInt($('#membership_' + membershipId).attr('data-members')) - 1,
			maxMembers = $('#membership_' + membershipId).data('max-members');

		$(this).closest('.member').remove();
		$('#membership_' + membershipId).attr('data-members', members);

		if (members < maxMembers) {
			$('.add-member').show();
		}
	}
});

$(document).on('click', '.is-child', function (e) {
	if ($(this).parents('.member').find('.child-required').length)
		if ($(this).is(':checked')) {
			$(this).parents('.member').find('.child-required').removeClass('uhide');
			$(this).parents('.member').find('.child-optional').removeClass('uhide');
			$(this).parents('.member').find('.adult-required:not(.child-required)').addClass('uhide');
			$(this).parents('.member').find('.adult-optional:not(.child-required)').addClass('uhide');
		} else {
			$(this).parents('.member').find('.child-required').addClass('uhide');
			$(this).parents('.member').find('.child-optional').addClass('uhide');
			$(this).parents('.member').find('.adult-required').removeClass('uhide');
			$(this).parents('.member').find('.adult-optional:not(.form-required)').removeClass('uhide');
		}
});

$(document).on('click', '.checkout-memberships', function (e) {
	e.preventDefault();

	$('div[id^=formerror]').addClass('uhide');
	$('#checkout-memberships .error').removeClass('error');

	$.ajax({
		url: "/page-checkout-memberships-form.php",
		data: $('#checkout-memberships').serialize(),
		dataType: "json",

		success: function(data)
		{
			if (data['redirect'])
				ChangePage(data['redirect']);

			if (data["completed"])
			{
				window.location.hash = '#checkout-summary';
			}
			else
			{
				if (data["invalidFields"]) {
					$.each(data["invalidFields"], function(i, v) {
						FieldStatus('#' + i, v);
					});
				}

				if (data['formError']) {
					$.each(data["formError"], function(i, v) {
						$('#formerror_' + i).html(v).removeClass('uhide');
					});
				}

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