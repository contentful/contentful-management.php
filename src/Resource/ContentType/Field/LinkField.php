<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

/**
 * LinkField class.
 */
class LinkField extends BaseField
{
    /**
     * @var string[]
     */
    const VALID_LINK_TYPES = ['Asset', 'Entry'];

    /**
     * Type of the linked resource.
     *
     * Valid values are:
     * - Asset
     * - Entry
     *
     * @var string
     */
    private $linkType;

    /**
     * LinkField constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $linkType Either Entry or Asset
     *
     * @throws \RuntimeException If $linkType is not a valid value
     */
    public function __construct(string $id, string $name, string $linkType)
    {
        parent::__construct($id, $name);

        $this->setLinkType($linkType);
    }

    /**
     * @return string
     */
    public function getLinkType(): string
    {
        return $this->linkType;
    }

    /**
     * @param string $linkType
     *
     * @return static
     */
    public function setLinkType(string $linkType)
    {
        if (!$this->isValidLinkType($linkType)) {
            throw new \RuntimeException(\sprintf(
                'Invalid link type "%s". Valid values are %s.',
                $linkType,
                \implode(', ', self::VALID_LINK_TYPES)
            ));
        }

        $this->linkType = $linkType;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isValidLinkType(string $type): bool
    {
        return \in_array($type, self::VALID_LINK_TYPES, \true);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Link';
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data['linkType'] = $this->linkType;

        return $data;
    }
}
