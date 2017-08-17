<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\InternalServerErrorException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InternalServerErrorExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '6bf16c2ec5df8bbac2cb64feeb719558'],
            '{"requestId": "6bf16c2ec5df8bbac2cb64feeb719558","sys": {"type": "Error","id": "InternalServerError"}}',
            1.1,
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new InternalServerErrorException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('6bf16c2ec5df8bbac2cb64feeb719558', $exception->getRequestId());
        $this->assertEquals('This is an error', $exception->getMessage());
    }
}
