<?php
namespace deflou\components\triggers\values\plugins;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\IValuePluginDispatcher;

class PluginNow implements IValuePluginDispatcher
{
    public const NAME = 'now';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event, IValuePlugin $plugin): string|int
    {
        return preg_replace_callback(
            '/\@now\((.*)\)\@/i', 
            function ($matches) { 
                return date($matches[1]);
            }, 
            $triggerValue
        );
    }

    public function getTemplateData(IInstance $instance, ITrigger $trigger, IValuePlugin $plugin): array
    {
        return [
            'Y.m.d H:i:s',
            'd.m.Y H:i:s',
            'Y.m.d',
            'd.m.Y'
        ];
    }
}
