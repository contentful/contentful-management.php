<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Extension;

/**
 * FieldType class.
 *
 * This class is a representation of an item in a "fieldType" array in a UI extension.
 *
 * ``` php
 * // A single parameter for "simple" field types
 * $fieldType = new FieldType('Symbol');
 * $fieldType = new FieldType('Text');
 * $fieldType = new FieldType('Integer');
 * $fieldType = new FieldType('Number');
 * $fieldType = new FieldType('Date');
 * $fieldType = new FieldType('Boolean');
 * $fieldType = new FieldType('Object');
 *
 * // Either ["Asset"] or ["Entry"] as second parameter, if "Link" is the first
 * $fieldType = new FieldType('Link', ['Asset']);
 * $fieldType = new FieldType('Link', ['Entry']);
 *
 * // If the first parameter is "Array",
 * // then the second will have to be an array with the string "Symbol",
 * // or with the string "Link" and either "Entry" or "Asset"
 * $fieldType = new FieldType('Array', ['Symbol']);
 * $fieldType = new FieldType('Array', ['Link', 'Asset']);
 * $fieldType = new FieldType('Array', ['Link', 'Entry']);
 * ```
 */
class FieldType implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * FieldType constructor.
     *
     * @param string $type
     * @param array  $options
     */
    public function __construct(string $type, array $options = [])
    {
        if (!$this->isValidFieldType($type)) {
            throw new \InvalidArgumentException(\sprintf(
                'Trying to create invalid extension field type "%s".',
                $type
            ));
        }

        if ('Link' === $type) {
            $this->setLinkFieldType(...$options);

            return;
        }

        if ('Array' === $type) {
            $this->setArrayFieldType(...$options);

            return;
        }

        $this->data = [
            'type' => $type,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->getData();
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isValidFieldType(string $type): bool
    {
        return \in_array($type, ['Symbol', 'Text', 'Integer', 'Number', 'Date', 'Boolean', 'Object', 'Link', 'Array'], \true);
    }

    /**
     * Creates a structure for a "Link" field type.
     *
     * @param string $linkType
     */
    private function setLinkFieldType(string $linkType)
    {
        if (!\in_array($linkType, ['Entry', 'Asset'], \true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Trying to create link field type, but link type must be either "Entry" or "Asset", "%s" given.',
                $linkType
            ));
        }

        $this->data = [
            'type' => 'Link',
            'linkType' => $linkType,
        ];
    }

    /**
     * Creates a structure for an "Array" field type.
     *
     * @param string      $arrayType Either "Symbol" or "Link"
     * @param string|null $linkType  Either "Asset" or "Entry" if $arrayType is "Link", null otherwise
     */
    private function setArrayFieldType(string $arrayType, string $linkType = \null)
    {
        if ('Symbol' !== $arrayType && 'Link' !== $arrayType) {
            throw new \InvalidArgumentException(\sprintf(
                'Trying to create array field type using invalid type "%s".',
                $arrayType
            ));
        }

        if ('Link' === $arrayType) {
            if (!\in_array($linkType, ['Entry', 'Asset'], \true)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Trying to create array field type with items type "Link", but link type must be either "Entry" or "Asset", "%s" given.',
                    $linkType
                ));
            }

            $this->data = [
                'type' => 'Array',
                'items' => [
                    'type' => 'Link',
                    'linkType' => $linkType,
                ],
            ];

            return;
        }

        // By exclusion, it must be a Symbol array
        $this->data = [
            'type' => 'Array',
            'items' => [
                'type' => 'Symbol',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
