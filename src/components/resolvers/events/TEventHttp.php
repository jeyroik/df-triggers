<?php
namespace deflou\components\resolvers\events;

use deflou\interfaces\resolvers\events\IEventHttp;
use extas\components\collections\TBuildAll;
use extas\components\collections\TCollection;
use extas\components\Item;
use extas\components\parameters\Param;
use extas\interfaces\collections\ICollection;
use extas\interfaces\parameters\IParam;

trait TEventHttp
{
    public function getHttpRequest(array $request = []): ICollection
    {
        return $this->getHttpCollection($request ?: $_REQUEST, IEventHttp::SUBJECT__REQUEST);
    }

    public function getHttpHeaders(array $headers = []): ICollection
    {
        return $this->getHttpCollection($headers ?: getallheaders(), IEventHttp::SUBJECT__HEADERS);
    }

    public function getHttpJson(array $json = []): ICollection
    {
        if (empty($json)) {
            $parsed = json_decode(file_get_contents('php://input'), true);
            if ($parsed) {
                $json = $parsed;
            }
        }

        return $this->getHttpCollection($json, IEventHttp::SUBJECT__JSON);
    }

    protected function getHttpCollection(array $source, string $subject): ICollection
    {
        $data = [
            IEventHttp::FIELD__SUBJECT => [
                IParam::FIELD__NAME => IEventHttp::FIELD__SUBJECT, 
                IParam::FIELD__VALUE => $subject
            ]
        ];

        foreach ($source as $name => $value) {
            $data[$name] = [
                IParam::FIELD__NAME => $name,
                IParam::FIELD__VALUE => $value
            ];
        }

        return new class ($data) extends Item implements ICollection {

            use TCollection;
            use TBuildAll;

            public function buildOne(string $name, bool $errorIfMissed = false): IParam
            {
                $this->hasOne($name, $errorIfMissed);

                return new Param($this->getOne($name));
            }

            protected function getSubjectForExtension(): string
            {
                return $this->buildOne(IEventHttp::FIELD__SUBJECT)->getValue();
            }
        };
    }
}
