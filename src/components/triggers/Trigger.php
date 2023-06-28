<?php
namespace deflou\components\triggers;

use deflou\components\applications\vendors\THasVendor;
use deflou\components\triggers\events\TriggerEvent;
use deflou\components\triggers\operations\TriggerOperation;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use extas\components\Item;
use extas\components\THasCreatedAt;
use extas\components\THasDescription;
use extas\components\THasState;
use extas\components\THasStringId;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository applications()
 * @method IRepository instances()
 */
class Trigger extends Item implements ITrigger
{
    use THasStringId;
    use THasDescription;
    use THasState;
    use THasCreatedAt;
    use THasVendor;

    public function getEvent(): array
    {
        return $this->config[static::FIELD__EVENT] ?? [];
    }

    public function buildEvent(): ITriggerEvent
    {
        return new TriggerEvent($this->getEvent());
    }

    public function getOperation(): array
    {
        return $this->config[static::FIELD__OPERATION] ?? [];
    }

    public function buildOperation(): ITriggerOperation
    {
        return new TriggerOperation($this->getOperation());
    }

    public function getApplicationId(ETrigger $et): string
    {
        return $this->config[$et->getAppIdField()] ?? '';
    }

    public function getApplication(ETrigger $et): ?IApplication
    {
        return $this->applications()->one([
            IApplication::FIELD__ID => $this->getApplicationId($et)
        ]);
    }

    public function getApplicationVersion(ETrigger $et): string
    {
        return $this->config[$et->getAppVerField()] ?? '';
    }

    public function getInstanceId(ETrigger $et): string
    {
        return $this->config[$et->getInstIdField()] ?? '';
    }

    public function getInstance(ETrigger $et): ?IInstance
    {
        return $this->instances()->one([
            IInstance::FIELD__ID => $this->getInstanceId($et)
        ]);
    }

    public function getInstanceVersion(ETrigger $et): string
    {
        return $this->config[$et->getInstVerField()] ?? '';
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
