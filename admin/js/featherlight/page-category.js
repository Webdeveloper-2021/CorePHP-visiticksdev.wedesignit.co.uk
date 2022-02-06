$(document).on('click', '#content-category .product-row.item .less', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) - 1;

	changeQuantityCategory(product, quantity);
});

$(document).on('click', '#content-category .product-row.item .more', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) + 1;

	changeQuantityCategory(product, quantity);
});

$(document).on('input', '#content-category .product-row.item .amount input', function() {
	changeQuantityCategory($(this).parents('.product-row'), $(this).parents('.product-row').data('maxquantity'));
});

$(document).on('blur', '#content-category .product-row.item .amount input', function() {
	if (! $(this).val() || $(this).val() < 0 || $(this).val().length < 1)
		changeQuantityCategory($(this).parents('.product-row'), 0);
	else if ($(this).val() > 0 && $(this).val() < $(this).parents('.product-row').data('minquantity'))
		changeQuantityCategory($(this).parents('.product-row'), $(this).parents('.product-row').data('minquantity'));
	else if ($(this).val() > $(this).parents('.product-row').data('maxquantity'))
		changeQuantityCategory($(this).parents('.product-row'), $(this).parents('.product-row').data('maxquantity'));
	else
		updateTotal();
});

$(document).on('change', '#donations_toggle', function() {
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

	updateTotal();
});

function changeQuantityCategory(product, quantity) {
	if (quantity < 0)
		quantity = 0;

	if (quantity > product.data('maxquantity'))
		quantity = product.data('maxquantity');

	if (quantity > 0 && quantity < product.data('minquantity'))
		quantity = product.data('minquantity');

	product.attr('data-quantity', quantity);
	product.find('.amount').find('input').val(quantity);

	updateTotal();
}

function updateTotal() {
	let total = 0;

	$('#content-category .product-row.item').each(function() {
		let price = $(this).attr('data-price'),
			qty = $(this).find('.amount input').val();

		total += parseFloat(price * qty);
	});

	$('#content-category .total-row')
		.attr('data-total', parseFloat(total).toFixed(2))
		.find('.product-row-price span')
		.text(parseFloat(total).toFixed(2));
}

$(document).on('click', '.modal .close-modal', function(e) {
	e.preventDefault();

	$('#addedoverlay').fadeOut();
});

$(document).on('click', '.add-to-cart', function (e) {
	e.preventDefault();

	$('#formerror').addClass('uhide');
	$('#formsuccess').addClass('uhide');

	if ($('.total-row').attr('data-total') <= 0) {
		$('#formerror').removeClass('uhide');
		restoreBtnText();

		return false;
	}

	$.ajax(
	{
	url: "/add-to-cart.php",
	data: $('#category-products').serialize(),
	dataType: "json",

		success: function(data)
		{
			if(data["completed"])
			{
				$('#content-category .product-row.item').each(function() {
					// category reduce

					let max_quantity = $(this).data("maxquantity");
					let select_quantity = $(this).data("quantity");
					let remain_quantity = Number(max_quantity) - Number(select_quantity);
					$(this).attr("data-maxquantity" , remain_quantity);
					$(this).find(".cart").attr("max",remain_quantity);
					//
					$(this).attr('data-quantity', 0);
					$(this).find('.amount input').val(0);
				});

				$('#content-category .total-row')
					.attr('data-total', 0)
					.find('.product-row-price span')
					.text(parseFloat(0).toFixed(2));


				$('a.add-to-cart .sitebutton').text('Added');
				$('body').removeClass('disabled');
				$('#addedoverlay').fadeIn();

				updateCartItemsCount();

				setTimeout(restoreBtnText, 3000);
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

