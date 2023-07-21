<?php
namespace deflou\interfaces\triggers\values;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use extas\interfaces\IHasValue;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface IValueSense extends IItem, IHasValue, IHaveParams
{
    public const SUBJECT = 'deflou.trigger.value.sense';

    public const FIELD__PLUGINS_NAMES = 'plugins_names';

    public function getPluginsNames(): array;
    public function setPluginsNames(array $names): static;
    public function addPluginsNames(...$names): static;

    public function applyPlugins(IResolvedEvent $event): static;
}
