<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Updatable;

/**
 * DeliveryApiKey class.
 *
 * This class represents a resource with type "ApiKey" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
 */
class DeliveryApiKey extends ApiKey implements Creatable, Updatable, Deletable
{
    /**
     * @var Link|null
     */
    protected $previewApiKey;

    /**
     * ApiKey constructor.
     *
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        parent::__construct('DeliveryApiKey');
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceUriPart(): string
    {
        return 'api_keys';
    }

    /**
     * @return Link|null
     */
    public function getPreviewApiKey()
    {
        return $this->previewApiKey;
    }
}
