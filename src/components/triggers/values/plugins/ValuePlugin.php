<?php
namespace deflou\components\triggers\values\plugins;

use deflou\components\applications\THasApplicationName;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\templates\contexts\IContextTrigger;

use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasStringId;

class ValuePlugin extends Item implements IValuePlugin
{
    use THasStringId;
    use THasName;
    use THasDescription;
    use THasParams;
    use THasApplicationName;
    use THasClass;

    public function __invoke(string|int $value, IResolvedEvent $event): string|int
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin($value, $event, $this);
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

    public function getTemplateData(IContext|IContextTrigger $context): array
    {
        $plugin = $this->buildClassWithParameters();
        return $plugin->getTemplateData($this, $context);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
