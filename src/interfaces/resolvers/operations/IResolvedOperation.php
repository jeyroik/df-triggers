<?php
namespace deflou\interfaces\resolvers\operations;

use deflou\interfaces\resolvers\operations\results\IOperationResult;
use extas\interfaces\IItem;

interface IResolvedOperation extends IItem
{
    public const SUBJECT = 'deflou.resolved.operation';

    public function run(): IOperationResult;
}
