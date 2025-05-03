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
use Contentful\Management\Exception\RateWaitTooLongException;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Implementation\ClientCustomException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class RateWaitTooLongExceptionTest extends BaseTestCase
{
    public function testMaxRetryLimitWaitExceeded()
    {
        $this->expectException(RateWaitTooLongException::class);

        $callCount = 0;
        $httpClient = $this->createHttpClient(function (RequestInterface $request) use (&$callCount) {
            ++$callCount;
            if ($callCount < 3) {
                $response = new Response(
                    429,
                    [
                        'X-Contentful-Request-Id' => 'd533d76293f8bb047467344a28beffe0',
                        'X-Contentful-RateLimit-Reset' => 2,
                        'X-Contentful-RateLimit-Second-Remaining' => 200,
                    ],
                    $this->getFixtureContent('rate_limit.json')
                );

                throw new RateLimitExceededException(new ClientException('Reached rate limit', $request, $response), 'Reached rate limit');
            }

            return new Response(200, [], $this->getFixtureContent('rate_limit.json'));
        });

        $client = new ClientCustomException('irrelevant', $httpClient, ['max_rate_limit_retries' => 2]);
        $client->request('GET', '/');

        $this->assertSame(429, $client->getMessages()[0]->getResponse()->getStatusCode());
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
