<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Updatable;
use function GuzzleHttp\json_encode;

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
     * @return Link|null
     */
    public function getPreviewApiKey()
    {
        return $this->previewApiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $deliveryApiKey = parent::jsonSerialize();
        $deliveryApiKey['previewApiKey'] = $this->previewApiKey;

        return $deliveryApiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);
        unset($body['accessToken']);
        unset($body['previewApiKey']);

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
    }
}
