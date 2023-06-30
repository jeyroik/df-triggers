<?php
namespace deflou\components\resolvers;

use deflou\components\applications\THasApplication;
use deflou\components\instances\THasInstance;
use deflou\interfaces\resolvers\IResolver;
use extas\components\Item;
use extas\components\parameters\THasParams;

abstract class Resolver extends Item implements IResolver
{
    use THasInstance;
    use THasApplication;
    use THasParams;

    public function getEventName(): string
    {
        return $this->config[static::FIELD__EVENT_NAME] ?? '';
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
