<?php
namespace deflou\interfaces\triggers;

use deflou\interfaces\extensions\triggers\IExtensionTrigger;
use deflou\interfaces\instances\IInstance;
use extas\interfaces\IItem;
use deflou\interfaces\resolvers\events\IResolvedEvent;

interface ITriggerService extends IItem
{
    public const SUBJECT = 'df.trigger.service';

    /**
     * Get active triggers
     * 
     * @param string $instanceId
     * @param string $eventName
     * @param array $vendorNames
     * @return ITrigger[]
     */
    public function getActiveTriggers(string $instanceId, string $eventName, array $vendorNames): array;
    public function isApplicableTrigger(IResolvedEvent $event, ITrigger $trigger): bool;

    /**
     * @param  IInstance $instance
     * @param  string    $vendorName
     * @return ITrigger|IExtensionTrigger
     */
    public function createTriggerForInstance(IInstance $instance, string $vendorName): ITrigger;
}
