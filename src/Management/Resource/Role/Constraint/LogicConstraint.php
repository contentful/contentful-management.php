<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\Role\Constraint;

/**
 * LogicConstraint class.
 */
abstract class LogicConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    protected $children = [];

    /**
     * LogicConstraint constructor.
     *
     * @param ConstraintInterface[] $children
     */
    public function __construct(array $children = [])
    {
        $this->setChildren($children);
    }

    /**
     * @return string
     */
    abstract protected function getOperator(): string;

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
     * @return static
     */
    public function addChild(ConstraintInterface $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param ConstraintInterface[] $children
     *
     * @return static
     */
    public function setChildren(array $children)
    {
        foreach ($children as $child) {
            if (!$child instanceof ConstraintInterface) {
                throw new \InvalidArgumentException(sprintf(
                    'Argument "$children" of "%s::setChildren()" must be an array of "ConstraintInterface" objects.',
                    static::class
                ));
            }
        }

        $this->children = $children;

        return $this;
    }

    /**
     * @return static
     */
    public function clearChildren()
    {
        $this->children = [];

        return $this;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            $this->getOperator() => $this->children,
        ];
    }
}
