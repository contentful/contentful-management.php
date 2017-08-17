<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Exception;

use Contentful\Management\Exception\DefaultLocaleNotDeletableException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class DefaultLocaleNotDeletableExceptionTest extends TestCase
{
    public function testExceptionStructure()
    {
        $request = new Request('POST', 'https://api.contentful.com/spaces/34luz0flcmxt/locales/0WDL9F2Q3xuC4ynhHEy7nE');
        $response = new Response(
            403,
            ['X-Contentful-Request-Id' => 'ba3ebdba60cc777f91746503330a6007'],
            '{"requestId":"ba3ebdba60cc777f91746503330a6007","message":"Cannot delete a default locale","sys":{"type":"Error","id":"DefaultLocaleNotDeletable"}}',
            1.1,
            'Forbidden'
        );

        $guzzleException = new ClientException('This is an error', $request, $response);
        $exception = new DefaultLocaleNotDeletableException($guzzleException);

        $this->assertTrue($exception->hasResponse());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertEquals('ba3ebdba60cc777f91746503330a6007', $exception->getRequestId());
        $this->assertEquals('Cannot delete a default locale', $exception->getMessage());
    }
}
