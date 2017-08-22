<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\RateLimitExceededException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RateLimitExceededExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('GET', 'https://preview.contentful.com/spaces/bc32cj3kyfet/entries?limit=6');
        $response = new Response(
            429,
            [
                'X-Contentful-Request-Id' => 'db2d795acb78e0592af00759986c744b',
                'X-Contentful-RateLimit-Hour-Limit' => '180000',
                'X-Contentful-RateLimit-Hour-Remaining' => '179959',
                'X-Contentful-RateLimit-Reset' => '2727',
                'X-Contentful-RateLimit-Second-Limit' => '50',
                'X-Contentful-RateLimit-Second-Remaining' => '49',
            ],
            '{"sys": {"type": "Error","id": "RateLimitExceeded"},"message": "You have exceeded the rate limit of the Organization this Space belongs to by making too many API requests within a short timespan. Please wait a moment before trying the request again.","requestId": "4d0274fb176b51ae43a64b98639a3090"}',
            1.1,
            ''
        );

        $guzzleException = new ClientException('This is an error', $request, $response);

        $exception = new RateLimitExceededException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('db2d795acb78e0592af00759986c744b', $exception->getRequestId());
        $this->assertEquals('You have exceeded the rate limit of the Organization this Space belongs to by making too many API requests within a short timespan. Please wait a moment before trying the request again.', $exception->getMessage());
        $this->assertEquals(180000, $exception->getHourLimit());
        $this->assertEquals(179959, $exception->getHourRemaining());
        $this->assertEquals(2727, $exception->getRateLimitReset());
        $this->assertEquals(50, $exception->getSecondLimit());
        $this->assertEquals(49, $exception->getSecondRemaining());
    }
}
