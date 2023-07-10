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
use deflou\interfaces\triggers\operations\plugins\templates\ITemplateContext;
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

    public function getPluginsTemplates(IInstance $eventInstance, ITrigger $trigger, ITemplateContext $context): array
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
            
            $this->applyContextPlugins($data, $opPlugin, $template, $context);
            $this->applyPluginPlugins($data, $opPlugin, $template, $context);

            if (!$template) {
                continue;
            }

            $result[$opPlugin->getName()] = $template;
        }

        return $result;
    }

    protected function applyContextPlugins(array $templateData, ITriggerOperationPlugin $opPlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageTriggerOpTemplate::NAME . $context) as $plugin) {
            /**
             * @var IStageTriggerOpTemplate $plugin
             */
            $plugin($templateData, $opPlugin, $template, $context);
        }
    }

    protected function applyPluginPlugins(array $templateData, ITriggerOperationPlugin $opPlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageTriggerOpTemplate::NAME . $context . '.' . $opPlugin->getName()) as $plugin) {
            /**
             * @var IStageTriggerOpTemplate $plugin
             */
            $plugin($templateData, $opPlugin, $template, $context);
        }
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
