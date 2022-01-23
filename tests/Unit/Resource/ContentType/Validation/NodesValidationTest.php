<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\Resource\ContentType\Validation\NodesValidation;
use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use Contentful\Tests\Management\BaseTestCase;

class NodesValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new NodesValidation(
            [new SizeValidation(1)],
            [new SizeValidation(null, 1)],
            [new LinkContentTypeValidation(['test']), new SizeValidation(1, 2)],
            [new LinkContentTypeValidation(['test']), new SizeValidation(1)],
            [new LinkContentTypeValidation(['test']), new SizeValidation(1)]
        );

        $this->assertJsonFixtureEqualsJsonObject(
            'Unit/Resource/ContentType/Validation/nodes_validation.json',
            $validation
        );
    }

    public function testGetSetData()
    {
        $sizeValidation = new SizeValidation(1);
        $linkContentTypeValidation = new LinkContentTypeValidation(['test']);

        $validation = new NodesValidation(
            [$sizeValidation],
            [$sizeValidation],
            [$linkContentTypeValidation, $sizeValidation],
            [$linkContentTypeValidation, $sizeValidation],
            [$linkContentTypeValidation, $sizeValidation]
        );

        $this->assertSame(['RichText'], $validation->getValidFieldTypes());

        $this->assertSame([$sizeValidation], $validation->getAssetHyperlinkValidations());
        $this->assertSame([$sizeValidation], $validation->getEmbeddedAssetBlockValidations());
        $this->assertSame([$linkContentTypeValidation, $sizeValidation], $validation->getEmbeddedEntryBlockValidations());
        $this->assertSame([$linkContentTypeValidation, $sizeValidation], $validation->getEmbeddedEntryInlineValidations());
        $this->assertSame([$linkContentTypeValidation, $sizeValidation], $validation->getEntryHyperlinkValidations());
    }

    public function testGetAddedData()
    {
        $sizeValidation = new SizeValidation(1);

        $validation = new NodesValidation([], [], [], [], []);

        $this->assertSame([], $validation->getAssetHyperlinkValidations());
        $this->assertSame([], $validation->getEmbeddedAssetBlockValidations());
        $this->assertSame([], $validation->getEmbeddedEntryBlockValidations());
        $this->assertSame([], $validation->getEmbeddedEntryInlineValidations());
        $this->assertSame([], $validation->getEntryHyperlinkValidations());

        $validation->addAssetHyperlinkValidation($sizeValidation);
        $validation->addEmbeddedAssetBlockValidation($sizeValidation);
        $validation->addEmbeddedEntryBlockValidation($sizeValidation);
        $validation->addEmbeddedEntryInlineValidation($sizeValidation);
        $validation->addEntryHyperlinkValidation($sizeValidation);

        $this->assertSame([$sizeValidation], $validation->getAssetHyperlinkValidations());
        $this->assertSame([$sizeValidation], $validation->getEmbeddedAssetBlockValidations());
        $this->assertSame([$sizeValidation], $validation->getEmbeddedEntryBlockValidations());
        $this->assertSame([$sizeValidation], $validation->getEmbeddedEntryInlineValidations());
        $this->assertSame([$sizeValidation], $validation->getEntryHyperlinkValidations());
    }
}
