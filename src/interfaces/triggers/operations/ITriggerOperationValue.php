<?php
namespace deflou\interfaces\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use extas\interfaces\IHasValue;
use extas\interfaces\IItem;

interface ITriggerOperationValue extends IItem, IHasValue
{
    public const SUBJECT = 'deflou.trigger.operation.value';

    public const FIELD__PLUGINS = 'plugins';

    public function getPlugins(): array;
    public function setPlugins(array $plugins): static;
    public function applyPlugins(IResolvedEvent $event): static;
}
