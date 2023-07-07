<?php
namespace deflou\interfaces\extensions\instances;

use deflou\components\triggers\ETrigger;
use extas\interfaces\parameters\IParametred;

interface IExtensionInstanceTriggers
{
    /**
     * Return active triggers for an event or an operation
     *
     * @param  ETrigger    $et
     * @param  IParametred $evOrOp Event or Operation
     * @return array
     */
    public function getActiveTriggers(ETrigger $et, IParametred $evOrOp): array;
}
