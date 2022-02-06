<?php

	function form_process_makesafe($variable)
	{
	return htmlspecialchars_decode($variable);
	}

	function form_process_WYSIWYG($variable)
	{
	return $variable;
	}

///*****************************************************************************///

	function form_valid_email($email)
	{
//	$email = trim($email);
//
//	$email_array = explode('@', $email);
//	$local_array = explode('.', $email_array[0]);
//
//		if(strlen($email) > 100 || strlen($email) < 5 || preg_match('/[\x00-\x1F\x7F-\xFF]/', $email) || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email))
//		{
//		return "FALSE";
//		}
//
//		foreach ($local_array as $local_part)
//		{
//			if (!preg_match('/^(([A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+)|("[^"]+"))$/', $local_part))
//			return "FALSE";
//
//			if (preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/', $email_array[1]) || preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/', $email_array[1]))
//			return "TRUE";
//			else
//			{
//			$domain_array = explode('.', $email_array[1]);
//
//				if (sizeof($domain_array) < 2)
//				return "FALSE";
//
//				foreach ($domain_array as $domain_part)
//				if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]))$/', $domain_part))
//				return "FALSE";
//
//			return "TRUE";
//			}
//		}
        return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

///*****************************************************************************///

	function form_valid_string($type,$string,$min = 1,$max = '',$alphanumeric = false)
	{
	$string = trim($string);
	$string = html_entity_decode($string);

		if($type == "VARCHAR")
		{
			if(!$max)
			{
			$max = "200";
			}
		}
		elseif($type == "TEXT")
		{
			if(!$max)
			{
			$max = "80000";
			}
		}
		elseif($type == "PASSWORD")
		{
			if(!$max)
			{
			$max = "100";
			}

			if(!$min)
			{
			$min = "6";
			}
		}
		elseif($type == "USERNAME")
		{
		$max = "50";
		$min = "4";
		}
		elseif($type == "POSCODE")
		{
		$max = "5";
		$min = "1";
		}

		if($type == "PASSWORD" && (!preg_match("#[0-9]+#",$string) || !preg_match("#[A-Z]+#",$string) || !preg_match("#[a-z]+#",$string) | !preg_match("#[\W]+#",$string)))
		{
		return false;
		}
		elseif($type == "USERNAME" && preg_match('/\s/',$string))
		{
		return false;
		}
		elseif($alphanumeric && !ctype_alnum($string))
		{
		return false;
		}
		elseif(strlen($string) > $max)
		{
		return false;
		}
		elseif($min && strlen($string) < $min)
		{
		return false;
		}

		return true;
	}

///*****************************************************************************///

	function form_valid_Integer($string,$type,$minlength,$maxlength)
	{
	$integertest = $string/1;

		if(!is_numeric($string) || !is_int($integertest))
		{
		return false;
		}

        if($type == "VALUE")
        {
            if($string > $maxlength || $string < $minlength)
            {
            return false;
            }

            return true;
        }
        elseif($type == "LENGTH")
        {
            if(strlen($string) > $maxlength || strlen($string) < $minlength)
            {
            return false;
            }

            return true;
        }

        return true;
	}