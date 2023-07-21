<?php
namespace deflou\components\triggers\events;

use deflou\components\triggers\values\Value;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\events\ITriggerEventValue;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\components\THasDescription;
use extas\components\THasName;
use Generator;

class TriggerEvent extends Item implements ITriggerEvent
{
    use THasName;
    use THasDescription;
    use THasParams;

    /**
     * @return Generator|ITriggerEventValue[]
     */
    public function eachParamValue(): Generator
    {
        $params = $this->buildParams()->buildAll();

        foreach ($params as $name => $param) {
            $value = new Value($param->getValue());

            yield $name => $value->eachSense();
        }
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
