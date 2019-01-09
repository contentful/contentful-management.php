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
 * InclusionFilter class.
 *
 * The InclusionFilter can be used to compare a resource's field
 * or metadata against set of specific values.
 *
 * ``` json
 * {
 *   "in": [{"doc": "sys.environment.sys.id"}, ["qa", "staging"]
 * }
 * ```
 */
class InclusionFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $doc;

    /**
     * @var string[]
     */
    private $values = [];

    /**
     * InclusionFilter constructor.
     *
     * @param string   $doc
     * @param string[] $values
     */
    public function __construct(string $doc, array $values)
    {
        $this->doc = $doc;
        $this->values = $values;
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
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param string[] $values
     *
     * @return static
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return ['in' => [
            ['doc' => $this->doc],
            \array_values($this->values),
        ]];
    }
}
