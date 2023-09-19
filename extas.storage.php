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
            "aliases" => ["triggerEventConditionPlugins", "conditionPlugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ],
        "trigger_value_plugins" => [
            "namespace" => "deflou\\repositories",
            "item_class" => "deflou\\components\\triggers\\values\\plugins\\ValuePlugin",
            "pk" => "id",
            "aliases" => ["triggerValuePlugins"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                  .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
            ]
        ]
    ]
];
