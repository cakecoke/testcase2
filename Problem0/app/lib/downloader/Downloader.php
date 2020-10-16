<?php

namespace app\lib\downloader;

use app\lib\downloader\model\DownloadedEntityModel;
use app\lib\logger\Logger;
use app\lib\source\model\SourceModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Response;

class Downloader
{
    private const STATE_REJECTED = 'rejected';

    /**
     * @var SourceModel[]
     */
    public $sources;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * Downloader constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return DownloadedEntityModel[]
     */
    public function download(): \Generator
    {
        $handler = new CurlMultiHandler();
        $stack   = HandlerStack::create($handler);
        $client  = new Client(['handler' => $stack]);


        $promises = [];
        $sources  = [];
        foreach ($this->sources as $source) {
            try {
                $sources[$source->getOutputToFile()]  = $source;
                $promises[$source->getOutputToFile()] = $client->getAsync($source->getUrl());
            } catch (\InvalidArgumentException $e) {
                $this->logger->error("Invalid url {$source->getUrl()} skipping");
            }
        }

        $this->logger->info('download started');

        foreach (Promise\settle($promises)->wait() as $k => $promise) {

            [$state, $response] = array_values($promise);

            /** @var SourceModel $source */
            $source = $sources[$k];

            if ($state == self::STATE_REJECTED) {
                /** @var ConnectException $response */
                $path = $response->getRequest()->getUri()->getPath();
                $this->logger->error("Failed to connect to {$path}");
                continue;
            }

            /** @var Response $response */
            $this->logger->info("Got {$response->getBody()->getSize()} bytes from {$source->getUrl()}");


            yield (new DownloadedEntityModel())
                ->setBody($response->getBody()->getContents())
                ->setSource($source)
                ->setHeaders($response->getHeaders());
        }
    }
}