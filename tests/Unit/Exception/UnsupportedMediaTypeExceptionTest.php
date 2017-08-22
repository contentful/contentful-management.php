<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\UnsupportedMediaTypeException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UnsupportedMediaTypeExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            415,
            ['X-Contentful-Request-Id' => '554f5306b9c15e43c1f50842e2544949'],
            '{"requestId":"554f5306b9c15e43c1f50842e2544949","message":"Please specify an HTTP \"Content-Type\" header with an API version, for example: \"application/vnd.contentful.management.v1+json\".","sys":{"type":"Error","id":"UnsupportedMediaType"}}',
            1.1,
            'Unsupported Media Type'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new UnsupportedMediaTypeException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('554f5306b9c15e43c1f50842e2544949', $exception->getRequestId());
        $this->assertEquals('Please specify an HTTP "Content-Type" header with an API version, for example: "application/vnd.contentful.management.v1+json".', $exception->getMessage());
    }
}
