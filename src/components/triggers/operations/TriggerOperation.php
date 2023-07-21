<?php
namespace deflou\components\triggers\operations;

use deflou\components\triggers\values\Value;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use deflou\interfaces\triggers\values\IValue;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasDescription;
use extas\components\THasName;
use Generator;

class TriggerOperation extends Item implements ITriggerOperation
{
    use THasName;
    use THasDescription;
    use THasParams;

    /**
     * 
     *
     * @return Generator|IValue[]
     */
    public function eachParamValue(): Generator
    {
        $params = $this->buildParams()->buildAll();

        foreach ($params as $name => $param) {
            yield $name => new Value($param->getValue());
        }
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
