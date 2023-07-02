<?php
namespace deflou\interfaces\triggers\events\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\ITriggerEventValuePlugin;
use extas\interfaces\IItem;

interface IValuePluginDispatcher extends IItem
{
    public function __invoke(IInstance $instance, string $paramName, ITriggerEventValuePlugin $plugin): array;
}
