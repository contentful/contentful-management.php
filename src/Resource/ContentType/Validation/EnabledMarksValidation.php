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
 * EnableMarksValidation class stub.
 */
class EnabledMarksValidation implements ValidationInterface
{
    private const VALID_FIELD_TYPES = ['RichText'];

    /**
     * @var array
     */
    private $enabledMarks;

    public function __construct(array $enabledMarks)
    {
        $this->enabledMarks = $enabledMarks;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return self::VALID_FIELD_TYPES;
    }

    public function getEnabledMarks(): array
    {
        return $this->enabledMarks;
    }

    public function setEnabledMarks(array $enabledMarks)
    {
        $this->enabledMarks = $enabledMarks;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return ['enabledMarks' => $this->enabledMarks];
    }
}
