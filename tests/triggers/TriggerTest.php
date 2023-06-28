<?php

use deflou\components\triggers\Trigger;
use extas\components\repositories\RepoItem;
use extas\components\repositories\TSnuffRepository;
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
        $this->deleteRepo('triggers');
        $this->deleteRepo('trigger_event_condition_plugins');
        $this->deleteRepo('trigger_operation_plugins');
        $this->deleteRepo('trigger_event_value_plugins');
    }

    protected function buildRepos()
    {
        $config = include __DIR__ . '/../../extas.storage.php';

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
    }

    protected function getAppJsonDecoded(): array
    {
        return empty($this->serviceConfig) 
            ? $this->serviceConfig = json_decode(file_get_contents(static::PATH__SERVICE_JSON), true)
            : $this->serviceConfig;
    }
}
