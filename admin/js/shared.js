	// Set Global Ajax Values
	$.ajaxSetup(
		{
			cache: "false",
			type: "POST"
		});

	$(document).ajaxError(function() {
		restoreBtnText();
	});

	// Field Error Handler
	function FieldStatus(FieldID,FieldValue)
	{
		if (FieldValue == 'error')
		{
			$(FieldID).removeClass('normal');
			$(FieldID).addClass('error');
		}
		else
		{
			$(FieldID).removeClass('error');
			$(FieldID).addClass('normal');
		}
	}

	function ChangePage(page,contentid,successid)
	{
		LoadContent(page,contentid,successid);
		ActiveClass(page);
		
		$('html, body').animate({scrollTop:0}, 350);
	}

	function updateCartItemsCount()
	{
		$.get('/cart-items-count.php', function(response) {
			if (response.cartItemsCount) {
				$('#top-cart-items').text(response.cartItemsCount);
			}
		}, "json");
	}

	function showHidePanel1()
	{
		var accountoption;

		for(var i=0;i< document.checkout.accountoption.length;i++)
		{
			if(document.checkout.accountoption[i].checked)
			{
				accountoption = document.checkout.accountoption[i].value;
			}
		}

		document.getElementById('showpassword').style.display = accountoption == 'CREATEACCOUNT' ? 'inline' : 'none';
	}

	function showHidePanel2()
	{
		var paymentoption;

		for(var i=0;i< document.checkout.paymentoption.length;i++)
		{
			if(document.checkout.paymentoption[i].checked)
			{
				paymentoption = document.checkout.paymentoption[i].value;
			}
		}

		if (paymentoption == 1) {
			document.getElementById('showcard').style.display = 'inline';
			$('.address .form-required').show();
		} else {
			document.getElementById('showcard').style.display = 'none';
			$('.address .form-required').hide();
		}
	}

	function showHidePanel7(){
		for(var i=0;i<document.checkout.usertype.length;i++){
			if(document.checkout.usertype[i].checked){
				var usertype=document.checkout.usertype[i].value;
			}
		}

		if(usertype=='COMPANY'){
			$('#showcompany').removeClass('uhide');

			if (NAME_REQUIRED)
				$('.requiredfield').show();
			else
				$('.requiredfield').hide();
		}else{
			$('#showcompany').addClass('uhide');
			$('.requiredfield').show();
		}
	}

	$(document).on('click', '.showHideAddress', function() {
		console.log("OK");
		$(this).parents('.content-half').find('.addresscont').toggleClass("uhide");
		$(this).parents('.content-half').find('.marketing').toggle();
	});

	$(document).on('click', '.showHideName', function() {
		$(this).parents('.content-half').find('.namecont').toggleClass('uhide');

		let phoneElm = $(this).parents('.content-half').find('[id^=phone]');
		let emailElm = $(this).parents('.content-half').find('[id^=email]');

		if ($(this).is(':checked')) {
			phoneElm.val(phoneElm.data('billingphone'));
			emailElm.val(emailElm.data('billingemail'));
		} else {
			phoneElm.val('');
			emailElm.val('');
		}
	});

	function LoadContent(page,contentid,successid)
	{
		let data = {};

		if (contentid)
			data.contentid = contentid;

		if (successid)
			data.successid = successid;

		if (page.split('?')[0] == 'page-confirmation.php') {
			if (!ORDERCOMPLETED) {
				if (CONFIRMD) {
					if (!$.isEmptyObject(POSTDATA)) {
						data = POSTDATA;
					} else {
						CONFIRMD = 0;
						window.location.hash = '#home';
						return;
					}
				}

				$("#maincontent").html('<div class="order-processing"><div class="loadingio-spinner-eclipse-600rdy69hu5"><div class="ldio-rz52aey6hp8"><div></div></div></div><p>Order Processing</p></div>');
			} else {
				ORDERCOMPLETED = 0;
				window.location.hash = '#home';
				return;
			}
		}

		$.ajax(
			{
				url: "/" + page,
				data: data,
				dataType: "json",

				beforeSend: function() {
					$('body').addClass('disabled');
				},
				success: function(data)
				{
					if(typeof data["redirect"] === "undefined" || data["redirect"] === "")
					{
						if(data["content"] === "ERROR")
						{
							$("#maincontent").html('<p>' + GENERIC_ERROR_MSG + '</p>');
							$('html, body').animate({scrollTop:0}, 350);
							$('body').removeClass('disabled');
						}
						else
						{
							$("#maincontent").fadeOut("fast", function() {
								$('html, body').animate({scrollTop:0}, 350);

								$("#maincontent").html(data["content"]).fadeIn("slow", function() {
									$('body').removeClass('disabled');

									if (data['scrollTo']) {
										$('html, body').animate({scrollTop: $(data['scrollTo']).offset().top - 50}, 350);
									}

									$('[tabindex]').each(function(index, elm) {
										$(elm).attr('tabindex', index+1);
									});
								});
							});
						}
					}
					else
					{
						LoadContent(data["redirect"], data["contentid"]);
					}
				},
				error: function(jqXHR,exception)
				{
					$("#maincontent").html('<p>' + GENERIC_ERROR_MSG + '</p>');
					$('html, body').animate({scrollTop:0}, 350);
					$('body').removeClass('disabled');
				}
			});
	}

	function GetContentId(hash)
	{
		var one = hash.split('|'),
			two = one[0].split('_');

		return two[1];
	}

	function GetSuccessId(hash)
	{
		var one = hash.split('|');

		return one[1];
	}

	function GetPage(hash)
	{
		var page = "page-home.php",
			one = hash.split('?'),
			two = one[0].split('_');

		hash = two[0];

		if(hash !== "#home" && hash !== "#" && hash !== "")
		{
			hash = hash.substr(1);
			page = "page-" + hash + ".php";

			if (typeof one[1] !== "undefined")
				page += "?" + one[1];
		}

		return page;
	}

	function ActiveClass(page)
	{
		if(page === "page-home.php")
		{
			$('#home').addClass('active');
			$('#account').removeClass('active');
			$('#cart').removeClass('active');
		}
		else if(page.includes("account"))
		{
			$('#home').removeClass('active');
			$('#account').addClass('active');
			$('#cart').removeClass('active');
		}
		else if(page === "page-cart.php")
		{
			$('#home').removeClass('active');
			$('#account').removeClass('active');
			$('#cart').addClass('active');
		}
		else
		{
			$('#home').removeClass('active');
			$('#account').removeClass('active');
			$('#cart').removeClass('active');
		}
	}

	function Initialise()
	{
		var hash = window.top.location.hash,
			page = GetPage(hash),
			contentid = GetContentId(hash),
			successid = GetSuccessId(hash);

		LoadContent(page, contentid, successid);
		ActiveClass(page);
		updateCartItemsCount();
	}

	$(document).ready(Initialise);
	$(window).on('hashchange', Initialise);

	$(document).on('focus','.placeholder',function()
	{
		if($(this).val() == $(this).attr('placeholder'))
		{
			$(this).val('');
		}
	});

	$(document).on('blur','.placeholder',function()
	{
		if($(this).val() == '')
		{
			$(this).val($(this).attr('placeholder'));
		}
	});

	function loader(e) {
		let elm = $(e),
			btn = elm.hasClass('sitebutton') ? elm : elm.find('.sitebutton'),
			orgText = btn.is('input') ? btn.val() : btn.html();

		if (btn.is('input')) {
			btn.val('Loading...');
		} else {
			if (btn.attr('href') == window.location.hash)
				return false;

			btn.text('Loading...');
		}

		btn.addClass('faded disabled').attr('data-orgtext', orgText);
		btn.parents('a').addClass('disabled');

		$('body').addClass('disabled');
	}

	function restoreBtnText() {
		$('.sitebutton[data-orgtext]').each(function() {
			if ($(this).is('input')) {
				$(this).val($(this).data('orgtext'));
			} else {
				$(this).html($(this).data('orgtext'));
			}

			$(this).removeClass('disabled faded');
			$(this).parents('a').removeClass('disabled');
			$('body').removeClass('disabled');
		})
	}
	
	if(document.cookie.indexOf("CookiePermission") ===-1 )
	{
	$("#cookienotice").show();
	}

	function sitecenter_set_cookie(c_name,value,expiredays)
	{
	var exdate=new Date()
	exdate.setDate(exdate.getDate()+expiredays)
	document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+";SameSite=None;Secure"
	}

	$("#removecookie").click(function ()
	{
	sitecenter_set_cookie('CookiePermission','CookiePermission',365)
	$("#cookienotice").remove();
	});

	$.extend(
		{
			redirectPost: function(location, args)
			{
				var form = '';
				$.each( args, function( key, value ) {
					value = value.split('"').join('\"');
					form += '<input type="hidden" name="'+key+'" value="'+value+'">';
				});
				$('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
			}
		});