<?php
namespace deflou\interfaces\resolvers\events;

use deflou\interfaces\applications\IHaveApplication;
use deflou\interfaces\instances\IHaveInstance;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use extas\interfaces\parameters\IHaveParams;

interface IResolvedEvent extends IItem, IHaveParams, IHasName, IHaveApplication, IHaveInstance
{
    public const SUBJECT = 'df.resolved.event';
}
