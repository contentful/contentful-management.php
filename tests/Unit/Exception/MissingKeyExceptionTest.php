<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\MissingKeyException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class MissingKeyExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            400,
            ['X-Contentful-Request-Id' => '761a55845f700069f3c3b4dae6ad1117'],
            '{"requestId":"761a55845f700069f3c3b4dae6ad1117","details":{"key":"resource"},"sys":{"type":"Error","id":"MissingKey"}}',
            '1.1',
            'Bad Request'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new MissingKeyException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('761a55845f700069f3c3b4dae6ad1117', $exception->getRequestId());
        $this->assertSame('Request body is missing a required key.', $exception->getMessage());
        $this->assertSame('resource', $exception->getKey());
    }
}
