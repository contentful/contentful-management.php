<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2022 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * EnabledNodeTypesValidation class stub.
 */
class EnabledNodeTypesValidation implements ValidationInterface
{
    private const VALID_FIELD_TYPES = ['RichText'];

    /**
     * @var array
     */
    private $enabledNodeTypes;

    public function __construct(array $enabledNodeTypes)
    {
        $this->enabledNodeTypes = $enabledNodeTypes;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return self::VALID_FIELD_TYPES;
    }

    public function getEnabledNodeTypes(): array
    {
        return $this->enabledNodeTypes;
    }

    public function setEnabledNodeTypes(array $enabledNodeTypes)
    {
        $this->enabledNodeTypes = $enabledNodeTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return ['enabledNodeTypes' => $this->enabledNodeTypes];
    }
}
