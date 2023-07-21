<?php
namespace deflou\interfaces\triggers\values\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;

/**
 * Specific event/operation methods will be available by extensions.
 */
interface IValuePluginDispatcher
{
    public function __invoke(string|int $value, IResolvedEvent $event, IValuePlugin $plugin): string|int;
    public function getTemplateData(IInstance $instance, ITrigger $trigger, IValuePlugin $plugin): array;
}
