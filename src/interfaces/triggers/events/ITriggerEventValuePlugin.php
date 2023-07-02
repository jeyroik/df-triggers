<?php
namespace deflou\interfaces\triggers\events;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface ITriggerEventValuePlugin extends IItem, IHaveUUID, IHasName, IHasDescription, IHasClass, IHaveParams
{
    public const SUBJECT = 'deflou.trigger.event.value.plugin';

    public const FIELD__APPLY_TO = 'apply_to';
    public const FIELD__APPLICATION_NAME = 'app_name';

    public function getApplicationName(): string;
    public function setApplicationName(string $name): static;

    public function getApplyTo(): array;
    public function setApplyTo(array $applyTo): static;
}
