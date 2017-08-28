<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Management\Mapper\MapperInterface;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\ResourceArray;

/**
 * ResourceBuilder class.
 *
 * This class is responsible for populating PHP objects
 * using data received from Contentful's API.
 */
class ResourceBuilder
{
    /**
     * @var MapperInterface[]
     */
    private static $mappers = [];

    /**
     * @var callable[]
     */
    private $dataMapperMatchers = [];

    /**
     * Creates or updates an object using given data.
     * This method will overwrite properties of the $resource parameter.
     *
     * @param array                  $data
     * @param ResourceInterface|null $resource
     *
     * @return ResourceInterface|ResourceArray
     */
    public function build(array $data, ResourceInterface $resource = null)
    {
        return $this->getMapper($data)
            ->map($resource, $data);
    }

    /**
     * @param array $data
     *
     * @return MapperInterface
     *
     * @throws \RuntimeException
     */
    private function getMapper(array $data): MapperInterface
    {
        $fqcn = $this->determineMapperFqcn($data);

        if (!isset(self::$mappers[$fqcn])) {
            self::$mappers[$fqcn] = new $fqcn($this);
        }

        return self::$mappers[$fqcn];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function determineMapperFqcn(array $data): string
    {
        $type = $this->getSystemType($data);
        $fqcn = '\\Contentful\\Management\\Mapper\\'.$type;

        if (isset($this->dataMapperMatchers[$type])) {
            $matchedFqcn = call_user_func_array($this->dataMapperMatchers[$type], [$data]);

            // If the custom user-defined matcher does not return nothing
            // we default to the base mapper, so the user doesn't have
            // to manually return the default value
            if (!$matchedFqcn) {
                return $fqcn;
            }

            if (!class_exists($matchedFqcn, true)) {
                throw new \RuntimeException(sprintf(
                    'Mapper class "%s" does not exist.',
                    $matchedFqcn
                ));
            }

            return $matchedFqcn;
        }

        return $fqcn;
    }

    /**
     * Sets a callable which will receive raw data (the JSON payload
     * converted to a PHP array) and will determine the FQCN
     * for appropriate mapping of that resource.
     *
     * @param string        $type              The system type as defined in ResourceBuilder::getSystemType()
     * @param callable|null $dataMapperMatcher A valid callable
     *
     * @return $this
     */
    public function setDataMapperMatcher(string $type, callable $dataMapperMatcher = null)
    {
        $this->dataMapperMatchers[$type] = $dataMapperMatcher;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function getSystemType(array $data): string
    {
        switch ($data['sys']['type']) {
            case 'Array':
                return 'ResourceArray';

            case 'Asset':
            case 'ContentType':
            case 'EditorInterface':
            case 'Entry':
            case 'Locale':
            case 'Organization':
            case 'Role':
            case 'PersonalAccessToken':
            case 'PreviewApiKey':
            case 'Space':
            case 'SpaceMembership':
            case 'Upload':
            case 'User':
            case 'WebhookCallDetails':
                return $data['sys']['type'];

            case 'Snapshot':
                return $data['sys']['snapshotEntityType'].'Snapshot';

            case 'ApiKey':
                return 'DeliveryApiKey';
            case 'Webhook':
                return 'WebhookHealth';
            case 'WebhookCallOverview':
                return 'WebhookCall';
            case 'WebhookDefinition':
                return 'Webhook';
        }

        throw new \InvalidArgumentException(sprintf(
            'Unexpected system type "%s" while trying to build a resource.',
            $data['sys']['type']
        ));
    }
}
