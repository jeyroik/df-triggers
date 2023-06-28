<?php
namespace deflou\interfaces\triggers\events;

use deflou\interfaces\instances\IInstance;
use extas\interfaces\IItem;

interface ITriggerEventValueService extends IItem
{
    public const SUBJECT = 'deflou.trigger.event.value.service';

    public const APPLICATION__ANY = '*';

    public function getValues(IInstance $instance): array;
}
