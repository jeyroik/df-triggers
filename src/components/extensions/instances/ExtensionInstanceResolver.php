<?php
namespace deflou\components\extensions\instances;

use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\IResolver;
use extas\components\extensions\Extension;

class ExtensionInstanceResolver extends Extension implements IExtensionInstanceResolver
{
    public function buildResolver(string $eventName, array $params, IInstance $instance = null): IResolver
    {
        $resolverClass = $instance->getResolver();

        return new $resolverClass([
            IResolver::FIELD__APPLICATION_ID => $instance->getApplicationId(),
            IResolver::FIELD__INSTANCE_ID => $instance->getId(),
            IResolver::FIELD__EVENT_NAME => $eventName,
            IResolver::FIELD__PARAMS => $params
        ]);
    }
}
