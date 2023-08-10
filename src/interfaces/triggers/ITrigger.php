<?php
namespace deflou\interfaces\triggers;

use deflou\components\triggers\ETrigger;
use deflou\interfaces\applications\events\IEvent;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\applications\operations\IOperation;
use deflou\interfaces\applications\vendors\IHaveVendor;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use extas\interfaces\IHasCreatedAt;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasState;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

/**
 * ITrigger
 * {
 *  "id": "<uuid>",
 *  "title": "...",
 *  "description": "...",
 *  "eaid": "<uuid>",
 *  "eav": "1.0.0",
 *  "eiid": "<uuid>",
 *  "eiv": "1.0.0",
 *  "oaid": "<uuid>",
 *  "oav": "1.0.0",
 *  "oiid": "<uuid>",
 *  "oiv": "1.0.0",
 *  "created_at": "",
 *  "vendor": {
 *    "name": "..."
 *  },
 *  "state": "...",
 *  "event": {
 *      "title": "...",
 *      "description": "...",
 *      "name": "...",
 *      "params": {
 *          "param1": {
 *              "name": "...",
 *              "title": "...",
 *              "description": "...",
 *              "value": [
 *                      {
 *                          "value": "@event.par1 @now(Y.m.d)(-2d)@",
 *                          "plugins_names": ["event", "now"],
 *                          "params": {
 *                              "<condition.dispatcher.name>": {
 *                                  "name": "<condition.dispatcher.name>",
 *                                  "value": "gt"
 *                              },
 *                              "edge": {
 *                                  "name": "edge",
 *                                  "value": "and"
 *                              }
 *                          }
 *                      },
 *                      {
 *                          "value": "@now(Y.m.d)(0)@",
 *                          "plugins_names": ["now"],
 *                          "params": {
 *                              "<condition.dispatcher.name>": {
 *                                  "name": "<condition.dispatcher.name>",
 *                                  "value": "lt"
 *                              },
 *                              "edge": {
 *                                  "name": "edge",
 *                                  "value": "and"
 *                              }
 *                          }
 *                      },
 *              ]
 *          }
 *      }
 *  },
 *  "operation": {
 *      "title": "...",
 *      "description": "...",
 *      "name": "...",
 *      "params": {
 *          "param1": {
 *              "name": "...",
 *              "title": "...",
 *              "description": "...",
 *              "value": [{
 *                 "value": "Got @event.param1@ on @now(Y.m.d H:i:s)(0)@",
 *                 "plugins_names": ["event", "now"],
 *                 "params": {}
 *              }]
 *          }
 *      }
 *  }
 * }
 */
interface ITrigger extends IItem, IHaveUUID, IHasDescription, IHasCreatedAt, IHaveVendor, IHasState
{
    public const SUBJECT = 'deflou.trigger';

    public const FIELD__EVENT = IETrigger::T__EVENT;
    public const FIELD__EVENT_APPLICATION_ID            = IETrigger::SHORT__EVENT . IETrigger::FIELD__AID;
    public const FIELD__EVENT_INSTANCE_ID               = IETrigger::SHORT__EVENT . IETrigger::FIELD__IID;
    public const FIELD__EVENT_APPLICATION_VERSION       = IETrigger::SHORT__EVENT . IETrigger::FIELD__AV;
    public const FIELD__EVENT_INSTANCE_VERSION          = IETrigger::SHORT__EVENT . IETrigger::FIELD__IV;
    
    public const FIELD__OPERATION = IETrigger::T__OPERATION;
    public const FIELD__OPERATION_APPLICATION_ID        = IETrigger::SHORT__OPERATION . IETrigger::FIELD__AID;
    public const FIELD__OPERATION_INSTANCE_ID           = IETrigger::SHORT__OPERATION . IETrigger::FIELD__IID;
    public const FIELD__OPERATION_APPLICATION_VERSION   = IETrigger::SHORT__OPERATION . IETrigger::FIELD__AV;
    public const FIELD__OPERATION_INSTANCE_VERSION      = IETrigger::SHORT__OPERATION . IETrigger::FIELD__IV;

    public function getEvent(): array;
    public function buildEvent(): ITriggerEvent;
    public function setEvent(array $event): static;

    public function getOperation(): array;
    public function buildOperation(): ITriggerOperation;
    public function setOperation(array $operation): static;

    public function getApplicationId(ETrigger $et): string;
    public function getApplication(ETrigger $et): ?IApplication;
    public function getInstanceId(ETrigger $et): string;
    public function getInstance(ETrigger $et): ?IInstance;
    public function getApplicationVersion(ETrigger $et): string;
    public function getInstanceVersion(ETrigger $et): string;

    public function setApplicationId(ETrigger $et, string $id): static;
    public function setInstanceId(ETrigger $et, string $id): static;
    public function setApplicationVersion(ETrigger $et, string $version): static;
    public function setInstanceVersion(ETrigger $et, string $version): static;
}
