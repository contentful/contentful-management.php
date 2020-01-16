<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Role\Constraint;

/**
 * PathsConstraint class.
 *
 * The PathsConstraint can be used to allow access to resources
 * matching specific paths.
 *
 * ``` json
 * {
 *   "paths": [{"doc": "fields.%.%"}]
 * }
 * ```
 */
class PathsConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    private $doc = '';

    /**
     * PathsConstraint constructor.
     */
    public function __construct(string $doc = '')
    {
        $this->doc = $doc;
    }

    public function getDoc(): string
    {
        return $this->doc;
    }

    /**
     * @return static
     */
    public function setDoc(string $doc)
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return ['paths' => [
            ['doc' => $this->doc],
        ]];
    }
}
