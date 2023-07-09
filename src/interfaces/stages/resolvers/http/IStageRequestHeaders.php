<?php
namespace deflou\interfaces\stages\resolvers\http;

use deflou\interfaces\applications\options\IOptions;

interface IStageRequestHeaders
{
    public const NAME = 'deflou.resolver.http.request.headers';

    public function __invoke(array &$requestHeaders, IOptions $destinationOptions): void;
}
