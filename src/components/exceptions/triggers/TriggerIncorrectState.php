<?php
namespace deflou\components\exceptions\triggers;

use Throwable;

class TriggerIncorrectState extends \Exception
{
    public const DELIMITER = 'vs';

    /**
     * Missed constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        list($need, $real) = explode(static::DELIMITER, $message);

        $message = 'Incorrect state, need: ' . $need . ', real: ' . $real;
        parent::__construct($message, $code, $previous);
    }
}
