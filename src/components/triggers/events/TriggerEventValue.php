<?php
namespace deflou\components\triggers\events;

use deflou\components\triggers\events\conditions\ConditionService;
use deflou\interfaces\triggers\events\ITriggerEventValue;
use extas\components\Item;
use extas\components\THasValue;

class TriggerEventValue extends Item implements ITriggerEventValue
{
    use THasValue;

    public function getCondition(): array
    {
        return $this->config[static::FIELD__CONDITION] ?? '';
    }

    public function setCondition(array $condition): static
    {
        $this->config[static::FIELD__CONDITION] = $condition;

        return $this;
    }

    public function met(string|int $value): bool
    {
        $condService = new ConditionService();

        return $condService->met($this, $value);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
