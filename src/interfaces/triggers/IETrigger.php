<?php
namespace deflou\interfaces\triggers;

interface IETrigger
{
    public const SHORT__EVENT = 'e';
    public const SHORT__OPERATION = 'o';

    public const FIELD__AID = 'aid';
    public const FIELD__IID = 'iid';
    public const FIELD__AV = 'av';
    public const FIELD__IV = 'iv';

    public const T__EVENT = 'event';
    public const T__OPERATION = 'operation';

    public function getShort(): string;

    public function getAppIdField(): string;

    public function getInstIdField(): string;

    public function getAppVerField(): string;

    public function getInstVerField(): string;
}
