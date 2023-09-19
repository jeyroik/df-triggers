<?php
namespace deflou\components\triggers\events\conditions\plugins;

use deflou\components\triggers\events\conditions\ConditionDescription;
use deflou\interfaces\templates\IWithTemplate;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\events\conditions\IConditionPluginDispatcher;
use extas\components\conditions\ConditionParameter;
use extas\components\Item;
use extas\interfaces\conditions\ICondition;
use extas\interfaces\repositories\IRepository;

/**
 * Parameter in a plugin:
 * 
 * {
 *  "class": "deflou\\components\\triggers\\events\\conditions\\plugins\\ConditionBasic",
 *  "params": {
 *      "items": {
 *          "name": "items",
 *          "value": ["eq", "!eq", "gt", "gte", ...]
 *      }
 *  }
 *  ...
 * }
 * 
 * @method IRepository conditions()
 */
class ConditionBasic extends Item implements IConditionPluginDispatcher
{
    public const PARAM__ITEMS = 'items';

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool
    {
        $tmp = new ConditionParameter([
            ConditionParameter::FIELD__VALUE => $triggerValue,
            ConditionParameter::FIELD__CONDITION => $condition
        ]);

        return $tmp->isConditionMet($eventValue);
    }

    /**
     * @deprecated 
     *
     * @param  IWithTemplate $plugin
     * @return array
     */
    public function getDescriptions(IWithTemplate $plugin): array
    {
        $query = $plugin->buildParams()->hasOne(static::PARAM__ITEMS) 
            ? [ICondition::FIELD__ALIASES => $plugin->buildParams()->buildOne(static::PARAM__ITEMS)->getValue()]
            : [];

        /**
         * @var ICondition[] $conditions
         */
        $conditions = $this->conditions()->all($query);
        $result = [];

        foreach ($conditions as $c) {
            $result[] = new ConditionDescription([
                ConditionDescription::FIELD__NAME => $c->getName(),
                ConditionDescription::FIELD__TITLE => $c->getTitle(),
                ConditionDescription::FIELD__DESCRIPTION => $c->getDescription(),
                ConditionDescription::FIELD__PLUGIN => $plugin->getName()
            ]);
        }

        return $result;
    }

    public function getTemplateData(IWithTemplate $templated, IContext $context): array
    {
        return $this->getDescriptions($templated);
    }

    protected function getSubjectForExtension(): string
    {
        return 'deflou.trigger.event.value.plugin.basic';
    }
}
