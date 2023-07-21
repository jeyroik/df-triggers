<?php
namespace deflou\interfaces\triggers\values;

use extas\interfaces\IItem;
use Generator;

interface IValue extends IItem
{
    public const SUBJECT = 'deflou.trigger.value';

    /**
     * Get each value sense
     *
     * @return Generator|IValueSense[]
     */
    public function eachSense(): Generator;
}
