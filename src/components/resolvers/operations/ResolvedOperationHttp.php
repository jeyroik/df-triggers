<?php
namespace deflou\components\resolvers\operations;

use deflou\components\resolvers\operations\results\EResultStatus;
use deflou\components\resolvers\operations\results\OperationResult;
use deflou\interfaces\resolvers\operations\IResolvedOperationHttp;
use deflou\interfaces\resolvers\operations\results\IOperationResult;
use deflou\interfaces\stages\resolvers\http\IStageRequestResponse;
use Psr\Http\Message\ResponseInterface;

class ResolvedOperationHttp extends ResolvedOperation implements IResolvedOperationHttp
{
    use TOperationHttp;

    public function run(): IOperationResult
    {
        $method = $this->getMethod();
        if (method_exists($this, $method)) {
            /**
             * @var ResponseInterface $response
             */
            try {
                $response = $this->sendRequest($method);
            } catch (\Exception $e) {
                return $this->makeFailedResult($e->getMessage());
            }
            $data = [];
            $instanceName = $this->getInstance()->getName();

            foreach ($this->getPluginsByStage(IStageRequestResponse::NAME . $instanceName) as $plugin) {
                $plugin($response, $data);
            }

            return new OperationResult([
                OperationResult::FIELD__STATUS => $response->getStatusCode(),
                OperationResult::FIELD__MESSAGE => 'success',
                OperationResult::FIELD__DATA => $data
            ]);
        }

        return $this->makeFailedResult('failed');
    }

    public function getMethod(): string
    {
        return $this->config[static::FIELD__METHOD] ?? '';
    }

    public function getUrl(): string
    {
        return $this->config[static::FIELD__URL] ?? '';
    }

    protected function sendRequest(string $method): ResponseInterface
    {
        $opParams = $this->buildParams();
        $requestParams = $opParams->buildOne(static::PARAM__REQUEST_PARAMS)->getValue();
        $requestHeaders = $opParams->buildOne(static::PARAM__REQUEST_HEADERS)->getValue();
        $requestOptions = $opParams->buildOne(static::PARAM__REQUEST_OPTIONS)->getValue();

        return $this->$method($this->getUrl(), $requestParams, $requestHeaders, $requestOptions);
    }

    protected function makeFailedResult(string $message): IOperationResult
    {
        return new OperationResult([
            OperationResult::FIELD__STATUS => EResultStatus::Failed->value,
            OperationResult::FIELD__MESSAGE => $message,
            OperationResult::FIELD__DATA => []
        ]);
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
