<?php
namespace deflou\components\triggers;

use deflou\components\exceptions\triggers\TriggerEmptyEventData;
use deflou\components\exceptions\triggers\TriggerIncorrectState;
use deflou\interfaces\applications\vendors\IVendor;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\ITriggerService;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\Item;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParametred;
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
            ITrigger::FIELD__EVENT . '.'. ITriggerEvent::FIELD__NAME => $eventName,
            ITrigger::FIELD__VENDOR . '.' . IVendor::FIELD__NAME => $vendorNames,
            ITrigger::FIELD__STATE => ETriggerState::Active->value
        ]);
    }

    public function isApplicableTrigger(IResolvedEvent $event, ITrigger $trigger): bool
    {
        $triggerEvent = $trigger->buildEvent();
        $resolvedEventParams = $event->getParamsValues();
        
        foreach ($triggerEvent->eachParamValue() as $name => $triggerEventValue) {

            $incomeEventValue = $resolvedEventParams[$name] ?? null;

            if (!$triggerEventValue->met($incomeEventValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Insert event data into trigger.
     *
     * @param  string   $triggerId
     * @param  array    $eventData [name => '...', 'params' => ['<par1.name>' => ['value' => '<par1.value>']]]
     * @return ITrigger with inserted and detailed event
     */
    public function insertEvent(string $triggerId, array $eventData): ITrigger
    {
        /**
         * @var ITrigger $trigger
         */
        $trigger = $this->triggers()->one([ITrigger::FIELD__ID => $triggerId]);

        $this->validateDataForEventInsert($trigger, $eventData);

        $params = $eventData[ITriggerEvent::FIELD__PARAMS] ?? [];

        $eventInstance = $trigger->getInstance(ETrigger::Event);
        $eventDesc = $eventInstance->buildEvents()->buildOne($eventData[ITriggerEvent::FIELD__NAME]);

        $eventData[ITriggerEvent::FIELD__TITLE] = $eventDesc->getTitle();
        $eventData[ITriggerEvent::FIELD__DESCRIPTION] = $eventDesc->getDescription();
        $eventData[ITriggerEvent::FIELD__PARAMS] = $this->insertEventParams($eventDesc, $params);

        $trigger->setEvent($eventData);
        $this->triggers()->update($trigger);

        return $trigger;
    }

    public function insertOperationInstance(ITrigger &$trigger, IInstance $instance): bool
    {
        $app = $instance->getApplication();
        $trigger->setInstanceId(ETrigger::Operation, $instance->getId())
                ->setInstanceVersion(ETrigger::Operation, $instance->getVersion())
                ->setApplicationId(ETrigger::Operation, $app->getId())
                ->setApplicationVersion(ETrigger::Operation, $app->getVersion())
        ;
        
        return $this->triggers()->update($trigger) > 0;
    }

    protected function validateDataForEventInsert(?ITrigger $trigger, array $eventData): void
    {
        if (!$trigger) {
            throw new MissedOrUnknown('trigger');
        }

        if ($trigger->getState() != ETriggerState::OnConstruct->value) {
            throw new TriggerIncorrectState(ETriggerState::OnConstruct->value . TriggerIncorrectState::DELIMITER . $trigger->getState());
        }

        if (empty($eventData)) {
            throw new TriggerEmptyEventData();
        }
    }

    protected function insertEventParams(IParametred $eventDesc, array $params): array
    {
        $descParams = $eventDesc->buildParams()->buildAll();

        foreach ($descParams as $paramName => $param) {
            if (isset($params[$paramName])) {
                $params[$paramName][IParam::FIELD__NAME] = $paramName;
                $params[$paramName][IParam::FIELD__TITLE] = $param->getTitle();
                $params[$paramName][IParam::FIELD__DESCRIPTION] = $param->getDescription();
            }
        }

        return $params;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
