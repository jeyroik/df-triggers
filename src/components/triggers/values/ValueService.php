<?php
namespace deflou\components\triggers\values;

use deflou\components\templates\TemplateService;
use deflou\interfaces\triggers\values\IValueSense;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\templates\contexts\IContext;
use deflou\interfaces\triggers\values\IValueService;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;

use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerValuePlugins()
 */
class ValueService extends Item implements IValueService
{
    protected static $plugins = [];

    public function applyPlugins(IValueSense $sense, IResolvedEvent $event): static
    {
        $builtPlugins = $this->buildPlugins($sense->getPluginsNames());
        $value = $sense->getValue();

        foreach ($builtPlugins as $plugin) {
            $value = $plugin($value, $event);
        }

        $sense->setValue($value);

        return $this;
    }

    public function getPluginsTemplates(IContext $context): array
    {
        return (new TemplateService())->getTemplates($this->triggerValuePlugins(), $context);
    }

    protected function buildPlugin(string $name): ?IValuePlugin
    {
        if (!isset(self::$plugins[$name])) {
            self::$plugins[$name] = $this->triggerValuePlugins()->one([IValuePlugin::FIELD__NAME => $name]);
        }

        return self::$plugins[$name];
    }

    protected function buildPlugins(array $plugins): array
    {
        $builtPlugins = [];

        foreach ($plugins as $name) {
            $builtPlugins[$name] = $this->buildPlugin($name);
        }

        return $builtPlugins;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
