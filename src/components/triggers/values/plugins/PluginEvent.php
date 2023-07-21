<?php
namespace deflou\components\triggers\values\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\IValuePluginDispatcher;
use extas\components\Replace;

class PluginEvent implements IValuePluginDispatcher
{
    public const NAME = 'event';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event, IValuePlugin $plugin): string|int
    {
        return Replace::please()->apply(['event' => $event->getParamsValues()])->to($triggerValue);
    }

    public function getTemplateData(IInstance $instance, ITrigger $trigger, IValuePlugin $plugin): array
    {
        return $instance->buildEvents()->buildOne($trigger->buildEvent()->getName())->buildParams()->buildAll();
    }
}
