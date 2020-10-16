<?php

namespace app\lib\parser;

use app\lib\downloader\model\DownloadedEntityModel;
use app\lib\logger\Logger;
use app\lib\parser\exception\NoStrategyException;
use app\lib\parser\model\DataModel;
use app\lib\parser\strategy\JsonStrategy;
use app\lib\parser\strategy\Strategy;
use DI\Container;

class Parser
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var DownloadedEntityModel
     */
    private $model;

    /**
     * Parser constructor.
     *
     * @param DownloadedEntityModel $model
     * @param Logger                $logger
     * @param Container             $container
     *
     * @internal param JsonStrategy $jsonStrategy
     */
    public function __construct(
        DownloadedEntityModel $model,
        Logger $logger,
        Container $container
    )
    {

        $this->logger    = $logger;
        $this->container = $container;
        $this->model     = $model;
    }

    public function parse(): DataModel
    {
        $strategy = $this->getStrategy();
        $this->logger->info("decoding {$strategy->getStrategyName()}");

        return $strategy->generateDataModel();
    }

    /**
     * @return Strategy
     * @throws NoStrategyException
     */
    private function getStrategy(): Strategy
    {
        if (strpos(strtolower($this->model->getContentType()), 'json') !== false) {
            return $this->container->make(JsonStrategy::class, ['model' => $this->model]);
        }

        throw new NoStrategyException();
    }
}