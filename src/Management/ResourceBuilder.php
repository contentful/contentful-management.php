<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\File\File;
use Contentful\File\FileInterface;
use Contentful\File\ImageFile;
use Contentful\File\LocalUploadFile;
use Contentful\File\RemoteUploadFile;
use Contentful\Link;
use Contentful\Management\Field\FieldInterface;
use Contentful\Management\Field\Validation;
use Contentful\Management\Field\Validation\ValidationInterface;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\Resource\DeliveryApiKey;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\Resource\Locale;
use Contentful\Management\Resource\Organization;
use Contentful\Management\Resource\PublishedContentType;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Management\Resource\Upload;
use Contentful\Management\Resource\User;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\Resource\WebhookCallDetails;
use Contentful\Management\Resource\WebhookHealth;
use Contentful\Management\Role\Constraint\AndConstraint;
use Contentful\Management\Role\Constraint\ConstraintInterface;
use Contentful\Management\Role\Constraint\EqualityConstraint;
use Contentful\Management\Role\Constraint\NotConstraint;
use Contentful\Management\Role\Constraint\OrConstraint;
use Contentful\Management\Role\Permissions;
use Contentful\Management\Role\Policy;
use Contentful\ResourceArray;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * ResourceBuilder class.
 *
 * This class is responsible for populating PHP objects using data received from Contentful's API.
 */
class ResourceBuilder
{
    /**
     * @var \Closure[]
     */
    private static $hydratorCache = [];

    /**
     * ResourceBuilder constructor.
     *
     * Empty constructor for forward compatibility.
     */
    public function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return ResourceInterface|ResourceArray
     */
    public function buildObjectsFromRawData(array $data)
    {
        $type = $data['sys']['type'];

        switch ($type) {
            case 'Array':
                return $this->buildArray($data);
            case 'ApiKey':
                return $this->buildDeliveryApiKey($data);
            case 'Asset':
                return $this->buildAsset($data);
            case 'ContentType':
                // The /public/content_types endpoint is a weird exception that returns
                // data in a mix of CDA and CMA formats
                if (isset($data['sys']['revision'])) {
                    return $this->buildPublishedContentType($data);
                }

                return $this->buildContentType($data);
            case 'Entry':
                return $this->buildEntry($data);
            case 'Locale':
                return $this->buildLocale($data);
            case 'Organization':
                return $this->buildOrganization($data);
            case 'PreviewApiKey':
                return $this->buildPreviewApiKey($data);
            case 'Role':
                return $this->buildRole($data);
            case 'Snapshot':
                switch ($data['sys']['snapshotEntityType']) {
                    case 'ContentType':
                        return $this->buildContentTypeSnapshot($data);
                    case 'Entry':
                        return $this->buildEntrySnapshot($data);
                    default:
                        throw new \InvalidArgumentException('Unexpected snapshot entity type "'.$data['snapshotEntityType'].'" when trying to build snapshot.');
                }
            case 'Space':
                return $this->buildSpace($data);
            case 'SpaceMembership':
                return $this->buildSpaceMembership($data);
            case 'Upload':
                return $this->buildUpload($data);
            case 'User':
                return $this->buildUser($data);
            case 'Webhook':
                return $this->buildWebhookHealth($data);
            case 'WebhookCallDetails':
                return $this->buildWebhookCallDetails($data);
            case 'WebhookCallOverview':
                return $this->buildWebhookCall($data);
            case 'WebhookDefinition':
                return $this->buildWebhook($data);
            default:
                throw new \InvalidArgumentException('Unexpected type "'.$type.'"" while trying to build object.');
        }
    }

    /**
     * Updates an object using given data.
     * This method will overwrite properties of the $object parameter.
     *
     * @param object $object
     * @param array  $data
     */
    public function updateObjectFromRawData($object, array $data)
    {
        $type = $data['sys']['type'];

        switch ($type) {
            case 'ApiKey':
                return $this->updateDeliveryApiKey($object, $data);
            case 'Asset':
                return $this->updateAsset($object, $data);
            case 'ContentType':
                return $this->updateContentType($object, $data);
            case 'Entry':
                return $this->updateEntry($object, $data);
            case 'Locale':
                return $this->updateLocale($object, $data);
            case 'Role':
                return $this->updateRole($object, $data);
            case 'Space':
                return $this->updateSpace($object, $data);
            case 'SpaceMembership':
                return $this->updateSpaceMembership($object, $data);
            case 'Upload':
                return $this->updateUpload($object, $data);
            case 'WebhookDefinition':
                return $this->updateWebhook($object, $data);
            default:
                throw new \InvalidArgumentException('Unexpected type "'.$type.'"" while trying to update object.');
        }
    }

