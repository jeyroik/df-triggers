<?php
namespace deflou\components\triggers\events\conditions;

use deflou\interfaces\triggers\events\conditions\IConditionDescription;
use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;

class ConditionDescription extends Item implements IConditionDescription
{
    use THasName;
    use THasDescription;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
