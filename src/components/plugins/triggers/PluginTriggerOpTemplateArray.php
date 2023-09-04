<?php
namespace deflou\components\plugins\triggers;

use deflou\interfaces\stages\templates\IStageTemplate;
use deflou\interfaces\templates\IWithTemplate;
use deflou\interfaces\templates\contexts\IContext;
use extas\components\plugins\Plugin;

class PluginTriggerOpTemplateArray extends Plugin implements IStageTemplate
{
    public const CONTEXT__ARRAY = 'array';

    public function __invoke(array $templateData, IWithTemplate $plugin, mixed &$template, IContext $context): void
    {
        if (!empty($templateData)) {
            $template = [
                'plugin' => [
                    'name' => $plugin->getName(),
                    'title' => $plugin->getTitle(),
                    'description' => $plugin->getDescription(),
                ],
                'items' => $this->toArrayRecursive($templateData)
            ];
        }
    }

    protected function toArrayRecursive(array $item, array $result = []): array
    {
        foreach ($item as $name => $value) {
            if (is_array($value)) {
                $result[$name] = $this->toArrayRecursive($value, $result);
            } elseif (is_object($value)) {
                if (method_exists($value, '__toArray')) {
                    $result[$name] = $this->toArrayRecursive($value->__toArray(), $result);
                } else {
                    $result[$name] = '<object>';
                }
            } else {
                $result[$name] = $value;
            }
        }

        return $result;
    }
}
