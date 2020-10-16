<?php

namespace app\lib\downloader\model;

use app\lib\source\model\SourceModel;

class DownloadedEntityModel
{
    private const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var SourceModel
     */
    private $source;

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return DownloadedEntityModel
     */
    public function setBody(string $body): DownloadedEntityModel
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return SourceModel
     */
    public function getSource(): SourceModel
    {
        return $this->source;
    }

    /**
     * @param SourceModel $source
     *
     * @return DownloadedEntityModel
     */
    public function setSource(SourceModel $source): DownloadedEntityModel
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return DownloadedEntityModel
     */
    public function setHeaders(array $headers): DownloadedEntityModel
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param null $key
     *
     * @return array
     */
    public function getHeaders($key = null): array
    {
        if ($key) {
            return $this->headers[$key];
        }

        return $this->headers;
    }

    public function getContentType()
    {
        return $this->getHeaders(self::HEADER_CONTENT_TYPE)[0];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getSource()->getOutputToFile();
    }
}