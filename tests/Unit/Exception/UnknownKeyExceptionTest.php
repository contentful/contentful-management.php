<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\UnknownKeyException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class UnknownKeyExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '95cde6bc52fc73cb955059b5d6635671'],
            '{"requestId":"95cde6bc52fc73cb955059b5d6635671","message":"The body you sent contains an unknown key.","details":{"keys":["default","avx"]},"sys":{"type":"Error","id":"UnknownKey"}}',
            '1.1',
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new UnknownKeyException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('95cde6bc52fc73cb955059b5d6635671', $exception->getRequestId());
        $this->assertSame('The body you sent contains an unknown key.', $exception->getMessage());
        $this->assertSame(['default', 'avx'], $exception->getKeys());
    }
}
