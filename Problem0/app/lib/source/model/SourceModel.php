<?php

namespace app\lib\source\model;


class SourceModel
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $outputToFile;

    /**
     * @var string
     */
    private $headerArrayPath;

    /**
     * @var string
     */
    private $headerArrayKey = ''; // todo 7.1 nullable types?

    /**
     * @var string
     */
    private $dataPath;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputToFile()
    {
        return $this->outputToFile;
    }

    public function getOutputToFileWithPath()
    {
        return APP_RUNTIME_DIR . '/' . $this->getOutputToFile();
    }

    /**
     * @param string $outputToFile
     *
     * @return $this
     */
    public function setOutputToFile(string $outputToFile)
    {
        $this->outputToFile = $outputToFile;

        return $this;
    }

    /**
     * @param string $headerArrayPath
     *
     * @return SourceModel
     */
    public function setHeaderArrayPath(string $headerArrayPath): SourceModel
    {
        $this->headerArrayPath = $headerArrayPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderArrayPath(): string
    {
        return $this->headerArrayPath;
    }

    /**
     * @param string $headerArrayKey
     *
     * @return SourceModel
     */
    public function setHeaderArrayKey(string $headerArrayKey): SourceModel
    {
        $this->headerArrayKey = $headerArrayKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderArrayKey(): string
    {
        return $this->headerArrayKey;
    }

    /**
     * @param string $dataPath
     *
     * @return SourceModel
     */
    public function setDataPath(string $dataPath): SourceModel
    {
        $this->dataPath = $dataPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataPath(): string
    {
        return $this->dataPath;
    }
}
