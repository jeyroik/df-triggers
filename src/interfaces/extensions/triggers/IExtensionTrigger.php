<?php
namespace deflou\interfaces\extensions\triggers;

interface IExtensionTrigger
{
    public function activate(): bool;
    public function suspend(): bool;
    public function delete(): bool;
    public function resume(): bool;
    public function toConstruct(): bool;
}
