<?php
namespace deflou\interfaces\stages\triggers;

use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use deflou\interfaces\triggers\operations\plugins\templates\ITemplateContext;

interface IStageTriggerOpTemplate
{
    public const NAME = 'deflou.trigger.op.template.';

    public function __invoke(array $templateData, ITriggerOperationPlugin $plugin, mixed &$template, ITemplateContext $context): void;
}
