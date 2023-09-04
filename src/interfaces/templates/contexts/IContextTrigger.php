<?php
namespace deflou\interfaces\templates\contexts;

interface IContextTrigger extends IContext
{
    public const PARAM__TRIGGER = 'trigger';
    
    /**
     * @var string ETrigger::Operation|ETrigger::Event
     */
    public const PARAM__FOR = 'for';
}
