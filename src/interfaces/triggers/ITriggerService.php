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

    /**
     * Insert event data into a trigger.
     *
     * @param  string   $triggerId
     * @param  array    $eventData [name => '...', 'params' => ['<par1.name>' => ['value' => '<par1.value>']]]
     * @return ITrigger with inserted and detailed event
     */
    public function insertEvent(string $triggerId, array $eventData): ITrigger;

    /**
     * Insert operation data into a trigger
     *
     * @param string $triggerId
     * @param array $opData [name => '', 'params' => [
     *                                      '<p1.name>'=> [
     *                                          'value' => [
     *                                              'plugins' => ['<plug1.name>', ...], 
     *                                              'value' => ''
     *                       ]]]]
     * @return ITrigger with inserted and detailed event
     */
    public function insertOperation(string $triggerId, array $opData): ITrigger;

    public function insertOperationInstance(ITrigger &$trigger, IInstance $instance): bool;
}
