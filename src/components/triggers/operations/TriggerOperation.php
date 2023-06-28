<?php
namespace deflou\components\triggers\operations;

use deflou\interfaces\triggers\operations\ITriggerOperation;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasDescription;
use extas\components\THasName;

class TriggerOperation extends Item implements ITriggerOperation
{
    use THasName;
    use THasDescription;
    use THasParams;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
