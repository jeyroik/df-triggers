<?php
namespace deflou\components\triggers\events\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\templates\IWithTemplate;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\IValuePluginDispatcher;
use extas\components\Item;
use extas\components\parameters\Param;
use extas\components\Replace;
use extas\interfaces\parameters\IParam;

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
 *                  "description": "...",
 *                  "value": "..."
 *              }
 *          ]
 *      }
 *  }
 * }
 * 
 * In a trigger value:
 * "value": "@list.paramName"
 */
class PluginList extends Item implements IValuePluginDispatcher
{
    public const NAME = 'list';

    public function __invoke(string|int $value, IResolvedEvent $event, IValuePlugin $plugin): string|int
    {
        $params = array_column($plugin->getParams(), IParam::FIELD__VALUE, IParam::FIELD__NAME);

        return Replace::please()->apply([static::NAME => $params])->to($value);
    }

    public function getTemplateData(IWithTemplate $templated, IContext|IContextTrigger $context): array
    {
        if (!$templated->buildParams()->hasOne(static::NAME)) {
            return [];
        }

        $list = $templated->buildParams()->buildOne(static::NAME)->getValue();
        $result = [];

        foreach ($list as $item) {
            $result[] = new Param($item);
        }

        return $result;
    }

    protected function getSubjectForExtension(): string
    {
        return 'deflou.trigger.event.value.plugin.list';
    }
}
