<?php
namespace deflou\interfaces\triggers\operations;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;
use Generator;

interface ITriggerOperation extends IItem, IHasName, IHasDescription, IHaveParams
{
    public const SUBJECT = 'deflou.trigger.operation';

    /**
     * @return Generator|ITriggerOperationValue[]
     */
    public function eachParamValue(): Generator;
}
