<?php

use deflou\components\applications\Application;
use deflou\components\applications\EStates;
use deflou\components\extensions\instanes\ExtensionInstanceResolver;
use deflou\components\instances\InstanceService;
use deflou\components\resolvers\operations\ResolvedOperationHttp;
use deflou\components\resolvers\ResolverHttp;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\ETriggerState;
use deflou\components\triggers\Trigger;
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
use extas\components\extensions\Extension;
use extas\components\repositories\RepoItem;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParametred;
use \PHPUnit\Framework\TestCase;

/**
 * Class TriggerTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class TriggerTest extends TestCase
{
    use TSnuffRepository;

    public const PATH__SERVICE_JSON = __DIR__ . '/../resources/service.json';
    public const PATH__SERVICE_JSON_2 = __DIR__ . '/../resources/service.2.json';
    public const PATH__INSTALL = __DIR__ . '/../tmp';
    protected array $serviceConfig = [];

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=vendor/jeyroik/extas-foundation/resources/container.dist.json");
        $this->buildBasicRepos();
        $this->buildRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
        $this->deleteRepo('applications');
        $this->deleteRepo('instances');
        $this->deleteRepo('instances_info');
        $this->deleteRepo('triggers');
        $this->deleteRepo('trigger_event_condition_plugins');
        $this->deleteRepo('trigger_operation_plugins');
        $this->deleteRepo('trigger_event_value_plugins');
    }

    protected function buildRepos()
    {
        $config = include __DIR__ . '/../../extas.storage.php';
        foreach ($config['tables'] as $name => $options) {
            $options['namespace'] = 'tests\\tmp';
            $config['tables'][$name] = $options;
        }

        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', $config['tables']);
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'applications' => [
                "namespace" => "tests\\tmp",
                "item_class" => "deflou\\components\\applications\\Application",
                "pk" => "name",
                "aliases" => ["applications", "apps"],
                "hooks" => [],
                "code" => [
                    'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                    .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
                ]
            ]
        ]);
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'instances' => [
                "namespace" => "tests\\tmp",
                "item_class" => "deflou\\components\\instances\\Instance",
                "pk" => "name",
                "aliases" => ["instances"],
                "hooks" => [],
                "code" => [
                    'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                    .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'aid\']);'
                ]
            ]
        ]);
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'instances_info' => [
                "namespace" => "tests\\tmp",
                "item_class" => "deflou\\components\\instances\\InstanceInfo",
                "pk" => "name",
                "aliases" => ["instancesInfo"],
                "hooks" => [],
                "code" => [
                    'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                    .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'iid\']);'
                ]
            ]
        ]);
    }

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

        $trigger = new Trigger();

        $trigger->extensions()->create(new Extension([
            Extension::FIELD__CLASS => ExtensionInstanceResolver::class,
            Extension::FIELD__INTERFACE => IExtensionInstanceResolver::class,
            Extension::FIELD__SUBJECT => IInstance::SUBJECT,
            Extension::FIELD__METHODS => ['buildResolver']
        ]));

        $trigger->applications()->create(new Application([
            Application::FIELD__NAME => 'test',
            Application::FIELD__TITLE => 'test title',
            Application::FIELD__DESCRIPTION => 'test description',
            Application::FIELD__AVATAR => '',
            Application::FIELD__PACKAGE => '',
            Application::FIELD__RESOLVER => ResolverHttp::class,
            Application::FIELD__STATE => EStates::Accepted,
            Application::FILED__VERSION => '1.0.0',
            Application::FIELD__VENDOR => [
                IVendor::FIELD__NAME => 'vendor1'
            ],
            Application::FIELD__OPTIONS => [
                "operation__base_url" => [
                    "name" => "operation__base_url",
                    "value" => "http://localhost"
                ],
                "operaton__method" => [
                    "name" => "operaton__method",
                    "value" => "post"
                ],
                "test_operation__url" => [
                    "name" => "test_operation__url",
                    "value" => "/operation/path?with=param&or=without"
                ],
                "test_operation__method" => [
                    "name" => "test_operation__method",
                    "value" => "get"
                ],
                "test_operation2__url" => [
                    "name" => "test_operation__url",
                    "value" => "/operation2"
                ]
            ],
            Application::FIELD__EVENTS => [
                "test_event" => [
                    IParametred::FIELD__NAME => "test_event",
                    IParametred::FIELD__PARAMS => [

                    ]
                ]
            ],
            Application::FIELD__OPERATIONS => [
                "test_operation" => [
                    IParametred::FIELD__NAME => "test_operation",
                    IParametred::FIELD__PARAMS => [

                    ]
                ],
                "test_operation2" => [
                    IParametred::FIELD__NAME => "test_operation2",
                    IParametred::FIELD__PARAMS => [

                    ]
                ]
            ]
        ]));

        /**
         * @var IApplication $app
         */
        $app = $trigger->applications()->one([IApplication::FIELD__NAME => 'test']);
        $instanceService = new InstanceService();
        $instance = $instanceService->createInstanceFromApplication($app, 'vendor0');
        $triggerService = new TriggerService();

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
        ETriggerState::Active->activate($trigger);
        $trigger1->triggers()->update($trigger1);

        $trigger1->setApplicationId(ETrigger::Operation, $app->getId());
        $trigger1->setInstanceId(ETrigger::Operation, $instance->getId());
        $trigger1->setApplicationVersion(ETrigger::Operation, $app->getVersion());
        $trigger1->setInstanceVersion(ETrigger::Operation, $instance->getVersion());

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
                            ICondition::FIELD__CONDITION => 'neq'
                        ]
                    ]
                ]
            ]
        ]);
        ETriggerState::Active->activate($trigger2);
        $trigger1->triggers()->update($trigger2);

        $trigger3 = $triggerService->createTriggerForInstance($instance, 'vendor2');
        ETriggerState::Active->activate($trigger3);
        $trigger1->triggers()->update($trigger3);

        print_r($trigger1->triggers()->all([]));
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
