<?php
namespace deflou\components\triggers;

use deflou\interfaces\triggers\IHavePlugin;

/**
 * @property array $config
 */
trait THasPlugin
{
    public function getPlugin(): string
    {
        return $this->config[IHavePlugin::FIELD__PLUGIN] ?? '';
    }

    public function setPlugin(string $plugin): static
    {
        $this[IHavePlugin::FIELD__PLUGIN] = $plugin;

        return $this;
    }
}
