<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class VersionMismatchExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            409,
            ['X-Contentful-Request-Id' => '0b22bcf71e0d624fbd878bc398746d2e'],
            '{"sys": {"type": "Error","id": "VersionMismatch"},"requestId": "0b22bcf71e0d624fbd878bc398746d2e"}',
            '1.1',
            'Conflict'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new VersionMismatchException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('0b22bcf71e0d624fbd878bc398746d2e', $exception->getRequestId());
        $this->assertSame('The version number you supplied is invalid.', $exception->getMessage());
    }
}
