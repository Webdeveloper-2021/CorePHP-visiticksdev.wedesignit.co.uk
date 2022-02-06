<?php
namespace includes\classes;

use Exception;

class API
{
    private static function request($url, $type, $body = '', $jsonDecode = true): array {
        try {
            if (!defined('API_URL')) {
                throw new Exception("API_URL is not defined in config file.");
            }

            if (!in_array($type, ['POST', 'GET', 'PUT', 'DEL'])) {
                throw new Exception("Invalid API request type. Must be POST/GET/PUT/DEL.");
            }

            $requestUrl = trailingslashit(API_URL) . $url;
            $headers = ["Content-Type: application/json"];
            $headers[] = "Authorization: Bearer " . Cache::get('apiToken');

            $ch = curl_init($requestUrl);

            if(!$ch || $ch == "FALSE")
            {
                $result = [
                    'ok'        => false,
                    'content'   => curl_error($ch)
                ];
            }
            else
            {
                if($type === "POST")
                {
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES));
                }
                else
                {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES));
                }

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);

                if (defined(API_USERAGENT)) {
                    curl_setopt($ch, CURLOPT_USERAGENT, API_USERAGENT);
                }

                $content = curl_exec($ch);
                $response = curl_getinfo($ch);
                curl_close($ch);


                $httpCode = $response['http_code'];

                if ($jsonDecode)
                    $content = json_decode($content);

                if($httpCode == 200 || $httpCode == 204 || $httpCode == 201 || $httpCode == 202)
                {
                    $result = [
                        'ok'        => true,
                        'httpCode'  => $httpCode,
                        'content'   => $content
                    ];
                }
                elseif($httpCode == 401)
                {
                    self::getToken();

                    return self::request($url, $type, $body, $jsonDecode);
                }
                else
                {

                    $result = [
                        'ok'        => false,
                        'httpCode'  => $httpCode,
                        'content'   => $content
                    ];
                }
            }

//            if(SETTING_LOGS)
//            {
//                run_logs_create($date,$datetime,$install_type,$install_version,$requesturl,$requesttype,$requestbody,$curlresponse,$content);
//            }

        } catch(Exception $e) {
            die('Error: ' . $e->getMessage());
        }

        return $result;
    }

    private static function getToken() {
        try {
            if (!defined('API_USERNAME') || !defined('API_PASSWORD')) {
                throw new Exception("API username and/or password not defined in config.");
            }

            $body = [
                'username' => API_USERNAME,
                'password' => API_PASSWORD
            ];

            $result = self::post('users/authenticate', $body, false);

            if ($result['ok']) {
                Cache::set('apiToken', $result['content']);
            } else {
                throw new Exception($result['httpCode'] . ': ' . $result['content']);
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public static function post($url, $body = '', $jsonDecode = true): array {
        return self::request($url, 'POST', $body, $jsonDecode);
    }

    public static function get($url, $body = '', $jsonDecode = true): array {
        return self::request($url, 'GET', $body, $jsonDecode);
    }

    public static function put($url, $body = '', $jsonDecode = true): array {
        return self::request($url, 'PUT', $body, $jsonDecode);
    }

    public static function del($url, $body = '', $jsonDecode = true): array {
        return self::request($url, 'DEL', $body, $jsonDecode);
    }
}