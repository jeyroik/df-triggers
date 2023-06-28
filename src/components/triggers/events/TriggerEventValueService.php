<?php
namespace deflou\components\triggers\events;

use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\ITriggerEventValuePlugin;
use deflou\interfaces\triggers\events\ITriggerEventValueService;
use extas\components\Item;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository triggerEventValuePlugins()
 */
class TriggerEventValueService extends Item implements ITriggerEventValueService
{
    public function getValues(IInstance $instance): array
    {
        /**
         * @var ITriggerEventValuePlugin[] $plugins
         */
        $plugins = $this->triggerEventValuePlugins()->all([
            ITriggerEventValuePlugin::FIELD__APPLICATION_NAME => [$instance->getApplication()->getName(), static::APPLICATION__ANY]
        ]);
        $result = [];

        foreach ($plugins as $plugin) {
            $dispatcher = $plugin->buildClassWithParameters();
            $result = array_merge($result, $dispatcher($instance));
        }

        return $result;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
