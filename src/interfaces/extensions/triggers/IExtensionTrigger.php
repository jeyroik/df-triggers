<?php
namespace deflou\interfaces\extensions\triggers;

use deflou\components\triggers\ETriggerState;

interface IExtensionTrigger
{
    public function activate(): bool;
    public function suspend(): bool;
    public function delete(): bool;
    public function resume(): bool;
    public function toConstruct(): bool;

    public function stateIs(ETriggerState $state): bool;
    public function stateIsNot(ETriggerState $state): bool;
}
