<?php

use deflou\components\extensions\instanes\ExtensionInstanceResolver;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\instances\IInstance;
use extas\components\repositories\RepoItem;
use extas\interfaces\extensions\IExtension;

return [
    "name" => "jeyroik/df-triggers",
    "tables" => [
        "triggers" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\Trigger",
            "pk" => "id",
            "aliases" => ["triggers"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
            ]
        ],
        "trigger_event_condition_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\events\\conditions\\ConditionPlugin",
            "pk" => "id",
            "aliases" => ["triggerEventConditionPlugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
        "trigger_operation_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\operations\\TriggerOperationPlugin",
            "pk" => "id",
            "aliases" => ["triggerOperationPlugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
        "trigger_event_value_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\instances\\InstanceInfo",
            "pk" => "id",
            "aliases" => ["triggerEventValuePlugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
    ],
    "extensions" => [
        [
            IExtension::FIELD__CLASS => ExtensionInstanceResolver::class,
            IExtension::FIELD__INTERFACE => IExtensionInstanceResolver::class,
            IExtension::FIELD__SUBJECT => IInstance::SUBJECT,
            IExtension::FIELD__METHODS => ['buildResolver']
        ]
    ]
];
