<?php

namespace Helper\Support;

class Service
{
    public function all($endpoint, $param)
    {
        return $this->request($endpoint, $param);
    }

    private function request($endpoint, $param = '', $postParam = '', $method = 'GET')
    {
        if (is_array($param)) {
            $endpoint = $endpoint . '?' . http_build_query($param);
        }
        $ch = curl_init();
        $options = [
            CURLOPT_SSL_VERIFYPEER => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $_ENV['API_KEY'] . ':',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 60 * 10,
            CURLOPT_URL => "https://sandbox-app.vindi.com.br/api/v1/$endpoint",
            CURLOPT_HTTPHEADER => ['content-type: application/json'],
        ];
        if ($method !== 'GET') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            $options[CURLOPT_POSTFIELDS] = $postParam;
        }
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if (!in_array($info['http_code'], range(200, 299))) {
            return false;
        }
        return json_decode($result, true);

    }

    public function update($endpoint, $id, $postParam)
    {
        $endpoint = "$endpoint/$id";
        $this->request($endpoint, '', $postParam, 'PUT');
    }

}
