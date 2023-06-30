<?php

use deflou\components\applications\Application;
use deflou\components\applications\AppWriter;
use deflou\components\applications\EStates;
use deflou\components\instances\InstanceService;
use deflou\components\resolvers\operations\ResolvedOperationHttp;
use deflou\components\resolvers\ResolverHttp;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\applications\vendors\IVendor;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\IResolver;
use deflou\interfaces\triggers\events\conditions\ICondition;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\events\ITriggerEventValue;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use deflou\interfaces\triggers\operations\ITriggerOperationValue;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParametred;
use tests\ExtasTestCase;

/**
 * Class TriggerTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class TriggerTest extends ExtasTestCase
{
    public const PATH__SERVICE_JSON = __DIR__ . '/../resources/service.json';
    public const PATH__SERVICE_JSON_2 = __DIR__ . '/../resources/service.2.json';
    public const PATH__INSTALL = __DIR__ . '/../tmp';
    protected array $serviceConfig = [];

    protected array $libsToInstall = [
        'jeyroik/df-applications' => ['php', 'json'],
        'jeyroik/extas-conditions' => ['php', 'json'],
        '' => ['php', 'php'] // own
    ];
    protected bool $isNeedInstallLibsItems = true;
    protected string $testPath = __DIR__;

    public function testFlow()
    {
        /**
         * 1. Создать инстанс с резолвером http
         * 2. Создать триггер с вендором "тест"
         * 3. Создать триггер с вендором "тест", но с неподходящими параметрами события
         * 4. Создать триггер с вендором "тест2"
         * 5. Получить все триггеры для вендора "тест" для инстанса -> должно быть 2 триггера
         * 6. Отфильтровать триггеры по параметрам события -> должен остаться только 1 триггер
         * 7. Запустить операции по оставшемуся триггеру
         */

        $triggerService = new TriggerService();
        $appWriter = new AppWriter();
        $appWriter->createAppByConfigPath(__DIR__ . '/../resources/app.json');

        /**
         * @var IApplication $app
         */
        $app = $triggerService->applications()->one([IApplication::FIELD__NAME => 'test']);
        $instanceService = new InstanceService();
        $instance = $instanceService->createInstanceFromApplication($app, 'vendor0');
        

        $trigger1 = $triggerService->createTriggerForInstance($instance, 'vendor0');
        $this->assertInstanceOf(ITrigger::class, $trigger1);
        $this->assertEquals($app->getId(), $trigger1->getApplicationId(ETrigger::Event));
        $this->assertEquals($app->getId(), $trigger1->getApplication(ETrigger::Event)->getId());
        $this->assertEquals($instance->getId(), $trigger1->getInstanceId(ETrigger::Event));
        $this->assertEquals($instance->getId(), $trigger1->getInstance(ETrigger::Event)->getId());
        $this->assertEquals($app->getVersion(), $trigger1->getApplicationVersion(ETrigger::Event));
        $this->assertEquals($instance->getVersion(), $trigger1->getInstanceVersion(ETrigger::Event));

        $eventData = [
            ITriggerEvent::FIELD__NAME => 'test_event',
            ITriggerEvent::FIELD__PARAMS => [
                'param1' => [
                    IParam::FIELD__NAME => 'param1',
                    IParam::FIELD__VALUE => [
                        ITriggerEventValue::FIELD__VALUE => 5,
                        ITriggerEventValue::FIELD__CONDITION => [
                            ICondition::FIELD__PLUGIN => 'basic_conditions',
                            ICondition::FIELD__CONDITION => 'eq'
                        ]
                    ]
                ]
            ]
        ];
        $trigger1->setEvent($eventData);
        $this->assertEquals($eventData, $trigger1->getEvent());

        $opData = [
            ITriggerOperation::FIELD__NAME => 'test_operation',
            ITriggerOperation::FIELD__PARAMS => [
                'param2' => [
                    IParam::FIELD__NAME => 'param2',
                    IParam::FIELD__VALUE => [
                        ITriggerOperationValue::FIELD__PLUGINS => ['event', 'now'],
                        ITriggerOperationValue::FIELD__VALUE => 'Got @event.param1 as param1 from event at @now(Y-m-d)@'
                    ]
                ]
            ]
        ];
        $trigger1->setOperation($opData);
        $this->assertEquals($opData, $trigger1->getOperation());
        ETriggerState::Active->activate($trigger1);
        
        $trigger1->setApplicationId(ETrigger::Operation, $app->getId());
        $trigger1->setInstanceId(ETrigger::Operation, $instance->getId());
        $trigger1->setApplicationVersion(ETrigger::Operation, $app->getVersion());
        $trigger1->setInstanceVersion(ETrigger::Operation, $instance->getVersion());
        $triggerService->triggers()->update($trigger1);

        $trigger2 = $triggerService->createTriggerForInstance($instance, 'vendor0');
        $trigger2->setEvent([
            ITriggerEvent::FIELD__NAME => 'test_event',
            ITriggerEvent::FIELD__PARAMS => [
                'param1' => [
                    IParam::FIELD__NAME => 'param1',
                    IParam::FIELD__VALUE => [
                        ITriggerEventValue::FIELD__VALUE => 5,
                        ITriggerEventValue::FIELD__CONDITION => [
                            ICondition::FIELD__PLUGIN => 'basic_conditions',
                            ICondition::FIELD__CONDITION => '!eq'
                        ]
                    ]
                ]
            ]
        ]);
        ETriggerState::Active->activate($trigger2);
        $triggerService->triggers()->update($trigger2);

        $trigger3 = $triggerService->createTriggerForInstance($instance, 'vendor2');
        ETriggerState::Active->activate($trigger3);
        $triggerService->triggers()->update($trigger3);

        $triggers = $triggerService->getActiveTriggers($instance->getId(), 'test_event', ['vendor0']);
        $this->assertCount(2, $triggers);

        /**
         * @var IInstance|IExtensionInstanceResolver $instance
         */
        $resolver = $instance->buildResolver('test_event', [
            ResolverHttp::PARAM__REQUEST => [
                IParam::FIELD__NAME => ResolverHttp::PARAM__REQUEST,
                IParam::FIELD__VALUE => [
                    'param1' => 5
                ]
            ]
        ]);
        $this->assertInstanceOf(IResolver::class, $resolver);
        $this->assertEquals('test_event', $resolver->getEventName());

        $resolvedEvent = $resolver->resolveEvent();
        $this->assertInstanceOf(IResolvedEvent::class, $resolvedEvent);
        $applicableCount = 0;
        
        foreach ($triggers as $trigger) {
            if ($triggerService->isApplicableTrigger($resolvedEvent, $trigger)) {
                $applicableCount++;

                /**
                 * @var IInstance|IExtensionInstanceResolver $opInstance
                 */
                $opInstance = $trigger->getInstance(ETrigger::Operation);

                /**
                 * @var ResolvedOperationHttp $resolvedOp
                 */
                $resolvedOp = $opInstance->buildResolver('test_event', [])->resolveOperation($resolvedEvent, $trigger);
                
                $this->assertInstanceOf(ResolvedOperationHttp::class, $resolvedOp);
                $this->assertEquals('Got 5 as param1 from event at ' . date('Y-m-d'), $resolvedOp->buildParams()->buildOne('param2')->getValue());

                $result = $resolvedOp->run();
                $this->assertFalse($result->isSuccess());
            }
        }

        $this->assertEquals(1, $applicableCount);
    }

    protected function getAppJsonDecoded(): array
    {
        return empty($this->serviceConfig) 
            ? $this->serviceConfig = json_decode(file_get_contents(static::PATH__SERVICE_JSON), true)
            : $this->serviceConfig;
    }
}
