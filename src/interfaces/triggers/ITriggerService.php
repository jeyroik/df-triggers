<?php
namespace deflou\interfaces\triggers;
use extas\interfaces\IItem;

interface ITriggerSevice extends IItem
{
    public const SUBJECT = 'df.trigger.service';

    public function isApplicableTrigger(array $data, ITrigger $trigger): bool;
}
