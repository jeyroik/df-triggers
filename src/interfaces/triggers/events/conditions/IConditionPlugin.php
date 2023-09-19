<?php
namespace deflou\interfaces\triggers\events\conditions;

use deflou\interfaces\templates\IWithTemplate;

interface IConditionPlugin extends IWithTemplate
{
    public const SUBJECT = 'deflou.trigger.event.condition.plugin';

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool;

    /**
     * @return IConditionDescription[]
     */
    public function getConditionDescriptions(): array;
}
