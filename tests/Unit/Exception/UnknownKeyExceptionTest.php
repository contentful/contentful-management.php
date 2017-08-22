<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\UnknownKeyException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UnknownKeyExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '95cde6bc52fc73cb955059b5d6635671'],
            '{"requestId":"95cde6bc52fc73cb955059b5d6635671","message":"The body you sent contains an unknown key.","details":{"keys":["default","avx"]},"sys":{"type":"Error","id":"UnknownKey"}}',
            1.1,
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new UnknownKeyException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('95cde6bc52fc73cb955059b5d6635671', $exception->getRequestId());
        $this->assertEquals('The body you sent contains an unknown key.', $exception->getMessage());
        $this->assertEquals(['default', 'avx'], $exception->getKeys());
    }
}
