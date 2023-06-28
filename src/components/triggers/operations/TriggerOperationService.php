<?php
namespace deflou\components\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use deflou\interfaces\triggers\operations\ITriggerOperationService;
use deflou\interfaces\triggers\operations\ITriggerOperationValue;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerOperationPlugins()
 */
class TriggerOperationService extends Item implements ITriggerOperationService
{
    protected static $plugins = [];

    public function buildPlugin(string $name): ?ITriggerOperationPlugin
    {
        if (!isset(self::$plugins[$name])) {
            self::$plugins[$name] = $this->triggerOperationPlugins()->one([ITriggerOperationPlugin::FIELD__NAME => $name]);
        }

        return self::$plugins[$name];
    }

    public function buildPlugins(array $plugins): array
    {
        $builtPlugins = [];

        foreach ($plugins as $name) {
            $builtPlugins[$name] = $this->buildPlugin($name);
        }

        return $builtPlugins;
    }

    public function applyPlugins(ITriggerOperationValue &$value, IResolvedEvent $event): static
    {
        $builtPlugins = $this->buildPlugins($value->getPlugins());
        $triggerValue = $value->getValue();

        foreach ($builtPlugins as $plugin) {
            $triggerValue = $plugin($triggerValue, $event);
        }

        $value->setValue($value);

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
