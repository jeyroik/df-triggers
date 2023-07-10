<?php

use deflou\components\extensions\instances\ExtensionInstanceResolver;
use deflou\components\extensions\instances\ExtensionInstanceTriggers;
use deflou\components\extensions\triggers\ExtensionTrigger;
use deflou\components\plugins\triggers\PluginTriggerOpTemplateArray;
use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\operations\plugins\PluginEvent;
use deflou\components\triggers\operations\plugins\PluginNow;
use deflou\components\triggers\operations\plugins\PluginText;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\extensions\instances\IExtensionInstanceTriggers;
use deflou\interfaces\extensions\triggers\IExtensionTrigger;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\stages\triggers\IStageTriggerOpTemplate;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperationPlugin;
use deflou\interfaces\triggers\operations\ITriggerOperationService;
use extas\interfaces\extensions\IExtension;
use extas\interfaces\plugins\IPlugin;

return [
    "name" => "jeyroik/df-triggers",
    "plugins" => [
        [
            IPlugin::FIELD__CLASS => PluginTriggerOpTemplateArray::class,
            IPlugin::FIELD__STAGE => IStageTriggerOpTemplate::NAME . PluginTriggerOpTemplateArray::CONTEXT__ARRAY
        ]
    ],
    "extensions" => [
        [
            IExtension::FIELD__CLASS => ExtensionInstanceResolver::class,
            IExtension::FIELD__INTERFACE => IExtensionInstanceResolver::class,
            IExtension::FIELD__SUBJECT => IInstance::SUBJECT,
            IExtension::FIELD__METHODS => ['buildResolver']
        ],
        [
            IExtension::FIELD__CLASS => ExtensionInstanceTriggers::class,
            IExtension::FIELD__INTERFACE => IExtensionInstanceTriggers::class,
            IExtension::FIELD__SUBJECT => IInstance::SUBJECT,
            IExtension::FIELD__METHODS => ['getActiveTriggers']
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
            ITriggerOperationPlugin::FIELD__NAME => PluginText::NAME,
            ITriggerOperationPlugin::FIELD__TITLE => 'Текст',
            ITriggerOperationPlugin::FIELD__DESCRIPTION => 'Вставить какой-либо текст',
            ITriggerOperationPlugin::FIELD__CLASS => PluginText::class,
            ITriggerOperationPlugin::FIELD__APPLICATION_NAME => ITriggerOperationService::ANY
        ],
        [
            ITriggerOperationPlugin::FIELD__NAME => PluginEvent::NAME,
            ITriggerOperationPlugin::FIELD__TITLE => 'Данные из события',
            ITriggerOperationPlugin::FIELD__DESCRIPTION => 'Подставить данные из события',
            ITriggerOperationPlugin::FIELD__CLASS => PluginEvent::class,
            ITriggerOperationPlugin::FIELD__APPLICATION_NAME => ITriggerOperationService::ANY
        ],
        [
            ITriggerOperationPlugin::FIELD__NAME => PluginNow::NAME,
            ITriggerOperationPlugin::FIELD__TITLE => 'Текущие время и дата',
            ITriggerOperationPlugin::FIELD__DESCRIPTION => 'Подставить текущее время и/или дату',
            ITriggerOperationPlugin::FIELD__CLASS => PluginNow::class,
            ITriggerOperationPlugin::FIELD__APPLICATION_NAME => ITriggerOperationService::ANY
        ],
    ]
];
