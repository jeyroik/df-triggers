<?php
namespace deflou\components\triggers\events\conditions;

use deflou\interfaces\extensions\triggers\IExtensionTriggerEventValue;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\conditions\ICondition;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\events\conditions\IConditionService;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\IValueSense;
use deflou\interfaces\triggers\values\plugins\templates\ITemplateContext;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerEventConditionPlugins()
 */
class ConditionService extends Item implements IConditionService
{
    protected static $plugins = [];

    /**
     * @param  IValueSense|IExtensionTriggerEventValue $value
     * @return ICondition
     */
    public function buildCondition(IValueSense|IExtensionTriggerEventValue $value): ICondition
    {
        return new Condition($value->getCondition());
    }

    public function buildPlugin(ICondition $condition): ?IConditionPlugin
    {
        $pluginName = $condition->getPlugin();

        if (!isset(self::$plugins[$pluginName])) {
            self::$plugins[$pluginName] = $this->triggerEventConditionPlugins()->one([IConditionPlugin::FIELD__NAME => $pluginName]);
        }

        return self::$plugins[$pluginName];
    }

    public function met(IValueSense $value, string|int $incomeEventValue): bool
    {
        $condition = $this->buildCondition($value);
        $plugin = $this->buildPlugin($condition);

        return $plugin($value->getValue(), $condition->getCondition(), $incomeEventValue);
    }

    /**
     * @return IConditionDescription[]
     */
    public function getDescriptions(): array
    {
        $conditionPlugins = $this->triggerEventConditionPlugins()->all([]);
        $descriptions = [];

        foreach ($conditionPlugins as $p) {
            $descriptions = array_merge($descriptions, $p->getConditionDescriptions());
        }

        return $descriptions;
    }

    public function getPluginsTemplates(IInstance $instance, ITrigger $trigger, ITemplateContext $context): array
    {
        $applyTo = [static::ANY];

        if ($context->buildParams()->hasOne(static::PARAM__APPLY_TO)) {
            $applyTo = array_merge($applyTo, $context->buildParams()->buildOne(static::PARAM__APPLY_TO)->getValue());
        }

        /**
         * @var IConditionPlugin[] $plugins
         */
        $plugins = $this->conditionPlugins()->all([
            IConditionPlugin::FIELD__APPLICATION_NAME => [$trigger->getApplication(ETrigger::Operation)->getName(), static::ANY],
            IConditionPlugin::FIELD__APPLY_TO_PARAM => $applyTo
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

    protected function applyContextPlugins(array $templateData, IConditionPlugin $conditionPlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageConditionTemplate::NAME . $context) as $plugin) {
            /**
             * @var IStageConditionTemplate $plugin
             */
            $plugin($templateData, $valuePlugin, $template, $context);
        }
    }

    protected function applyPluginPlugins(array $templateData, IConditionPlugin $conditionPlugin, mixed &$template, ITemplateContext $context): void
    {
        foreach ($this->getPluginsByStage(IStageConditionTemplate::NAME . $context . '.' . $conditionPlugin->getName()) as $plugin) {
            /**
             * @var IStageConditionTemplate $plugin
             */
            $plugin($templateData, $valuePlugin, $template, $context);
        }
    }


    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
