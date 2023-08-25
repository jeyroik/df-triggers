<?php
namespace deflou\interfaces\triggers\values;

use deflou\components\triggers\ETrigger;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\triggers\ITrigger;
use extas\interfaces\IItem;

interface IValueService extends IItem
{
    public const SUBJECT = 'deflou.trigger.value.service';
    public const ANY = '*';
    public const PARAM__APPLY_TO = 'apply_to';

    public function getPluginsTemplates(ETrigger $et, ITrigger $trigger, IContext $context): array;
    public function applyPlugins(IValueSense $sense, IResolvedEvent $event): static;
}
