<?php
namespace deflou\interfaces\triggers\values\plugins\templates;

use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface ITemplateContext extends IItem, IHasName, IHaveParams
{
    public const SUBJECT = 'deflou.trigger.value.plugin.template';
}
