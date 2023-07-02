<?php

use extas\components\repositories\RepoItem;

return [
    "name" => "jeyroik/df-triggers",
    "tables" => [
        "triggers" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\Trigger",
            "pk" => "id",
            "aliases" => ["triggers"],
            "hooks" => [
                'create-after' => true
            ],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
            ]
        ],
        "trigger_event_condition_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\events\\conditions\\ConditionPlugin",
            "pk" => "id",
            "aliases" => ["triggerEventConditionPlugins", "trigger_event_condition_plugins"],
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
            "aliases" => ["triggerOperationPlugins", "trigger_operation_plugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
        "trigger_event_value_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\events\\TriggerEventValuePlugin",
            "pk" => "id",
            "aliases" => ["triggerEventValuePlugins", "trigger_event_value_plugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
    ]
];
