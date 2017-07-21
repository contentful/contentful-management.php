<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Role\Constraint;

/**
 * OrConstraint class.
 *
 * The OrConstaint evaluates if one if its conditions returns true.
 * The value of the OrConstraint needs to be an array,
 * with an arbitrary amount of constraints.
 *
 * OrConstraint can be used to enable an effect for various different resources.
 * E.g. a user should only be allowed to read entries of a specific content type
 * or all assets:
 *
 * ``` json
 * {
 *   "or": [
 *     {"equals": [{"doc": "sys.type"}, "Entry"]},
 *     {"equals": [{"doc": "sys.type"}, "Asset"]}
 *   ]
 * }
 * ```
 */
class OrConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $children = [];

    /**
     * OrConstraint constructor.
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
        return ['or' => $this->children];
    }
}
