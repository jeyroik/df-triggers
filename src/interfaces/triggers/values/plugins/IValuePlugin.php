<?php
namespace deflou\interfaces\triggers\values\plugins;

use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\IWithTemplate;

interface IValuePlugin extends IWithTemplate
{
    public const SUBJECT = 'deflou.trigger.value.plugin';

    public function __invoke(string|int $value, IResolvedEvent $event): string|int;
}
