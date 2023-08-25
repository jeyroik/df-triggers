<?php
namespace deflou\components\triggers\values\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\templates\contexts\IContextTrigger;
use deflou\interfaces\templates\IWithTemplate;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\IValuePluginDispatcher;
use extas\components\Replace;

class PluginEvent implements IValuePluginDispatcher
{
    public const NAME = 'event';

    public function __invoke(string|int $triggerValue, IResolvedEvent $event, IValuePlugin $plugin): string|int
    {
        return Replace::please()->apply(['event' => $event->getParamsValues()])->to($triggerValue);
    }

    public function getTemplateData(IWithTemplate $templated, IContext|IContextTrigger $context): array
    {
        /**
         * @var ITrigger $trigger
         */
        $trigger  = $context->buildParams()->buildOne($context::PARAM__TRIGGER)->getValue();
        $for      = $context->buildParams()->buildOne($context::PARAM__FOR)->getValue();
        $instance = $trigger->getInstance($for);

        return $instance->buildEvents()->buildOne($trigger->buildEvent()->getName())->buildParams()->buildAll();
    }
}
