<?php
namespace deflou\components\plugins\triggers;

use deflou\interfaces\stages\triggers\IStageTriggerTemplate;
use deflou\interfaces\triggers\values\plugins\IValuePlugin;
use deflou\interfaces\triggers\values\plugins\templates\ITemplateContext;
use extas\components\plugins\Plugin;

class PluginTriggerOpTemplateArray extends Plugin implements IStageTriggerTemplate
{
    public const CONTEXT__ARRAY = 'array';

    public function __invoke(array $templateData, IValuePlugin $plugin, mixed &$template, ITemplateContext $context): void
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
