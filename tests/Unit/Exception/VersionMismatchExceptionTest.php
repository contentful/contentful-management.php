<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\VersionMismatchException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class VersionMismatchExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            409,
            ['X-Contentful-Request-Id' => '0b22bcf71e0d624fbd878bc398746d2e'],
            '{"sys": {"type": "Error","id": "VersionMismatch"},"requestId": "0b22bcf71e0d624fbd878bc398746d2e"}',
            1.1,
            'Conflict'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new VersionMismatchException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('0b22bcf71e0d624fbd878bc398746d2e', $exception->getRequestId());
        $this->assertEquals('This is an error', $exception->getMessage());
    }
}
