<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\FallbackLocaleNotRenameableException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class FallbackLocaleNotRenameableExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales');
        $response = new Response(
            403,
            ['X-Contentful-Request-Id' => 'ba4351a9e72616cffffc3c175bcc5271'],
            '{"requestId":"ba4351a9e72616cffffc3c175bcc5271","message":"Cannot change the code of a locale which is fallback of another one.","sys":{"type":"Error","id":"FallbackLocaleNotRenameable"}}',
            '1.1',
            'Forbidden'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new FallbackLocaleNotRenameableException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('ba4351a9e72616cffffc3c175bcc5271', $exception->getRequestId());
        $this->assertSame('Cannot change the code of a locale which is fallback of another one.', $exception->getMessage());
    }
}
