<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\MissingKeyException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class MissingKeyExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '761a55845f700069f3c3b4dae6ad1117'],
            '{"requestId":"761a55845f700069f3c3b4dae6ad1117","details":{"key":"resource"},"sys":{"type":"Error","id":"MissingKey"}}',
            1.1,
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new MissingKeyException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('761a55845f700069f3c3b4dae6ad1117', $exception->getRequestId());
        $this->assertEquals('This is an error', $exception->getMessage());
        $this->assertEquals('resource', $exception->getKey());
    }
}
