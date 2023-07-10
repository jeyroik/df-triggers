<?php
namespace deflou\components\triggers\operations\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use deflou\interfaces\triggers\operations\plugins\IPluginDispatcher;

class PluginNow implements IPluginDispatcher
{
    public const NAME = 'now';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        return preg_replace_callback('/\@now\((.*)\)\@/i', function ($matches) { return date($matches[1]);}, $triggerValue);
    }

    public function getTemplateData(IInstance $eventInstance, ITrigger $trigger, ITriggerOperationPlugin $plugin): array
    {
        return [
            'Y.m.d H:i:s',
            'd.m.Y H:i:s',
            'Y.m.d',
            'd.m.Y'
        ];
    }
}
