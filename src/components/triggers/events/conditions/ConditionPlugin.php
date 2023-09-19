<?php
namespace deflou\components\triggers\events\conditions;

use deflou\components\applications\THasApplicationName;
use deflou\interfaces\templates\contexts\IContext;
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
    use THasApplicationName;

    public function __invoke(string|int $triggerValue, string $condition, string|int $eventValue): bool
    {
        $dispatcher = $this->buildClassWithParameters();
        return $dispatcher($triggerValue, $condition, $eventValue);
    }

    public function getApplyToParams(): array
    {
        return $this[static::FIELD__APPLY_TO_PARAM] ?? [];
    }

    public function setApplyToParams(array $applyTo): static
    {
        $this[static::FIELD__APPLY_TO_PARAM] = $applyTo;

        return $this;
    }

    /**
     * @return IConditionDescription[]
     */
    public function getConditionDescriptions(): array
    {
        $dispatcher = $this->buildClassWithParameters();
        return $dispatcher->getDescriptions($this);
    }

    public function getTemplateData(IContext $context): array
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin->getTemplateData($this, $context);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
