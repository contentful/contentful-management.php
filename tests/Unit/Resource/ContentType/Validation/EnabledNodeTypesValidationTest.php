<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\EnabledNodeTypesValidation;
use Contentful\Tests\Management\BaseTestCase;

class EnabledNodeTypesValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new EnabledNodeTypesValidation(['heading-1', 'heading-2', 'ordered-list']);

        $this->assertJsonFixtureEqualsJsonObject(
            'Unit/Resource/ContentType/Validation/enabled_node_types_validation.json',
            $validation
        );
    }

    public function testGetSetData()
    {
        $validation = new EnabledNodeTypesValidation(['heading-1', 'heading-2', 'ordered-list']);

        $this->assertSame(['RichText'], $validation->getValidFieldTypes());

        $this->assertSame(['heading-1', 'heading-2', 'ordered-list'], $validation->getEnabledNodeTypes());

        $validation->setEnabledNodeTypes(
            [
                'hyperlink',
                'entry-hyperlink',
                'asset-hyperlink',
                'embedded-asset-block',
                'embedded-entry-inline',
                'embedded-entry-block',
            ]
        );

        $this->assertSame(
            [
                'hyperlink',
                'entry-hyperlink',
                'asset-hyperlink',
                'embedded-asset-block',
                'embedded-entry-inline',
                'embedded-entry-block',
            ],
            $validation->getEnabledNodeTypes()
        );
    }
}
