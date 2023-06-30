<?php
namespace deflou\interfaces\triggers\operations\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;

interface IPluginDispatcher
{
    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int;
}
