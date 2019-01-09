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
 * NotFilter class.
 *
 * The NotFilter inverts the result of its value.
 * The value of the NotFilter must be another constraint.
 *
 * A typical use case for the NotFilter is the inversion of whitelists to blacklists.
 *
 * ``` json
 * {
 *   "not": {
 *     "equals": [{"doc": "sys.contentType.sys.id"}, "content-type-id"]
 *   }
 * }
 * ```
 */
class NotFilter implements FilterInterface
{
    /**
     * @var FilterInterface
     */
    private $child;

    /**
     * NotConstraint constructor.
     *
     * @param FilterInterface $child
     */
    public function __construct(FilterInterface $child)
    {
        $this->child = $child;
    }

    /**
     * @return FilterInterface
     */
    public function getChild(): FilterInterface
    {
        return $this->child;
    }

    /**
     * @param FilterInterface $child
     *
     * @return static
     */
    public function setChild(FilterInterface $child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return ['not' => $this->child];
    }
}
