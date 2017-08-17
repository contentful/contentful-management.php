<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Role\Constraint;

/**
 * EqualityConstraint class.
 *
 * The EqualityConstraint can be used to compare a resource's field
 * or meta dataagainst a specific value.
 *
 * EqualityConstraints are one of the very basic constraints
 * and are typically used ensure the type of a document or to match entries of a content type:
 *
 * ``` json
 * {
 *   "equals": [{"doc": "sys.type"}, "Asset"]
 * }
 * ```
 */
class EqualityConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    private $doc = '';

    /**
     * @var mixed|null
     */
    private $value;

    /**
     * InConstraint constructor.
     *
     * @param string     $doc
     * @param mixed|null $value
     */
    public function __construct(string $doc = '', $value = null)
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
     * @return $this
     */
    public function setDoc(string $doc)
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

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
        return ['equals' => [
            ['doc' => $this->doc],
            $this->value,
        ]];
    }
}
