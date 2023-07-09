<?php
namespace deflou\interfaces\resolvers\operations;

use deflou\interfaces\resolvers\operations\IResolvedOperation;

interface IResolvedOperationHttp extends IResolvedOperation, IOperationHttp
{
    public const FIELD__METHOD = 'method';
    public const FIELD__URL = 'url';

    public const PARAM__REQUEST_PARAMS = 'request__params';
    public const PARAM__REQUEST_HEADERS = 'request__headers';
    public const PARAM__REQUEST_OPTIONS = 'request__options';

    public function getMethod(): string;
    public function getUrl(): string;
}
