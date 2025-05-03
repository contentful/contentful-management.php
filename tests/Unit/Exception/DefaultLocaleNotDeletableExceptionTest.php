<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Exception;

use Contentful\Management\Exception\DefaultLocaleNotDeletableException;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class DefaultLocaleNotDeletableExceptionTest extends BaseTestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales/0WDL9F2Q3xuC4ynhHEy7nE');
        $response = new Response(
            403,
            ['X-Contentful-Request-Id' => 'ba3ebdba60cc777f91746503330a6007'],
            '{"requestId":"ba3ebdba60cc777f91746503330a6007","message":"Cannot delete a default locale.","sys":{"type":"Error","id":"DefaultLocaleNotDeletable"}}',
            '1.1',
            'Forbidden'
        );

        $guzzleException = new ClientException('This is an error.', $request, $response);
        $exception = new DefaultLocaleNotDeletableException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame('ba3ebdba60cc777f91746503330a6007', $exception->getRequestId());
        $this->assertSame('Cannot delete a default locale.', $exception->getMessage());
    }
}
