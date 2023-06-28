<?php
namespace deflou\interfaces\triggers;

use extas\interfaces\IItem;
use deflou\interfaces\resolvers\events\IResolvedEvent;

interface ITriggerSevice extends IItem
{
    public const SUBJECT = 'df.trigger.service';

    public function getActiveTriggers(string $instanceId, string $eventName, array $vendorNames): array;
    public function isApplicableTrigger(IResolvedEvent $event, ITrigger $trigger): bool;
}
