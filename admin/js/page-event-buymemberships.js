$(document).on('click', '#content-buymemberships .product-row.item .less', function(e) {
	e.preventDefault();

	console.log('asdas');

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) - 1;

	changeQuantityMemberships(product, quantity);
});

$(document).on('click', '#content-buymemberships .product-row.item .more', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) + 1;

	changeQuantityMemberships(product, quantity);
});

$(document).on('input', '#content-buymemberships .product-row.item .amount input', function() {
	if ($(this).val().length > 2)
		changeQuantityMemberships($(this).parents('.product-row'), 99);
	else
		updateTotalMemberships();
});

$(document).on('blur', '#content-buymemberships .product-row.item .amount input', function() {
	if (! $(this).val() || $(this).val() < 0 || $(this).val().length < 1)
		changeQuantityMemberships($(this).parents('.product-row'), 0);
	else
		updateTotalMemberships();
});

$(document).on('change', '#donations_toggle_memberships', function() {
	let with_donations = $(this).is(':checked');

	$('.product-row.item').each(function() {
		let price_no_donation = $(this).data('nodonation-price'),
			price_donation = $(this).data('donation-price');

		if (with_donations) {
			$(this).attr('data-price', price_donation);
			$(this).find('.product-row-price span').text(price_donation);
		} else {
			$(this).attr('data-price', price_no_donation);
			$(this).find('.product-row-price span').text(price_no_donation);
		}
	});

	$.post('/donations-toggle.php', {with_donations: with_donations});

	updateTotalMemberships();
});

function changeQuantityMemberships(product, quantity) {
	if (quantity < 0)
		quantity = 0;

	product.attr('data-quantity', quantity);
	product.find('.amount').find('input').val(quantity);

	updateTotalMemberships();
}

function updateTotalMemberships() {
	let total = 0;

	$('#content-buymemberships .product-row.item').each(function() {
		let price = $(this).attr('data-price'),
			qty = $(this).find('.amount input').val();

		total += parseFloat(price * qty);
	});

	$('#content-buymemberships .total-row')
		.attr('data-total', parseFloat(total).toFixed(2))
		.find('.product-row-price span')
		.text(parseFloat(total).toFixed(2));
}

$(document).on('click', '.check-buymemberships', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');
	$('#formsuccess').addClass('uhide');

	if ($('.total-row').attr('data-total') <= 0) {
		$('#formerror').removeClass('uhide');

		return false;
	}

	$.ajax(
	{
	url: "/check-buymemberships.php",
	data: $('#form-buymemberships').serialize(),
	dataType: "json",

		success: function(data)
		{
			if(data["completed"])
			{
				window.location.hash = "#event-time_" + $('#form-buymemberships input[name=eventid]').val() + "|" + data["people"];
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

