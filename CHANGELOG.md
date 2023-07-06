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
- Added method `insertEvent(string $triggerId, array $eventData)` to `ITriggerService`.

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