<?php
namespace deflou\components\triggers\events;

use deflou\components\applications\THasApplicationName;
use deflou\interfaces\triggers\events\ITriggerEventValuePlugin;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasStringId;

class TriggerEventValuePlugin extends Item implements ITriggerEventValuePlugin
{
    use THasStringId;
    use THasName;
    use THasDescription;
    use THasClass;
    use THasParams;
    use THasApplicationName;

    public function getApplyTo(): array
    {
        return $this->config[static::FIELD__APPLY_TO] ?? '';
    }

    public function setApplyTo(array $applyTo): static
    {
        $this[static::FIELD__APPLY_TO] = $applyTo;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
