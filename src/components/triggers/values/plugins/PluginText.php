<?php
namespace deflou\components\triggers\values\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\IValuePluginDispatcher;

class PluginText implements IValuePluginDispatcher
{
    public const NAME = 'text';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event, IValuePlugin $plugin): string|int
    {
        return $triggerValue;
    }

    public function getTemplateData(IInstance $instance, ITrigger $trigger, IValuePlugin $plugin): array
    {
        return [];
    }
}
