<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\FallbackLocaleNotDeletableException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class FallbackLocaleNotDeletableExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales/0WDL9F2Q3xuC4ynhHEy7nE');
        $response = new Response(
            403,
            ['X-Contentful-Request-Id' => '0324c03ccf507f52515d39ab54e89516'],
            '{"requestId":"0324c03ccf507f52515d39ab54e89516","message":"Cannot delete locale which is fallback of another one","sys":{"type":"Error","id":"FallbackLocaleNotDeletable"}}',
            1.1,
            'Forbidden'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new FallbackLocaleNotDeletableException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('0324c03ccf507f52515d39ab54e89516', $exception->getRequestId());
        $this->assertEquals('Cannot delete locale which is fallback of another one', $exception->getMessage());
    }
}
