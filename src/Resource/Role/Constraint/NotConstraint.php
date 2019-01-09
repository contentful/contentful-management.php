<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Role\Constraint;

/**
 * NotConstraint class.
 *
 * The NotConstraint inverts the result of its value.
 * The value of the NotConstraint must be another constraint.
 *
 * A typical use case for the NotConstraint is the inversion of whitelists to blacklists.
 * If for example a user should not be able to see entries of a specific content type,
 * it can either be achieved by denying access to those content types
 * or by allowing access to all but the entries of the content type:
 *
 * ``` json
 * {
 *   "not": {
 *     "equals": [{"doc": "sys.contentType.sys.id"}, "content-type-id"]
 *   }
 * }
 * ```
 */
class NotConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface|null
     */
    private $child;

    /**
     * NotConstraint constructor.
     *
     * @param ConstraintInterface|null $child
     */
    public function __construct(ConstraintInterface $child = \null)
    {
        $this->child = $child;
    }

    /**
     * @return ConstraintInterface|null
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param ConstraintInterface $child
     *
     * @return static
     */
    public function setChild(ConstraintInterface $child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->child
            ? ['not' => [$this->child]]
            : ['not' => []];
    }
}
