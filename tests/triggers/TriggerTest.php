<?php

use deflou\components\applications\AppWriter;
use deflou\components\instances\InstanceService;
use deflou\components\plugins\triggers\PluginTriggerOpTemplateArray;
use deflou\components\resolvers\operations\ResolvedOperationHttp;
use deflou\components\resolvers\operations\results\EResultStatus;
use deflou\components\resolvers\ResolverHttp;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\events\conditions\Condition;
use deflou\components\triggers\events\conditions\ConditionService;
use deflou\components\triggers\events\conditions\EConditionEdge;
use deflou\components\triggers\events\conditions\plugins\ConditionBasic;
use deflou\components\triggers\THasTrigger;
use deflou\components\triggers\Trigger;
use deflou\components\triggers\TriggerService;
use deflou\components\triggers\values\plugins\ValuePlugin;
use deflou\components\triggers\values\ValueSense;
use deflou\components\triggers\values\ValueService;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\extensions\instances\IExtensionInstanceResolver;
use deflou\interfaces\extensions\instances\IExtensionInstanceTriggers;
use deflou\interfaces\extensions\triggers\IExtensionTriggerEventValue;
use deflou\interfaces\instances\IInstance;
use deflou\interfaces\resolvers\events\IResolvedEvent;
use deflou\interfaces\resolvers\IResolver;
use deflou\interfaces\resolvers\operations\IResolvedOperationHttp;
use deflou\interfaces\resolvers\operations\results\IOperationResultData;
use deflou\interfaces\triggers\events\conditions\IConditionPlugin;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\IHaveTrigger;
use deflou\interfaces\triggers\ITrigger;
use deflou\interfaces\triggers\operations\ITriggerOperation;
use deflou\interfaces\triggers\values\IValueSense;
use deflou\components\templates\contexts\ContextAny;
use deflou\components\templates\contexts\ContextTrigger;
use deflou\interfaces\stages\templates\IStageTemplate;
use deflou\interfaces\templates\contexts\IContextTrigger;
use extas\components\Item;
use extas\components\parameters\Param;
use extas\components\plugins\Plugin;
use extas\interfaces\parameters\IParam;
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

        /**
         * @var IExtensionInstanceTriggers|IInstance $instance
         */
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
                        [
                            IValueSense::FIELD__VALUE => 5,
                            IValueSense::FIELD__PLUGINS_NAMES => ['text'],
                            IValueSense::FIELD__PARAMS => [
                                'basic_conditions' => [
                                    IParam::FIELD__NAME => 'basic_conditions',
                                    IParam::FIELD__VALUE => 'like'
                                ],
                                IExtensionTriggerEventValue::PARAM__EDGE => [
                                    IParam::FIELD__NAME => IExtensionTriggerEventValue::PARAM__EDGE,
                                    IParam::FIELD__VALUE => EConditionEdge::And->value
                                ]
                            ]
                        ],
                        [
                            IValueSense::FIELD__VALUE => 50,
                            IValueSense::FIELD__PLUGINS_NAMES => ['text'],
                            IValueSense::FIELD__PARAMS => [
                                'basic_conditions' => [
                                    IParam::FIELD__NAME => 'basic_conditions',
                                    IParam::FIELD__VALUE => '!eq'
                                ],
                                IExtensionTriggerEventValue::PARAM__EDGE => [
                                    IParam::FIELD__NAME => IExtensionTriggerEventValue::PARAM__EDGE,
                                    IParam::FIELD__VALUE => EConditionEdge::Or->value
                                ]
                            ]
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
                        [
                            IValueSense::FIELD__PLUGINS_NAMES => ['text', 'event', 'now'],
                            IValueSense::FIELD__VALUE => 'Got @event.param1 as param1 from event at @now(Y-m-d)@'
                        ]
                    ]
                ]
            ]
        ];
        $trigger1->setOperation($opData);
        $this->assertEquals($opData, $trigger1->getOperation());
        
        $inserted = $triggerService->insertOperationInstance($trigger1, $instance);
        $this->assertTrue($inserted);
        
        $trigger1->activate();

        $this->assertCount(1, $instance->getActiveTriggers(ETrigger::Event, $instance->buildEvents()->buildOne($trigger1->buildEvent()->getName())));

        $trigger2 = $triggerService->createTriggerForInstance($instance, 'vendor0');
        $trigger2->setEvent([
            ITriggerEvent::FIELD__NAME => 'test_event',
            ITriggerEvent::FIELD__PARAMS => [
                'param1' => [
                    IParam::FIELD__NAME => 'param1',
                    IParam::FIELD__VALUE => [
                        [
                            IValueSense::FIELD__VALUE => 5,
                            IValueSense::FIELD__PLUGINS_NAMES => ['text'],
                            IValueSense::FIELD__PARAMS => [
                                'basic_conditions' => [
                                    IParam::FIELD__NAME => 'basic_conditions',
                                    IParam::FIELD__VALUE => '!like'
                                ],
                                IExtensionTriggerEventValue::PARAM__EDGE => [
                                    IParam::FIELD__NAME => IExtensionTriggerEventValue::PARAM__EDGE,
                                    IParam::FIELD__VALUE => EConditionEdge::And->value
                                ]
                            ]
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

                $params = $resolvedOp->buildParams();
                $this->assertTrue($params->hasOne(IResolvedOperationHttp::PARAM__REQUEST_PARAMS));
                $this->assertTrue($params->hasOne(IResolvedOperationHttp::PARAM__REQUEST_HEADERS));
                $this->assertTrue($params->hasOne(IResolvedOperationHttp::PARAM__REQUEST_OPTIONS));

                $requestParams = $params->buildOne(IResolvedOperationHttp::PARAM__REQUEST_PARAMS)->getValue();
                $this->assertEquals('Got 5 as param1 from event at ' . date('Y-m-d'), $requestParams['param2']);

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
        $this->assertTrue($trigger1->stateIs(ETriggerState::Deleted));

        $trigger1->resume();
        $trigger1 = $triggerService->triggers()->one([ITrigger::FIELD__ID => $trigger1->getId()]);
        $this->assertTrue($trigger1->stateIsNot(ETriggerState::Deleted));
        $this->assertTrue($trigger1->stateIs(ETriggerState::Active));

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

        $descriptions = $cService->getPluginsTemplates(new ContextTrigger([
            ContextTrigger::FIELD__NAME => PluginTriggerOpTemplateArray::CONTEXT__ARRAY
        ]));

        $this->assertCount(1, $descriptions);
        $this->assertArrayHasKey('basic_conditions', $descriptions);

        $externalData = [
            ITriggerEvent::FIELD__NAME => 'test_event',
            ITriggerEvent::FIELD__PARAMS => [
                'some' => [
                    IParam::FIELD__VALUE => [
                        [
                            IValueSense::FIELD__VALUE => 'ok',
                            IValueSense::FIELD__PLUGINS_NAMES => ['text'],
                            IValueSense::FIELD__PARAMS => [
                                'basic_conditions' => [
                                    IParam::FIELD__NAME => 'basic_conditions',
                                    IParam::FIELD__VALUE => 'eq'
                                ],
                                IExtensionTriggerEventValue::PARAM__EDGE => [
                                    IParam::FIELD__NAME => IExtensionTriggerEventValue::PARAM__EDGE,
                                    IParam::FIELD__VALUE => EConditionEdge::And->value
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $trigger = $triggerService->insertEvent($trigger1->getId(), $externalData);
        $event = $trigger->buildEvent();
        $this->assertEquals('Test event', $event->getTitle());
        $this->assertEquals('This is test event', $event->getDescription());

        $param = $event->buildParams()->buildOne('some');
        $this->assertEquals('Some', $param->getTitle());
        $this->assertEquals('Some param', $param->getDescription());

        $externalData = [
            ITriggerOperation::FIELD__NAME => 'test_operation',
            ITriggerOperation::FIELD__PARAMS => [
                'some' => [
                    IParam::FIELD__VALUE => [
                        [
                            IValueSense::FIELD__PLUGINS_NAMES => ['text', 'event', 'now'],
                            IValueSense::FIELD__VALUE => 'ok @event.some on @now(Y.m.d)@'
                        ]
                    ]
                ]
            ]
        ];
        $trigger = $triggerService->insertOperation($trigger->getId(), $externalData);
        $op = $trigger->buildOperation();
        $this->assertEquals('Test operation', $op->getTitle());
        $this->assertEquals('This is test operation', $op->getDescription());

        $param = $op->buildParams()->buildOne('some');
        $this->assertEquals('Some', $param->getTitle());
        $this->assertEquals('Some param', $param->getDescription());

        $vService = new ValueService();
        $vService->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginTriggerOpTemplateArray::class,
            Plugin::FIELD__STAGE => IStageTemplate::NAME . PluginTriggerOpTemplateArray::CONTEXT__ARRAY . '.event'
        ]));
        $vService->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginTriggerOpTemplateArray::class,
            Plugin::FIELD__STAGE => IStageTemplate::NAME . PluginTriggerOpTemplateArray::CONTEXT__ARRAY . '.now'
        ]));

        $templates = $vService->getPluginsTemplates( 
            new ContextAny([
                ContextAny::FIELD__NAME => PluginTriggerOpTemplateArray::CONTEXT__ARRAY,
                ContextAny::FIELD__PARAMS => [
                    IContextTrigger::PARAM__TRIGGER => [
                        IParam::FIELD__NAME => IContextTrigger::PARAM__TRIGGER,
                        IParam::FIELD__VALUE => $trigger
                    ],
                    IContextTrigger::PARAM__FOR => [
                        IParam::FIELD__NAME => IContextTrigger::PARAM__FOR,
                        IParam::FIELD__VALUE => ETrigger::Event
                    ]
                ]
            ])
        );
        $this->assertCount(2, $templates);
        foreach ($templates as $template) {
            $this->assertIsArray($template);
            $this->assertArrayHasKey('plugin', $template);
            $this->assertArrayHasKey('name', $template['plugin']);
            $this->assertArrayHasKey('title', $template['plugin']);
            $this->assertArrayHasKey('description', $template['plugin']);
            $this->assertArrayHasKey('items', $template);
            $this->assertIsMissedObjects($template['items']);
        }
    }

    public function testBasics(): void
    {
        $vp = new ValuePlugin();
        $vp->setApplyToParams(['test']);
        $this->assertEquals(['test'], $vp->getApplyToParams());

        $vs = new ValueSense([
            ValueSense::FIELD__PLUGINS_NAMES => ['test']
        ]);
        $vs->addPluginsNames('test0', 'test1');
        $this->assertEquals(['test', 'test0', 'test1'], $vs->getPluginsNames());

        $state = ETriggerState::Active;
        $trigger = new Trigger();
        $state->activate($trigger);
        $this->assertEquals(ETriggerState::Active->value, $trigger->getState());

        ETriggerState::Suspended->set($trigger);
        $this->assertEquals(ETriggerState::Suspended->value, $trigger->getState());
        $this->assertEquals('Остановлен', ETriggerState::Suspended->title('unknown lang'));
        
        ETriggerState::Deleted->delete($trigger);
        $this->assertEquals(ETriggerState::Deleted->value, $trigger->getState());

        ETriggerState::Suspended->suspend($trigger);
        $this->assertEquals(ETriggerState::Suspended->value, $trigger->getState());

        $this->assertEquals('И', EConditionEdge::And->to(EConditionEdge::LANG__RU));

        $cond = new Condition();
        $cond->setPlugin('test')->setCondition('eq');
        $this->assertEquals([Condition::FIELD__PLUGIN => 'test', Condition::FIELD__CONDITION => 'eq'], $cond->__toArray());
    }

    protected function assertIsMissedObjects(array $item, string $message = ''): bool
    {
        foreach ($item as $value) {
            if (is_object($value)) {
                throw new \Exception($message ?: 'Found object in an array: ' . print_r($item, true));
            } elseif (is_array($value)) {
                $this->assertIsMissedObjects($value);
            }
        }

        return true;
    }

    protected function getAppJsonDecoded(): array
    {
        return empty($this->serviceConfig) 
            ? $this->serviceConfig = json_decode(file_get_contents(static::PATH__SERVICE_JSON), true)
            : $this->serviceConfig;
    }
}
