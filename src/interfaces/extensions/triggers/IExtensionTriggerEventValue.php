<?php
namespace deflou\interfaces\extensions\triggers;

use deflou\components\triggers\events\conditions\EConditionEdge;

interface IExtensionTriggerEventValue
{
    public const PARAM__EDGE = 'edge';

    public function getCondition(): array;
    public function setCondition(array $condition): static;
    public function met(string|int $value): bool;
    public function getEdge(): string;
    public function buildEdge(): ?EConditionEdge;
}
