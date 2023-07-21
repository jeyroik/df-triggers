# 3.0.0

- Unified events and operations values.
  - Removed `ITriggerOperationValue`
  - Removed `ITriggerEventValue`
  - Removed `ITriggerEventValuePlugin`
  - Removed `ITriggerOperationPlugin`
  - Removed `ITriggerEventValueService`
  - Removed `ITriggerOperationService`
  - Changed plugins namespace:
    - `PluginEvent`: deflou\components\triggers\operations\plugins -> deflou\components\triggers\values\plugins
    - `PluginNow`: deflou\components\triggers\operations\plugins -> deflou\components\triggers\values\plugins
    - `PluginText`: deflou\components\triggers\operations\plugins -> deflou\components\triggers\values\plugins
  - Renamed and changed namespace for `ValuePluginList` -> `PluginList`
    - Namespace: deflou\components\triggers\events\plugins -> deflou\components\triggers\values\plugins
  - Removed `IValueDescription`
  - Value consits from senses now, so there are can be several senses (for example, several conditions for value).
  - Plugins may be applied to event values too.
- Added edges for condition (to compile several conditions together, see tests for example):
  - `And`
  - `Or`
- Added `EConditionEdge`
- Changed `TemplateContext` namespace to values.

# 2.1.0

- Added operation plugin `text`.
- Added skipping plugin, if there is no template for it.

# 2.0.0

- Added `IHaveParams`, `IHaveInstance` to `IResolvedOperation`.
- Added `ResolvedOperation`.
- Added protected method `Resolver::compileOperationParams(ITriggerOperation $operation, IResolvedEvent $resolvedEvent): array`.
- Added stages
  - IStageRequestHeaders: you can easy add headers to a http request now.
  - IStageRequestOptions: you can easy add options to a http request now.
  - IStageRequestResponse: you can easy parse response from a http request now.

# 1.3.1

- Fixed getting active triggers.

# 1.3.0

- Added extension `IInstance::getActiveTriggers(ETrigger $et, IParametred $evOrOp): ITrigger[]`.

# 1.2.1

- Added `TriggerEmptyData` exception.
- Marked `TriggerEmptyEventData` as deprecated.

# 1.2.0

- Added method `ITriggerService::insertOperation(string $triggerId, array $opData): ITriggers`.

# 1.1.0

- Added `ITriggerService::insertOperationInstance(ITrigger &$trigger, IInstance $instance): bool`.

# 1.0.0

- Added template context.

# 0.7.1

- Added personal for trigger operation plugin stage.

# 0.7.0

- Added method `ITriggerOperationService::getPluginsTemplates(IInstance $eventInstance, ITrigger $trigger, string $context): array`.
- Added stage `IStageTriggerOpTemplate` (warning: use it only with context suffix, see extas.php for example).
- Added plugin `PluginTriggerOpTemplateArray` for an `array` context of `IStageTriggerOpTemplate`.
- Reduced code duplication by using `jeyroik/df-applications::IHaveApplicationName`.

# 0.6.0

- Added methods to `IExtensionTrigger`:
  - `stateIs(ETriggerState $state): bool`
  - `stateIsNot(ETriggerState $state): bool`
- Added method `ITriggerService::insertEvent(string $triggerId, array $eventData)`.

# 0.5.0

- Added `IHavePluygin`.

# 0.4.2

- Added package name.

# 0.4.1

- Added repos aliases.

# 0.4.0

- Added `IValueDescription`.
- Added simple value plugin.
- Updated tests.

# 0.3.1

- Turned hook `create-after` on.

# 0.3.0

- Added `IHaveTrigger`.

# 0.2.0

- Added `IExtensionTrigger`, this extension provides methods for `ITrigger`:
  - `toConstruct()`
  - `activate()`
  - `suspend()`
  - `delete()`
  - `resume()`

# 0.1.1

- Added this changelog.
- Fixed code smell in the `ResolvedOperationHttp`.

# 0.1.0

- Added `ITrigger` and other basic entities and value objects.
- Added `ResolverHttp`.
- Added operation plugins `event` and `now`.
- Added event condition plugin `basic_conditions`.