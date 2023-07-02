<?php
namespace deflou\interfaces\triggers\events;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\plugins\IValueDescription;
use extas\interfaces\IItem;

interface ITriggerEventValueService extends IItem
{
    public const SUBJECT = 'deflou.trigger.event.value.service';

    public const ANY = '*';

    /**
     * @param  IInstance $instance
     * @param  string    $paramName
     * @return IValueDescription[]
     */
    public function getValues(IInstance $instance, string $paramName = ''): array;
}
