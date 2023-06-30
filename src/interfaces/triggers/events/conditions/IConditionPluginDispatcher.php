<?php
namespace deflou\interfaces\triggers\events\conditions;

interface IConditionPluginDispatcher
{
    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool;
}
