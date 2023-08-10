<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2023 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Resource\EntryInterface;
use Contentful\Management\Proxy\Extension\EntryProxyExtension;
use Contentful\Management\Resource\Behavior\ArchivableTrait;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\FindReferencesTrait;
use Contentful\Management\Resource\Behavior\PublishableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Entry as SystemProperties;

/**
 * Entry class.
 *
 * This class represents a resource with type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries
 */
class Entry extends BaseResource implements EntryInterface, CreatableInterface
{
    use ArchivableTrait;
    use DeletableTrait;
    use EntryProxyExtension;
    use PublishableTrait;
    use UpdatableTrait;
    use FindReferencesTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $contentTypeId;

    /**
     * @var array[]
     */
    protected $fields = [];

    /**
     * Entry constructor.
     */
    public function __construct(string $contentTypeId)
    {
        $this->contentTypeId = $contentTypeId;
    }

    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $fields = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            $fields[$fieldName] = [];

            foreach ($fieldData as $locale => $data) {
                $fields[$fieldName][$locale] = $this->getFormattedData($data);
            }
        }

        return [
            'sys' => $this->sys,
            'fields' => (object) $fields,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'entry' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId(): string
    {
        return $this->sys->getSpace()->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentId(): string
    {
        return $this->sys->getEnvironment()->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntryId(): string
    {
        return $this->sys->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return ['X-Contentful-Content-Type' => $this->contentTypeId];
    }

    /**
     * Formats data for JSON encoding.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function getFormattedData($data)
    {
        if ($data instanceof DateTimeImmutable) {
            return (string) $data;
        }

        if (\is_array($data)) {
            if (isset($data['nodeType'])) {
                return $this->formatRichTextField($data);
            }

            return \array_map([$this, 'getFormattedData'], $data);
        }

        return $data;
    }

    /**
     * Rich text fields have a data object which PHP converts
     * to a simple array when empty.
     * The Management API does not recognize the value and throws an errors,
     * so we make an educated guess and force the data property to be an object.
     */
    private function formatRichTextField(array $value): array
    {
        if (\array_key_exists('data', $value) && !$value['data']) {
            $value['data'] = new \stdClass();
        }

        if (isset($value['content']) && \is_array($value['content'])) {
            foreach ($value['content'] as $index => $content) {
                if (\is_array($content) && isset($content['nodeType'])) {
                    $value['content'][$index] = $this->formatRichTextField($content);
                }
            }
        }

        return $value;
    }

    /**
     * @return mixed
     */
    public function getField(string $name, string $locale)
    {
        return $this->fields[$name][$locale] ?? null;
    }

    public function getFields(string $locale = null): array
    {
        if (null === $locale) {
            return $this->fields;
        }

        $fields = [];
        foreach ($this->fields as $name => $field) {
            $fields[$name] = $field[$locale] ?? null;
        }

        return $fields;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function setField(string $name, string $locale, $value)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = [];
        }

        $this->fields[$name][$locale] = $value;

        return $this;
    }

    /**
     * Provides simple setX/getX capabilities,
     * without recurring to code generation.
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $action = \mb_substr($name, 0, 3);
        if ('get' !== $action && 'set' !== $action) {
            \trigger_error(\sprintf(
                'Call to undefined method %s::%s()',
                static::class,
                $name
            ), \E_USER_ERROR);
        }

        $field = $this->extractFieldName($name);

        return 'get' === $action
            ? $this->getField($field, ...$arguments)
            : $this->setField($field, ...$arguments);
    }

    private function extractFieldName(string $name): string
    {
        return \lcfirst(\mb_substr($name, 3));
    }
}
