<?php
namespace deflou\components\triggers\operations\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use deflou\interfaces\triggers\operations\plugins\IPluginDispatcher;
use extas\components\Replace;

class PluginEvent implements IPluginDispatcher
{
    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        return Replace::please()->apply(['event' => $event->getParamsValues()])->to($triggerValue);
    }

    public function getTemplateData(IInstance $eventInstance, ITrigger $trigger, ITriggerOperationPlugin $plugin): array
    {
        return $eventInstance->buildEvents()->buildOne($trigger->buildEvent()->getName())->buildParams()->buildAll();
    }
}
