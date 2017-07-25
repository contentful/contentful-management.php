<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Role\Constraint;

/**
 * AndConstraint class.
 *
 * The AndConstraint evaluates if all its conditions are true.
 * The value of the AndConstraint needs to be an array,
 * with an arbitrary amount of constraints.
 *
 * The AndConstraint is necessary and useful for a broad variety of use cases.
 * An example for the constraint is the limitation to a specific content type:
 *
 * ``` json
 * {
 *   "and": [
 *     {"equals": [{"doc": "sys.type"}, "Entry"]},
 *     {"equals": [{"doc": "sys.contentType.sys.id"}, "content-type-id"]}
 *   ]
 * }
 * ```
 */
class AndConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $children = [];

    /**
     * AndConstraint constructor.
     *
     * @param ConstraintInterface[] $children
     */
    public function __construct(array $children = [])
    {
        $this->setChildren($children);
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param ConstraintInterface $child
     *
     * @return $this
     */
    public function addChild(ConstraintInterface $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param ConstraintInterface[] $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {
        foreach ($children as $child) {
            if (!$child instanceof ConstraintInterface) {
                throw new \InvalidArgumentException('Argument $children of ChildrenConstraintTrait::setChildren must be an array of ConstraintInterface objects');
            }
        }

        $this->children = $children;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearChildren()
    {
        $this->children = [];

        return $this;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize(): array
    {
        return ['and' => $this->children];
    }
}
