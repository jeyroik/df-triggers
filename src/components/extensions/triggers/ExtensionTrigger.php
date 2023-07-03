<?php
namespace deflou\components\extensions\triggers;

use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\extensions\triggers\IExtensionTrigger;
use deflou\interfaces\triggers\ITrigger;
use extas\components\extensions\Extension;

class ExtensionTrigger extends Extension implements IExtensionTrigger
{
    public function toConstruct(ITrigger &$trigger = null): bool
    {
        $trigger->setState(ETriggerState::OnConstruct->value);
        
        return $this->updateTrigger($trigger);
    }

    public function activate(ITrigger &$trigger = null): bool
    {
        $trigger->setState(ETriggerState::Active->value);

        return $this->updateTrigger($trigger);
    }

    public function suspend(ITrigger &$trigger = null): bool
    {
        $trigger->setState(ETriggerState::Suspended->value);

        return $this->updateTrigger($trigger);
    }

    public function delete(ITrigger &$trigger = null) : bool 
    {
        $trigger->setState(ETriggerState::Deleted->value);

        return $this->updateTrigger($trigger);
    }

    public function resume(ITrigger &$trigger = null): bool
    {
        return $this->activate($trigger);
    }

    public function stateIs(ETriggerState $state, ITrigger &$trigger = null): bool
    {
        return $trigger->getState() == $state->value;
    }

    public function stateIsNot(ETriggerState $state, ITrigger &$trigger = null): bool
    {
        return !$this->stateIs($state, $trigger);
    }

    protected function updateTrigger(ITrigger $trigger): bool
    {
        $service = new TriggerService();
        $updated = $service->triggers()->update($trigger);

        return $updated > 0 ? true : false;
    }
}
