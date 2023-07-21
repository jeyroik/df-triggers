<?php
namespace deflou\components\extensions\triggers;

use deflou\components\triggers\events\conditions\ConditionService;
use deflou\components\triggers\events\conditions\EConditionEdge;
use deflou\interfaces\extensions\triggers\IExtensionTriggerEventValue;
use deflou\interfaces\triggers\events\conditions\ICondition;
use deflou\interfaces\triggers\values\IValueSense;
use extas\components\extensions\Extension;
use extas\interfaces\parameters\IParam;

class ExtensionTriggerEventValue extends Extension implements IExtensionTriggerEventValue
{
    public function getCondition(IValueSense $sense = null): array
    {
        $params = $sense->buildParams()->buildAll();

        foreach ($params as $param) {
            if ($param->getName() != static::PARAM__EDGE) {
                return [
                    ICondition::FIELD__PLUGIN => $param->getName(), 
                    ICondition::FIELD__CONDITION => $param->getValue()
                ];
            }
        }

        return [];
    }

    public function setCondition(array $condition, IValueSense $sense = null): static
    {
        $params = $sense->buildParams();
        $edge = $params->hasOne(static::PARAM__EDGE) ? $params->getOne(static::PARAM__EDGE) : null;

        $newParams = [
            $condition[ICondition::FIELD__PLUGIN] => [
                IParam::FIELD__NAME => $condition[ICondition::FIELD__PLUGIN],
                IParam::FIELD__VALUE => $condition[ICondition::FIELD__CONDITION]
            ]
        ];

        if ($edge) {
            $newParams[static::PARAM__EDGE] = $edge;
        }

        $sense->setParams($newParams);

        return $this;
    }

    public function met(string|int $value, IValueSense $sense = null): bool
    {
        $condService = new ConditionService();

        return $condService->met($sense, $value);
    }

    public function getEdge(IValueSense $sense = null): string
    {
        $params = $sense->buildParams();

        return $params->hasOne(static::PARAM__EDGE) ? $params->buildOne(static::PARAM__EDGE)->getValue() : '';
    }

    public function buildEdge(IValueSense $sense = null): ?EConditionEdge
    {
        $params = $sense->buildParams();

        return $params->hasOne(static::PARAM__EDGE) ? EConditionEdge::tryFrom($this->getEdge($sense)) : null;
    }
}
