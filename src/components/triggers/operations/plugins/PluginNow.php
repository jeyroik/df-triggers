<?php
namespace deflou\components\triggers\operations\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\operations\plugins\IPluginDispatcher;

class PluginNow implements IPluginDispatcher
{
    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        return preg_replace_callback('/\@now\((.*)\)\@/i', function ($matches) { return date($matches[1]);}, $triggerValue);
    }
}
