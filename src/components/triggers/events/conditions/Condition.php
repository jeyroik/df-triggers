<?php
namespace deflou\components\triggers\events\conditions;

use deflou\interfaces\triggers\events\conditions\ICondition;
use extas\components\Item;

class Condition extends Item implements ICondition
{
    public function getPlugin(): string
    {
        return $this->config[static::FIELD__PLUGIN] ?? '';
    }

    public function getCondition(): string
    {
        return $this->config[static::FIELD__CONDITION] ?? '';
    }

    public function setPlugin(string $plugin): static
    {
        $this->config[static::FIELD__PLUGIN] = $plugin;

        return $this;
    }

    public function setCondition(string $condition): static
    {
        $this->config[static::FIELD__CONDITION] = $condition;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
