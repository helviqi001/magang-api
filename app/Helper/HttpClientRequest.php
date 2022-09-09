<?php
namespace App\Helper;

use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

trait HttpClientRequest {
    private $headers = [
        'Accept-Encoding' => 'gzip, deflate, br'
    ];

    public function setHeaders($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $data) {
                $this->headers[$key] = $data;
            }
        }
        return $this;
    }

    public function get($url, $param = [], $isArray = false)
    {
        return $this->ClientRequest($url, $param, [], 'GET', $isArray);
    }

    public function post($url, $param = [], $isArray = false)
    {
        return $this->ClientRequest($url, $param, [], 'POST', $isArray);
    }

    public function put($url, $param = [], $isArray = false)
    {
        return $this->ClientRequest($url, $param, [], 'PUT', $isArray);
    }

    public function patch($url, $param = [], $isArray = false)
    {
        return $this->ClientRequest($url, $param, [], 'PATCH', $isArray);
    }

    public function delete($url, $param = [], $isArray = false)
    {
        return $this->ClientRequest($url, $param, [], 'DELETE', $isArray);
    }

    public function file($url, $multipart = [], $isArray = false)
    {
        return $this->ClientRequest($url, [], $multipart, 'POST', $isArray);
    }

    public function getAsync($url, $param = [])
    {
        return $this->ClientRequestAsync($url, $param, [], 'GET');
    }

    public function postAsync($url, $param = [])
    {
        return $this->ClientRequestAsync($url, $param, [], 'POST');
    }

    public function putAsync($url, $param = [])
    {
        return $this->ClientRequestAsync($url, $param, [], 'PUT');
    }

    public function patchAsync($url, $param = [])
    {
        return $this->ClientRequestAsync($url, $param, [], 'PATCH');
    }

    public function deleteAsync($url, $param = [])
    {
        return $this->ClientRequestAsync($url, $param, [], 'DELETE');
    }

    public function fileAsync($url, $multipart = [])
    {
        return $this->ClientRequestAsync($url, [], $multipart, 'POST');
    }

    /**
     * Client Request
     *
     * @param string $url
     * @param array $jsonParam
     * @param array $multipartParam
     * @param string $method
     * @param false $isArray
     * @return JsonResponse
     */
    protected function ClientRequest(string $url, array $jsonParam = [], array $multipartParam = [], string $method = 'GET', bool $isArray = false): JsonResponse
    {
        $client = new Client([
            'http_errors' => false,
            'base_uri' => $this->baseUri,
        ]);

        $options['headers'] = $this->headers;

        if (!empty($jsonParam)) {
            $options['json'] = $jsonParam;
        }

        if (!empty($multipartParam)) {
            $options['multipart'] = $multipartParam;
        }

        $res = $client->request($method, $url, $options);
        $response = json_decode($res->getBody()->getContents(), $isArray);
        return response()->json($response, $res->getStatusCode());
    }

    /**
     * Client Request Async
     *
     * @param string $url
     * @param array $jsonParam
     * @param array $multipartParam
     * @param string $method
     * @param false $isArray
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function ClientRequestAsync(string $url, array $jsonParam = [], array $multipartParam = [], string $method = 'GET'): \GuzzleHttp\Promise\PromiseInterface
    {
        $client = new Client([
            'http_errors' => false,
            'base_uri' => $this->baseUri,
        ]);
        $options = [
            'timeout' => env('CLIENT_REQUEST_TIMEOUT'),
        ];

        if (!empty($jsonParam)) {
            $options['headers'] = $this->headers;
            $options['json'] = $jsonParam;
        }

        if (!empty($multipartParam)) {
            $options['multipart'] = $multipartParam;
        }

        return $client->requestAsync($method, $url, $options);
    }
}
