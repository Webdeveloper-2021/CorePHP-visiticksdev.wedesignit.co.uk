function sitecenter_cookies_enabled(enabled)
{
	if(enabled == "NO")
	{
		var cookieEnabled = (navigator.cookieEnabled) ? true : false;

		if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
		{ 
		document.cookie="testcookie";
		cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
		}

		if(cookieEnabled == true)
		{
		document.getElementById('cookies').style.display = 'inline';
		document.getElementById('nocookies').style.display = 'none';
		}
		else
		{
		document.getElementById('cookies').style.display = 'none';
		document.getElementById('nocookies').style.display = 'inline';
		}
	}
}