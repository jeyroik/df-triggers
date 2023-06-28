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
    public function getActiveTriggers(string $instanceId, string $eventName, array $vendorNames): array
    {
        return $this->triggers()->all([
            ITrigger::FIELD__EVENT_INSTANCE_ID => $instanceId,
            ITrigger::FIELD__EVENT => $eventName,
            ITrigger::FIELD__VENDOR . '.' . IVendor::FIELD__NAME => $vendorNames,
            ITrigger::FIELD__STATE => ETriggerState::Active->value
        ]);
    }

    public function isApplicableTrigger(IResolvedEvent $event, ITrigger $trigger): bool
    {
        $triggerEvent = $trigger->buildEvent();
        
        foreach ($triggerEvent->eachParamValue() as $name => $triggerEventValue) {

            $incomeEventValue = $event[$name] ?? null;

            if (!$triggerEventValue->met($incomeEventValue)) {
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
