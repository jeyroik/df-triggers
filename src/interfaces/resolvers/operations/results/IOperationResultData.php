<?php
namespace deflou\interfaces\resolvers\operations\results;

use extas\interfaces\collections\ICollection;
use extas\interfaces\IItem;

interface IOperationResultData extends IItem, ICollection
{
    public const SUBJECT = 'deflou.operation.result.data';
}
