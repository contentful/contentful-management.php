<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Api\Link;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * DeliveryApiKey class.
 *
 * This class represents a resource with type "ApiKey" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
 */
class DeliveryApiKey extends ApiKey implements CreatableInterface
{
    use DeletableTrait,
        UpdatableTrait;

    /**
     * @var Link
     */
    protected $previewApiKey;

    /**
     * ApiKey constructor.
     *
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
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

        if (!$body['environments']) {
            unset($body['environments']);
        }

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->getSystemProperties()->getSpace()->getId(),
            'deliveryApiKey' => $this->getSystemProperties()->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
    }

    /**
     * @return Link
     */
    public function getPreviewApiKey()
    {
        return $this->previewApiKey;
    }
}
