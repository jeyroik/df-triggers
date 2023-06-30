<?php
namespace deflou\components\triggers;

use deflou\interfaces\applications\vendors\IVendor;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\ITriggerService;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggers()
 */
class TriggerService extends Item implements ITriggerService
{
    public function createTriggerForInstance(IInstance $instance, string $vendorName): ITrigger
    {
        $trigger = new Trigger([
            Trigger::FIELD__EVENT_APPLICATION_ID => $instance->getApplicationId(),
            Trigger::FIELD__EVENT_APPLICATION_VERSION => $instance->getApplication()->getVersion(),
            Trigger::FIELD__EVENT_INSTANCE_ID => $instance->getId(),
            Trigger::FIELD__EVENT_INSTANCE_VERSION => $instance->getVersion(),
            Trigger::FIELD__CREATED_AT => time(),
            Trigger::FIELD__TITLE => '',
            Trigger::FIELD__DESCRIPTION => '',
            Trigger::FIELD__STATE => ETriggerState::OnConstruct->value,
            Trigger::FIELD__VENDOR => [
                IVendor::FIELD__NAME => $vendorName
            ]
        ]);

        return $this->triggers()->create($trigger);
    }

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
