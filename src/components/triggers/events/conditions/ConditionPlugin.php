<?php
namespace deflou\components\triggers\events\conditions;

use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasStringId;

class ConditionPlugin extends Item implements IConditionPlugin
{
    use THasStringId;
    use THasName;
    use THasDescription;
    use THasClass;
    use THasParams;

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool
    {
        $dispatcher = $this->buildClassWithParameters();
        return $dispatcher($triggerValue, $condition, $eventValue);
    }

    /**
     * @return IConditionDescription[]
     */
    public function getConditionDescriptions(): array
    {
        $dispatcher = $this->buildClassWithParameters();
        return $dispatcher->getDescriptions($this);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
