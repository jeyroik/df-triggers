<?php
namespace deflou\interfaces\triggers;

interface IHavePlugin
{
    public const FIELD__PLUGIN = 'plugin';

    public function getPlugin(): string;
    public function setPlugin(string $plugin): static;
}
