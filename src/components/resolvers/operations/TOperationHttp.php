<?php
namespace deflou\components\resolvers\operations;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

trait TOperationHttp
{
    public function getHttpClient(): ClientInterface
    {
        return new Client();
    }

    public function get(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface
    {
        $options['query'] = $params;
        $options['headers'] = $headers;

        return $this->getHttpClient()->request('get', $url, $options);
    }

    public function post(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface
    {
        $options['form_params'] = $params;
        $options['headers'] = $headers;

        return $this->getHttpClient()->request('post', $url, $options);
    }

    public function put(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface
    {
        $options['form_params'] = $params;
        $options['headers'] = $headers;

        return $this->getHttpClient()->request('put', $url, $options);
    }

    public function delete(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface
    {
        $options['form_params'] = $params;
        $options['headers'] = $headers;

        return $this->getHttpClient()->request('delete', $url, $options);
    }

    public function json(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface
    {
        $options['json'] = $params;
        $options['headers'] = $headers;

        return $this->getHttpClient()->request('post', $url, $options);
    }
}
