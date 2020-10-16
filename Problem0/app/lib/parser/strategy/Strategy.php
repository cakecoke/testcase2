<?php

namespace app\lib\parser\strategy;

use app\lib\downloader\model\DownloadedEntityModel;
use app\lib\logger\Logger;
use app\lib\parser\model\DataModel;

abstract class Strategy
{
    const PATH_DELIMITER = '.';

    /**
     * @var DownloadedEntityModel
     */
    protected $downloadedEntity;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Strategy constructor.
     *
     * @param DownloadedEntityModel $model
     */
    public function __construct(DownloadedEntityModel $model, Logger $logger)
    {
        $this->downloadedEntity = $model;
        $this->logger           = $logger;
    }

    /**
     * @return DataModel
     */
    abstract function generateDataModel(): DataModel;

    /**
     * @return string
     */
    abstract function getStrategyName(): string;

    /**
     * @return array
     */
    abstract function getBodyDecoded(): array;
}