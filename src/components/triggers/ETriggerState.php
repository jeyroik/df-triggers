<?php
namespace deflou\components\triggers;

use deflou\interfaces\triggers\ITrigger;

enum ETriggerState: string
{
    case OnConstruct = 'on_construct';
    case Active = 'active';
    case Suspended = 'suspended';
    case Deleted = 'deleted';

    public const LANG__RU = 'ru';
    public const LANG__EN = 'en';
    public const LANG__DEFAULT = self::LANG__RU;

    public function title(string $lang): string
    {
        $langs = $this->getConfig()[$this->value];

        return $langs[$lang] ?? $langs[static::LANG__DEFAULT];
    }

    public function set(ITrigger &$trigger): void
    {
        $trigger->setState($this->value);
    }

    public function activate(ITrigger &$trigger): void
    {
        $trigger->setState(static::Active->value);
    }

    public function suspend(ITrigger &$trigger): void
    {
        $trigger->setState(static::Suspended->value);
    }

    public function delete(ITrigger &$trigger): void
    {
        $trigger->setState(static::Deleted->value);
    }

    protected function getConfig(): array
    {
        return [
            static::OnConstruct->value => [
                static::LANG__EN => 'On constructing',
                static::LANG__RU => 'Не закончен'
            ],
            static::Active->value => [
                static::LANG__EN => 'Active',
                static::LANG__RU => 'Работает'
            ],
            static::Suspended->value => [
                static::LANG__EN => 'Suspended',
                static::LANG__RU => 'Остановлен'
            ],
            static::Deleted->value => [
                static::LANG__EN => 'Deleted',
                static::LANG__RU => 'Удалён'
            ]
        ];
    }
}
