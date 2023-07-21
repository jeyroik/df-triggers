<?php
namespace deflou\interfaces\triggers\values;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\templates\ITemplateContext;
use extas\interfaces\IItem;

interface IValueService extends IItem
{
    public const SUBJECT = 'deflou.trigger.value.service';
    public const ANY = '*';
    public const PARAM__APPLY_TO = 'apply_to';

    public function getPluginsTemplates(IInstance $instance, ITrigger $trigger, ITemplateContext $context): array;
    public function applyPlugins(IValueSense $sense, IResolvedEvent $event): static;
}
