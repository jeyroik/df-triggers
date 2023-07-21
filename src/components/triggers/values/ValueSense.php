<?php
namespace deflou\components\triggers\values;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\values\IValueSense;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasValue;

class ValueSense extends Item implements IValueSense
{
    use THasValue;
    use THasParams;

    public function getPluginsNames(): array
    {
        return $this[static::FIELD__PLUGINS_NAMES] ?? [];
    }

    public function setPluginsNames(array $names): static
    {
        $this[static::FIELD__PLUGINS_NAMES] = $names;

        return $this;
    }

    public function addPluginsNames(...$names): static
    {
        $pluginNames = $this->getPluginsNames();

        foreach ($names as $name) {
            if (!in_array($name, $pluginNames)) {
                $pluginNames[] = $name;
            }
        }

        $this->setPluginsNames($pluginNames);

        return $this;
    }

    public function applyPlugins(IResolvedEvent $event): static
    {
        $service = new ValueService();
        $service->applyPlugins($this, $event);

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
