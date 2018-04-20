<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

class CloseIOApiService
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    private function closeIORequest(string $method, string $path, array $queryParams = []): Response
    {
        try {

            return $this->httpClient->request(
                $method,
                sprintf('/api/v1%s', $path),
                [
                    'query' => $queryParams,
                ]
            );

        } catch (GuzzleException $exception) {
            switch (true) {
                case $exception instanceof ClientException:
                    throw new ClientException($exception->getMessage(), $exception->getRequest(),$exception->getResponse());
                    break;
                case $exception instanceof ServerException:
                    throw new ServerException($exception->getMessage(), $exception->getRequest(), $exception->getResponse());
                    break;
                case $exception instanceof BadResponseException:
                default:
                    throw new BadResponseException($exception->getMessage(), $exception->getRequest(), $exception->getResponse());
            }
        }
    }

    /**
     * Maximum limit is 100
     * @param array $params
     * @return array
     */
    public function getCalls(array $params = []): array
    {
        $params = array_merge([
            '_limit' => 100,
            '_skip' => 0,
        ], $params);

        return json_decode(
            $this->setCloseIORequest($params),
            true
        );
    }

    /**
     * @param array $params
     * @return Response
     * @throws \Exception
     */
    private function setCloseIORequest(array $params): string
    {
        return $this->closeIORequest('GET', '/activity/call/', $params)->getBody()->getContents();
    }
}