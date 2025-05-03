<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Core\Exception\RateLimitExceededException;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Implementation\ClientCustomException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class RateLimitExceededExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('GET', 'https://preview.contentful.com/spaces/bc32cj3kyfet/entries?limit=6');
        $response = new Response(
            429,
            [
                'X-Contentful-Request-Id' => 'db2d795acb78e0592af00759986c744b',
                'X-Contentful-RateLimit-Reset' => '2727',
            ],
            '{"sys": {"type": "Error","id": "RateLimitExceeded"},"message": "You have exceeded the rate limit of the Organization this Space belongs to by making too many API requests within a short timespan. Please wait a moment before trying the request again.","requestId": "4d0274fb176b51ae43a64b98639a3090"}',
            '1.1',
            ''
        );

        $guzzleException = new ClientException('This is an error', $request, $response);

        $exception = new RateLimitExceededException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('db2d795acb78e0592af00759986c744b', $exception->getRequestId());
        $this->assertSame('You have exceeded the rate limit of the Organization this Space belongs to by making too many API requests within a short timespan. Please wait a moment before trying the request again.', $exception->getMessage());
        $this->assertSame(2727, $exception->getRateLimitReset());
    }

    public function testRetryLimitReachedWithLimit()
    {
        $this->expectException(RateLimitExceededException::class);

        $httpClient = $this->createHttpClient(function (RequestInterface $request) use (&$callCount) {
            $response = new Response(
                429,
                [
                    'X-Contentful-Request-Id' => 'd533d76293f8bb047467344a28beffe0',
                    'X-Contentful-RateLimit-Reset' => 2,
                    'X-Contentful-RateLimit-Second-Remaining' => 1,
                ],
                $this->getFixtureContent('rate_limit.json')
            );

            throw new RateLimitExceededException(new ClientException('Reached rate limit', $request, $response), 'Reached rate limit');
        });

        $client = new ClientCustomException('irrelevant', $httpClient, ['max_rate_limit_retries' => 2]);
        $client->request('GET', '/');

        $this->assertSame(429, $client->getMessages()[0]->getResponse()->getStatusCode());
    }

    public function testRetryLimiteReachedNoLimit()
    {
        $this->expectException(RateLimitExceededException::class);

        $httpClient = $this->createHttpClient(function (RequestInterface $request) use (&$callCount) {
            $response = new Response(
                429,
                [
                    'X-Contentful-Request-Id' => 'd533d76293f8bb047467344a28beffe0',
                    'X-Contentful-RateLimit-Reset' => 2,
                    'X-Contentful-RateLimit-Second-Remaining' => 1,
                ],
                $this->getFixtureContent('rate_limit.json')
            );

            throw new RateLimitExceededException(new ClientException('Reached rate limit', $request, $response), 'Reached rate limit');
        });

        $client = new ClientCustomException('irrelevant', $httpClient);
        $client->request('GET', '/');

        $this->assertSame(429, $client->getMessages()[0]->getResponse()->getStatusCode());
    }

    public function testRetrylimitTwo()
    {
        $callCount = 0;
        $httpClient = $this->createHttpClient(function (RequestInterface $request) use (&$callCount) {
            ++$callCount;
            if ($callCount < 3) {
                $response = new Response(
                    429,
                    [
                        'X-Contentful-Request-Id' => 'd533d76293f8bb047467344a28beffe0',
                        'X-Contentful-RateLimit-Reset' => 2,
                        'X-Contentful-RateLimit-Second-Remaining' => 1,
                    ],
                    $this->getFixtureContent('rate_limit.json')
                );

                throw new RateLimitExceededException(new ClientException('Reached rate limit', $request, $response), 'Reached rate limit');
            }

            return new Response(200, [], $this->getFixtureContent('rate_limit.json'));
        });

        $client = new ClientCustomException('irrelevant', $httpClient, ['max_rate_limit_retries' => 2]);
        $client->request('GET', '/');

        $this->assertSame(200, $client->getMessages()[0]->getResponse()->getStatusCode());
    }

    public function testRetryWithoutSecondsRemainingHeader()
    {
        $callCount = 0;
        $httpClient = $this->createHttpClient(function (RequestInterface $request) use (&$callCount) {
            ++$callCount;
            if ($callCount < 3) {
                $response = new Response(
                    429,
                    [
                        'X-Contentful-Request-Id' => 'd533d76293f8bb047467344a28beffe0',
                        'X-Contentful-RateLimit-Reset' => 2,
                    ],
                    $this->getFixtureContent('rate_limit.json')
                );

                throw new RateLimitExceededException(new ClientException('Reached rate limit', $request, $response), 'Reached rate limit');
            }

            return new Response(200, [], $this->getFixtureContent('rate_limit.json'));
        });

        $client = new ClientCustomException('irrelevant', $httpClient, ['max_rate_limit_retries' => 2]);
        $client->request('GET', '/');

        $this->assertSame(200, $client->getMessages()[0]->getResponse()->getStatusCode());
    }

    public function createHttpClient(?callable $handlerOverride = null)
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(function (callable $handler) use ($handlerOverride) {
            return function (RequestInterface $request, array $options) use ($handler, $handlerOverride) {
                $handler = $handlerOverride ?: $handler;

                return $handler($request, $options);
            };
        });

        return new HttpClient(['handler' => $stack]);
    }
}
