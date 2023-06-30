<?php
namespace deflou\interfaces\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

interface ITriggerOperationPlugin extends IItem, IHaveUUID, IHasName, IHasDescription, IHasClass
{
    public const SUBJECT = 'deflou.trigger.operation.plugin';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int;
}
