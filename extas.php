<?php

use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\operations\plugins\PluginEvent;
use deflou\components\triggers\operations\plugins\PluginNow;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;

return [
    "trigger_event_condition_plugins" => [
        [
            IConditionPlugin::FIELD__NAME => 'basic_conditions',
            IConditionPlugin::FIELD__TITLE => 'Basic conditions',
            IConditionPlugin::FIELD__DESCRIPTION => 'Full list of basic conditions for numbers and text',
            IConditionPlugin::FIELD__CLASS => ConditionBasic::class
        ]
    ],
    "trigger_operation_plugins" => [
        [
            ITriggerOperationPlugin::FIELD__NAME => 'event',
            ITriggerOperationPlugin::FIELD__TITLE => 'Данные из события',
            ITriggerOperationPlugin::FIELD__DESCRIPTION => 'Подставить данные из события',
            ITriggerOperationPlugin::FIELD__CLASS => PluginEvent::class
        ],
        [
            ITriggerOperationPlugin::FIELD__NAME => 'now',
            ITriggerOperationPlugin::FIELD__TITLE => 'Текущие время и дата',
            ITriggerOperationPlugin::FIELD__DESCRIPTION => 'Подставить текущее время и/или дату',
            ITriggerOperationPlugin::FIELD__CLASS => PluginNow::class
        ],
    ]
];
