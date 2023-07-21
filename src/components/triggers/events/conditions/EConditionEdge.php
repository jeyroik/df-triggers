<?php
namespace deflou\components\triggers\events\conditions;

enum EConditionEdge: string
{
    case And = 'and';
    case Or = 'or';

    const LANG__RU = 'ru';
    const LANG__EN = 'en';

    public function to(string $lang): string
    {
        $voc = [
            $this::And->value => [
                static::LANG__RU => 'И',
                static::LANG__EN => 'And'
            ],
            $this::Or->value => [
                static::LANG__RU => 'Или',
                static::LANG__EN => 'Or'
            ]
        ];

        return $voc[$this->value][$lang] ?? $this->value;
    }
}
