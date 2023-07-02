<?php
namespace deflou\components\triggers\events\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\ITriggerEventValuePlugin;
use deflou\interfaces\triggers\events\plugins\IValuePluginDispatcher;
use extas\components\Item;

/**
 * In a plugin config:
 * {
 *  "class": "deflou\\components\\triggers\\events\\plugins\\ValuePluginList",
 *  "params": {
 *      "list": {
 *          "name": "list",
 *          "value": [
 *              {
 *                  "name": "...",
 *                  "title": "...",
 *                  "description": "..."
 *              }
 *          ]
 *      }
 *  }
 * }
 */
class ValuePluginList extends Item implements IValuePluginDispatcher
{
    public const PARAM__LIST = 'list';

    public function __invoke(IInstance $instance, string $paramName, ITriggerEventValuePlugin $plugin): array
    {
        if (!$plugin->buildParams()->hasOne(static::PARAM__LIST)) {
            return [];
        }

        $list = $plugin->buildParams()->buildOne(static::PARAM__LIST)->getValue();
        $result = [];

        foreach ($list as $item) {
            $result[] = new ValueDescription($item);
        }

        return $result;
    }

    protected function getSubjectForExtension(): string
    {
        return 'deflou.trigger.event.value.plugin.list';
    }
}
