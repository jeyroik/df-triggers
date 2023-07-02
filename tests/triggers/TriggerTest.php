<?php

use deflou\components\applications\Application;
use deflou\components\applications\AppWriter;
use deflou\components\applications\EStates;
use deflou\components\instances\InstanceService;
use deflou\components\resolvers\operations\ResolvedOperationHttp;
use deflou\components\resolvers\operations\results\EResultStatus;
use deflou\components\resolvers\ResolverHttp;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\events\conditions\ConditionService;
use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\events\plugins\ValuePluginList;
use deflou\components\triggers\events\TriggerEventValuePlugin;
use deflou\components\triggers\events\TriggerEventValueService;
use deflou\components\triggers\THasTrigger;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\applications\vendors\IVendor;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\IResolver;
use deflou\interfaces\resolvers\operations\results\IOperationResultData;
use deflou\interfaces\triggers\events\conditions\ICondition;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\events\ITriggerEventValue;
use deflou\interfaces\triggers\events\ITriggerEventValueService;
use deflou\interfaces\triggers\events\plugins\IValueDescription;
use deflou\interfaces\triggers\IHaveTrigger;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use deflou\interfaces\triggers\operations\ITriggerOperationValue;
use extas\components\Item;
use extas\components\parameters\Param;
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
        
        $trigger1->setApplicationId(ETrigger::Operation, $app->getId());
        $trigger1->setInstanceId(ETrigger::Operation, $instance->getId());
        $trigger1->setApplicationVersion(ETrigger::Operation, $app->getVersion());
        $trigger1->setInstanceVersion(ETrigger::Operation, $instance->getVersion());
        $trigger1->activate();

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
        $trigger2->activate();

        $trigger3 = $triggerService->createTriggerForInstance($instance, 'vendor2');
        $trigger3->activate();

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
                $this->assertTrue($result->isFailed());

                $data = $result->buildData();
                $this->assertInstanceOf(IOperationResultData::class, $data);

                $status = $result->buildStatus();
                $this->assertInstanceOf(EResultStatus::class, $status);

                $this->assertNotEmpty($result->getMessage());
            }
        }

        $this->assertEquals(1, $applicableCount);

        $trigger1->suspend();
        $trigger1 = $triggerService->triggers()->one([ITrigger::FIELD__ID => $trigger1->getId()]);
        $this->assertEquals(ETriggerState::Suspended->value, $trigger1->getState());

        $trigger1->delete();
        $trigger1 = $triggerService->triggers()->one([ITrigger::FIELD__ID => $trigger1->getId()]);
        $this->assertEquals(ETriggerState::Deleted->value, $trigger1->getState());

        $trigger1->resume();
        $trigger1 = $triggerService->triggers()->one([ITrigger::FIELD__ID => $trigger1->getId()]);
        $this->assertEquals(ETriggerState::Active->value, $trigger1->getState());

        $trigger1->toConstruct();
        $trigger1 = $triggerService->triggers()->one([ITrigger::FIELD__ID => $trigger1->getId()]);
        $this->assertEquals(ETriggerState::OnConstruct->value, $trigger1->getState());

        $tmp = new class extends Item implements IHaveTrigger {
            use THasTrigger;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $tmp->setTriggerId($trigger1->getId());
        $triggerX = $tmp->getTrigger();
        $this->assertInstanceOf(ITrigger::class, $triggerX);
        $this->assertEquals($triggerX->getId(), $trigger1->getId());

        $cService = new ConditionService();
        $descriptions = $cService->getDescriptions();
        $this->assertGreaterThan(5, $descriptions);

        /**
         * @var IConditionPlugin $cPlugin
         */
        $cPlugin = $cService->triggerEventConditionPlugins()->one([IConditionPlugin::FIELD__NAME => 'basic_conditions']);
        $cPlugin->addParam(new Param([
            Param::FIELD__NAME => ConditionBasic::PARAM__ITEMS,
            Param::FIELD__VALUE => ['eq', '!eq']
        ]));
        $cService->triggerEventConditionPlugins()->update($cPlugin);

        $descriptions = $cService->getDescriptions();
        $this->assertCount(2, $descriptions);

        foreach ($descriptions as $d) {
            $this->assertEquals('basic_conditions', $d->getPlugin());
            $d->setPlugin('test');
            $this->assertEquals('test', $d->getPlugin());
        }

        $valueService = new TriggerEventValueService();
        $valueService->triggerEventValuePlugins()->create(new TriggerEventValuePlugin([
            TriggerEventValuePlugin::FIELD__NAME => 'simple_list',
            TriggerEventValuePlugin::FIELD__APPLICATION_NAME => ITriggerEventValueService::ANY,
            TriggerEventValuePlugin::FIELD__CLASS => ValuePluginList::class,
            TriggerEventValuePlugin::FIELD__APPLY_TO => [ITriggerEventValueService::ANY],
            TriggerEventValuePlugin::FIELD__PARAMS => [
                ValuePluginList::PARAM__LIST => [
                    IParam::FIELD__NAME => ValuePluginList::PARAM__LIST,
                    IParam::FIELD__VALUE => [
                        [
                            IValueDescription::FIELD__NAME => 'test',
                            IValueDescription::FIELD__TITLE => 'test',
                            IValueDescription::FIELD__DESCRIPTION => 'test'
                        ]
                    ]
                ]
            ]
        ]));

        $values = $valueService->getValues($instance, 'test');
        $this->assertCount(1, $values);

        $value = array_shift($values);

        $this->assertInstanceOf(IValueDescription::class, $value);
    }

    protected function getAppJsonDecoded(): array
    {
        return empty($this->serviceConfig) 
            ? $this->serviceConfig = json_decode(file_get_contents(static::PATH__SERVICE_JSON), true)
            : $this->serviceConfig;
    }
}
