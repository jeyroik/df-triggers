<?php
namespace deflou\interfaces\triggers\events\conditions;

use deflou\interfaces\triggers\events\ITriggerEventValue;
use deflou\interfaces\triggers\values\IValueSense;
use extas\interfaces\IItem;

interface IConditionService extends IItem
{
    public const SUBJECT = 'deflou.trigger.event.condition.service';

    public function buildCondition(IValueSense $value): ICondition;
    public function buildPlugin(ICondition $condition): ?IConditionPlugin;
    public function met(IValueSense $value, string|int $incomeEventValue): bool;
    /**
     * @return IConditionDescription[]
     */
    public function getDescriptions(): array;
}
