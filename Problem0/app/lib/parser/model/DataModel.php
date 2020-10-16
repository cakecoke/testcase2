<?php

namespace app\lib\parser\model;

use app\lib\source\model\SourceModel;

class DataModel
{
    /**
     * @var SourceModel
     */
    private $sourceModel;


    /**
     * @var array
     */
    private $headers;


    /**
     * @var array
     */
    private $data;

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return DataModel
     */
    public function setHeaders(array $headers): DataModel
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return SourceModel
     */
    public function getSourceModel(): SourceModel
    {
        return $this->sourceModel;
    }

    /**
     * @param SourceModel $sourceModel
     *
     * @return DataModel
     */
    public function setSourceModel(SourceModel $sourceModel): DataModel
    {
        $this->sourceModel = $sourceModel;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return DataModel
     */
    public function setData(array $data): DataModel
    {
        $this->data = $data;

        return $this;
    }
}