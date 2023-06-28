<?php
namespace deflou\components\resolvers\operations\results;

use deflou\interfaces\resolvers\operations\results\IOperationResult;
use deflou\interfaces\resolvers\operations\results\IOperationResultData;
use extas\components\Item;

class OperationResult extends Item implements IOperationResult
{
    public function getStatus(): string
    {
        return $this->config[static::FIELD__STATUS] ?? '';
    }

    public function buildStatus(): EResultStatus
    {
        return EResultStatus::from($this->getStatus());
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === EResultStatus::Sucess;
    }

    public function isFailed(): bool
    {
        return $this->getStatus() === EResultStatus::Failed;
    }

    public function getMessage(): string
    {
        return $this->config[static::FIELD__MESSAGE] ?? '';
    }

    public function getData(): array
    {
        return $this->config[static::FIELD__DATA] ?? [];
    }

    public function buildData(): IOperationResultData
    {
        return new OperationResultData($this->getData());
    }

    public function setStatus(EResultStatus $status): static
    {
        $this->config[static::FIELD__STATUS] = $status->value;

        return $this;
    }
    public function setMessage(string $message): static
    {
        $this->config[static::FIELD__MESSAGE] = $message;

        return $this;
    }

    public function setData(array $data): static
    {
        $this->config[static::FIELD__DATA] = $data;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
