<?php
namespace deflou\components\resolvers\operations;

use deflou\components\instances\THasInstance;
use deflou\interfaces\resolvers\operations\IResolvedOperation;
use extas\components\Item;
use extas\components\parameters\THasParams;

abstract class ResolvedOperation extends Item implements IResolvedOperation
{
    use THasParams;
    use THasInstance;
}
