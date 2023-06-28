<?php
namespace deflou\components\triggers\events\conditions\plugins;

use deflou\interfaces\triggers\events\conditions\IConditionPluginDispatcher;
use extas\components\conditions\ConditionParameter;

class ConditionBasic implements IConditionPluginDispatcher
{
    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool
    {
        $tmp = new ConditionParameter([
            ConditionParameter::FIELD__VALUE => $triggerValue,
            ConditionParameter::FIELD__CONDITION => $condition
        ]);

        return $tmp->isConditionMet($eventValue);
    }
}
