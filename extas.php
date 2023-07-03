<?php

use deflou\components\extensions\instances\ExtensionInstanceResolver;
use deflou\components\extensions\triggers\ExtensionTrigger;
use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\operations\plugins\PluginEvent;
use deflou\components\triggers\operations\plugins\PluginNow;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\extensions\triggers\IExtensionTrigger;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use extas\interfaces\extensions\IExtension;

return [
    "name" => "jeyroik/df-triggers",
    "extensions" => [
        [
            IExtension::FIELD__CLASS => ExtensionInstanceResolver::class,
            IExtension::FIELD__INTERFACE => IExtensionInstanceResolver::class,
            IExtension::FIELD__SUBJECT => IInstance::SUBJECT,
            IExtension::FIELD__METHODS => ['buildResolver']
        ],
        [
            IExtension::FIELD__CLASS => ExtensionTrigger::class,
            IExtension::FIELD__INTERFACE => IExtensionTrigger::class,
            IExtension::FIELD__SUBJECT => ITrigger::SUBJECT,
            IExtension::FIELD__METHODS => ['toConstruct', 'activate', 'suspend', 'delete', 'resume', 'stateIs', 'stateIsNot']
        ]
    ],
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
