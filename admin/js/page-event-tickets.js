$(document).on('click', '#event-tickets-cont .product-row.item .less', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) - 1;

	changeQuantity(product, quantity);
});

$(document).on('click', '#event-tickets-cont .product-row.item .more', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) + 1;

	changeQuantity(product, quantity);
});

$(document).on('input', '#event-tickets-cont .product-row.item .amount input', function() {
	if ($(this).val().length > 2)
		changeQuantity($(this).parents('.product-row'), $(this).parents('.product-row').data('maxquantity'));
	else
		updateTotalEvent();
});

$(document).on('blur', '#event-tickets-cont .product-row.item .amount input', function() {
	if (! $(this).val() || $(this).val() < 0 || $(this).val().length < 1)
		changeQuantity($(this).parents('.product-row'), 0);
	else if ($(this).val() > 0 && $(this).val() < $(this).parents('.product-row').data('minquantity'))
		changeQuantity($(this).parents('.product-row'), $(this).parents('.product-row').data('minquantity'));
	else if ($(this).val() > $(this).parents('.product-row').data('maxquantity'))
		changeQuantity($(this).parents('.product-row'), $(this).parents('.product-row').data('maxquantity'));
	else
		updateTotalEvent();
});

$(document).on('change', '#donations_toggle_event', function() {
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

	updateTotalEvent();
});

function changeQuantity(product, quantity) {
	if (quantity < 0)
		quantity = 0;

	if (quantity > product.data('maxquantity'))
		quantity = product.data('maxquantity');

	if (quantity > 0 && quantity < product.data('minquantity'))
		quantity = product.data('minquantity');

	product.attr('data-quantity', quantity);
	product.find('.amount').find('input').val(quantity);

	updateTotalEvent();
}

function updateTotalEvent() {
	let total = 0;

	$('#event-tickets-cont .product-row.item').each(function() {
		let price = $(this).attr('data-price'),
			qty = $(this).find('.amount input').val();

		total += parseFloat(price * qty);
	});

	$('#event-tickets-cont .total-row')
		.attr('data-total', parseFloat(total).toFixed(2))
		.find('.product-row-price span')
		.text(parseFloat(total).toFixed(2));
}

$(document).on('click', '.event-time', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');
	$('#formsuccess').addClass('uhide');

	if ($('#event-tickets-cont .total-row').attr('data-total') <= 0) {
		$('#formerror').removeClass('uhide');

		restoreBtnText();
		return false;
	}

	$.ajax(
	{
	url: "/page-event-tickets-form.php",
	data: $('#event-tickets').serialize(),
	dataType: "json",

		success: function(data)
		{
			if(data["completed"])
			{
				window.location.hash = "#event-time_" + $('#event-tickets').find('input[name=eventid]').val();
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

