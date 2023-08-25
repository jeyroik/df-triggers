<?php
namespace deflou\interfaces\triggers\values\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\IDispatcher;

/**
 * Specific event/operation methods will be available by extensions.
 */
interface IValuePluginDispatcher extends IDispatcher
{
    public function __invoke(string|int $value, IResolvedEvent $event, IValuePlugin $plugin): string|int;
}
