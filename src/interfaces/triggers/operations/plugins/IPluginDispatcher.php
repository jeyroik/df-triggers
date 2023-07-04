<?php
namespace deflou\interfaces\triggers\operations\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;

interface IPluginDispatcher
{
    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int;

    public function getTemplateData(IInstance $eventInstance, ITrigger $trigger, ITriggerOperationPlugin $plugin): array;
}
