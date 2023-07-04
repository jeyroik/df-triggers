<?php
namespace deflou\components\triggers\operations\plugins\templates;

use deflou\interfaces\triggers\operations\plugins\templates\ITemplateContext;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasName;

class TemplateContext extends Item implements ITemplateContext
{
    use THasName;
    use THasParams;

    public function __toString(): string
    {
        return $this->getName();
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
