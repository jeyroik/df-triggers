<?php
namespace deflou\interfaces\triggers\events\conditions;

use deflou\interfaces\triggers\IHavePlugin;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

interface IConditionDescription extends IItem, IHasName, IHasDescription, IHavePlugin
{
    public const SUBJECT = 'deflou.condition.description';
}
