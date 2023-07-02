<?php
namespace deflou\components\triggers;

use deflou\interfaces\triggers\IHaveTrigger;
use deflou\interfaces\triggers\ITrigger;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggers()
 * @property array $config
 */
trait THasTrigger
{
    public function getTriggerId(): string
    {
        return $this->config[IHaveTrigger::FIELD__TRIGGER_ID] ?? '';
    }

    public function getTrigger(): ?ITrigger
    {
        return $this->triggers()->one([ITrigger::FIELD__ID => $this->getTriggerId()]);
    }

    public function setTriggerId(string $id): static
    {
        $this[IHaveTrigger::FIELD__TRIGGER_ID] = $id;

        return $this;
    }
}
