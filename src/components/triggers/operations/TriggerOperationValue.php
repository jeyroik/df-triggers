<?php
namespace deflou\components\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\operations\ITriggerOperationValue;
use extas\components\Item;
use extas\components\THasValue;

/**
 * {
 *  "plugins": [<plugin1.name>, <plugin2.name>, ...],
 *  "value": "..."
 * }
 */
class TriggerOperationValue extends Item implements ITriggerOperationValue
{
    use THasValue;

    public function getPlugins(): array
    {
        return $this->config[static::FIELD__PLUGINS] ?? [];
    }

    public function setPlugins(array $plugins): static
    {
        $this[static::FIELD__PLUGINS] = $plugins;

        return $this;
    }

    public function applyPlugins(IResolvedEvent $event): static
    {
        $service = new TriggerOperationService();
        $service->applyPlugins($this, $event);

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