    /**
     * @param string $class
     * @param array  $properties
     *
     * @return object
     */
    private function createObject(string $class, array $properties)
    {
        $reflectedClass = new \ReflectionClass($class);
        $object = $reflectedClass->newInstanceWithoutConstructor();

        $hydrator = $this->getHydrator($class, $object);
        $hydrator($object, $properties);

        return $object;
    }

    /**
     * @param string $class
     * @param object $object
     * @param array  $properties
     */
    private function updateObject(string $class, $object, array $properties)
    {
        $hydrator = $this->getHydrator($class, $object);
        $hydrator($object, $properties);
    }

    /**
     * @param string $class
     * @param object $object
     *
     * @return \Closure
     */
    private function getHydrator(string $class, $object): \Closure
    {
        if (isset(self::$hydratorCache[$class])) {
            return self::$hydratorCache[$class];
        }

        $hydrator = \Closure::bind(function ($object, $properties) {
            foreach ($properties as $property => $value) {
                $object->$property = $value;
            }
        }, null, $object);

        self::$hydratorCache[$class] = $hydrator;

        return $hydrator;
    }

    /**
     * @param array $sys
     *
     * @return SystemProperties
     */
    private function buildSystemProperties(array $sys): SystemProperties
    {
        return new SystemProperties($sys);
    }

