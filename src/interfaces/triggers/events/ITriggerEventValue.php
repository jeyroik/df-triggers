<?php
namespace deflou\interfaces\triggers\events;

use extas\interfaces\IHasValue;
use extas\interfaces\IItem;

interface ITriggerEventValue extends IItem, IHasValue
{
    public const SUBJECT = 'deflou.trigger.event.value';

    public const FIELD__CONDITION = 'condition';
    
    public function getCondition(): array;
    public function setCondition(array $condition): static;
    public function met(string|int $value): bool;
}
