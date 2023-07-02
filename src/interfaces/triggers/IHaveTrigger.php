<?php
namespace deflou\interfaces\triggers;

interface IHaveTrigger
{
    public const FIELD__TRIGGER_ID = 'trigger_id';

    public function getTriggerId(): string;
    public function getTrigger(): ?ITrigger;
    public function setTriggerId(string $id): static;
}
