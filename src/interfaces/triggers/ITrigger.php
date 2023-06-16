<?php
namespace deflou\interfaces\triggers;

use deflou\interfaces\applications\events\IEvent;
use deflou\interfaces\applications\vendors\IHaveVendor;
use extas\interfaces\IHasCreatedAt;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasState;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

/**
 * ITrigger
 * {
 *  "eaid": "<uuid>",
 *  "eiid": "<uuid>",
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
 *                  {"condition_plugin":"compare_default", "options": <mixed>}
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
 *              "value": "Got @event.param1@ on @now(Y.m.d H:i:s)",
 *              "plugins": ["event", "now"]
 *          }
 *      }
 *  }
 * }
 */
interface ITrigger extends IItem, IHaveUUID, IHasDescription, IHasCreatedAt, IHaveVendor, IHasState
{
    public const SUBJECT = 'deflou.trigger';

    public const FIELD__EVENT = 'event';
    public const FIELD__EVENT_APPLICATION_ID = 'eaid';//ещё надо версию приложения и инстанса
    public const FIELD__EVENT_INSTANCE_ID = 'eiid';
    public const FIELD__OPERATION = 'operation';
    public const FIELD__OPERATION_APPLICATION_ID = 'oaid';
    public const FIELD__OPERATION_INSTANCE_ID = 'oiid';

    public function getEvent(): array;
    public function buildEvent(): IEvent;
}
