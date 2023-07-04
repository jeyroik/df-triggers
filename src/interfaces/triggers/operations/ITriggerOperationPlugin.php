<?php
namespace deflou\interfaces\triggers\operations;

use deflou\interfaces\applications\IHaveApplicationName;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface ITriggerOperationPlugin extends IItem, IHaveUUID, IHasName, IHasDescription, IHasClass, IHaveParams, IHaveApplicationName
{
    public const SUBJECT = 'deflou.trigger.operation.plugin';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int;

    public function getTemplateData(IInstance $eventInstance, ITrigger $trigger): array;
}
