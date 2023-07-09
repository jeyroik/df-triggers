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
use deflou\interfaces\stages\resolvers\http\IStageRequestHeaders;
use deflou\interfaces\stages\resolvers\http\IStageRequestOptions;
use extas\components\parameters\Param;
use extas\interfaces\parameters\IParam;

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
 *      "operation__method": {
 *          ...,
 *          "value": "post" // post|get|put|delete|etc
 *      },
 *      "<operation.name>__url": {
 *          ...,
 *          "value": "/operation/path?with=param&or=without"
 *      },
 *      "<operation.name>__method": { // may be omitted
 *          ...,
 *          "value": "post" // post|get|put|delete|etc
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

    public const PARAM__REQUEST = 'request';
    public const PARAM__HEADERS = 'headers';
    public const PARAM__JSON = 'json';

    public function resolveEvent(): IResolvedEvent
    {
        $data = [
            IResolvedEvent::FIELD__NAME => $this->getEventName(),
            IResolvedEvent::FIELD__INSTANCE_ID => $this->getInstanceId(),
            IResolvedEvent::FIELD__APPLICATION_ID => $this->getApplicationId()
        ];
        $params = $this->buildParams();
        $request = $params->hasOne(static::PARAM__REQUEST) ? $params->buildOne(static::PARAM__REQUEST)->getValue() : [];
        $headers = $params->hasOne(static::PARAM__HEADERS) ? $params->buildOne(static::PARAM__HEADERS)->getValue() : [];
        $json    = $params->hasOne(static::PARAM__JSON) ? $params->buildOne(static::PARAM__JSON)->getValue() : [];

        $data[IResolvedEvent::FIELD__PARAMS] = array_merge(
            $this->getHttpRequest($request)->getAll(), 
            $this->getHttpHeaders($headers)->getAll(), 
            $this->getHttpJson($json)->getAll()
        );

        return new ResolvedEvent($data);
    }

    public function resolveOperation(IResolvedEvent $resolvedEvent, ITrigger $trigger): IResolvedOperation
    {
        $operation = $trigger->buildOperation();
        $resolvedOperation = $this->createResolvedOperationObject($trigger);
        $resolvedOperation->addParam(new Param([
            Param::FIELD__NAME => ResolvedOperationHttp::PARAM__REQUEST_PARAMS,
            Param::FIELD__VALUE => $this->compileOperationParams($operation, $resolvedEvent)
        ]));

        return $resolvedOperation;
    }

    protected function createResolvedOperationObject(ITrigger $trigger): IResolvedOperation
    {
        $operation = $trigger->buildOperation();
        $instance = $trigger->getInstance(ETrigger::Operation);
        $options = $instance->buildOptions();
        
        $requestHeaders = [];
        foreach ($this->getPluginsByStage(IStageRequestHeaders::NAME) as $plugin) {
            $plugin($requestHeaders, $options);
        }

        $requestOptions = [];
        foreach ($this->getPluginsByStage(IStageRequestOptions::NAME) as $plugin) {
            $plugin($requestOptions, $options);
        }
        
        $resolvedOperation = new ResolvedOperationHttp([
            ResolvedOperationHttp::FIELD__METHOD => $this->getOperationMethod($options, $operation->getName()),
            ResolvedOperationHttp::FIELD__URL => $options->buildOne(static::OPTION__BASE_URL)->getValue() 
                                               . $options->buildOne($operation->getName() . static::OPTION__URL_PREFIX)->getValue(),
            ResolvedOperationHttp::FIELD__PARAMS => [
                ResolvedOperationHttp::PARAM__REQUEST_HEADERS => [
                    IParam::FIELD__NAME => ResolvedOperationHttp::PARAM__REQUEST_HEADERS,
                    IParam::FIELD__VALUE => $requestHeaders
                ],
                ResolvedOperationHttp::PARAM__REQUEST_OPTIONS => [
                    IParam::FIELD__NAME => ResolvedOperationHttp::PARAM__REQUEST_OPTIONS,
                    IParam::FIELD__VALUE => $requestOptions
                ]
            ]
        ]);

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
