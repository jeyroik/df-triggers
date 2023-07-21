<?php
namespace deflou\interfaces\stages\triggers;

use deflou\interfaces\triggers\values\plugins\templates\ITemplateContext;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;

interface IStageTriggerTemplate
{
    public const NAME = 'deflou.trigger.value.plugin.template.';

    public function __invoke(array $templateData, IValuePlugin $plugin, mixed &$template, ITemplateContext $context): void;
}
