<?php
namespace deflou\interfaces\resolvers\operations;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

interface IOperationHttp
{
    public function getHttpClient(): ClientInterface;

    public function get(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface;
    public function post(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface;
    public function put(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface;
    public function delete(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface;
    public function json(string $url, array $params = [], array $headers = [], array $options = []): ResponseInterface;
}
