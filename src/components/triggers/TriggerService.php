<?php
namespace deflou\components\triggers;

use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\ITriggerSevice;
use extas\components\Item;

class TriggerService extends Item implements ITriggerSevice
{
    public function isApplicableTrigger(array $data, ITrigger $trigger): bool
    {
        $triggerParams = $trigger->buildEvent()->buildParams()->buildItems();
        
        foreach ($triggerParams as $tParam) {
            if (!isset($data[$tParam->getName()])) {
                return false;
            }

            if (!$tParam->buildValue()->isApplicable($data[$tParam->getName()])) {
                return false;
            }
        }

        return true;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
