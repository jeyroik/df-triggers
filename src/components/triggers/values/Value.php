<?php
namespace deflou\components\triggers\values;

use deflou\interfaces\triggers\values\IValue;
use extas\components\Item;
use Generator;

class Value extends Item implements IValue
{
    public function eachSense(): Generator
    {
        foreach ($this->config as $sense) {
            yield new ValueSense($sense);
        }
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
