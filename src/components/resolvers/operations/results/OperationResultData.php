<?php
namespace deflou\components\resolvers\operations\results;

use deflou\interfaces\resolvers\operations\results\IOperationResultData;
use extas\components\collections\TCollection;
use extas\components\Item;

class OperationResultData extends Item implements IOperationResultData
{
    use TCollection;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
