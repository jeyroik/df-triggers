<?php
namespace deflou\interfaces\triggers\events\conditions;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

interface IConditionPlugin extends IItem, IHaveUUID, IHasClass, IHasName, IHasDescription
{
    public const SUBJECT = 'deflou.trigger.event.condition.plugin';

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool;
}
