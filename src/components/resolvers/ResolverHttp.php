<?php
namespace deflou\components\resolvers;

use deflou\components\resolvers\events\ResolvedEvent;
use deflou\components\resolvers\events\TEventHttp;
use deflou\components\resolvers\operations\ResolvedOperationHttp;
use deflou\components\resolvers\operations\TOperationHttp;
use deflou\components\triggers\ETrigger;
use deflou\interfaces\applications\options\IOptions;
use deflou\interfaces\resolvers\events\IEventHttp;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\operations\IOperationHttp;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\resolvers\operations\IResolvedOperation;
use extas\components\parameters\Param;

/**
 * in the application config
 * {
 *  ...,
 *  "options": {
 *      ...,
 *      "operation__base_url": {
 *          ...,
 *          "value": "https://base.url"
 *      },
 *      "operaton__method": {
 *          ...,
 *          "value": "post" // post|get|put|delete
 *      },
 *      "<operation.name>__url": {
 *          ...,
 *          "value": "/operation/path?with=param&or=without"
 *      },
 *      "<operation.name>__method": { // may be omitted
 *          ...,
 *          "value": "post" // post|get|put|delete
 *      }
 *  }
 * }
 */
class ResolverHttp extends Resolver implements IEventHttp, IOperationHttp
{
    use TEventHttp;
    use TOperationHttp;

    public const OPTION__BASE_URL = 'operation__base_url';
    public const OPTION__METHOD = 'operation__method';
    public const OPTION__URL_PREFIX = '__url';
    public const OPTION__METHOD_PREFIX = '__method';

    public function resolveEvent(): IResolvedEvent
    {
        $data = [
            IResolvedEvent::FIELD__NAME => $this->getEventName(),
            IResolvedEvent::FIELD__INSTANCE_ID => $this->getInstanceId(),
            IResolvedEvent::FIELD__APPLICATION_ID => $this->getApplicationId()
        ];
        $data = array_merge($data, $this->getHttpRequest(), $this->getHttpHeaders(), $this->getHttpJson());

        return new ResolvedEvent($data);
    }

    public function resolveOperation(IResolvedEvent $resolvedEvent, ITrigger $trigger): IResolvedOperation
    {
        $operation = $trigger->buildOperation();
        $options = $trigger->getInstance(ETrigger::Operation)->buildOptions();
        $resolvedOperation = new ResolvedOperationHttp([
            ResolvedOperationHttp::FIELD__METHOD => $this->getOperationMethod($options, $operation->getName()),
            ResolvedOperationHttp::FIELD__URL => $options->buildOne(static::OPTION__BASE_URL)->getValue() 
                                               . $options->buildOne($operation->getName() . static::OPTION__URL_PREFIX)->getValue()
        ]);
        
        $operation = $trigger->buildOperation();
        foreach ($operation->eachParamValue() as $name => $triggerOperationValue) {
            $triggerOperationValue->applyPlugins($resolvedEvent);
            $resolvedOperation->addParam(new Param([
                Param::FIELD__NAME => $name,
                Param::FIELD__VALUE => $triggerOperationValue->getValue()
            ]));
        }

        return $resolvedOperation;
    }

    protected function getOperationMethod(IOptions $options, string $operationName): string
    {
        $method = 'get';

        if ($options->hasOne($operationName . static::OPTION__METHOD_PREFIX)) {
            $method = $options->buildOne($operationName . static::OPTION__METHOD_PREFIX)->getValue();
        } elseif ($options->hasOne(static::OPTION__METHOD)) {
            $method = $options->buildOne(static::OPTION__METHOD)->getValue();
        }

        return $method;
    }
}
