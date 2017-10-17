<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

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
     * An array for caching mapper instances.
     *
     * @var MapperInterface[]
     */
    private static $mappers = [];

    /**
     * An array for storing data matcher callables.
     *
     * @var callable[]
     */
    private $dataMapperMatchers = [];

    /**
     * Creates or updates an object using given data.
     * This method will overwrite properties of the $resource parameter.
     *
     * @param array                  $data     The raw API data
     * @param ResourceInterface|null $resource A object if it needs to be updated, or null otherwise
     *
     * @return ResourceInterface|ResourceArray
     */
    public function build(array $data, ResourceInterface $resource = null)
    {
        $fqcn = $this->determineMapperFqcn($data);

        return $this->getMapper($fqcn)
            ->map($resource, $data);
    }

    /**
     * Returns the mapper object appropriate for the given data.
     *
     * @param string $fqcn
     *
     * @throws \RuntimeException
     *
     * @return MapperInterface
     */
    public function getMapper(string $fqcn): MapperInterface
    {
        if (!isset(self::$mappers[$fqcn])) {
            self::$mappers[$fqcn] = new $fqcn($this);
        }

        return self::$mappers[$fqcn];
    }

    /**
     * Determines the fully-qualified class name of the mapper object
     * that will handle the mapping process.
     *
     * This function will use user-defined data matchers, if avaiable.
     *
     * If the user-defined matcher does not return anything,
     * we default to the base mapper, so the user doesn't have
     * to manually return the default value.
     *
     * @param array $data The raw API data
     *
     * @return string The mapper's fully-qualified class name
     */
    private function determineMapperFqcn(array $data): string
    {
        $type = $this->getSystemType($data);
        $fqcn = '\\Contentful\\Management\\Mapper\\'.$type;

        if (isset($this->dataMapperMatchers[$type])) {
            $matchedFqcn = $this->dataMapperMatchers[$type]($data);

            if (!$matchedFqcn) {
                return $fqcn;
            }

            if (!\class_exists($matchedFqcn, true)) {
                throw new \RuntimeException(\sprintf(
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
     * @return static
     */
    public function setDataMapperMatcher(string $type, callable $dataMapperMatcher = null)
    {
        $this->dataMapperMatchers[$type] = $dataMapperMatcher;

        return $this;
    }

    /**
     * Determines the SDK resource type given the API system type.
     * Most of the types the two values coincide, but sometimes the SDK
     * applies some optimizations, such as having different classes for
     * entry and content type snapshots, even though the API represented them
     * both as "Snapshot".
     *
     * @param array $data The raw data fetched from the API
     *
     * @throws \InvalidArgumentException If the data array provided doesn't contain meaningful information
     *
     * @return string The system type that works in the SDK
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
            case 'Extension':
            case 'Locale':
            case 'Organization':
            case 'Role':
            case 'PersonalAccessToken':
            case 'PreviewApiKey':
            case 'Space':
            case 'SpaceMembership':
            case 'Upload':
            case 'User':
                return $data['sys']['type'];

            case 'Snapshot':
                return $data['sys']['snapshotEntityType'].'Snapshot';

            case 'ApiKey':
                return 'DeliveryApiKey';
            case 'Webhook':
                return 'WebhookHealth';
            case 'WebhookCallDetails':
            case 'WebhookCallOverview':
                return 'WebhookCall';
            case 'WebhookDefinition':
                return 'Webhook';
        }

        throw new \InvalidArgumentException(\sprintf(
            'Unexpected system type "%s" while trying to build a resource.',
            $data['sys']['type']
        ));
    }
}
