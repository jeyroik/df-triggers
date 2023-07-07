<?php
namespace deflou\components\exceptions\triggers;

use Throwable;

class TriggerEmptyData extends \Exception
{
    /**
     * Missed constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Empty data', $code, $previous);
    }
}
