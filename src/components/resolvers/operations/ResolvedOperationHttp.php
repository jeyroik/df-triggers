<?php
namespace deflou\components\resolvers\operations;

use deflou\components\instances\THasInstance;
use deflou\components\resolvers\operations\results\EResultStatus;
use deflou\components\resolvers\operations\results\OperationResult;
use deflou\interfaces\instances\IHaveInstance;
use deflou\interfaces\resolvers\operations\IResolvedOperation;
use deflou\interfaces\resolvers\operations\results\IOperationResult;
use extas\components\Item;
use extas\components\parameters\THasParams;
use extas\interfaces\parameters\IHaveParams;
use Psr\Http\Message\ResponseInterface;

class ResolvedOperationHttp extends Item implements IResolvedOperation, IHaveParams, IHaveInstance
{
    use TOperationHttp;
    use THasParams;
    use THasInstance;

    public const FIELD__METHOD = 'method';
    public const FIELD__URL = 'url';

    public function run(): IOperationResult
    {
        $method = $this->getMethod();
        if (method_exists($this, $method)) {
            /**
             * @var ResponseInterface $response
             */
            try {
                $response = $this->$method($this->getUrl(), $this->getParamsValues());
            } catch (\Exception $e) {
                return $this->makeFailedResult($e->getMessage());
            }
            $body = $response->getBody() . '';
            $data = [];
            $instanceName = $this->getInstance()->getName();

            foreach ($this->getPluginsByStage('http.operation.resolve.body.' . $instanceName) as $plugin) {
                $plugin($body, $data);
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
