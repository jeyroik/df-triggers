<?php
namespace deflou\components\triggers\events\plugins;

use deflou\interfaces\triggers\events\plugins\IValueDescription;
use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;

class ValueDescription extends Item implements IValueDescription
{
    use THasName;
    use THasDescription;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
