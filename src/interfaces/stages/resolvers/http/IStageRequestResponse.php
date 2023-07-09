<?php
namespace deflou\interfaces\stages\resolvers\http;

use Psr\Http\Message\ResponseInterface;

/**
 * Parse response and return structured data as array by $data argument.
 */
interface IStageRequestResponse
{
    public const NAME = 'deflou.resolver.http.request.response.';

    public function __invoke(ResponseInterface $response, array &$data): void;
}
