<?php
namespace deflou\components\triggers;

use deflou\interfaces\triggers\IETrigger;

enum ETrigger: string implements IETrigger
{
    case Event = 'event';
    case Operation = 'operation';

    public function getShort(): string
    {
        return [
            static::Event->value => static::SHORT__EVENT,
            static::Operation->value => static::SHORT__OPERATION
        ][$this->value] ?? '';
    }

    public function getAppIdField(): string
    {
        return $this->getShort() . static::FIELD__AID;
    }

    public function getInstIdField(): string
    {
        return $this->getShort() . static::FIELD__IID;
    }

    public function getAppVerField(): string
    {
        return $this->getShort() . static::FIELD__AV;
    }

    public function getInstVerField(): string
    {
        return $this->getShort() . static::FIELD__IV;
    }
}
