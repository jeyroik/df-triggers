<?php
namespace deflou\components\resolvers\events;

use deflou\components\applications\THasApplication;
use deflou\components\instances\THasInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasName;

class ResolvedEvent extends Item implements IResolvedEvent
{
    use THasParams;
    use THasName;
    use THasApplication;
    use THasInstance;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
