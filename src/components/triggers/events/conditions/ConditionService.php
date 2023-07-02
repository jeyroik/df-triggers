<?php
namespace deflou\components\triggers\events\conditions;

use deflou\interfaces\triggers\events\conditions\ICondition;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\events\conditions\IConditionService;
use deflou\interfaces\triggers\events\ITriggerEventValue;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerEventConditionPlugins()
 */
class ConditionService extends Item implements IConditionService
{
    protected static $plugins = [];

    public function buildCondition(ITriggerEventValue $value): ICondition
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

    public function met(ITriggerEventValue $value, string|int $incomeEventValue): bool
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

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
