<?php
namespace deflou\components\triggers\values;

use deflou\components\triggers\ETrigger;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\values\IValueSense;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\stages\triggers\IStageTriggerTemplate;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\IValueService;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\templates\ITemplateContext;

use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerValuePlugins()
 */
class ValueService extends Item implements IValueService
{
    protected static $plugins = [];

    public function applyPlugins(IValueSense $sense, IResolvedEvent $event): static
    {
        $builtPlugins = $this->buildPlugins($sense->getPluginsNames());
        $value = $sense->getValue();

        foreach ($builtPlugins as $plugin) {
            $value = $plugin($value, $event);
        }

        $sense->setValue($value);

        return $this;
    }

    public function getPluginsTemplates(IInstance $instance, ITrigger $trigger, ITemplateContext $context): array
    {
        $applyTo = [static::ANY];

        if ($context->buildParams()->hasOne(static::PARAM__APPLY_TO)) {
            $applyTo = array_merge($applyTo, $context->buildParams()->buildOne(static::PARAM__APPLY_TO)->getValue());
        }

        /**
         * @var IValuePlugin[] $plugins
         */
        $plugins = $this->triggerValuePlugins()->all([
            IValuePlugin::FIELD__APPLICATION_NAME => [$trigger->getApplication(ETrigger::Operation)->getName(), static::ANY],
            IValuePlugin::FIELD__APPLY_TO_PARAM => $applyTo
        ]);

        $result = [];

        foreach ($plugins as $plugin) {
            $data = $plugin->getTemplateData($instance, $trigger);
            $template = null;
            
            $this->applyContextPlugins($data, $plugin, $template, $context);
            $this->applyPluginPlugins($data, $plugin, $template, $context);

            if (!$template) {
                continue;
            }

            $result[$plugin->getName()] = $template;
        }

        return $result;
    }

    protected function applyContextPlugins(array $templateData, IValuePlugin $valuePlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageTriggerTemplate::NAME . $context) as $plugin) {
            /**
             * @var IStageTriggerTemplate $plugin
             */
            $plugin($templateData, $valuePlugin, $template, $context);
        }
    }

    protected function applyPluginPlugins(array $templateData, IValuePlugin $valuePlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageTriggerTemplate::NAME . $context . '.' . $valuePlugin->getName()) as $plugin) {
            /**
             * @var IStageTriggerTemplate $plugin
             */
            $plugin($templateData, $valuePlugin, $template, $context);
        }
    }

    protected function buildPlugin(string $name): ?IValuePlugin
    {
        if (!isset(self::$plugins[$name])) {
            self::$plugins[$name] = $this->triggerValuePlugins()->one([IValuePlugin::FIELD__NAME => $name]);
        }

        return self::$plugins[$name];
    }

    protected function buildPlugins(array $plugins): array
    {
        $builtPlugins = [];

        foreach ($plugins as $name) {
            $builtPlugins[$name] = $this->buildPlugin($name);
        }

        return $builtPlugins;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
