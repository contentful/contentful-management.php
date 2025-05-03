<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\InternalServerErrorException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class InternalServerErrorExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '6bf16c2ec5df8bbac2cb64feeb719558'],
            '{"requestId": "6bf16c2ec5df8bbac2cb64feeb719558","sys": {"type": "Error","id": "InternalServerError"}}',
            '1.1',
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new InternalServerErrorException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('6bf16c2ec5df8bbac2cb64feeb719558', $exception->getRequestId());
        $this->assertSame('This is an error.', $exception->getMessage());
    }
}
