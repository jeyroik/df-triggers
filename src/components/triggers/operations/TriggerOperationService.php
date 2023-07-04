<?php
namespace deflou\components\triggers\operations;

use deflou\components\triggers\ETrigger;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\stages\triggers\IStageTriggerOpTemplate;
use deflou\interfaces\triggers\ITrigger;
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

        $value->setValue($triggerValue);

        return $this;
    }

    public function getPluginsTemplates(IInstance $eventInstance, ITrigger $trigger, string $context): array
    {
        /**
         * @var ITriggerOperationPlugin[] $plugins
         */
        $plugins = $this->triggerOperationPlugins()->all([
            ITriggerOperationPlugin::FIELD__APPLICATION_NAME => [$trigger->getApplication(ETrigger::Operation)->getName(), static::ANY]
        ]);

        $result = [];

        foreach ($plugins as $opPlugin) {
            $data = $opPlugin->getTemplateData($eventInstance, $trigger);
            $template = null;
            foreach ($this->getPluginsByStage(IStageTriggerOpTemplate::NAME . $context) as $plugin) {
                /**
                 * @var IStageTriggerOpTemplate $plugin
                 */
                $plugin($data, $opPlugin, $template);
            }
            $result[$opPlugin->getName()] = $template;
        }

        return $result;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
