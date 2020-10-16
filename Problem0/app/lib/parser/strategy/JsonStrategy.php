<?php

namespace app\lib\parser\strategy;


use app\lib\parser\exception\DecodeException;
use app\lib\parser\model\DataModel;

class JsonStrategy extends Strategy
{
    /**
     * @var array
     */
    private $bodyDecoded = [];

    /**
     * @return DataModel
     */
    function generateDataModel(): DataModel
    {
        return (new DataModel)
            ->setHeaders($this->getHeaders())
            ->setData($this->getData())
            ->setSourceModel($this->downloadedEntity->getSource());
    }

    /**
     * @return array
     * @throws DecodeException
     */
    private function getHeaders(): array
    {
        $body      = $this->getBodyDecoded();
        $path      = $this->downloadedEntity->getSource()->getHeaderArrayPath();
        $headerKey = $this->downloadedEntity->getSource()->getHeaderArrayKey();

        $this->logger->info('getting headers');

        // extracting headers
        $headers = $this->getByPath($path, $body);

        // nested headers
        if ($headerKey && is_array($headers[0])) {
            $headerArrays = $headers;
            $headers      = [];
            if (!isset($headerArrays[0][$headerKey])) {
                $this->logger->error("invalid header key \"$headerKey\"");
                throw new DecodeException();
            }

            foreach ($headerArrays as $headerArray) {
                $headers[] = $headerArray[$headerKey];
            }

        }

        if (!$headers) {
            $this->logger->error("no headers in path provided");
            throw new DecodeException();
        }

        return $headers;
    }

    /**
     * @return array
     * @throws DecodeException
     */
    function getBodyDecoded(): array
    {
        if ($this->bodyDecoded) {
            return $this->bodyDecoded;
        }

        try {
            return \GuzzleHttp\json_decode($this->downloadedEntity->getBody(), true);
        } catch (\InvalidArgumentException $e) {
            throw new DecodeException;
        }
    }

    /**
     * todo trait
     *
     * @param       $path
     * @param array $data
     *
     * @return array
     * @throws DecodeException
     */
    private function getByPath($path, array $data): array
    {
        foreach (explode(self::PATH_DELIMITER, $path) as $key) {
            if (!isset($data[$key])) {
                $this->logger->error("could not resolve path $path");
                throw new DecodeException();
            }
            $data = $data[$key];
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        $body = $this->getBodyDecoded();
        $path = $this->downloadedEntity->getSource()->getDataPath();

        $this->logger->info('getting data');

        return $this->getByPath($path, $body);
    }

    /**
     * @return string
     */
    function getStrategyName(): string
    {
        return 'json';
    }
}