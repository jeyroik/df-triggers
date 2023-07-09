<?php
namespace deflou\interfaces\stages\resolvers\http;

use deflou\interfaces\applications\options\IOptions;

interface IStageRequestOptions
{
    public const NAME = 'deflou.resolver.http.request.options';

    public function __invoke(array &$requestOptions, IOptions $destinationOptions): void;
}
