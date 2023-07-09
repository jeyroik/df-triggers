<?php
namespace deflou\interfaces\resolvers\operations;

use deflou\interfaces\instances\IHaveInstance;
use deflou\interfaces\resolvers\operations\results\IOperationResult;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface IResolvedOperation extends IItem, IHaveInstance, IHaveParams
{
    public const SUBJECT = 'deflou.resolved.operation';

    public function run(): IOperationResult;
}
