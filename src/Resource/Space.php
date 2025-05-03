<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Proxy\Extension\SpaceProxyExtension;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Space as SystemProperties;

use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * Space class.
 *
 * This class represents a resource with type "Space" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Space extends BaseResource implements CreatableInterface
{
    use DeletableTrait;
    use SpaceProxyExtension;
    use UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $organizationId;

    /**
     * @var string|null
     */
    protected $defaultLocale;

    /**
     * Space constructor.
     */
    public function __construct(string $name, string $organizationId, ?string $defaultLocale = null)
    {
        $this->name = $name;
        $this->organizationId = $organizationId;
        $this->defaultLocale = $defaultLocale;
    }

    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }

    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        if ($this->defaultLocale) {
            $body['defaultLocale'] = $this->defaultLocale;
        }

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getId(),
        ];
    }

    protected function getSpaceId()
    {
        return $this->sys->getId();
    }

    public function getHeadersForCreation(): array
    {
        return ['X-Contentful-Organization' => $this->organizationId];
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
