<?php
namespace deflou\components\triggers;

use deflou\interfaces\applications\vendors\IVendor;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\ITriggerSevice;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggers()
 */
class TriggerService extends Item implements ITriggerSevice
{
    public function getTriggers(string $instanceId, string $eventName, array $vendorNames): array
    {
        return $this->triggers()->all([
            ITrigger::FIELD__EVENT_INSTANCE_ID => $instanceId,
            ITrigger::FIELD__EVENT => $eventName,
            ITrigger::FIELD__VENDOR . '.' . IVendor::FIELD__NAME => $vendorNames
        ]);
    }

    public function isApplicableTrigger(IResolvedEvent $event, ITrigger $trigger): bool
    {
        $triggerParams = $trigger->buildEvent()->buildParams()->buildItems();
        
        foreach ($triggerParams as $tParam) {
            if (!isset($event[$tParam->getName()])) {
                return false;
            }

            // if (!$conditionService->areConditionsMet($tParam->getValue(), $event)) { return false; }
            if (!$tParam->buildValue()->isApplicable($data[$tParam->getName()])) {
                return false;
            }
        }

        return true;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
