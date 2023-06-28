<?php
namespace deflou\interfaces\resolvers;

use deflou\interfaces\applications\IHaveApplication;
use deflou\interfaces\instances\IHaveInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\operations\IResolvedOperation;
use deflou\interfaces\triggers\ITrigger;
use extas\interfaces\IItem;

interface IResolver extends IItem, IHaveInstance, IHaveApplication
{
    public const SUBJECT = 'df.resolver';
    public const FIELD__EVENT_NAME = 'event_name';

    public function resolveEvent(): IResolvedEvent;
    public function resolveOperation(IResolvedEvent $event, ITrigger $trigger): IResolvedOperation;
    public function getEventName(): string;
}
