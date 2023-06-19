<?php
namespace deflou\interfaces\triggers;

use deflou\components\triggers\ETrigger;
use deflou\interfaces\applications\events\IEvent;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\applications\operations\IOperation;
use deflou\interfaces\applications\vendors\IHaveVendor;
use deflou\interfaces\instances\IInstance;
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
 *              "value": {
 *                  "type": "event_value",
 *                  "value": [{"condition_plugin":"compare_default", "options": <mixed>}]
 *              }
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
 *              "value": {
 *                  "type": "operation_value",
 *                  "value": "Got @event.param1@ on @now(Y.m.d H:i:s)@",
 *                  "plugins": ["event", "now"]
 *              }
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
    public function buildEvent(): IEvent;

    public function getOperation(): array;
    public function buildOperation(): IOperation;

    public function getApplicationId(ETrigger $et): string;
    public function getApplication(ETrigger $et): ?IApplication;
    public function getInstanceId(ETrigger $et): string;
    public function getInstance(ETrigger $et): ?IInstance;
    public function getApplicationVersion(ETrigger $et): string;
    public function getInstanceVersion(ETrigger $et): string;
}
