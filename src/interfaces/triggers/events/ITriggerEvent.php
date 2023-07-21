<?php
namespace deflou\interfaces\triggers\events;

use deflou\interfaces\triggers\values\IValue;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;
use Generator;

interface ITriggerEvent extends IItem, IHaveParams, IHasName, IHasDescription
{
    public const SUBJECT = 'deflou.trigger.event';

    /**
     * @return Generator|IValue[]
     */
    public function eachParamValue(): Generator;
}
