<?php
namespace deflou\interfaces\triggers\events;

use deflou\interfaces\applications\IHaveApplicationName;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface ITriggerEventValuePlugin extends IItem, IHaveUUID, IHasName, IHasDescription, IHasClass, IHaveParams, IHaveApplicationName
{
    public const SUBJECT = 'deflou.trigger.event.value.plugin';

    public const FIELD__APPLY_TO = 'apply_to';

    public function getApplyTo(): array;
    public function setApplyTo(array $applyTo): static;
}
