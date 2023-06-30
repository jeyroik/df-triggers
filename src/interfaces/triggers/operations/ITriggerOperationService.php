<?php
namespace deflou\interfaces\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use extas\interfaces\IItem;

interface ITriggerOperationService extends IItem
{
    public const SUBJECT = 'deflou.trigger.operation.service';

    public function buildPlugins(array $plugins): array;
    public function buildPlugin(string $name): ?ITriggerOperationPlugin;
    public function applyPlugins(ITriggerOperationValue &$value, IResolvedEvent $event): static;
}
