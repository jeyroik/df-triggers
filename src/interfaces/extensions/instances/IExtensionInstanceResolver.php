<?php
namespace deflou\interfaces\extensions\instances;

use deflou\interfaces\resolvers\IResolver;

interface IExtensionInstanceResolver
{
    public function buildResolver(string $eventName, array $params): IResolver;
}
