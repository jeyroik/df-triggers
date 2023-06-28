<?php
namespace deflou\interfaces\resolvers\events;

use deflou\interfaces\applications\IHaveApplication;
use deflou\interfaces\applications\params\IHaveParams;
use deflou\interfaces\instances\IHaveInstance;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

interface IResolvedEvent extends IItem, IHaveParams, IHasName, IHaveApplication, IHaveInstance
{
    public const SUBJECT = 'df.resolved.event';
}
