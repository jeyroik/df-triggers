<?php
namespace deflou\interfaces\resolvers\operations\results;

use deflou\components\resolvers\operations\results\EResultStatus;
use extas\interfaces\IItem;

interface IOperationResult extends IItem
{
    public const SUBJECT = 'deflou.operation.result';

    public const FIELD__STATUS = 'status';
    public const FIELD__MESSAGE = 'message';
    public const FIELD__DATA = 'data';

    public const STATUS__SUCCESS = 'success';
    public const STATUS__FAILED = 'failed';

    public function getStatus(): string;
    public function buildStatus(): EResultStatus;
    public function isSuccess(): bool;
    public function isFailed(): bool;

    public function getMessage(): string;

    public function getData(): array;
    public function buildData(): IOperationResultData;

    public function setStatus(EResultStatus $status): static;
    public function setMessage(string $message): static;
    public function setData(array $data): static;
}
