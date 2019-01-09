<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Webhook;

/**
 * EqualityFilter class.
 *
 * The EqualityFilter can be used to compare a resource's field
 * or metadata against a specific value.
 *
 * EqualityFilters are one of the very basic filters
 * and are typically used ensure the type of a document or to match entries of a content type:
 *
 * ``` json
 * {
 *   "equals": [{"doc": "sys.environment.sys.id"}, "staging"]
 * }
 * ```
 */
class EqualityFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $doc;

    /**
     * @var string
     */
    private $value;

    /**
     * EqualityFilter constructor.
     *
     * @param string $doc
     * @param string $value
     */
    public function __construct(string $doc, string $value)
    {
        $this->doc = $doc;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getDoc(): string
    {
        return $this->doc;
    }

    /**
     * @param string $doc
     *
     * @return static
     */
    public function setDoc(string $doc)
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return ['equals' => [
            ['doc' => $this->doc],
            $this->value,
        ]];
    }
}
