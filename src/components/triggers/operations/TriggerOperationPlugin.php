<?php
namespace deflou\components\triggers\operations;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use extas\components\Item;
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

    public function __invoke(string|int $triggerValue, IResolvedEvent $event): string|int
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin($triggerValue, $event);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
