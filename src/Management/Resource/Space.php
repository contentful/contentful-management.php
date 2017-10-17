<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Updatable;
use function GuzzleHttp\json_encode;

/**
 * Space class.
 *
 * This class represents a resource with type "Space" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Space extends BaseResource implements Creatable, Updatable, Deletable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $organizationId;

    /**
     * @var string|null
     */
    protected $defaultLocale;

    /**
     * Space constructor.
     *
     * @param string      $name
     * @param string      $organizationId
     * @param string|null $defaultLocale
     */
    public function __construct(string $name, string $organizationId, string $defaultLocale = null)
    {
        parent::__construct('Space');
        $this->name = $name;
        $this->organizationId = $organizationId;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        if ($this->defaultLocale) {
            $body['defaultLocale'] = $this->defaultLocale;
        }

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }
}
