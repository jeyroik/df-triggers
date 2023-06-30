<?php
namespace deflou\interfaces\resolvers\events;

use extas\interfaces\collections\ICollection;

interface IEventHttp
{
    public const SUBJECT__REQUEST = 'http.request';
    public const SUBJECT__HEADERS = 'http.headers';
    public const SUBJECT__JSON = 'http.json';
    public const FIELD__SUBJECT = 'deflou_http_subject';

    public function getHttpRequest(array $request = []): ICollection;
    public function getHttpHeaders(array $headers = []): ICollection;
    public function getHttpJson(array $json = []): ICollection;
}
