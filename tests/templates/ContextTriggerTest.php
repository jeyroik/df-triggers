<?php
use deflou\components\applications\AppWriter;
use deflou\components\instances\InstanceService;
use deflou\components\templates\contexts\ContextTrigger;
use deflou\components\triggers\ETrigger;
use deflou\components\triggers\events\conditions\EConditionEdge;
use deflou\components\triggers\TriggerService;
use deflou\interfaces\extensions\triggers\IExtensionTriggerEventValue;
use deflou\interfaces\triggers\events\ITriggerEvent;
use deflou\interfaces\triggers\values\IValueSense;
use extas\interfaces\parameters\IParam;
use tests\ExtasTestCase;

class ContextTriggerTest extends ExtasTestCase
{
    protected array $libsToInstall = [
        'jeyroik/df-applications' => ['php', 'json'],
        ''=> ['php', 'php']
        //'vendor/lib' => ['php', 'json'] storage ext, extas ext
    ];
    protected bool $isNeedInstallLibsItems = true;
    protected string $testPath = __DIR__;

    public function testBasicFunctions(): void
    {
        $appService = new AppWriter();
        $app = $appService->createAppByConfigPath(__DIR__ . '/../resources/app.json');

        $is = new InstanceService();
        $instance = $is->createInstanceFromApplication($app, 'test');

        $ts = new TriggerService();
        $trigger = $ts->createTriggerForInstance($instance, 'test');

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

        $trigger = $ts->insertEvent($trigger->getId(), $externalData);

        $ctx = new ContextTrigger([
            ContextTrigger::FIELD__PARAMS => [
                ContextTrigger::PARAM__FOR => [
                    IParam::FIELD__NAME => ContextTrigger::PARAM__FOR,
                    IParam::FIELD__VALUE => ETrigger::Event
                ],
                ContextTrigger::PARAM__TRIGGER => [
                    IParam::FIELD__NAME => ContextTrigger::PARAM__TRIGGER,
                    IParam::FIELD__VALUE => $trigger
                ]
            ]
        ]);

        $this->assertEquals(['test'], $ctx->getApplicationNames());
        $this->assertEquals(['some'], $ctx->getApplyTo());
    }
}
