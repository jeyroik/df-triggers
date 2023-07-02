<?php
namespace deflou\interfaces\triggers\events\conditions;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

interface IConditionDescription extends IItem, IHasName, IHasDescription
{
    public const SUBJECT = 'deflou.condition.description';
}
