<?php

namespace app\lib\exporter;

use app\lib\exporter\exception\NoStrategyException;
use app\lib\exporter\strategy\CsvStrategy;
use app\lib\exporter\strategy\Strategy;
use app\lib\logger\Logger;
use app\lib\parser\model\DataModel;
use DI\Container;

class Exporter
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var DataModel
     */
    private $model;
    /**
     * @var Container
     */
    private $container;

    /**
     * Exporter constructor.
     *
     * @param DataModel $model
     * @param Logger    $logger
     * @param Container $container
     */
    public function __construct(DataModel $model, Logger $logger, Container $container)
    {
        $this->logger    = $logger;
        $this->model     = $model;
        $this->container = $container;
    }


    public function export()
    {
        $this->logger->info('export started');
        $strategy = $this->getStrategy();
        $strategy->computeDiff();
        $strategy->export();
    }

    /**
     * @return Strategy
     * @throws NoStrategyException
     */
    private function getStrategy(): Strategy
    {
        if (strpos(strtolower($this->model->getSourceModel()->getOutputToFile()), 'csv') !== false) {
            return $this->container->make(CsvStrategy::class, ['model' => $this->model,]);
        }

        throw new NoStrategyException();
    }
}