<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Management\Resource;

use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class PreviewApiKeyTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\PreviewApiKey" can only be instantiated as a result of an API call, manual creation is not allowed.
     */
    public function testInvalidCreation()
    {
        new PreviewApiKey();
    }

    /**
     * @return PreviewApiKey
     */
    public function testJsonSerialize(): PreviewApiKey
    {
        $previewApiKey = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'PreviewApiKey',
            ],
            'name' => 'Preview Key',
            'description' => null,
            'accessToken' => 'ee8b264bf66ca66e0c005411cff6009456b256d0011f617bfbe128d0f0c99f9f>',
        ]);

        $json = '{"sys":{"type":"PreviewApiKey"},"name":"Preview Key","description":null}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($previewApiKey));

        return $previewApiKey;
    }

    /**
     * @param PreviewApiKey $previewApiKey
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\PreviewApiKey", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(PreviewApiKey $previewApiKey)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'PreviewApiKey',
            ]], $previewApiKey);
    }
}
