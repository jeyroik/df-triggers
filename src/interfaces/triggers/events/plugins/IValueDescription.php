<?php
namespace deflou\interfaces\triggers\events\plugins;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

interface IValueDescription extends IItem, IHasName, IHasDescription
{
    public const SUBJECT = 'deflou.trigger.event.value.description';
}
