<?php

use deflou\components\extensions\instances\ExtensionInstanceResolver;
use deflou\components\extensions\instances\ExtensionInstanceTriggers;
use deflou\components\extensions\triggers\ExtensionTrigger;
use deflou\components\extensions\triggers\ExtensionTriggerEventValue;
use deflou\components\plugins\triggers\PluginTriggerOpTemplateArray;
use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\events\plugins\PluginList;
use deflou\components\triggers\values\plugins\PluginEvent;
use deflou\components\triggers\values\plugins\PluginNow;
use deflou\components\triggers\values\plugins\PluginText;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\extensions\instances\IExtensionInstanceTriggers;
use deflou\interfaces\extensions\triggers\IExtensionTrigger;
use deflou\interfaces\extensions\triggers\IExtensionTriggerEventValue;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\stages\triggers\IStageTriggerTemplate;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\values\IValueSense;
use deflou\interfaces\triggers\values\IValueService;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use extas\interfaces\extensions\IExtension;
use extas\interfaces\plugins\IPlugin;

return [
    "name" => "jeyroik/df-triggers",
    "plugins" => [
        [
            IPlugin::FIELD__CLASS => PluginTriggerOpTemplateArray::class,
            IPlugin::FIELD__STAGE => IStageTriggerTemplate::NAME . PluginTriggerOpTemplateArray::CONTEXT__ARRAY
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
        ],
        [
            IExtension::FIELD__CLASS => ExtensionTriggerEventValue::class,
            IExtension::FIELD__INTERFACE => IExtensionTriggerEventValue::class,
            IExtension::FIELD__SUBJECT => IValueSense::SUBJECT,
            IExtension::FIELD__METHODS => ['met', 'getCondition', 'setCondition', 'getEdge', 'buildEdge']
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
    "trigger_value_plugins" => [
        [
            IValuePlugin::FIELD__NAME => PluginText::NAME,
            IValuePlugin::FIELD__TITLE => 'Текст',
            IValuePlugin::FIELD__DESCRIPTION => 'Вставить какой-либо текст',
            IValuePlugin::FIELD__CLASS => PluginText::class,
            IValuePlugin::FIELD__APPLICATION_NAME => IValueService::ANY,
            IValuePlugin::FIELD__APPLY_TO_PARAM => [IValueService::ANY]
        ],
        [
            IValuePlugin::FIELD__NAME => PluginEvent::NAME,
            IValuePlugin::FIELD__TITLE => 'Данные из события',
            IValuePlugin::FIELD__DESCRIPTION => 'Подставить данные из события',
            IValuePlugin::FIELD__CLASS => PluginEvent::class,
            IValuePlugin::FIELD__APPLICATION_NAME => IValueService::ANY,
            IValuePlugin::FIELD__APPLY_TO_PARAM => [IValueService::ANY]
        ],
        [
            IValuePlugin::FIELD__NAME => PluginNow::NAME,
            IValuePlugin::FIELD__TITLE => 'Текущие время и дата',
            IValuePlugin::FIELD__DESCRIPTION => 'Подставить текущее время и/или дату',
            IValuePlugin::FIELD__CLASS => PluginNow::class,
            IValuePlugin::FIELD__APPLICATION_NAME => IValueService::ANY,
            IValuePlugin::FIELD__APPLY_TO_PARAM => [IValueService::ANY]
        ]
    ]
];
