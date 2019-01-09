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
 * RegexpFilter class.
 *
 * The RegexpFilter can be used to compare a resource's field
 * or metadata against a specific pattern.
 *
 * ``` json
 * {
 *   "regexp": [{"doc": "sys.environment.sys.id"}, {"pattern": "^ci-.+$"}]
 * }
 * ```
 */
class RegexpFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $doc;

    /**
     * @var string
     */
    private $pattern;

    /**
     * RegexpFilter constructor.
     *
     * @param string $doc
     * @param string $pattern
     */
    public function __construct(string $doc, string $pattern)
    {
        $this->doc = $doc;
        $this->pattern = $pattern;
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
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return ['regexp' => [
            ['doc' => $this->doc],
            ['pattern' => $this->pattern],
        ]];
    }
}