    /**
     * Creates a ResourceArray object containing an array of a certain resource.
     *
     * @param array $data
     *
     * @return ResourceArray
     */
    private function buildArray(array $data): ResourceArray
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = $this->buildObjectsFromRawData($item);
        }

        // Some endpoints don't have a `total` key, so we default it to zero
        $total = $data['total'] ?? 0;

        return new ResourceArray($items, $total, $data['limit'], $data['skip']);
    }

    /**
     * @param array $data
     *
     * @return DeliveryApiKey
     */
    private function buildDeliveryApiKey(array $data): DeliveryApiKey
    {
        return $this->createObject(DeliveryApiKey::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'accessToken' => $data['accessToken'],
            'description' => $data['description'],
            'previewApiKey' => isset($data['preview_api_key'])
                ? new Link($data['preview_api_key']['sys']['id'], 'PreviewApiKey')
                : null,
        ]);
    }

    /**
     * @param DeliveryApiKey $deliveryApiKey
     * @param array          $data
     */
    private function updateDeliveryApiKey(DeliveryApiKey $deliveryApiKey, array $data)
    {
        $this->updateObject(DeliveryApiKey::class, $deliveryApiKey, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'accessToken' => $data['accessToken'],
            'description' => $data['description'],
            'previewApiKey' => new Link($data['preview_api_key']['sys']['id'], 'PreviewApiKey'),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Asset
     */
    private function buildAsset(array $data): Asset
    {
        $fields = $data['fields'];

        return $this->createObject(Asset::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'title' => $fields['title'] ?? null,
            'description' => $fields['description'] ?? null,
            'file' => isset($fields['file']) ? array_map([$this, 'buildFile'], $fields['file']) : null,
        ]);
    }

    /**
     * @param Asset $asset
     * @param array $data
     */
    private function updateAsset(Asset $asset, array $data)
    {
        $fields = $data['fields'];

        $this->updateObject(Asset::class, $asset, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'title' => $fields['title'] ?? null,
            'description' => $fields['description'] ?? null,
            'file' => isset($fields['file']) ? array_map([$this, 'buildFile'], $fields['file']) : null,
        ]);
    }

    /**
     * @param array $data
     *
     * @return FileInterface
     */
    private function buildFile(array $data): FileInterface
    {
        if (isset($data['uploadFrom'])) {
            return new LocalUploadFile(
                $data['fileName'],
                $data['contentType'],
                new Link($data['uploadFrom']['sys']['id'], $data['uploadFrom']['sys']['linkType'])
            );
        }

        if (isset($data['upload'])) {
            return new RemoteUploadFile($data['fileName'], $data['contentType'], $data['upload']);
        }

        $details = $data['details'];
        if (isset($details['image'])) {
            return new ImageFile(
                $data['fileName'],
                $data['contentType'],
                $data['url'],
                $details['size'],
                $details['image']['width'],
                $details['image']['height']
            );
        }

        return new File(
            $data['fileName'],
            $data['contentType'],
            $data['url'],
            $details['size']
        );
    }

    /**
     * @param array $data
     *
     * @return ContentType
     */
    private function buildContentType(array $data): ContentType
    {
        return $this->createObject(ContentType::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'displayField' => $data['displayField'] ?? null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return PublishedContentType
     */
    private function buildPublishedContentType(array $data): PublishedContentType
    {
        return $this->createObject(PublishedContentType::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'displayField' => $data['displayField'] ?? null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields']),
        ]);
    }

    /**
     * @param ContentType $contentType
     * @param array       $data
     */
    private function updateContentType(ContentType $contentType, array $data)
    {
        $this->updateObject(ContentType::class, $contentType, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'displayField' => $data['displayField'] ?? null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Field\FieldInterface
     */
    private function buildContentTypeField(array $data): FieldInterface
    {
        $fieldTypes = [
            'Array' => Field\ArrayField::class,
            'Boolean' => Field\BooleanField::class,
            'Date' => Field\DateField::class,
            'Integer' => Field\IntegerField::class,
            'Link' => Field\LinkField::class,
            'Location' => Field\LocationField::class,
            'Number' => Field\NumberField::class,
            'Object' => Field\ObjectField::class,
            'Symbol' => Field\SymbolField::class,
            'Text' => Field\TextField::class,
        ];

        $type = $data['type'];

        $hydratorData = [
            'id' => $data['id'],
            'name' => $data['name'],
            'required' => $data['required'] ?? null,
            'localized' => $data['localized'] ?? null,
            'disabled' => $data['disabled'] ?? null,
            'omitted' => $data['omitted'] ?? null,
            'validations' => isset($data['validations']) ? array_map([$this, 'buildFieldValidation'], $data['validations']) : null,
        ];

        if ($type === 'Link') {
            $hydratorData['linkType'] = $data['linkType'];
        }

        if ($type === 'Array') {
            $items = $data['items'];
            $hydratorData['itemsType'] = $items['type'];
            $hydratorData['itemsLinkType'] = $items['linkType'] ?? null;
            $hydratorData['itemsValidations'] = isset($items['validations']) ? array_map([$this, 'buildFieldValidation'], $items['validations']) : null;
        }

        return $this->createObject($fieldTypes[$type], $hydratorData);
    }

    /**
     * @param array $data
     *
     * @return Validation\ValidationInterface
     */
    private function buildFieldValidation(array $data): ValidationInterface
    {
        $validations = [
            'size' => Validation\SizeValidation::class,
            'in' => Validation\InValidation::class,
            'linkContentType' => Validation\LinkContentTypeValidation::class,
            'linkMimetypeGroup' => Validation\LinkMimetypeGroupValidation::class,
            'range' => Validation\RangeValidation::class,
            'regexp' => Validation\RegexpValidation::class,
            'unique' => Validation\UniqueValidation::class,
            'dateRange' => Validation\DateRangeValidation::class,
            'assetImageDimensions' => Validation\AssetImageDimensionsValidation::class,
            'assetFileSize' => Validation\AssetFileSizeValidation::class,
        ];

        $type = array_keys($data)[0];
        $class = $validations[$type];

        return $class::fromApiResponse($data);
    }

    /**
     * @param array $data
     *
     * @return Entry
     */
    private function buildEntry(array $data): Entry
    {
        return $this->createObject(Entry::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'fields' => $data['fields'] ?? [],
        ]);
    }

    /**
     * @param Entry $entry
     * @param array $data
     */
    private function updateEntry(Entry $entry, array $data)
    {
        $this->updateObject(Entry::class, $entry, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'fields' => $data['fields'] ?? [],
        ]);
    }

    /**
     * @param array $data
     *
     * @return Locale
     */
    private function buildLocale(array $data): Locale
    {
        return $this->createObject(Locale::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'code' => $data['code'],
            'contentDeliveryApi' => $data['contentDeliveryApi'],
            'contentManagementApi' => $data['contentManagementApi'],
            'default' => $data['default'],
            'optional' => $data['optional'],
        ]);
    }

    /**
     * @param Locale $locale
     * @param array  $data
     */
    private function updateLocale(Locale $locale, array $data)
    {
        $this->updateObject(Locale::class, $locale, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'code' => $data['code'],
            'contentDeliveryApi' => $data['contentDeliveryApi'],
            'contentManagementApi' => $data['contentManagementApi'],
            'default' => $data['default'],
            'optional' => $data['optional'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return Organization
     */
    private function buildOrganization(array $data): Organization
    {
        return $this->createObject(Organization::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return PreviewApiKey
     */
    private function buildPreviewApiKey(array $data): PreviewApiKey
    {
        return $this->createObject(PreviewApiKey::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'accessToken' => $data['accessToken'],
            'description' => $data['description'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return Role
     */
    private function buildRole(array $data): Role
    {
        return $this->createObject(Role::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'],
            'policies' => array_map([$this, 'buildPolicy'], $data['policies']),
            'permissions' => $this->buildPermissions($data['permissions']),
        ]);
    }

    /**
     * @param Role  $role
     * @param array $data
     */
    private function updateRole(Role $role, array $data)
    {
        $this->updateObject(Role::class, $role, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'],
            'policies' => array_map([$this, 'buildPolicy'], $data['policies']),
            'permissions' => $this->buildPermissions($data['permissions']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Policy
     */
    private function buildPolicy(array $data): Policy
    {
        return $this->createObject(Policy::class, [
            'effect' => $data['effect'],
            'actions' => $data['actions'],
            'constraint' => isset($data['constraint']) ? $this->buildConstraint($data['constraint']) : null,
        ]);
    }

    /**
     * @param array $data
     *
     * @return ConstraintInterface
     */
    public function buildConstraint(array $data): ConstraintInterface
    {
        reset($data);
        $key = key($data);

        switch ($key) {
            case 'and':
                return $this->createObject(AndConstraint::class, [
                    'children' => array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'or':
                return $this->createObject(OrConstraint::class, [
                    'children' => array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'not':
                return $this->createObject(NotConstraint::class, [
                    'child' => $this->buildConstraint($data[$key][0]),
                ]);
            case 'equals':
                /**
                 * The $data[$key] array *should* be in the form
                 * [{"doc": "sys.type"}, "Entry"]
                 * with the object with the "doc" property in the first position,
                 * and the actual value in the second position.
                 * Just to be safe, we check whether the 'doc' key exists in the first element,
                 * so we know that *that* element is the doc, and the other contains the value.
                 */
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return $this->createObject(EqualityConstraint::class, [
                    'doc' => $data[$key][$docKey]['doc'],
                    'value' => $data[$key][$valueKey],
                ]);
            default:
                throw new \RuntimeException('Could not determine the constraint type');
        }
    }

    /**
     * @param array $data
     *
     * @return Permissions
     */
    private function buildPermissions(array $data): Permissions
    {
        return $this->createObject(Permissions::class, [
            'contentDelivery' => $data['ContentDelivery'],
            'contentModel' => $data['ContentModel'],
            'settings' => $data['Settings'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return ContentTypeSnapshot
     */
    private function buildContentTypeSnapshot(array $data): ContentTypeSnapshot
    {
        return $this->createObject(ContentTypeSnapshot::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'contentType' => $this->buildContentType($data['snapshot']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return EntrySnapshot
     */
    private function buildEntrySnapshot(array $data): EntrySnapshot
    {
        return $this->createObject(EntrySnapshot::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'entry' => $this->buildEntry($data['snapshot']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Space
     */
    private function buildSpace(array $data): Space
    {
        return $this->createObject(Space::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
        ]);
    }

    /**
     * @param Space $space
     * @param array $data
     */
    private function updateSpace(Space $space, array $data)
    {
        $this->updateObject(Space::class, $space, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return SpaceMembership
     */
    private function buildSpaceMembership(array $data): SpaceMembership
    {
        return $this->createObject(SpaceMembership::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'admin' => $data['admin'],
            'email' => $data['email'] ?? null,
            'roles' => array_map(function (array $role) {
                return new Link($role['sys']['id'], 'Role');
            }, $data['roles'] ?? []),
            'user' => new Link($data['user']['sys']['id'], 'User'),
        ]);
    }

    /**
     * @param SpaceMembership $spaceMembership
     * @param array           $data
     */
    private function updateSpaceMembership(SpaceMembership $spaceMembership, array $data)
    {
        $this->updateObject(SpaceMembership::class, $spaceMembership, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'admin' => $data['admin'],
            'email' => $data['email'] ?? null,
            'roles' => array_map(function (array $role) {
                return new Link($role['sys']['id'], 'Role');
            }, $data['roles'] ?? []),
            'user' => new Link($data['user']['sys']['id'], 'User'),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Upload
     */
    private function buildUpload(array $data): Upload
    {
        return $this->createObject(Upload::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'body' => null,
        ]);
    }

    /**
     * @param Upload $upload
     * @param array  $data
     */
    private function updateUpload(Upload $upload, array $data)
    {
        $this->updateObject(Upload::class, $upload, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'body' => null,
        ]);
    }

    /**
     * @param array $data
     *
     * @return User
     */
    private function buildUser(array $data): User
    {
        return $this->createObject(User::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'avatarUrl' => $data['avatarUrl'],
            'email' => $data['email'],
            'activated' => $data['activated'],
            'signInCount' => $data['signInCount'],
            'confirmed' => $data['confirmed'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return Webhook
     */
    private function buildWebhook(array $data): Webhook
    {
        $headers = [];
        foreach ($data['headers'] as $header) {
            $headers[$header['key']] = $header['value'];
        }

        return $this->createObject(Webhook::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'url' => $data['url'],
            'httpBasicUsername' => $data['httpBasicUsername'] ?? null,
            'httpBasicPassword' => null,
            'topics' => $data['topics'],
            'headers' => $headers,
        ]);
    }

    /**
     * @param Webhook $webhook
     * @param array   $data
     */
    private function updateWebhook(Webhook $webhook, array $data)
    {
        $headers = [];
        foreach ($data['headers'] as $header) {
            $headers[$header['key']] = $header['value'];
        }

        // The API never returns the password in the response.
        // This means that the object that the user requested will have its `httpBasicPassword` field set to null.
        // It's a destructive behavior, but it's consinstent with the way the API works.
        $this->updateObject(Webhook::class, $webhook, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'url' => $data['url'],
            'httpBasicUsername' => $data['httpBasicUsername'] ?? null,
            'httpBasicPassword' => null,
            'topics' => $data['topics'],
            'headers' => $headers,
        ]);
    }

    /**
     * @param array $data
     *
     * @return WebhookCallDetails
     */
    private function buildWebhookCallDetails(array $data): WebhookCallDetails
    {
        return $this->createObject(WebhookCallDetails::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'request' => new Request(
                $data['request']['method'],
                $data['request']['url'],
                $data['request']['headers'],
                $data['request']['body']
            ),
            'response' => new Response(
                $data['response']['statusCode'],
                $data['response']['headers'],
                $data['response']['body']
            ),
            'statusCode' => $data['statusCode'],
            'eventType' => $data['eventType'],
            'url' => $data['url'],
            'error' => $data['errors'] ? $data['errors'][0] : null,
            'requestAt' => new \DateTimeImmutable($data['requestAt']),
            'responseAt' => new \DateTimeImmutable($data['responseAt']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return WebhookCall
     */
    private function buildWebhookCall(array $data): WebhookCall
    {
        return $this->createObject(WebhookCall::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'statusCode' => $data['statusCode'],
            'eventType' => $data['eventType'],
            'error' => $data['errors'] ? $data['errors'][0] : null,
            'url' => $data['url'],
            'requestAt' => new \DateTimeImmutable($data['requestAt']),
            'responseAt' => new \DateTimeImmutable($data['responseAt']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return WebhookHealth
     */
    private function buildWebhookHealth(array $data): WebhookHealth
    {
        return $this->createObject(WebhookHealth::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'total' => $data['calls']['total'],
            'healthy' => $data['calls']['healthy'],
        ]);
    }
}
