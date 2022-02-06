$(document).on('click', '#content-cart .product-row.item .less', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) - 1;

	changeQuantityCart(product, quantity);
});

$(document).on('click', '#content-cart .product-row.item .more', function(e) {
	e.preventDefault();

	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val()) + 1;

	changeQuantityCart(product, quantity);
});

$(document).on('input', '#content-cart .product-row.item .amount input', function() {
	let product = $(this).parents('.product-row'),
		quantity = parseInt(product.find('.amount input').val());

	changeQuantityCart(product, quantity);
});

$(document).on('blur', '#content-cart .product-row.item .amount input', function() {
	if (! $(this).val() || $(this).val() < 0 || $(this).val().length < 1)
		changeQuantityCart($(this).parents('.product-row'), 1);
	else
		updateTotalCart();
});

function changeQuantityCart(product, quantity) {
	if (quantity < 1 || !quantity)quantity = 1;
	let max_quantity = Number(product.find('.amount').find('input').attr("max"));
	quantity = Math.min(quantity,max_quantity);
	
	if (quantity < 1 || !quantity)quantity = 1;
	console.log(quantity);
	console.log(max_quantity);
	product.attr('data-quantity', quantity);

	product.find('.amount').find('input').val(quantity);

	if (product.hasClass('membership')) {
		if ($('.free-event-ticket-count').length) {
			$('input.free-event-ticket-count').val(quantity);
			$('span.free-event-ticket-count').text(quantity);
		}
	}

	updateTotalCart();
}

function updateTotalCart() {
	let total = 0;

	$('#content-cart .product-row.item').each(function() {
		let price = $(this).attr('data-price'),
			qty = $(this).find('.amount input').val();

		total += parseFloat(price * qty);
	});

	$('#content-cart .product-row.event').each(function() {
		let price = $(this).attr('data-price'),
			qty = $(this).attr('data-quantity');

		total += parseFloat(price * qty);
	});

	$('#content-cart .total-row')
		.attr('data-total', parseFloat(total).toFixed(2))
		.find('.product-row-price span')
		.text(parseFloat(total).toFixed(2));
}

var timer;
var _second = 1000;
var _minute = _second * 60;
var _hour = _minute * 60;

function showRemaining() {
	if (!document.getElementById('cart-warning'))
		return;

	var end = new Date(window.cart_expires * 1000);

	var now = new Date();
	var distance = end - now;
	if (distance < 0) {

		clearInterval(timer);
		document.getElementById('cart-warning').innerHTML = 'Sorry, your shopping cart has expired and your items have been removed.';
		document.getElementById('cart-items').innerHTML = '';

		$('#content-cart .total-row')
			.attr('data-total', 0)
			.find('.product-row-price span')
			.text(0);

		$('#tab-cart-items').text(0);
		$('#top-cart-items').text(0);

		$.post('/cart-expired.php');

		return;
	}

	var minutes = Math.floor((distance % _hour) / _minute);
	var seconds = Math.floor((distance % _minute) / _second);

	document.getElementById('countdown').innerHTML = '';
	document.getElementById('countdown').innerHTML += minutes + 'm ';
	document.getElementById('countdown').innerHTML += seconds + 's';
}

timer = setInterval(showRemaining, 1000);

$(document).on('click', '.remove-from-cart', function (e) {
	e.preventDefault();

	let id = $(this).data('id'),
		item_row = $(this).parents('.product-row');

	if(confirm('Are you sure you want to remove this item from your cart?')) {
		$.ajax(
		{
		url: "/remove-item.php",
		data: {
			id: id
		},
		dataType: "json",

			success: function(data)
			{
				if(data["completed"])
				{
					item_row.fadeOut(function() {
						$(this).remove();
						$('#tab-cart-items').text(data["cartItemCount"]);
						$('#top-cart-items').text(data["cartItemCount"]);

						updateTotalCart();

						if (data["cartItemCount"] < 1) {
							$('.cart-warning, .offer-row, .total-row').remove();
							$('#cart-items').html('<div class="product-row notice-row urow"><div>Your cart is currently empty.</div></div>')
						}
					});
				}
				else
				{
				$('#formerror').text(data['error']).removeClass('uhide');
				}
			},

			error: function(jqXHR,exception)
			{
			$('#formerror').text(GENERIC_ERROR_MSG).removeClass('uhide');
			}
		});
	}

	return false;
});

$(document).on('click', '.checkout', function (e) {
	e.preventDefault();

	$.ajax(
		{
			url: "/update-items.php",
			data: $('#cart-items').serialize(),
			dataType: "json",

			success: function(data)
			{
				if(data["completed"])
				{
					window.location.hash = "#checkout";
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