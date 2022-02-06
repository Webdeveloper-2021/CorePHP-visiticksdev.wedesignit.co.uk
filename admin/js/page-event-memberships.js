$(document).on('click', '#memberships-content .product-row.item .less', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) - 1;

	if (quantity < 1)
		quantity = 1;

	product.find('.amount input').val(quantity);
});

$(document).on('click', '#memberships-content .product-row.item .more', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) + 1;

	if (quantity > 99)
		quantity = 99;

	product.find('.amount input').val(quantity);
});

$(document).on('input', '#memberships-content .product-row.item .amount input', function() {
	if ($(this).val().length > 2)
		$(this).val(99)
});

$(document).on('blur', '#memberships-content .product-row.item .amount input', function() {
	if (! $(this).val() || $(this).val() < 0 || $(this).val().length < 1)
		$(this).val(1);
});

$(document).on('click', '.check-memberships', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');

	$.ajax(
		{
			url: "/check-memberships.php",
			data: $('#memberships-form').serialize(),
			dataType: "json",

			success: function(data)
			{
				if(data["completed"])
				{
					if (data["enough"])
						window.location.hash = "#event-time_" + $('#memberships-form input[name=eventid]').val() + "|" + data["people"];
					else
						window.location.hash = "#event-buymemberships_" + $('#memberships-form input[name=eventid]').val() + "|" + data["people"];
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