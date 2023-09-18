<?php
namespace deflou\components\triggers\events\conditions;

use deflou\components\templates\TemplateService;
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

    public function getPluginsTemplates(ITemplateContext $context): array
    {
        return (new TemplateService())->getTemplates($this->conditionPlugins(), $context);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
