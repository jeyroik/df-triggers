<?php
namespace deflou\interfaces\triggers\events\conditions;

use deflou\interfaces\triggers\events\ITriggerEventValue;
use extas\interfaces\IItem;

interface IConditionService extends IItem
{
    public const SUBJECT = 'deflou.trigger.event.condition.service';

    public function buildCondition(ITriggerEventValue $value): ICondition;
    public function buildPlugin(ICondition $condition): ?IConditionPlugin;
    public function met(ITriggerEventValue $value, string|int $incomeEventValue): bool;
}
