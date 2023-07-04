<?php
namespace deflou\components\triggers\operations;

use deflou\components\applications\THasApplicationName;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasStringId;

class TriggerOperationPlugin extends Item implements ITriggerOperationPlugin
{
    use THasStringId;
    use THasName;
    use THasDescription;
    use THasClass;
    use THasParams;
    use THasApplicationName;

    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin($triggerValue, $event);
    }

    public function getTemplateData(IInstance $eventInstance, ITrigger $trigger): array
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin->getTemplateData($eventInstance, $trigger, $this);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
