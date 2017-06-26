<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\ValidationFailedException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ValidationFailedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            422,
            ['X-Contentful-Request-Id' => 'e60465b1bdeae58b22a4d3cac520356c'],
            '{"requestId":"e60465b1bdeae58b22a4d3cac520356c","message":"The resource you sent in the body is invalid.","details":{"errors":[{"name":"taken","path":"display_code","value":"en-US"},{"name":"fallback locale creates a loop","path":"fallback_code","value":"en-US"}]},"sys":{"type":"Error","id":"ValidationFailed"}}',
            1.1,
            'Unprocessable Entity'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new ValidationFailedException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('e60465b1bdeae58b22a4d3cac520356c', $exception->getRequestId());
        $this->assertEquals('The resource you sent in the body is invalid.', $exception->getMessage());
    }
}
