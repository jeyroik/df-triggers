<?php
namespace deflou\components\triggers\operations\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\operations\plugins\IPluginDispatcher;
use extas\components\Replace;

class PluginEvent implements IPluginDispatcher
{
    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        return Replace::please()->apply(['event' => $event->getParamsValues()])->to($triggerValue);
    }
}
