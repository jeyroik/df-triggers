<?php
namespace deflou\components\templates\contexts;
use deflou\interfaces\templates\contexts\IContextTrigger;
use deflou\interfaces\triggers\ITrigger;
use deflou\components\triggers\ETrigger;
use deflou\interfaces\applications\IApplication;

class ContextTrigger extends Context implements IContextTrigger
{
    protected ?IApplication $triggerApp = null;
    protected ?ITrigger $trigger = null;
    protected ?ETrigger $et = null;

    public function getApplicationNames(): array
    {
        $app = $this->getApplication();

        if (!$app) {
            return [];
        }

        return [$app->getName()];
    }

    public function getApplyTo(): array
    {
        $trigger = $this->getTrigger();

        if (!$trigger) {
            return [];
        }

        $for = $this->getFor();

        if (!$for) {
            return [];
        }

        $paramHolder = $for == ETrigger::Event
                        ? $trigger->buildEvent()
                        : $trigger->buildOperation();

        return array_keys($paramHolder->getParams());
    }

    protected function getTrigger(): ?ITrigger
    {
        if ($this->trigger) {
            return $this->trigger;
        }

        $params = $this->buildParams();

        /**
         * @var ITrigger $trigger
         */
        $this->trigger = $params->hasOne(static::PARAM__TRIGGER) 
                    ? $params->buildOne(static::PARAM__TRIGGER)->getValue() 
                    : null;
        
        return $this->trigger;
    }

    protected function getFor(): ?ETrigger
    {
        if ($this->et) {
            return $this->et;
        }

        $params = $this->buildParams();

        /**
         * @var ITrigger $trigger
         */
        $this->et = $params->hasOne(static::PARAM__FOR) 
                    ? $params->buildOne(static::PARAM__FOR)->getValue()
                    : null;
        
        return $this->et;
    }

    protected function getApplication(): ?IApplication
    {
        if ($this->triggerApp) {
            return $this->triggerApp;
        }

        $trigger = $this->getTrigger();
        
        if (!$trigger) {
            return null;
        }

        $for = $this->getFor();

        if (!$for) {
            return null;
        }

        $this->triggerApp = $trigger->getApplication($for);

        return $this->triggerApp;
    }
}
