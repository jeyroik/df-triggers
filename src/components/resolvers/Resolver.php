<?php
namespace deflou\components\resolvers;

use deflou\components\applications\THasApplication;
use deflou\components\instances\THasInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\IResolver;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use extas\components\Item;
use extas\components\parameters\Param;
use extas\components\parameters\Params;
use extas\components\parameters\THasParams;
use extas\interfaces\parameters\IParams;

abstract class Resolver extends Item implements IResolver
{
    use THasInstance;
    use THasApplication;
    use THasParams;

    public function getEventName(): string
    {
        return $this->config[static::FIELD__EVENT_NAME] ?? '';
    }

    protected function compileOperationParams(ITriggerOperation $operation, IResolvedEvent $resolvedEvent): array
    {
        $requestParams = [];

        foreach ($operation->eachParamValue() as $name => $triggerOperationValue) {
            foreach ($triggerOperationValue->eachSense() as $sense) {
                $sense->applyPlugins($resolvedEvent);
                $requestParams[$name] = $sense->getValue();
            }
        }

        return $requestParams;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
