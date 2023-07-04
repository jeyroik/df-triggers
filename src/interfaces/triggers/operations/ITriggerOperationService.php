<?php
namespace deflou\interfaces\triggers\operations;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\plugins\templates\ITemplateContext;
use extas\interfaces\IItem;

interface ITriggerOperationService extends IItem
{
    public const SUBJECT = 'deflou.trigger.operation.service';

    public const ANY = '*';

    public function buildPlugins(array $plugins): array;
    public function buildPlugin(string $name): ?ITriggerOperationPlugin;
    public function applyPlugins(ITriggerOperationValue &$value, IResolvedEvent $event): static;
    public function getPluginsTemplates(IInstance $eventInstance, ITrigger $trigger, ITemplateContext $context): array;
}
