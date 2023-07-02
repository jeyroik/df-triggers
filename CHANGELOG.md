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