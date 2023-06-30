# df-trigger

DF trigger package

# Usage

```php
// get income request with specified instance id and event name
// get instance by instance id
// @var IInstance $instance
use deflou\components\triggers\TriggerService;

$resolvedEvent = $instance->buildResolver()->resolveEvent();
$triggerService = new TriggerService();

$triggers = $triggerService->getTriggers($instance->getId(), $eventName, ['vendorName1', 'vendorName2, ...']);

foreach ($triggers as $trigger) {
    if ($triggerService->isApplicableTrigger($resolvedEvent, $trigger)) {
        $result = $trigger->getInstance(ETrigger::Operation)->buildResolver()->resolveOperation($resolvedEvent, $trigger)->run();

        if ($result->isSuccess()) {
            echo 'Success trigger #' . $trigger->getId() . ' execution';
        } else {
            echo 'Failed trigger #' . $trigger->getId() . ' execution';
        }
    }
}
```
