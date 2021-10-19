<?php
namespace OSS\SDK\Libs;

class Client
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    protected $timeout = 0;
    protected $connecttimeout = 0;
    protected $apiGateWay;
    protected $appkey;
    protected $apptoken;

    public function __construct()
    {
        $this->apiGateWay = config('oss.api_url');
        $this->appkey = config('oss.app_key');
        $this->apptoken = config('oss.app_token');
    }

    public function request($method, $url, $params = [], $reqHeaders = [], $buildQuery = true)
    {
        $url = rtrim($this->apiGateWay, '/') . '/' . ltrim($url, '/');
        $ch = curl_init();
        switch ($method) {
            case self::METHOD_GET:
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
            case self::METHOD_POST:
            case self::METHOD_PUT:
            case self::METHOD_DELETE:
            if ($method == self::METHOD_POST) {
                curl_setopt($ch, CURLOPT_POST, true);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
            if ($buildQuery) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($params)));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
            break;
            default:
            curl_close($ch);
            return;
        }
        $headers = array_merge([
            'request-time: ' . time(),
            'appkey: ' . $this->appkey,
            'apptoken: ' . $this->apptoken,
        ], $reqHeaders);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->connecttimeout,
            CURLOPT_URL => $url,
        ]);
        $response = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        curl_close($ch);
        if ($httpInfo['http_code'] >= 400) {
            throw new \Exception($response);
        }
        $httpContentType = $httpInfo['content_type'];
        if ($httpContentType == 'application/json') {
            if ($data = json_decode($response, true)) {
                if (isset($data['code'])) {
                  if ($data['code'] === -1 && $data['message']) {
                    throw new \Exception($data['message']);
                  } else {
                    return $data['result'] ?? $data;
                  }
                }
                return $data;
            }
        }

        return $response;
    }

    public function get($url, $params = [], $headers = [])
    {
        return $this->request(self::METHOD_GET, $url . ($params ? ('?' . http_build_query($params)) : ''), [], $headers);
    }

    public function post($url, $params = [], $headers = [], $buildQuery = true)
    {
        return $this->request(self::METHOD_POST, $url, $params, $headers, $buildQuery);
    }

    public function put($url, $params = [], $headers = [], $buildQuery = true)
    {
        return $this->request(self::METHOD_PUT, $url, $params, $headers, $buildQuery);
    }

    public function del($url, $params = [], $headers = [], $buildQuery = true)
    {
        return $this->request(self::METHOD_DELETE, $url, $params, $headers, $buildQuery);
    }
}
