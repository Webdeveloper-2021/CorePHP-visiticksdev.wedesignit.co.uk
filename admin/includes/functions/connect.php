<?php

use includes\classes\Cache;

function run_connect_Curl($api_url, $api_useragent, $requesturl, $requesttype, $requestbody, $token, $api_logs, $date, $datetime, $install_type, $install_version)
	{
	$requesturl = $api_url."".$requesturl;
	$headers = array("Content-Type: application/json",);

		if($token != "")
		{
		$headers[] = "Authorization: Bearer ".$token;
		}

    $log = false;
	$ch = curl_init($requesturl);

		if(!$ch || $ch == "FALSE")
		{
		$result = "ERROR";
		}
		else
		{
			if($requesttype == "POST")
			{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestbody, JSON_UNESCAPED_SLASHES));
			}
			else
			{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requesttype);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestbody, JSON_UNESCAPED_SLASHES));
			}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, $api_useragent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

		$content = curl_exec($ch);
		$response = curl_getinfo($ch);
		$error = curl_error($ch);
		curl_close($ch);

		$curlresponse = $response['http_code'];

			if($curlresponse == 200 || $curlresponse == 204 || $curlresponse == 201 || $curlresponse == 202)
			{
			$result = "SUCCESS|X|".$content;
			}
			elseif($curlresponse == 400)
			{
                //dd($response,json_encode($requestbody, JSON_UNESCAPED_SLASHES), $content);
			$result = "FAIL|X|".$content;
			$log = true;
			}
			elseif($curlresponse == 401)
			{
			$result = "UNAUTH";
            $log = true;
			}
            elseif($curlresponse == 404)
            {
               //dd($response,json_encode($requestbody, JSON_UNESCAPED_SLASHES), $content);
            $result = "NOTFOUND";
            $log = true;
            }
			else
			{
			    //dd($response,json_encode($requestbody, JSON_UNESCAPED_SLASHES), $content);
			$result = "ERROR";
            $log = true;
			}
		}

		if($api_logs || $log)
		{
		run_logs_create($date,$datetime,$install_type,$install_version,$requesturl,$requesttype,$requestbody,$curlresponse,$content);
		}

	return $result;
	}

///*****************************************************************************///

	function process_connect_Curl($token,$requesturl,$requesttype,$requestbody,$api_url,$api_username,$api_password,$api_useragent,$api_logs,$date,$datetime,$install_type,$install_version)
	{

		if(!$token || $token == "")
		{
		$tokenrequesturl = "users/authenticate";
		$tokenrequesttype = "POST";
		$tokenrequestbody = array('username'=>$api_username,'password'=>$api_password);

		$tokenresponse = run_connect_Curl($api_url,$api_useragent,$tokenrequesturl,$tokenrequesttype,$tokenrequestbody,'',$api_logs,$date,$datetime,$install_type,$install_version);

			if($tokenresponse != "ERROR")
			{
			$tokenarray = explode('|X|',$tokenresponse);
			$token = $tokenarray[1];

			Cache::set('apiToken', $token);

			return process_connect_Curl($token,$requesturl,$requesttype,$requestbody,$api_url,'','',$api_useragent,$api_logs,$date,$datetime,$install_type,$install_version);
			}
			else
			{
			return "ERROR";
			}
		}
		else
		{
		$curlresponse = run_connect_Curl($api_url,$api_useragent,$requesturl,$requesttype,$requestbody,$token,$api_logs,$date,$datetime,$install_type,$install_version);

			if($curlresponse != "ERROR")
			{
				if($curlresponse == "UNAUTH")
				{
				process_connect_Curl('',$requesturl,$requesttype,$requestbody,$api_url,$api_username,$api_password,$api_useragent,$api_logs,$date,$datetime,$install_type,$install_version);
				}
				else
				{
				return $curlresponse;
				}
			}
			else
			{
			return "ERROR";
			}
		}
	}