<?php
namespace deflou\interfaces\triggers\events\conditions;

use deflou\interfaces\templates\IDispatcher;
use deflou\interfaces\templates\IWithTemplate;

interface IConditionPluginDispatcher extends IDispatcher
{
    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool;

    /**
     * @param  IConditionPlugin $plugin
     * @return IConditionDescription[]
     */
    public function getDescriptions(IWithTemplate $plugin): array;
}
