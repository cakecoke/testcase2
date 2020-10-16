<?php

namespace app\lib\exporter\strategy;

use app\lib\logger\Logger;
use app\lib\parser\model\DataModel;

abstract class Strategy
{
    /**
     * @var DataModel
     */
    protected $model;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var array
     */
    protected $dataToExport;

    /**
     * Strategy constructor.
     *
     * @param DataModel $model
     * @param Logger    $logger
     */
    public function __construct(DataModel $model, Logger $logger)
    {
        $this->model        = $model;
        $this->logger       = $logger;
        $this->dataToExport = $model->getData();
    }

    /**
     * @return void
     */
    public abstract function computeDiff();

    /**
     * @return void
     */
    public abstract function export();
}