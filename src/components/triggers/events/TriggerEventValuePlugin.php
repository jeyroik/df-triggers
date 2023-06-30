<?php
namespace deflou\components\triggers\events;

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
    
    public function getApplicationName(): string
    {
        return $this->config[static::FIELD__APPLICATION_NAME] ?? '';
    }

    public function setApplicationName(string $name): static
    {
        $this[static::FIELD__APPLICATION_NAME] = $name;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
