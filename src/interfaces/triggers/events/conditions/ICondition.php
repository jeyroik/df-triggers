<?php
namespace deflou\interfaces\triggers\events\conditions;

use extas\interfaces\IItem;

interface ICondition extends IItem
{
    public const SUBJECT = 'deflou.trigger.event.condition';

    public const FIELD__PLUGIN = 'plugin';
    public const FIELD__CONDITION = 'condition';

    public function getPlugin(): string;

    public function getCondition(): string;

    public function setPlugin(string $plugin): static;

    public function setCondition(string $condition): static;
}
