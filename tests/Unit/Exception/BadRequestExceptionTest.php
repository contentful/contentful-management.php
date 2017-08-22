<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\BadRequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BadRequestExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '65af11a800e5f67af57a2082eb9a405c'],
            '{"sys": {"type": "Error","id": "BadRequest"},"message": "Missing object","requestId": "65af11a800e5f67af57a2082eb9a405c"}',
            1.1,
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new BadRequestException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('65af11a800e5f67af57a2082eb9a405c', $exception->getRequestId());
        $this->assertEquals('Missing object', $exception->getMessage());
    }
}
