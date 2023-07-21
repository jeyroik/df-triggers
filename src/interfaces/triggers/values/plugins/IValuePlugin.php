<?php
namespace deflou\interfaces\triggers\values\plugins;

use deflou\interfaces\applications\IHaveApplicationName;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface IValuePlugin extends IItem, IHaveUUID, IHasName, IHasDescription, IHasClass, IHaveParams, IHaveApplicationName
{
    public const SUBJECT = 'deflou.trigger.value.plugin';

    public const FIELD__APPLY_TO_PARAM = 'apply_to_params';

    public function __invoke(string|int $value, IResolvedEvent $event): string|int;

    public function getApplyToParams(): array;
    public function setApplyToParams(array $applyTo): static;
    public function getTemplateData(IInstance $instance, ITrigger $trigger): array;
}
