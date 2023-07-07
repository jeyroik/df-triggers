<?php
namespace deflou\components\extensions\instances;

use deflou\components\triggers\ETrigger;
use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\extensions\instances\IExtensionInstanceTriggers;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\ITrigger;
use extas\components\extensions\Extension;
use extas\interfaces\parameters\IParametred;

class ExtensionInstanceTriggers extends Extension implements IExtensionInstanceTriggers
{
    public function getActiveTriggers(ETrigger $et, IParametred $evOrOp, IInstance $instance = null): array
    {
        $triggerService = new TriggerService();

        return $triggerService->triggers()->all([
            $et->getInstIdField() => $instance->getId(),
            $et->value => $evOrOp->getName(),
            ITrigger::FIELD__STATE => ETriggerState::Active->value
        ]);
    }
}
