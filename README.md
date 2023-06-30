![tests](https://github.com/jeyroik/df-triggers/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/df-triggers/coverage.svg?branch=master)
<a href="https://codeclimate.com/github/jeyroik/df-triggers/maintainability"><img src="https://api.codeclimate.com/v1/badges/28f620fc92a3dfdb3d20/maintainability" /></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/df-triggers/v)](//packagist.org/packages/jeyroik/df-triggers)
[![Total Downloads](https://poser.pugx.org/jeyroik/df-triggers/downloads)](//packagist.org/packages/jeyroik/df-triggers)
[![Dependents](https://poser.pugx.org/jeyroik/df-triggers/dependents)](//packagist.org/packages/jeyroik/df-triggers)


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
