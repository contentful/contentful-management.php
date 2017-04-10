<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

use Contentful\Management\Field\Validation\ValidationInterface;

class ArrayField extends AbstractField
{
    const VALID_ITEM_TYPES = ['Symbol', 'Link'];
    const VALID_LINK_TYPES = ['Asset', 'Entry'];

    /**
     * Type for items.
     *
     * Valid values are:
     * - Symbol
     * - Link
     *
     * @var string
     */
    private $itemsType;

    /**
     * (Array of links only) Type of links.
     *
     * Valid values are:
     * - Asset
     * - Entry
     *
     * @var string|null
     */
    private $itemsLinkType;

    /**
     * @var ValidationInterface[]
     */
    private $itemsValidations = [];

    public function __construct(string $id, string $name, string $itemsType, string $itemsLinkType = null)
    {
        parent::__construct($id, $name);

        if (!self::isValidItemType($itemsType)) {
            throw new \RuntimeException('Invalid items type ' . $itemsType . '. Valid values are ' . join(', ', self::VALID_ITEM_TYPES) . '.');
        }
        if ($itemsType === 'Link' && !self::isValidLinkType($itemsLinkType)) {
            throw new \RuntimeException('Invalid items link type ' . $itemsLinkType . '. Valid values are ' . join(', ', self::VALID_LINK_TYPES) . '.');
        }

        $this->itemsType = $itemsType;
        $this->itemsLinkType = $itemsLinkType;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Array';
    }

    /**
     * @return string
     */
    public function getItemsType(): string
    {
        return $this->itemsType;
    }

    /**
     * @param string $itemsType
     *
     * @return $this
     */
    public function setItemsType(string $itemsType)
    {
        $this->itemsType = $itemsType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemsLinkType()
    {
        return $this->itemsLinkType;
    }

    /**
     * @param string|null $itemsLinkType
     *
     * @return $this
     */
    public function setItemsLinkType(string $itemsLinkType = null)
    {
        $this->itemsLinkType = $itemsLinkType;

        return $this;
    }

    /**
     * @return ValidationInterface[]
     */
    public function getItemsValidations(): array
    {
        return $this->itemsValidations !== null ? $this->itemsValidations : [];
    }

    /**
     * @param ValidationInterface[] $itemsValidations
     *
     * @return $this
     */
    public function setItemsValidations(array $itemsValidations)
    {
        foreach ($itemsValidations as $validation) {
            if (!in_array($this->getItemsType(), $validation::getValidFieldTypes())) {
                throw new \RuntimeException('The validation ' . get_class($validation) . ' can not be used for fields of type ' . $this->getType() . '.');
            }
        }

        $this->itemsValidations = $itemsValidations;

        return $this;
    }

    /**
     * @param  ValidationInterface $validation
     *
     * @return $this
     */
    public function addItemsValidation(ValidationInterface $validation)
    {
        if (!in_array($this->getItemsType(), $validation::getValidFieldTypes())) {
            throw new \RuntimeException('The validation ' . get_class($validation) . ' can not be used for fields of type ' . $this->getType() . '.');
        }

        $this->validations[] = $validation;

        return $this;
    }

    /**
     * @param  string $type
     *
     * @return bool
     */
    private static function isValidItemType(string $type): bool
    {
        return in_array($type, self::VALID_ITEM_TYPES);
    }

    /**
     * @param  string $type
     *
     * @return bool
     */
    private static function isValidLinkType(string $type): bool
    {
        return in_array($type, self::VALID_LINK_TYPES);
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        $items = ['type' => $this->itemsType];
        if ($this->itemsType === 'Link') {
            $items['linkType'] = $this->itemsLinkType;
        }

        $data->items = (object) $items;

        return $data;
    }
}
