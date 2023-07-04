<?php
namespace deflou\interfaces\stages\triggers;

use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;

interface IStageTriggerOpTemplate
{
    public const NAME = 'deflou.trigger.op.template.';

    public function __invoke(array $templateData, ITriggerOperationPlugin $plugin, mixed &$template): void;
}
