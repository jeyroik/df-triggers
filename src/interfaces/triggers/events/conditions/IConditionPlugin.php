<?php
namespace deflou\interfaces\triggers\events\conditions;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface IConditionPlugin extends IItem, IHaveUUID, IHasClass, IHasName, IHasDescription, IHaveParams
{
    public const SUBJECT = 'deflou.trigger.event.condition.plugin';

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool;

    /**
     * @return IConditionDescription[]
     */
    public function getConditionDescriptions(): array;
}
