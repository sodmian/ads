<?php

namespace Controllers;

class Request
{
    static function send(string $action, $method = 'GET', $data = [])
    {
        try {
            $url = $_SERVER['SERVER_NAME'] . $action;

            $curl = curl_init();

            switch ($method) {
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    if ($data)
                        $url = sprintf("%s?%s", $url, http_build_query($data));
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_ENV['API_KEY_PUBLIC']
            ]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            $result = curl_exec($curl);

            if (!$result) {
                throw new \RuntimeException("Ошибка соединения");
            }

            curl_close($curl);

            return $result;
        } catch (\RuntimeException $e) {
            die($e->getMessage());
        }
    }
}